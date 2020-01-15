<?php

namespace App\Exports;

use App\Sicknote;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SickNoteLettersExport implements FromQuery, WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        if (auth()->user()->role_id == 3) {
            if (auth()->user()->company) {
                return Sicknote::query()->join('users', function ($join) {
                    $join->on('users.id', '=', 'sicknotes.creator_id')
                        ->where('sicknotes.company_id', auth()->user()->company->id);
                })->select('users.firstname', 'users.lastname', 'users.email', 'sicknotes.id', 'sicknotes.name', 'sicknotes.body', 'sicknotes.company_id', 'sicknotes.user_id', 'sicknotes.created_at');
            } else {
                return Sicknote::query()->join('users', function ($join) {
                    $join->on('users.id', '=', 'sicknotes.creator_id')
                        ->where('sicknotes.creator_id', auth()->id());
                })->select('users.firstname', 'users.lastname', 'users.email', 'sicknotes.id', 'sicknotes.name', 'sicknotes.body', 'sicknotes.company_id', 'sicknotes.user_id', 'sicknotes.created_at');
            }
        } else {
            return Sicknote::query()->join('users', 'users.id', '=', 'sicknotes.user_id')->select('users.firstname', 'users.lastname', 'users.email', 'sicknotes.id', 'sicknotes.name', 'sicknotes.body', 'sicknotes.company_id', 'sicknotes.user_id', 'sicknotes.created_at');
        }
    }

    public function headings(): array
    {
        return [
            'Patient FirstName',
            'Patient LastName',
            'Patient Email',
            'ID',
            'Sick Note Letter Name',
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
        return 'Sick Note Letters';
    }
}
