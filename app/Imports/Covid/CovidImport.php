<?php namespace App\Imports\Covid;

use App\Imports\Covid\CovidDataImport;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;

class CovidImport implements WithMultipleSheets, WithChunkReading, WithProgressBar, SkipsUnknownSheets
{
    use Importable;
    public function sheets(): array
    {
        return [
            0 => new CovidDataImport(),
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        // E.g. you can log that a sheet was not found.
        info("Sheet {$sheetName} was skipped");
    }

    public function chunkSize(): int
    {
        return 2000;
    }
}
