<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReportExportDaily implements FromArray, WithHeadings, WithTitle
{

    public function __construct(public $report, public $date, public $ageLabels) {
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        return $this->report;
    }

    public function headings(): array
    {
        return [
            "שעה",
            "מבקרים",
            "מבקרים גברים",
            "מבקרים נשים",
            "כמות עסקאות",
            "יחס המרה",
            "מכירות ₪",
            "ממוצע עסקה",
            "כמות פריטים",
            "ממוצע פריטים בעסקה",
            "ממוצע שווי פריט",
            "מבקרים {$this->ageLabels['earlyYouth']}",
            "מבקרים {$this->ageLabels['youth']}",
            "מבקרים {$this->ageLabels['middleAge']}",
            "מבקרים {$this->ageLabels['middleOld']}",
            "מבקרים {$this->ageLabels['elderly']}",
            "גברים {$this->ageLabels['earlyYouth']}",
            "גברים {$this->ageLabels['youth']}",
            "גברים {$this->ageLabels['middleAge']}",
            "גברים {$this->ageLabels['middleOld']}",
            "גברים {$this->ageLabels['elderly']}",
            "נשים {$this->ageLabels['earlyYouth']}",
            "נשים {$this->ageLabels['youth']}",
            "נשים {$this->ageLabels['middleAge']}",
            "נשים {$this->ageLabels['middleOld']}",
            "נשים {$this->ageLabels['elderly']}"
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->date;
    }
}
