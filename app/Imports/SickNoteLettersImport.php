<?php

namespace App\Imports;

use App\User;
use App\Sicknote;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SickNoteLettersImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Sicknote([
            'name'     => $row['sick_note_letter_name'] ? $row['sick_note_letter_name'] : null,
            'body'    => $row['body'] ? $row['body'] : null,
            'company_id'    => $row['company_id'] ? $row['company_id'] : null,
            'user_id'    => $row['user_id'] ? $row['user_id'] : User::where('email', $row['patient_email'])->value('id'),
            'creator_id'    => auth()->id(),
        ]);
    }
}
