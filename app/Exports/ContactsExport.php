<?php

namespace App\Exports;

use App\Contact;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ContactsExport implements FromQuery, WithHeadings, WithTitle
{
    use Exportable;

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        if (auth()->user()->role_id == 3) {
            if (auth()->user()->company) {
                return Contact::query()->join('users', function ($join) {
                    $join->on('users.id', '=', 'contacts.creator_id')
                        ->whereIn('contacts.creator_id', auth()->user()->company->users->pluck('id'));
                })->select('users.firstname', 'users.lastname', 'users.email as user_email', 'contacts.nhs_number', 'contacts.relative_name', 'contacts.relationship_type', 'contacts.date_of_birth', 'contacts.phone', 'contacts.email  as contact_email', 'contacts.medical_history', 'contacts.more_info as contact_more_info', 'contacts.user_id', 'contacts.created_at');
            } else {
                return Contact::query()->join('users', function ($join) {
                    $join->on('users.id', '=', 'contacts.creator_id')
                        ->where('contacts.creator_id', auth()->id());
                })->select('users.firstname', 'users.lastname', 'users.email as user_email', 'contacts.nhs_number', 'contacts.relative_name', 'contacts.relationship_type', 'contacts.date_of_birth', 'contacts.phone', 'contacts.email  as contact_email', 'contacts.medical_history', 'contacts.more_info as contact_more_info', 'contacts.user_id', 'contacts.created_at');
            }
        } else {
            return Contact::query()->join('users', 'users.id', '=', 'contacts.user_id')->select('users.firstname', 'users.lastname', 'users.email as user_email', 'contacts.nhs_number', 'contacts.relative_name', 'contacts.relationship_type', 'contacts.date_of_birth', 'contacts.phone', 'contacts.email  as contact_email', 'contacts.medical_history', 'contacts.more_info as contact_more_info', 'contacts.user_id', 'contacts.created_at');
        }
    }

    public function headings(): array
    {
        return [
            'Patient FirstName',
            'Patient LastName',
            'Patient Email',
            'NHS Number',
            'Name',
            'Relationship Type',
            'Date of Birth',
            'Phone',
            'Email',
            'Medical History',
            'More Info',
            'User Id',
            'Created On',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Contacts';
    }
}
