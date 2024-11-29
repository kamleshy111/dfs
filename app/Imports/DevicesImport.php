<?php

namespace App\Imports;

use App\Models\Device;
use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DevicesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        return new Device([
            'device_id' => $row['device_id'],
            'order_id' => $row['order_id'],
            'company_name' => $row['company_name'],
            'date_time' => $row['date_time'],
        ]);
    }
}
