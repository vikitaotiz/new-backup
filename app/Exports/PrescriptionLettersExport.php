<?php

namespace App\Exports;

use App\Prescription;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class PrescriptionLettersExport implements FromView, WithTitle
{
    public function view(): View
    {
        if (auth()->user()->role_id == 3) {
            if (auth()->user()->company) {
                $prescriptions = Prescription::where('company_id', auth()->user()->company->id)->get();
            } else {
                $prescriptions = Prescription::where('creator_id', auth()->id())->get();
            }
        } else {
            $prescriptions = Prescription::all();
        }

        return view('inc.patients.single_patient.prescriptionLettersExport', [
            'prescriptions' => $prescriptions
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Prescription Letters';
    }
}
