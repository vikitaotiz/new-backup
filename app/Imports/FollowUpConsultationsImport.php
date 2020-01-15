<?php

namespace App\Imports;

use App\User;
use App\FollowupConsultation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FollowUpConsultationsImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new FollowupConsultation([
            'patient_progress'     => $row['patient_progress'] ? $row['patient_progress'] : null,
            'assessment'    => $row['assessment'] ? $row['assessment'] : null,
            'plan'    => $row['plan'] ? $row['plan'] : null,
            'company_id'    => $row['company_id'] ? $row['company_id'] : null,
            'user_id'    => $row['user_id'] ? $row['user_id'] : User::where('email', $row['patient_email'])->value('id'),
            'creator_id'    => auth()->id(),
        ]);
    }
}
