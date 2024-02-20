<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct(public $data)
    {
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        \Log::info('DATA FOR EXPORT', ['d' => $this->data]);
        foreach ($this->data as $key => $value) {
            $sheets[] = new ReportExportDaily($value, $key);
        }

        return $sheets;
    }
}
