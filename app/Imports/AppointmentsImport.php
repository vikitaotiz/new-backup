<?php

namespace App\Imports;

use App\User;
use App\Appointment;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AppointmentsImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Appointment([
            'description'     => $row['description'] ? $row['description'] : null,
            'appointment_date'    => $row['appointment_date'] ? $row['appointment_date'] : null,
            'end_time'    => $row['end_time'] ? $row['end_time'] : null,
            'status'    => $row['status'] ? $row['status'] : null,
            'progress'    => $row['progress'] ? $row['progress'] : null,
            'color'    => $row['color'] ? $row['color'] : null,
            'service_id'    => $row['service_id'] ? $row['service_id'] : null,
            'doctor_id'    => $row['doctor_id'] ? $row['doctor_id'] : 4,
            'user_id'    => $row['user_id'] ? $row['user_id'] : User::where('email', $row['patient_email'])->value('id'),
            'creator_id'    => auth()->id(),
        ]);
    }
}
