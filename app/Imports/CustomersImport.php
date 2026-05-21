<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class CustomersImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $row)
    {
        // Skip rows with no name
        if (empty($row['name'])) {
            return null;
        }

        return new Customer([
            'name'          => $row['name']              ?? null,
            'address'       => $row['address']           ?? null,
            'phone'         => !empty($row['phone'])     ? $row['phone'] : null,
            'whatsapp'      => !empty($row['whatsapp'])  ? $row['whatsapp'] : null,
            'price_liter'   => $row['price_liter_rs']   ?? 0,
            'liter_per_day' => $row['liter_per_day']    ?? 0,
            'customer_type' => $row['customer_type']    ?? 'Home Delivery',
        ]);
    }
}