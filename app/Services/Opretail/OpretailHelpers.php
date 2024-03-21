<?php


namespace App\Services\Opretail;


trait OpretailHelpers
{
    /**
     * @param array $data
     * @return array
     */
    public static function mapGender(Array $data)
    {
        if (!$data) die('No gender data has been received.');

        $return = [];

        foreach ($data as $single) {
            if ($single['gender'] === 0)
                continue;
            $gender = $single['gender'] === 1 ? 'male' : 'female';
            $return[$gender] = [
                'count' => $single['peopleNum'],
                'percentage' => $single['percentage']
            ];
        }

        return $return;
    }

    /**
     * @param array $data
     * @return array
     */
    public static function mapAge(Array $data)
    {
        if (!$data) die('No Age data has been received.');

        $return = [];

        foreach ($data as $single) {
            $group = false;
            if ($single['ageDivisionType'] === 0) {
                $group = 'earlyYouth';
            } else if ($single['ageDivisionType'] === 1) {
                $group = 'youth';
            } else if ($single['ageDivisionType'] === 2) {
                $group = 'middleAge';
            } else if ($single['ageDivisionType'] === 3) {
                $group = 'middleOld';
            } else if ($single['ageDivisionType'] === 4) {
                $group = 'elderly';
            }

            if ($group) {
                $return[$group] = [
                    'count' => $single['peopleNum'],
                    'percentage' => $single['percentage'],
                    'description' => $single['ageTo'] ? "{$single['ageFrom']} - {$single['ageTo']}" : "> {$single['ageFrom']}",
                ];
            }
        }

        return $return;
    }

    /**
     * @param array $data
     * @param string $splitType
     * @return array
     */
    public static function mapHourlyWalkIn(Array $data, $splitType = 'hours')
    {
        if (!$data) die('No hourly data has been received.');

        $return = [];

        foreach ($data as $single) {
            if ($single['passengerFlow'] > 0) {
                $return[] = [
                    "date" => date('Y-m-d', strtotime($single['time'])),
                    "time" => date('H:i', strtotime($single['time'])),
                    "passengerFlow" => $single['passengerFlow'],
                ];
            }
        }

        return $return;
    }

    /**
     * @param $data
     * @param $date
     * @return array
     */
    public static function mapHourlyWalkInFromAgeGender($data, $date)
    {
        if (!$data) die('No hourly data has been received.');
        $return = [];
        $passengerFlow = 0;

        foreach ($data as $single) {
            if (isset($single->gender) && $single->gender !== 0) {
                $passengerFlow += $single->peopleNum;
            }
        }

        if ($passengerFlow > 0) {
            $return[] = [
                "date" => date('Y-m-d', strtotime($date)),
                "time" => date('H:i', strtotime($date)),
                "passengerFlow" => $passengerFlow,
            ];
        }

        return $return;
    }
}
