<?php

namespace App\Imports;

use App\User;
use App\Prescription;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PrescriptionLettersImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Prescription([
            'drug_allergies'     => $row['drug_allergies'] ? $row['drug_allergies'] : null,
            'comments'    => $row['comments'] ? $row['comments'] : null,
            'signature'    => $row['signature'] ? $row['signature'] : null,
            'company_id'    => $row['company_id'] ? $row['company_id'] : null,
            'user_id'    => $row['user_id'] ? $row['user_id'] : User::where('email', $row['patient_email'])->value('id'),
            'creator_id'    => auth()->id(),
        ]);
    }
}
