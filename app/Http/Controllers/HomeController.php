<?php

namespace App\Http\Controllers;

use App\Task;
use App\User;
use App\Appointment;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        $appointments = auth()->user()->appointments;

        // check user role
        if (auth()->user()->role_id == 3) {
            $tasks = Task::where('doctor_id', auth()->id())->count();
            $appointmentsAll = Appointment::where('doctor_id', auth()->id())->count();
            $doctors = 1;

            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $patients = User::where('role_id', 5)->whereIn('user_id', $company->users->pluck('id'))->count();
                }
            } else {
                $patients = User::where(['role_id' => 5, 'user_id' => auth()->id()])->count();
            }
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                $count = 0;
                $appointmentsAll = 0;
                $tasks = 0;

                foreach (auth()->user()->companies as $company) {
                    $doctors = User::whereIn('id', $company->users->pluck('id'))
                        ->where('role_id', '!=', 6)
                        ->count();

                    foreach ($company->users as $user) {
                        $count += User::where(['role_id' => 5, 'user_id' => $user->id])->count();
                        $appointmentsAll += Appointment::where('doctor_id', $user->id)->count();
                        $tasks += Task::where('doctor_id', $user->id)->count();
                    }
                }

                $patients = $count;
            } else {
                $tasks = 0;
                $appointmentsAll = 0;
                $doctors = 0;
                $patients = 0;
            }
        } elseif (auth()->user()->role_id == 6) {
            $count = 0;
            $appointmentsAll = 0;
            $tasks = 0;

            foreach (auth()->user()->company->users as $user) {
                $count += User::where(['role_id' => 5, 'user_id' => $user->id])->count();
                $appointmentsAll += Appointment::where('doctor_id', $user->id)->count();
                $tasks += Task::where('doctor_id', $user->id)->count();
            }

            $doctors = User::whereIn('id', auth()->user()->company->users->pluck('id'))
                ->where('role_id', '!=', 6)
                ->count();
            $patients = $count;
        } else {
            $tasks = Task::all()->count();
            $appointmentsAll = Appointment::all()->count();
            $doctors = User::whereNotIn('role_id', [1, 2, 5])
                ->count();
            $patients = User::where('role_id', 5)->count();
        }

        $appointmentsPastWeek = [];
        $appointmentsPastWeekLabel = [];
        $appointmentsPostWeek = [];
        $appointmentsPostWeekLabel = [];

        // Appointments for past seven days
        for ($i = 7; $i > 0; $i--) {
            $appointmentsPastWeekLabel[] = date('Y-m-d', strtotime("-".$i." days"));

            if (auth()->user()->role_id == 3){
                $appointmentsPastWeek[] = Appointment::where('doctor_id', auth()->id())->whereDate('appointment_date', date('Y-m-d', strtotime("-".$i." days")))->count();
            } elseif (auth()->user()->role_id == 4) {
                if (count(auth()->user()->companies)) {
                    $count = 0;

                    foreach (auth()->user()->companies as $company) {
                        foreach ($company->users as $user) {
                            $count += Appointment::where('doctor_id', $user->id)->whereDate('appointment_date', date('Y-m-d', strtotime("-".$i." days")))->count();
                        }
                    }
                } else {
                    $appointmentsPastWeek[] = 0;
                }
            } elseif (auth()->user()->role_id == 6) {
                $count = 0;

                foreach (auth()->user()->company->users as $user) {
                    $count += Appointment::where('doctor_id', $user->id)->whereDate('appointment_date', date('Y-m-d', strtotime("-".$i." days")))->count();
                }

                $appointmentsPastWeek[] = $count;
            } else {
                $appointmentsPastWeek[] = Appointment::whereDate('appointment_date', date('Y-m-d', strtotime("-".$i." days")))->count();
            }
        }

        // Appointments for next seven days
        for ($i = 1; $i < 8; $i++) {
            $appointmentsPostWeekLabel[] = date('Y-m-d', strtotime("+".$i." days"));

            if (auth()->user()->role_id == 3){
                $appointmentsPostWeek[] = Appointment::where('doctor_id', auth()->id())->whereDate('appointment_date', date('Y-m-d', strtotime("+".$i." days")))->count();
            } elseif (auth()->user()->role_id == 4) {
                if (count(auth()->user()->companies)) {
                    $count = 0;

                    foreach (auth()->user()->companies as $company) {
                        foreach ($company->users as $user) {
                            $count += Appointment::where('doctor_id', $user->id)->whereDate('appointment_date', date('Y-m-d', strtotime("+".$i." days")))->count();
                        }
                    }
                } else {
                    $appointmentsPostWeek[] = 0;
                }
            } elseif (auth()->user()->role_id == 6) {
                $count = 0;

                foreach (auth()->user()->company->users as $user) {
                    $count += Appointment::where('doctor_id', $user->id)->whereDate('appointment_date', date('Y-m-d', strtotime("+".$i." days")))->count();
                }

                $appointmentsPostWeek[] = $count;
            } else {
                $appointmentsPostWeek[] = Appointment::whereDate('appointment_date', date('Y-m-d', strtotime("+".$i." days")))->count();
            }
        }

        $appointmentsPastWeekLabel = json_encode($appointmentsPastWeekLabel);
        $appointmentsPastWeek = json_encode($appointmentsPastWeek);
        $appointmentsPostWeekLabel = json_encode($appointmentsPostWeekLabel);
        $appointmentsPostWeek = json_encode($appointmentsPostWeek);

        return view('home', compact('appointments', 'patients', 'doctors', 'appointmentsAll', 'tasks', 'appointmentsPastWeekLabel', 'appointmentsPastWeek', 'appointmentsPostWeekLabel', 'appointmentsPostWeek'));
    }
}
