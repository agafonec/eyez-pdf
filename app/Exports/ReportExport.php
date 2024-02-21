<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct(public $data, public $store)
    {
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        foreach ($this->data as $key => $value) {
            $ageLabels = $this->getAgeGroupTitles();
            $sheets[] = new ReportExportDaily($value, $key, $ageLabels);
        }

        return $sheets;
    }

    /**
     * @param $singleReportData
     * @return array
     */
    protected function getAgeGroupTitles()
    {
        $settings = $this->store->user->settings;
        $ageGroups = $settings['ageGroups'] ?? [];

        return [
            "earlyYouth" => $ageGroups['earlyYouth'] ?? 'Early Youth',
            "youth" => $ageGroups->youth ?? 'Youth',
            "middleAge" => $ageGroups['middleAge'] ?? 'Middle Age',
            "middleOld" => $ageGroups['middleOld'] ?? 'Middle Old',
            "elderly" => $ageGroups['elderly'] ?? 'Elderly'
        ];
    }
}
