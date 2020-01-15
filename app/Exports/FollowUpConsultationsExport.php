<?php

namespace App\Exports;

use App\FollowupConsultation;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FollowUpConsultationsExport implements FromQuery, WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        if (auth()->user()->role_id == 3) {
            if (auth()->user()->company) {
                return FollowupConsultation::query()->join('users', function ($join) {
                    $join->on('users.id', '=', 'followup_consultations.creator_id')
                        ->where('followup_consultations.company_id', auth()->user()->company->id);
                })->select('users.firstname', 'users.lastname', 'users.email', 'followup_consultations.id', 'followup_consultations.patient_progress', 'followup_consultations.assessment', 'followup_consultations.plan', 'followup_consultations.company_id', 'followup_consultations.user_id', 'followup_consultations.created_at');
            } else {
                return FollowupConsultation::query()->join('users', function ($join) {
                    $join->on('users.id', '=', 'followup_consultations.creator_id')
                        ->where('followup_consultations.creator_id', auth()->id());
                })->select('users.firstname', 'users.lastname', 'users.email', 'followup_consultations.id', 'followup_consultations.patient_progress', 'followup_consultations.assessment', 'followup_consultations.plan', 'followup_consultations.company_id', 'followup_consultations.user_id', 'followup_consultations.created_at');
            }
        } else {
            return FollowupConsultation::query()->join('users', 'users.id', '=', 'followup_consultations.user_id')->select('users.firstname', 'users.lastname', 'users.email', 'followup_consultations.id', 'followup_consultations.patient_progress', 'followup_consultations.assessment', 'followup_consultations.plan', 'followup_consultations.company_id', 'followup_consultations.user_id', 'followup_consultations.created_at');
        }
    }

    public function headings(): array
    {
        return [
            'Patient FirstName',
            'Patient LastName',
            'Patient Email',
            'ID',
            'Patient Progress',
            'Assessment',
            'Plan',
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
        return 'Follow Up Consultations';
    }
}
