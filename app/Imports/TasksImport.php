<?php

namespace App\Imports;

use App\Task;
use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TasksImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Task([
            'name'     => $row['name'] ? $row['name'] : null,
            'description'    => $row['description'] ? $row['description'] : null,
            'deadline'    => $row['deadline'] ? $row['deadline'] : null,
            'status'    => $row['status'] ? $row['status'] : null,
            'doctor_id'    => $row['doctor_id'] ? $row['doctor_id'] : null,
            'user_id'    => $row['user_id'] ? $row['user_id'] : User::where('email', $row['patient_email'])->value('id'),
            'creator_id'    => auth()->id(),
        ]);
    }
}
