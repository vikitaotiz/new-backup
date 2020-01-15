<?php

namespace App\Imports;

use App\User;
use App\InitialConsultation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InitialConsultationsImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new InitialConsultation([
            'complain'     => $row['presenting_complaint'] ? $row['presenting_complaint'] : null,
            'history_presenting_complaint'    => $row['history_presenting_complaint'] ? $row['history_presenting_complaint'] : null,
            'past_medical_history'    => $row['past_medical_history'] ? $row['past_medical_history'] : null,
            'drug_history'    => $row['drug_history'] ? $row['drug_history'] : null,
            'social_history'    => $row['social_history'] ? $row['social_history'] : null,
            'family_history'    => $row['family_history'] ? $row['family_history'] : null,
            'drug_allergies'    => $row['drug_allergies'] ? $row['drug_allergies'] : null,
            'examination'    => $row['examination'] ? $row['examination'] : null,
            'diagnosis'    => $row['diagnosis'] ? $row['diagnosis'] : null,
            'treatment'    => $row['treatment'] ? $row['treatment'] : null,
            'company_id'    => $row['company_id'] ? $row['company_id'] : null,
            'user_id'    => $row['user_id'] ? $row['user_id'] : User::where('email', $row['patient_email'])->value('id'),
            'creator_id'    => auth()->id(),
        ]);
    }
}
