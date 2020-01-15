<?php

namespace App\Exports;

use App\Appointment;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class AppointmentsExport implements FromView, WithTitle
{
    public function view(): View
    {
        if (auth()->user()->role_id == 3) {
            if (auth()->user()->company) {
                $appointments = collect();

                foreach (auth()->user()->company->users as $user) {
                    $appointment = Appointment::latest()->where('doctor_id', $user->id)->get();

                    if (count($appointment)) {
                        $appointments->push($appointment);
                    }
                }

                $appointments = $appointments->collapse();
            } else {
                $appointments = Appointment::latest()->where('doctor_id', auth()->id())->get();
            }
        } else {
            $appointments = Appointment::all();
        }

        return view('inc.patients.single_patient.appointments_export', [
            'appointments' => $appointments
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Appointments';
    }
}
