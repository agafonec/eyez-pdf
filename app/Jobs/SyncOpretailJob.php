<?php

namespace App\Jobs;

use App\Models\AgeGenderFlow;
use App\Models\AgeGroup;
use App\Models\HourlyPassengerFlow;
use App\Services\Opretail\OpretailApi;
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
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public string $method;
    public function __construct(
        public $store,
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
        $opretail = new OpretailApi(
            $this->store->opretailCredentials,
            $this->store,
            Carbon::parse($this->date)->startOfDay(),
            Carbon::parse($this->date)->endOfDay()
        );

        $this->setPassengerFlow($opretail);
        $this->setAgeGenderData($opretail);
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
                $hourlyFlowCreate = call_user_func([HourlyPassengerFlow::class, $this->method],
                    [
                        'store_id' => $this->store->id,
                        'time' => Carbon::parse("{$flow['date']} {$flow['time']}")->format('Y-m-d H:i:s')
                    ],
                    [
                        'passengerFlow' => $flow['passengerFlow']
                    ]
                );

                \Log::info('HOURLY FLOW CREATE', [
                    'timeBeforeParse' => "{$flow['date']} {$flow['time']}",
                    'timeAfterPArse' => Carbon::parse("{$flow['date']} {$flow['time']}")->format('Y-m-d H:i:s'),
                    'c' => $hourlyFlowCreate
                ]);

            }
        }
    }
}
