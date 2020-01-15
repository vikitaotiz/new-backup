<?php

namespace App\Http\Controllers;

use App\Http\Requests\Appointments\CreateAppointment;

use Illuminate\Http\Request;
use App\User;
use App\Appointment;
use App\Service;

use Carbon\Carbon;

class PatientAppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['servicesCount'])->only(['create', 'store']);
    }

    public function create($id)
    {
        $patient = User::find($id);

        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if(auth()->user()->role_id == 3) {
            $users = User::where(['id' => auth()->id()])->get();
            $services = Service::where('user_id', auth()->id())
                ->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $users = User::where('role_id', 3)->whereIn('id', $company->users->pluck('id'))->get();
                    $services = Service::whereIn('user_id', $company->users->pluck('id'))
                        ->get();
                }
            } else {
                $users = collect();
                $services = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $users = User::where('role_id', 3)->whereIn('id', $company->users->pluck('id'))->get();
                $services = Service::whereIn('user_id', $company->users->pluck('id'))
                    ->get();
            }
        } else {
            $users = User::where('role_id', 3)->get();
            $services = Service::all();
        }

        return view('appointments.patientappointment', compact('users', 'services', 'patient'));
    }

    public function store(CreateAppointment $request)
    {
        $user_id = $request->user_id;

        $appointment = new Appointment;

        $appointment->service_id = $request->service_id;

        $appointment->appointment_date = Carbon::createFromFormat('Y-m-d H:i', $request->appointment_date);

        $appointment->end_time = $request->end_time;

        $appointment->color = $request->color;

        $appointment->status = 'open';

        $appointment->description = $request->description;

        $appointment->user_id = $request->user_id;

        $appointment->doctor_id = $request->doctor_id;

        $appointment->creator_id = auth()->user()->id;

        $appointment->save();

        return redirect()->route('patients.show', $user_id)->with('success', 'Appointment Updated Successfully');

    }
}
