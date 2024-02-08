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
    public array $limitedDates;
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
        $this->limitedDates = $this->modifyDate($this->date, $this->store);

        \Log::info("=================- STARTED SYNC FOR STORE {$this->store->id} ======================");
        \Log::info('dates limits', $this->limitedDates);

        if ($this->store->workingDay($this->date)) {
            $opretail = new OpretailApi(
                $this->store->opretailCredentials,
                $this->store,
                $this->limitedDates['startDate'],
                $this->limitedDates['endDate']
            );

            $this->setPassengerFlow($opretail);
            $this->setAgeGenderData($opretail);
        }
    }

    /**
     * @param OpretailApi $opretail
     * @param $date
     */
    protected function setAgeGenderData(OpretailApi $opretail)
    {
        $ageGenderFlow = $opretail->getAgeGenderData();

        if (isset($ageGenderFlow['ageDistribution'])) {
            foreach ($ageGenderFlow['ageDistribution'] as $flow) {

                $ageGroup = call_user_func([AgeGroup::class, $this->method],
                    [
                        'store_id' => $this->store->id,
                        'group_id' => $flow['ageDivisionType']
                    ],
                    [
                        'ageFrom' => $flow['ageFrom'],
                        'ageTo' => $flow['ageTo'],
                    ]
                );

                foreach ($flow['genderDistribution'] as $genderFlow) {
                    if ($genderFlow['gender'] === 0 || $genderFlow['peopleNum'] === 0)
                        continue;

                    call_user_func([AgeGenderFlow::class, $this->method],
                        [
                            'store_id' => $this->store->id,
                            'date' => Carbon::parse($this->date)->endOfDay(),
                            'age_group_id' => $ageGroup->id,
                            'gender' => $genderFlow['gender'] === 1 ? 'male' : 'female',
                        ],
                        [
                            'people_count' => $genderFlow['peopleNum']
                        ]
                    );
                }
            }
        }
    }

    /**
     * @param OpretailApi $opretail
     */
    protected function setPassengerFlow(OpretailApi $opretail)
    {
        $hourlyFlow = $opretail->getStoreData();
        $hourlyWalkIn = [];

        foreach ($hourlyFlow['data'] as $data) {
            $hourlyWalkIn = array_merge($hourlyWalkIn, $data['dataList']);
        }
        $hourlyWalkIn = $opretail->mapHourlyWalkIn($hourlyWalkIn);

        foreach ($hourlyWalkIn as $flow) {
            if ($flow['passengerFlow'] > 0) {
                $flowTime = Carbon::parse("{$flow['date']} {$flow['time']}");

                if ($flowTime->lessThanOrEqualTo($this->limitedDates['endDate']) && $flowTime->greaterThanOrEqualTo($this->limitedDates['startDate'])) {
                    $hourlyFlowCreate = call_user_func([HourlyPassengerFlow::class, $this->method],
                        [
                            'store_id' => $this->store->id,
                            'time' => $flowTime->format('Y-m-d H:i:s')
                        ],
                        [
                            'passengerFlow' => $flow['passengerFlow']
                        ]
                    );

                    \Log::info('HOURLY FLOW CREATE', [
                        'timeAfterPArse' => Carbon::parse("{$flow['date']} {$flow['time']}")->format('Y-m-d H:i:s'),
                        'c' => $hourlyFlowCreate
                    ]);
                }
            }
        }
    }
}
