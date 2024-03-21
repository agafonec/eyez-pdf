<?php

namespace App\Jobs;

use App\Models\AgeGenderFlow;
use App\Models\AgeGroup;
use App\Models\HourlyPassengerFlow;
use App\Models\Store;
use App\Services\Opretail\OpretailApi;
use App\Traits\HasStoreDateFilter;
use Carbon\Carbon;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncOpretailJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels, HasStoreDateFilter;

    /**
     * Create a new job instance.
     */
    public string $method;
    public function __construct(
        public Store $store,
        public $date,
        public $type = 'create'
    ) {
        $this->onQueue('syncopretail');

        $this->method = $this->type === 'update' ? 'updateOrCreate' : 'firstOrCreate';
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        if ($this->store->workingDay($this->date)) {
            $startDate = Carbon::parse($this->date)->startOfHour();
            $endDate = Carbon::parse($this->date)->addHour()->startOfHour();

            $opretail = new OpretailApi(
                $this->store->opretailCredentials,
                $this->store,
                $startDate,
                $endDate
            );

            $this->setPassengerFlow($opretail, $endDate);
            $this->setAgeGenderData($opretail, $endDate);
        }
    }

    /**
     * @param OpretailApi $opretail
     * @param $date
     */
    protected function setAgeGenderData(OpretailApi $opretail, $date)
    {
        $ageGenderFlow = $opretail->getAgeGenderData();

        if ($ageGenderFlow->getStatusCode() === 200) {
            $ageGenderFlowData = $ageGenderFlow->getData();
            if (isset($ageGenderFlowData->data->ageDistribution)) {
                $this->singlePeriodAgeGender($ageGenderFlowData->data->ageDistribution, $date);
            }
        }
    }

    /**
     * @param $ageDistribution
     * @param $date
     */
    protected function singlePeriodAgeGender($ageDistribution, $date)
    {
        foreach ($ageDistribution as $flow) {

            $ageGroup = call_user_func([AgeGroup::class, 'updateOrCreate'],
                [
                    'store_id' => $this->store->id,
                    'group_id' => $flow->ageDivisionType
                ],
                [
                    'ageFrom' => $flow->ageFrom,
                    'ageTo' => $flow->ageTo,
                ]
            );

            foreach ($flow->genderDistribution as $genderFlow) {
                if ($genderFlow->gender === 0 || $genderFlow->peopleNum === 0)
                    continue;

                call_user_func([AgeGenderFlow::class, $this->method],
                    [
                        'store_id' => $this->store->id,
                        'date' => $date,
                        'age_group_id' => $ageGroup->id,
                        'gender' => $genderFlow->gender === 1 ? 'male' : 'female',
                    ],
                    [
                        'people_count' => $genderFlow->peopleNum
                    ]
                );
            }
        }
    }

    /**
     * @param OpretailApi $opretail
     */
    protected function setPassengerFlow(OpretailApi $opretail, $date)
    {
        $ageGenderFlow = $opretail->getAgeGenderData();
        \Log::info('Set passenger flow status', ['s' => $ageGenderFlow->getStatusCode()]);
        if ($ageGenderFlow->getStatusCode() === 200) {
            $ageGenderFlowData = $ageGenderFlow->getData();
            $hourlyWalkIn = $opretail->mapHourlyWalkInFromAgeGender($ageGenderFlowData->data->genderDistribution, $date);

            foreach ($hourlyWalkIn as $flow) {
                if ($flow['passengerFlow'] > 0) {
                    $hourlyFlowCreate = call_user_func([HourlyPassengerFlow::class, $this->method],
                        [
                            'store_id' => $this->store->id,
                            'time' => $date->format('Y-m-d H:i:s')
                        ],
                        [
                            'passengerFlow' => $flow['passengerFlow']
                        ]
                    );

                    \Log::info('HOURLY FLOW CREATE', [
                        'timeAfterPArse' => $date->format('Y-m-d H:i:s'),
                        'c' => $hourlyFlowCreate
                    ]);
                }
            }
        }
    }
}
