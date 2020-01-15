<?php

namespace App\Imports;

use App\User;
use App\Invoice;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InvoicesImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Invoice([
            'product_service'     => $row['product_service_id'] ? $row['product_service_id'] : null,
            'due_date'    => $row['due_date'] ? $row['due_date'] : null,
            'insurance_name'    => $row['insurance_name'] ? $row['insurance_name'] : null,
            'description'    => $row['description'] ? $row['description'] : null,
            'code_serial'    => $row['code_serial'] ? $row['code_serial'] : null,
            'quantity'    => $row['quantity'] ? $row['quantity'] : null,
            'charge_id'    => $row['charge_id'] ? $row['charge_id'] : null,
            'tax_id'    => $row['tax_id'] ? $row['tax_id'] : null,
            'currency_id'    => $row['currency_id'] ? $row['currency_id'] : null,
            'company_id'    => $row['company_id'] ? $row['company_id'] : null,
            'doctor_id'    => $row['doctor_id'] ? $row['doctor_id'] : null,
            'user_id'    => $row['user_id'] ? $row['user_id'] : User::where('email', $row['patient_email'])->value('id'),
            'creator_id'    => auth()->id(),
        ]);
    }
}
