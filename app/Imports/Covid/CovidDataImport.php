<?php

namespace App\Imports\Covid;

use App\Models\CountDaily;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CovidDataImport implements ToModel, WithHeadingRow
{

    /**
     * @param array $row
     *
     * @return \App\Models\CountDaily|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Model[]|null
     */
    public function model(array $row)
    {
        $count = (int) $row['count'];
        if($count > 0) {
            $createdAt = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date']));
            return new CountDaily([
                'created_at' => $createdAt,
                'count'      => $count
            ]);
        }
    }
}
