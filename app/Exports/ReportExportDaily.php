<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReportExportDaily implements FromArray, WithHeadings, WithTitle
{

    public function __construct(public $data, public $date) {
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        return $this->data;
    }

    public function map($row): array
    {
        return [
            $row->time,
            (string) $row->walkInCount,
            (string) $row->female,
            (string) $row->male,
            (string) $row->ordersCount,
            (string) $row->conversion,
            (string) $row->totalSales,
            (string) $row->atv,
            (string) $row->itemsCount,
            (string) $row->averageItemsPerOrder,
            (string) $row->averageItemPrice,
            (string) $row->earlyYouth,
            (string) $row->youth,
            (string) $row->middleAge,
            (string) $row->elderly,
            (string) $row->male_earlyYouth,
            (string) $row->male_youth,
            (string) $row->male_middleAge,
            (string) $row->male_elderly,
            (string) $row->female_earlyYouth,
            (string) $row->female_youth,
            (string) $row->female_middleAge,
            (string) $row->female_elderly,
        ];
    }

    public function headings(): array
    {
        return [
            'שעה',
            'מבקרים',
            'מבקרים גברים',
            'מבקרים נשים',
            'כמות עסקאות',
            'יחס המרה',
            'מכירות ₪',
            'ממוצע עסקה',
            'כמות פריטים',
            'ממוצע פריטים בעסקה',
            'ממוצע שווי פריט',
            'מבקרים ילדים',
            'מבקרים צעירים',
            'מבקרים בוגרים',
            'מבקרים מבוגרים',
            'מבקרים מבוגרים',
            'גברים ילדים',
            'גברים צעירים',
            'גברים בוגרים',
            'גברים מבוגרים',
            'נשים ילדים',
            'נשים צעירים',
            'נשים מבוגרים'
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
