<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if ($row['id'] == null) {
            return new User([
                'title'     => $row['title'] ? $row['title'] : null,
                'firstname'    => $row['first_name'] ? $row['first_name'] : null,
                'lastname'    => $row['last_name'] ? $row['last_name'] : null,
                'nhs_number'    => $row['nhs_number'] ? $row['nhs_number'] : null,
                'date_of_birth'    => $row['date_of_birth'] ? $row['date_of_birth'] : null,
                'phone'    => $row['phone'] ? $row['phone'] : null,
                'emergency_contact'    => $row['emergency_contacts'] ? $row['emergency_contacts'] : null,
                'email'    => $row['email'] ? $row['email'] : null,
                'gender'    => $row['gender'] ? $row['gender'] : null,
                'height'    => $row['height'] ? $row['height'] : null,
                'weight'    => $row['weight'] ? $row['weight'] : null,
                'blood_type'    => $row['blood_type'] ? $row['blood_type'] : null,
                'address'    => $row['address'] ? $row['address'] : null,
                'availability'    => $row['availability'] ? $row['availability'] : null,
                'occupation'    => $row['occupation'] ? $row['occupation'] : null,
                'referral_source'    => $row['referral_source'] ? $row['referral_source'] : null,
                'gp_details'    => $row['general_practioner_gp_details'] ? $row['general_practioner_gp_details'] : null,
                'medication_allergies'    => $row['medication_allergies'] ? $row['medication_allergies'] : null,
                'current_medication'    => $row['current_medication'] ? $row['current_medication'] : null,
                'deleted_at'    => $row['deleted_at'] ? $row['deleted_at'] : null,
                'more_info'    => $row['more_information'] ? $row['more_information'] : null,
                'gmc_no'    => $row['gmc_no'] ? $row['gmc_no'] : null,
                'password'    => Hash::make('12345678'),
                'user_id'    => $row['user_id'] ? $row['user_id'] : 4,
                'creator_id'    => auth()->id(),
            ]);
        }
    }
}
