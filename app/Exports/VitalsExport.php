<?php

namespace App\Exports;

use App\Vital;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VitalsExport implements FromQuery, WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        if (auth()->user()->role_id == 3) {
            if (auth()->user()->company) {
                return Vital::query()->join('users', function ($join) {
                    $join->on('users.id', '=', 'vitals.creator_id')
                        ->where('vitals.company_id', auth()->user()->company->id);
                })->select('users.firstname', 'users.lastname', 'users.email', 'vitals.id', 'vitals.temperature', 'vitals.pulse_rate', 'vitals.pain', 'vitals.height', 'vitals.weight', 'vitals.systolic_bp', 'vitals.diastolic_bp', 'vitals.respiratory_rate', 'vitals.oxygen_saturation', 'vitals.o2_administered', 'vitals.head_circumference', 'vitals.company_id', 'vitals.user_id', 'vitals.created_at');
            } else {
                return Vital::query()->join('users', function ($join) {
                    $join->on('users.id', '=', 'vitals.creator_id')
                        ->where('vitals.creator_id', auth()->id());
                })->select('users.firstname', 'users.lastname', 'users.email', 'vitals.id', 'vitals.temperature', 'vitals.pulse_rate', 'vitals.pain', 'vitals.height', 'vitals.weight', 'vitals.systolic_bp', 'vitals.diastolic_bp', 'vitals.respiratory_rate', 'vitals.oxygen_saturation', 'vitals.o2_administered', 'vitals.head_circumference', 'vitals.company_id', 'vitals.user_id', 'vitals.created_at');
            }
        } else {
            return Vital::query()->join('users', 'users.id', '=', 'vitals.user_id')->select('users.firstname', 'users.lastname', 'users.email', 'vitals.id', 'vitals.temperature', 'vitals.pulse_rate', 'vitals.pain', 'vitals.height', 'vitals.weight', 'vitals.systolic_bp', 'vitals.diastolic_bp', 'vitals.respiratory_rate', 'vitals.oxygen_saturation', 'vitals.o2_administered', 'vitals.head_circumference', 'vitals.company_id', 'vitals.user_id', 'vitals.created_at');
        }
    }

    public function headings(): array
    {
        return [
            'Patient FirstName',
            'Patient LastName',
            'Patient Email',
            'ID',
            'Temp',
            'Pulse Rate',
            'Pain',
            'Height',
            'Weight',
            'Systolic Bp',
            'Diastolic Bp',
            'Respiratory Rate',
            'Oxygen Saturation',
            'O2 Administered',
            'Head Circumference',
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
        return 'Vitals';
    }
}
