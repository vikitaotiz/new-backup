<?php

namespace App\Imports;

use App\User;
use App\Vital;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VitalsImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Vital([
            'temperature'     => $row['temp'] ? $row['temp'] : null,
            'pulse_rate'    => $row['pulse_rate'] ? $row['pulse_rate'] : null,
            'pain'    => $row['pain'] ? $row['pain'] : null,
            'height'    => $row['height'] ? $row['height'] : null,
            'weight'    => $row['weight'] ? $row['weight'] : null,
            'systolic_bp'    => $row['systolic_bp'] ? $row['systolic_bp'] : null,
            'diastolic_bp'    => $row['diastolic_bp'] ? $row['diastolic_bp'] : null,
            'respiratory_rate'    => $row['respiratory_rate'] ? $row['respiratory_rate'] : null,
            'oxygen_saturation'    => $row['oxygen_saturation'] ? $row['oxygen_saturation'] : null,
            'o2_administered'    => $row['o2_administered'] ? $row['o2_administered'] : null,
            'head_circumference'    => $row['head_circumference'] ? $row['head_circumference'] : null,
            'company_id'    => $row['company_id'] ? $row['company_id'] : null,
            'user_id'    => $row['user_id'] ? $row['user_id'] : User::where('email', $row['patient_email'])->value('id'),
            'creator_id'    => auth()->id(),
        ]);
    }
}
