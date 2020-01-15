<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromQuery, WithHeadings, WithTitle
{
    use Exportable;

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        // check user role
        if (auth()->user()->role_id == 3) {
            if (auth()->user()->company) {
                return User::query()->select('id', 'title', 'firstname', 'lastname', 'nhs_number', 'date_of_birth', 'phone', 'emergency_contact', 'email', 'created_at', 'gender', 'height', 'weight', 'blood_type', 'address', 'availability', 'occupation','referral_source', 'gp_details', 'medication_allergies', 'current_medication', 'more_info', 'deleted_at', 'gmc_no', 'user_id')->where('role_id', 5)->whereIn('user_id', auth()->user()->company->users->pluck('id'));
            } else {
                return User::query()->select('id', 'title', 'firstname', 'lastname', 'nhs_number', 'date_of_birth', 'phone', 'emergency_contact', 'email', 'created_at', 'gender', 'height', 'weight', 'blood_type', 'address', 'availability', 'occupation','referral_source', 'gp_details', 'medication_allergies', 'current_medication', 'more_info', 'deleted_at', 'gmc_no', 'user_id')->where(['role_id' => 5, 'user_id' => auth()->id()]);
            }
        } else {
            return User::query()->select('id', 'title', 'firstname', 'lastname', 'nhs_number', 'date_of_birth', 'phone', 'emergency_contact', 'email', 'created_at', 'gender', 'height', 'weight', 'blood_type', 'address', 'availability', 'occupation','referral_source', 'gp_details', 'medication_allergies', 'current_medication', 'more_info', 'deleted_at', 'gmc_no', 'user_id')->where('role_id', 5);
        }
    }

    public function headings(): array
    {
        return [
            'Id',
            'Title',
            'First Name',
            'Last Name',
            'NHS Number',
            'Date of Birth',
            'Phone',
            'Emergency contacts',
            'Email',
            'Created On',
            'Gender',
            'Height',
            'Weight',
            'Blood type',
            'Address',
            'Availability',
            'Occupation',
            'Referral Source',
            'General Practioner (GP) Details',
            'Medication Allergies',
            'Current Medication',
            'More Information',
            'Deleted At',
            'Gmc No',
            'User Id',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Patients';
    }
}
