<?php

namespace App\Exports;

use App\Referral;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReferralLettersExport implements FromQuery, WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        if (auth()->user()->role_id == 3) {
            if (auth()->user()->company) {
                return Referral::query()->join('users', function ($join) {
                    $join->on('users.id', '=', 'referrals.creator_id')
                        ->where('referrals.company_id', auth()->user()->company->id);
                })->select('users.firstname', 'users.lastname', 'users.email', 'referrals.id', 'referrals.name', 'referrals.body', 'referrals.company_id', 'referrals.user_id', 'referrals.created_at');
            } else {
                return Referral::query()->join('users', function ($join) {
                    $join->on('users.id', '=', 'referrals.creator_id')
                        ->where('referrals.creator_id', auth()->id());
                })->select('users.firstname', 'users.lastname', 'users.email', 'referrals.id', 'referrals.name', 'referrals.body', 'referrals.company_id', 'referrals.user_id', 'referrals.created_at');
            }
        } else {
            return Referral::query()->join('users', 'users.id', '=', 'referrals.user_id')->select('users.firstname', 'users.lastname', 'users.email', 'referrals.id', 'referrals.name', 'referrals.body', 'referrals.company_id', 'referrals.user_id', 'referrals.created_at');
        }
    }

    public function headings(): array
    {
        return [
            'Patient FirstName',
            'Patient LastName',
            'Patient Email',
            'ID',
            'Referral Letter Name',
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
        return 'Referral Letters';
    }
}
