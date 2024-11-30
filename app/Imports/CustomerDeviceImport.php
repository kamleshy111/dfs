<?php

namespace App\Imports;

use App\Models\CustomerDevice;
use App\Models\Device;
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


        $deviceExists = Device::where('id', $row['device_id'])->exists();

        $alreadyAssociated = CustomerDevice::where('device_id', $row['device_id'])->exists();


        if ($deviceExists && !$alreadyAssociated) {
            return new CustomerDevice([
                'customer_id' => $this->customerId,
                'device_id' => $row['device_id'],
            ]);
        }

        // Skip row if conditions are not met
        return null;
    }
}
