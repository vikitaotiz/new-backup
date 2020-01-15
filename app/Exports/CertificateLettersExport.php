<?php

namespace App\Exports;

use App\Certificate;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CertificateLettersExport implements FromQuery, WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        if (auth()->user()->role_id == 3) {
            if (auth()->user()->company) {
                return Certificate::query()->join('users', function ($join) {
                    $join->on('users.id', '=', 'certificates.creator_id')
                        ->where('certificates.company_id', auth()->user()->company->id);
                })->select('users.firstname', 'users.lastname', 'users.email', 'certificates.id', 'certificates.name', 'certificates.body', 'certificates.company_id', 'certificates.user_id', 'certificates.created_at');
            } else {
                return Certificate::query()->join('users', function ($join) {
                    $join->on('users.id', '=', 'certificates.creator_id')
                        ->where('certificates.creator_id', auth()->id());
                })->select('users.firstname', 'users.lastname', 'users.email', 'certificates.id', 'certificates.name', 'certificates.body', 'certificates.company_id', 'certificates.user_id', 'certificates.created_at');
            }
        } else {
            return Certificate::query()->join('users', 'users.id', '=', 'certificates.user_id')->select('users.firstname', 'users.lastname', 'users.email', 'certificates.id', 'certificates.name', 'certificates.body', 'certificates.company_id', 'certificates.user_id', 'certificates.created_at');
        }
    }

    public function headings(): array
    {
        return [
            'Patient FirstName',
            'Patient LastName',
            'Patient Email',
            'ID',
            'Certificate Letter Name',
            'Body',
            'Company Id',
            'User Id',
            'Created On',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Certificate Letters';
    }
}
