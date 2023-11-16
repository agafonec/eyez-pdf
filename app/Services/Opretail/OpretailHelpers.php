<?php


namespace App\Services\Opretail;


trait OpretailHelpers
{
    public static function mapGender(Array $data)
    {
        if (!$data) die('No gender data has been received.');

        $return = [];

        foreach ($data as $single) {
            if ($single['gender'] === 0)
                continue;
            $gender = $single['gender'] === 1 ? 'Male' : 'Female';
            $return[$gender] = $single['peopleNum'];
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
                $group = 'Teenagers';
            } else if ($single['ageDivisionType'] === 1) {
                $group = 'Youth';
            } else if ($single['ageDivisionType'] === 2) {
                $group = 'Middle aged';
            } else if ($single['ageDivisionType'] ===4) {
                $group = 'Elderly';
            }

            if ($group) {
                $return[$group] = $single['peopleNum'];
            }
        }

        return $return;
    }

    public static function mapHourlyWalkIn(Array $data, $splitType = 'hours')
    {
        if (!$data) die('No gender data has been received.');

        foreach ($data as $single) {
            if ($single['passengerFlow'] > 0) {
                if ($splitType === 'days') {
                    $return[] = [
                        "time" => date('H:i', strtotime($single['time'])),
                        "passengerFlow" => $single['passengerFlow'],
                    ];
                } else {
                    $return[] = [
                        "time" => date('H:i', strtotime($single['time'])),
                        "passengerFlow" => $single['passengerFlow'],
                    ];
                }

            }
        }

        return $return;
    }
}
