<?php

namespace App\Exports;

use App\InitialConsultation;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InitialConsultationsExport implements FromQuery, WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        if (auth()->user()->role_id == 3) {
            if (auth()->user()->company) {
                return InitialConsultation::query()->join('users', function ($join) {
                    $join->on('users.id', '=', 'initial_consultations.creator_id')
                        ->where('initial_consultations.company_id', auth()->user()->company->id);
                })->select('users.firstname', 'users.lastname', 'users.email', 'initial_consultations.id', 'initial_consultations.complain', 'initial_consultations.history_presenting_complaint', 'initial_consultations.past_medical_history', 'initial_consultations.drug_history', 'initial_consultations.social_history', 'initial_consultations.family_history', 'initial_consultations.drug_allergies', 'initial_consultations.examination', 'initial_consultations.diagnosis', 'initial_consultations.treatment', 'initial_consultations.company_id', 'initial_consultations.user_id', 'initial_consultations.created_at');
            } else {
                return InitialConsultation::query()->join('users', function ($join) {
                    $join->on('users.id', '=', 'initial_consultations.creator_id')
                        ->where('initial_consultations.creator_id', auth()->id());
                })->select('users.firstname', 'users.lastname', 'users.email', 'initial_consultations.id', 'initial_consultations.complain', 'initial_consultations.history_presenting_complaint', 'initial_consultations.past_medical_history', 'initial_consultations.drug_history', 'initial_consultations.social_history', 'initial_consultations.family_history', 'initial_consultations.drug_allergies', 'initial_consultations.examination', 'initial_consultations.diagnosis', 'initial_consultations.treatment', 'initial_consultations.company_id', 'initial_consultations.user_id', 'initial_consultations.created_at');
            }
        } else {
            return InitialConsultation::query()->join('users', 'users.id', '=', 'initial_consultations.user_id')->select('users.firstname', 'users.lastname', 'users.email', 'initial_consultations.id', 'initial_consultations.complain', 'initial_consultations.history_presenting_complaint', 'initial_consultations.past_medical_history', 'initial_consultations.drug_history', 'initial_consultations.social_history', 'initial_consultations.family_history', 'initial_consultations.drug_allergies', 'initial_consultations.examination', 'initial_consultations.diagnosis', 'initial_consultations.treatment', 'initial_consultations.company_id', 'initial_consultations.user_id', 'initial_consultations.created_at');
        }
    }

    public function headings(): array
    {
        return [
            'Patient FirstName',
            'Patient LastName',
            'Patient Email',
            'ID',
            'Presenting Complaint',
            'History Presenting Complaint',
            'Past Medical History',
            'Drug history',
            'Social History',
            'Family History',
            'Drug Allergies',
            'Examination',
            'Diagnosis',
            'Treatment',
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
        return 'Initial Consultations';
    }
}
