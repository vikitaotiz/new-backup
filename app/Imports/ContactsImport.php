<?php

namespace App\Imports;

use App\User;
use App\Contact;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ContactsImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Contact([
            'nhs_number'     => $row['nhs_number'] ? $row['nhs_number'] : null,
            'relative_name'    => $row['name'] ? $row['name'] : null,
            'relationship_type'    => $row['relationship_type'] ? $row['relationship_type'] : null,
            'date_of_birth'    => $row['date_of_birth'] ? $row['date_of_birth'] : null,
            'phone'    => $row['phone'] ? $row['phone'] : null,
            'email'    => $row['email'] ? $row['email'] : null,
            'medical_history'    => $row['medical_history'] ? $row['medical_history'] : null,
            'more_info'    => $row['more_info'] ? $row['more_info'] : null,
            'user_id'    => $row['user_id'] ? $row['user_id'] : User::where('email', $row['patient_email'])->value('id'),
            'creator_id'    => auth()->id(),
        ]);
    }
}
