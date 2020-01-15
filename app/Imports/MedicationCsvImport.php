<?php

namespace App\Imports;

use App\Medication;
use Maatwebsite\Excel\Concerns\ToModel;

class MedicationCsvImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Medication([
            'name' => $row[0],
            'user_id' => 1
        ]);
    }
}
