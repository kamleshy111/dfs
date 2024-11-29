<?php

namespace App\Imports;

use App\Models\CustomerDevice;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerDeviceImport implements ToModel, WithHeadingRow
{
    protected $customerId;

    public function __construct($customerId)
    {
        $this->customerId = $customerId;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new CustomerDevice([
            'customer_id' => $this->customerId,
            'device_id' => $row['device_id'],
        ]);
    }
}
