<?php

namespace App\Imports;

use App\Models\Rainfalldata;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class RainfalldataImport implements ToModel, WithStartRow
{

    public function  __construct(int $raingauge_id)
    {
        $this->raingauge_id = $raingauge_id;
    }

    /**
     * 
     * Skip first row when importing Excel file
     * 
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Rainfalldata([
            'raingauge_id' => $this->raingauge_id,
            'dateTime' => $row[0],
            'P1' => $row[1],
            'P2' => $row[2],
            'quality' => $row[3],
        ]);
    }
}
