<?php


namespace App\Traits;


use App\Models\Store;
use Carbon\Carbon;

trait HasStoreDateFilter
{
    public function modifyDate($date, Store $store) {
        $carbonDate = Carbon::parse($date);
        $dayOfWeek = $carbonDate->dayOfWeek;
        $schedule = $store->getSchedule();

        if ($schedule) {
            $foundSchedule = array_filter($schedule, function ($item) use ($dayOfWeek) {
                return $item['dayOfWeek'] == $dayOfWeek;
            });

            if (!empty($foundSchedule)) {
                $foundSchedule = reset($foundSchedule);

                $startDate = $this->matchDateWithStore($carbonDate, $foundSchedule['timeStart']);
                $endDate = $this->matchDateWithStore($carbonDate, $foundSchedule['timeEnd']);

                return [
                    'startDate' => $startDate->addSecond()->format('Y-m-d H:i:s'),
                    'endDate' => $endDate->addSecond()->format('Y-m-d H:i:s')
                ];
            } else {
                return [
                    'startDate' => Carbon::parse($date)->startOfDay()->addSecond(),
                    'endDate' => Carbon::parse($date)->endOfDay()->addSecond()
                ];
            }
        } else {
            return [
                'startDate' => Carbon::parse($date)->startOfDay()->addSecond(),
                'endDate' => Carbon::parse($date)->endOfDay()->addSecond()
            ];
        }
    }

    public function matchDateWithStore($date, $time)
    {
        $newTime = Carbon::createFromFormat('H:i:s', $time);
        $newDate = $date->copy();
        $newDate->setTime($newTime->hour, $newTime->minute, 0);

        return $newDate;
    }
}
