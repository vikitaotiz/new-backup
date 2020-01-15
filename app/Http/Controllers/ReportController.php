<?php

namespace App\Http\Controllers;

use App\Invoice;
use DateTime;
use App\User;
use App\Service;
use Carbon\Carbon;
use App\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createReferalMarketing()
    {
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if(auth()->user()->role_id == 3) {
            $users = User::where('id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $users = User::where('role_id', '!=', 5)
                        ->where('role_id', '!=', 1)
                        ->where('role_id', '!=', 2)
                        ->where('role_id', '!=', 6)
                        ->where('availability', 1)
                        ->whereIn('id', $company->users->pluck('id'))
                        ->get();
                }
            } else {
                $users = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $users = User::where('role_id', '!=', 5)
                    ->where('role_id', '!=', 1)
                    ->where('role_id', '!=', 2)
                    ->where('role_id', '!=', 6)
                    ->where('availability', 1)
                    ->whereIn('id', $company->users->pluck('id'))
                    ->get();
            }
        } else {
            $users = User::where('role_id', '!=', 5)
                ->where('role_id', '!=', 1)
                ->where('role_id', '!=', 2)
                ->where('role_id', '!=', 6)
                ->where('availability', 1)
                ->get();
        }

        return view('reports.referral_sources', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createPatientsByTotalInvoiced()
    {
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if(auth()->user()->role_id == 3) {
            $users = User::where('id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $users = User::where('role_id', '!=', 5)
                        ->where('role_id', '!=', 1)
                        ->where('role_id', '!=', 2)
                        ->where('role_id', '!=', 6)
                        ->where('availability', 1)
                        ->whereIn('id', $company->users->pluck('id'))
                        ->get();
                }
            } else {
                $users = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $users = User::where('role_id', '!=', 5)
                    ->where('role_id', '!=', 1)
                    ->where('role_id', '!=', 2)
                    ->where('role_id', '!=', 6)
                    ->where('availability', 1)
                    ->whereIn('id', $company->users->pluck('id'))
                    ->get();
            }
        } else {
            $users = User::where('role_id', '!=', 5)
                ->where('role_id', '!=', 1)
                ->where('role_id', '!=', 2)
                ->where('role_id', '!=', 6)
                ->where('availability', 1)
                ->get();
        }

        return view('reports.patients_by_total_invoiced', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createMarketingAppointments()
    {
        $appointments = [];
        $labels = [];
        $months = [];
        $years = [];

        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        $i = 4;
        while($i >= 0){
            $dateObj   = DateTime::createFromFormat('!m', Carbon::now()->subMonth($i)->month);
            $monthName = $dateObj->format('F'); // March

            $labels[] = $monthName . ' ' . Carbon::now()->subMonth($i)->year;
            $months[] = Carbon::now()->subMonth($i)->month;
            $years[] = Carbon::now()->subMonth($i)->year;

            $i--;
        }

        if (auth()->user()->role_id == 3) {
            $services = Service::where('user_id', auth()->id())->get();
            foreach ($services as $service) {
                $value = [];

                for ($i = 0; $i < 5; $i++) {
                    $value[] = Appointment::whereMonth('appointment_date', $months[$i])->whereYear('appointment_date', $years[$i])->where('doctor_id', auth()->id())->where('service_id', $service->id)->count();
                }

                $appointments[$service->name] = $value;
            }
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $services = Service::whereIn('user_id', $company->users->pluck('id'))->get();
                    foreach ($services as $service) {
                        $value = [];

                        for ($i = 0; $i < 5; $i++) {
                            $value[] = Appointment::whereMonth('appointment_date', $months[$i])->whereYear('appointment_date', $years[$i])->whereIn('doctor_id', $company->users->pluck('id'))->where('service_id', $service->id)->count();
                        }

                        $appointments[$service->name] = $value;
                    }
                }
            } else {
                $services = collect();

                foreach ($services as $service) {
                    $value = [];

                    for ($i = 0; $i < 5; $i++) {
                        $value[] = Appointment::whereMonth('appointment_date', $months[$i])->whereYear('appointment_date', $years[$i])->where('service_id', $service->id)->count();
                    }

                    $appointments[$service->name] = $value;
                }
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $services = Service::whereIn('user_id', $company->users->pluck('id'))->get();
                foreach ($services as $service) {
                    $value = [];

                    for ($i = 0; $i < 5; $i++) {
                        $value[] = Appointment::whereMonth('appointment_date', $months[$i])->whereYear('appointment_date', $years[$i])->whereIn('doctor_id', $company->users->pluck('id'))->where('service_id', $service->id)->count();
                    }

                    $appointments[$service->name] = $value;
                }
            }
        } else {
            $services = Service::all();
            foreach ($services as $service) {
                $value = [];

                for ($i = 0; $i < 5; $i++) {
                    $value[] = Appointment::whereMonth('appointment_date', $months[$i])->whereYear('appointment_date', $years[$i])->where('service_id', $service->id)->count();
                }

                $appointments[$service->name] = $value;
            }
        }

        $labels = json_encode($labels);
        $data = '';
        $i = 0;

        $colors = ['#f6ff48', '#ff3428', '#00ff67', '#15fff7', '#2a88ff',
        '#ff8675', '#e552ff', '#ff7c00', '#a6ff0f', '#8b6cff'];

        foreach ($appointments as $index => $value) {
            $i += 1;

            $data .= '{
                label: "' . $index . '",
                backgroundColor: "' . $colors[array_rand($colors)] . '",
                data: ' . json_encode($value) . ',
                stack: ' . $i . ',
            },';
        }

        $onlineAppointment = Appointment::where('creator_id', 2)->count();
        $otherAppointment = Appointment::where('creator_id', '!=', 2)->count();

        return view('reports.marketing_appointments', compact('data', 'labels', 'onlineAppointment', 'otherAppointment'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createMissedAppointments()
    {
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if(auth()->user()->role_id == 5) {
            $users = User::where('id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 3) {
            $users = User::where('id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $users = User::where('role_id', '!=', 5)
                        ->where('role_id', '!=', 1)
                        ->where('role_id', '!=', 2)
                        ->where('role_id', '!=', 6)
                        ->where('availability', 1)
                        ->whereIn('id', $company->users->pluck('id'))
                        ->get();
                }
            } else {
                $users = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            $users = User::where('role_id', '!=', 5)
                ->where('role_id', '!=', 1)
                ->where('role_id', '!=', 2)
                ->where('role_id', '!=', 6)
                ->where('availability', 1)
                ->whereIn('id', auth()->user()->company->users->pluck('id'))
                ->get();
        } else {
            $users = User::where('role_id', '!=', 5)
                ->where('role_id', '!=', 1)
                ->where('role_id', '!=', 2)
                ->where('role_id', '!=', 6)
                ->where('availability', 1)
                ->get();
        }

        return view('reports.missed_appointments', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createAppointmentsSchedule()
    {
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if(auth()->user()->role_id == 5) {
            $users = User::where('id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 3) {
            $users = User::where('id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $users = User::where('role_id', '!=', 5)
                        ->where('role_id', '!=', 1)
                        ->where('role_id', '!=', 2)
                        ->where('role_id', '!=', 6)
                        ->where('availability', 1)
                        ->whereIn('id', $company->users->pluck('id'))
                        ->get();
                }
            } else {
                $users = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            $users = User::where('role_id', '!=', 5)
                ->where('role_id', '!=', 1)
                ->where('role_id', '!=', 2)
                ->where('role_id', '!=', 6)
                ->where('availability', 1)
                ->whereIn('id', auth()->user()->company->users->pluck('id'))
                ->get();
        } else {
            $users = User::where('role_id', '!=', 5)
                ->where('role_id', '!=', 1)
                ->where('role_id', '!=', 2)
                ->where('role_id', '!=', 6)
                ->where('availability', 1)
                ->get();
        }

        return view('reports.appointments_schedule', compact('users'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function appointmentsSchedule(Request $request)
    {
        // Validate form data
        $rules = array(
            'appointment_date' => 'required|date',
            'end_date' => 'nullable|date|after:appointment_date',
            'doctor_id' => 'required',
        );

        $validator = Validator::make ( $request->all(), $rules);

        if ($validator->fails()){
            return response()->json(array('errors'=> $validator->getMessageBag()->toarray()));
        }

        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if (auth()->user()->role_id == 3) {
            if ($request->end_date) {
                $appointments = Appointment::with('doctor', 'user', 'service')->whereBetween('appointment_date', [$request->appointment_date, $request->end_date])->where('doctor_id', auth()->id())->groupBy('appointment_date')->groupBy('from')->get();
            } else {
                $appointments = Appointment::with('doctor', 'user', 'service')->whereDate('appointment_date', $request->appointment_date)->where('doctor_id', auth()->id())->groupBy('appointment_date')->groupBy('from')->get();
            }
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    if ($request->doctor_id == 'all') {
                        if ($request->end_date) {
                            $appointments = Appointment::with('doctor', 'user', 'service')->whereBetween('appointment_date', [$request->appointment_date, $request->end_date])->whereIn('doctor_id', $company->users->pluck('id'))->groupBy('appointment_date')->groupBy('from')->get();
                        } else {
                            $appointments = Appointment::with('doctor', 'user', 'service')->whereDate('appointment_date', $request->appointment_date)->whereIn('doctor_id', $company->users->pluck('id'))->groupBy('appointment_date')->groupBy('from')->get();
                        }
                    } else {
                        if ($request->end_date) {
                            $appointments = Appointment::with('doctor', 'user', 'service')->whereBetween('appointment_date', [$request->appointment_date, $request->end_date])->where('doctor_id', $request->doctor_id)->groupBy('appointment_date')->groupBy('from')->get();
                        } else {
                            $appointments = Appointment::with('doctor', 'user', 'service')->whereDate('appointment_date', $request->appointment_date)->where('doctor_id', $request->doctor_id)->groupBy('appointment_date')->groupBy('from')->get();
                        }
                    }
                }
            } else {
                if ($request->doctor_id == 'all') {
                    if ($request->end_date) {
                        $appointments = collect();
                    } else {
                        $appointments = collect();
                    }
                } else {
                    if ($request->end_date) {
                        $appointments = collect();
                    } else {
                        $appointments = collect();
                    }
                }
            }
        } elseif (auth()->user()->role_id == 6) {
            if ($request->doctor_id == 'all') {
                if ($request->end_date) {
                    $appointments = Appointment::with('doctor', 'user', 'service')->whereBetween('appointment_date', [$request->appointment_date, $request->end_date])->whereIn('doctor_id', auth()->user()->company->users->pluck('id'))->groupBy('appointment_date')->groupBy('from')->get();
                } else {
                    $appointments = Appointment::with('doctor', 'user', 'service')->whereDate('appointment_date', $request->appointment_date)->whereIn('doctor_id', auth()->user()->company->users->pluck('id'))->groupBy('appointment_date')->groupBy('from')->get();
                }
            } else {
                if ($request->end_date) {
                    $appointments = Appointment::with('doctor', 'user', 'service')->whereBetween('appointment_date', [$request->appointment_date, $request->end_date])->where('doctor_id', $request->doctor_id)->groupBy('appointment_date')->groupBy('from')->get();
                } else {
                    $appointments = Appointment::with('doctor', 'user', 'service')->whereDate('appointment_date', $request->appointment_date)->where('doctor_id', $request->doctor_id)->groupBy('appointment_date')->groupBy('from')->get();
                }
            }
        } else {
            if ($request->doctor_id == 'all') {
                if ($request->end_date) {
                    $appointments = Appointment::with('doctor', 'user', 'service')->whereBetween('appointment_date', [$request->appointment_date, $request->end_date])->groupBy('appointment_date')->groupBy('from')->get();
                } else {
                    $appointments = Appointment::with('doctor', 'user', 'service')->whereDate('appointment_date', $request->appointment_date)->groupBy('appointment_date')->groupBy('from')->get();
                }
            } else {
                if ($request->end_date) {
                    $appointments = Appointment::with('doctor', 'user', 'service')->whereBetween('appointment_date', [$request->appointment_date, $request->end_date])->where('doctor_id', $request->doctor_id)->groupBy('appointment_date')->groupBy('from')->get();
                } else {
                    $appointments = Appointment::with('doctor', 'user', 'service')->whereDate('appointment_date', $request->appointment_date)->where('doctor_id', $request->doctor_id)->groupBy('appointment_date')->groupBy('from')->get();
                }
            }
        }

        $services = [];
        $doctors = [];

        foreach ($appointments as $appointment) {
            if (array_key_exists($appointment->service->name, $services))
            {
                $services[$appointment->service->name] += 1;
            }
            else
            {
                $services[$appointment->service->name] = 1;
            }

            if (array_key_exists($appointment->doctor->firstname . ' ' . $appointment->doctor->lastname, $doctors))
            {
                $doctors[$appointment->doctor->firstname . ' ' . $appointment->doctor->lastname] += 1;
            }
            else
            {
                $doctors[$appointment->doctor->firstname . ' ' . $appointment->doctor->lastname] = 1;
            }
        }

        return response()->json(['status' => 200, 'data' => ['appointments' => $appointments, 'services' => $services, 'doctors' => $doctors]]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function missedAppointments(Request $request)
    {
        // Validate form data
        $rules = array(
            'appointment_date' => 'required|date',
            'end_date' => 'nullable|date|after:appointment_date',
            'doctor_id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){
            return response()->json(array('errors'=> $validator->getMessageBag()->toarray()));
        }

        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if (auth()->user()->role_id == 3) {
            if ($request->end_date) {
                $appointments = Appointment::with('doctor', 'user', 'service')->whereBetween('appointment_date', [$request->appointment_date, $request->end_date])->where(['doctor_id' => auth()->id(), 'progress' => 'did_not_attend'])->groupBy('appointment_date')->groupBy('from')->get();
            } else {
                $appointments = Appointment::with('doctor', 'user', 'service')->whereDate('appointment_date', $request->appointment_date)->where(['doctor_id' => auth()->id(), 'progress' => 'did_not_attend'])->groupBy('appointment_date')->groupBy('from')->get();
            }
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    if ($request->doctor_id == 'all') {
                        if ($request->end_date) {
                            $appointments = Appointment::with('doctor', 'user', 'service')->whereBetween('appointment_date', [$request->appointment_date, $request->end_date])->whereIn('doctor_id', $company->users->pluck('id'))->where('progress', 'did_not_attend')->groupBy('appointment_date')->groupBy('from')->get();
                        } else {
                            $appointments = Appointment::with('doctor', 'user', 'service')->whereDate('appointment_date', $request->appointment_date)->whereIn('doctor_id', $company->users->pluck('id'))->where('progress', 'did_not_attend')->groupBy('appointment_date')->groupBy('from')->get();
                        }
                    } else {
                        if ($request->end_date) {
                            $appointments = Appointment::with('doctor', 'user', 'service')->whereBetween('appointment_date', [$request->appointment_date, $request->end_date])->where(['doctor_id' => $request->doctor_id, 'progress' => 'did_not_attend'])->groupBy('appointment_date')->groupBy('from')->get();
                        } else {
                            $appointments = Appointment::with('doctor', 'user', 'service')->whereDate('appointment_date', $request->appointment_date)->where(['doctor_id' => $request->doctor_id, 'progress' => 'did_not_attend'])->groupBy('appointment_date')->groupBy('from')->get();
                        }
                    }
                }
            } else {
                if ($request->doctor_id == 'all') {
                    if ($request->end_date) {
                        $appointments = collect();
                    } else {
                        $appointments = collect();
                    }
                } else {
                    if ($request->end_date) {
                        $appointments = collect();
                    } else {
                        $appointments = collect();
                    }
                }
            }
        } elseif (auth()->user()->role_id == 6) {
            if ($request->doctor_id == 'all') {
                if ($request->end_date) {
                    $appointments = Appointment::with('doctor', 'user', 'service')->whereBetween('appointment_date', [$request->appointment_date, $request->end_date])->whereIn('doctor_id', auth()->user()->company->users->pluck('id'))->where('progress', 'did_not_attend')->groupBy('appointment_date')->groupBy('from')->get();
                } else {
                    $appointments = Appointment::with('doctor', 'user', 'service')->whereDate('appointment_date', $request->appointment_date)->whereIn('doctor_id', auth()->user()->company->users->pluck('id'))->where('progress', 'did_not_attend')->groupBy('appointment_date')->groupBy('from')->get();
                }
            } else {
                if ($request->end_date) {
                    $appointments = Appointment::with('doctor', 'user', 'service')->whereBetween('appointment_date', [$request->appointment_date, $request->end_date])->where(['doctor_id' => $request->doctor_id, 'progress' => 'did_not_attend'])->groupBy('appointment_date')->groupBy('from')->get();
                } else {
                    $appointments = Appointment::with('doctor', 'user', 'service')->whereDate('appointment_date', $request->appointment_date)->where(['doctor_id' => $request->doctor_id, 'progress' => 'did_not_attend'])->groupBy('appointment_date')->groupBy('from')->get();
                }
            }
        } else {
            if ($request->doctor_id == 'all') {
                if ($request->end_date) {
                    $appointments = Appointment::with('doctor', 'user', 'service')->whereBetween('appointment_date', [$request->appointment_date, $request->end_date])->where('progress', 'did_not_attend')->groupBy('appointment_date')->groupBy('from')->get();
                } else {
                    $appointments = Appointment::with('doctor', 'user', 'service')->whereDate('appointment_date', $request->appointment_date)->where('progress', 'did_not_attend')->groupBy('appointment_date')->groupBy('from')->get();
                }
            } else {
                if ($request->end_date) {
                    $appointments = Appointment::with('doctor', 'user', 'service')->whereBetween('appointment_date', [$request->appointment_date, $request->end_date])->where(['doctor_id' => $request->doctor_id, 'progress' => 'did_not_attend'])->groupBy('appointment_date')->groupBy('from')->get();
                } else {
                    $appointments = Appointment::with('doctor', 'user', 'service')->whereDate('appointment_date', $request->appointment_date)->where(['doctor_id' => $request->doctor_id, 'progress' => 'did_not_attend'])->groupBy('appointment_date')->groupBy('from')->get();
                }
            }
        }

        $services = [];
        $doctors = [];

        foreach ($appointments as $appointment) {
            if (array_key_exists($appointment->service->name, $services))
            {
                $services[$appointment->service->name] += 1;
            }
            else
            {
                $services[$appointment->service->name] = 1;
            }

            if (array_key_exists($appointment->doctor->firstname . ' ' . $appointment->doctor->lastname, $doctors))
            {
                $doctors[$appointment->doctor->firstname . ' ' . $appointment->doctor->lastname] += 1;
            }
            else
            {
                $doctors[$appointment->doctor->firstname . ' ' . $appointment->doctor->lastname] = 1;
            }
        }

        return response()->json(['status' => 200, 'data' => ['appointments' => $appointments, 'services' => $services, 'doctors' => $doctors]]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function marketingAppointments(Request $request)
    {
        // Validate form data
        $rules = array(
            'appointment_date' => 'required|date',
            'end_date' => 'required|date|after:appointment_date',
        );

        $validator = Validator::make ( $request->all(), $rules);

        if ($validator->fails()){
            return response()->json(array('errors'=> $validator->getMessageBag()->toarray()));
        }

        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if (auth()->user()->role_id == 3) {
            $appointments = Appointment::whereBetween('appointment_date', [$request->appointment_date, $request->end_date])->where('doctor_id', auth()->id())->count();
            $services = Service::where('user_id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $appointments = Appointment::whereBetween('appointment_date', [$request->appointment_date, $request->end_date])->whereIn('doctor_id', $company->users->pluck('id'))->count();
                    $services = Service::whereIn('user_id', $company->users->pluck('id'))->get();
                }
            } else {
                $services = collect();
                $appointments = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            $appointments = Appointment::whereBetween('appointment_date', [$request->appointment_date, $request->end_date])->whereIn('doctor_id', auth()->user()->company->users->pluck('id'))->count();
            $services = Service::whereIn('user_id', auth()->user()->company->users->pluck('id'))->get();
        } else {
            $services = Service::all();
            $appointments = Appointment::whereBetween('appointment_date', [$request->appointment_date, $request->end_date])->count();
        }

        $labels = [];
        $data = [];

        foreach ($services as $service) {
            $labels[] = $service->name;
            $data[] = Appointment::where('service_id', $service->id)->whereBetween('appointment_date', [$request->appointment_date, $request->end_date])->count();
        }

        return response()->json([$labels, $data, $appointments]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function patientsByTotalInvoiced(Request $request)
    {
        // Validate form data
        $rules = array(
            'from' => 'required|date',
            'to' => 'required|date|after:from',
            'doctor_id' => 'required',
        );

        $validator = Validator::make ( $request->all(), $rules);

        if ($validator->fails()){
            return response()->json(array('errors'=> $validator->getMessageBag()->toarray()));
        }

        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if (auth()->user()->role_id == 3) {
            $invoices = Invoice::with('user', 'charge', 'currency')->whereBetween('created_at', [$request->from, $request->to])->where('doctor_id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    if ($request->doctor_id == 'all') {
                        $invoices = Invoice::with('doctor', 'charge', 'currency')->whereBetween('created_at', [$request->from, $request->to])->whereIn('doctor_id', $company->users->pluck('id'))->get();
                    } else {
                        $invoices = Invoice::with('doctor', 'charge', 'currency')->whereBetween('created_at', [$request->from, $request->to])->where('doctor_id', $request->doctor_id)->get();
                    }
                }
            } else {
                if ($request->doctor_id == 'all') {
                    $invoices = collect();
                } else {
                    $invoices = collect();
                }
            }
        } elseif (auth()->user()->role_id == 6) {
            if ($request->doctor_id == 'all') {
                $invoices = Invoice::with('doctor', 'charge', 'currency')->whereBetween('created_at', [$request->from, $request->to])->whereIn('doctor_id', auth()->user()->company->users->pluck('id'))->get();
            } else {
                $invoices = Invoice::with('doctor', 'charge', 'currency')->whereBetween('created_at', [$request->from, $request->to])->where('doctor_id', $request->doctor_id)->get();
            }
        } else {
            if ($request->doctor_id == 'all') {
                $invoices = Invoice::with('user', 'charge', 'currency')->whereBetween('created_at', [$request->from, $request->to])->get();
            } else {
                $invoices = Invoice::with('user', 'charge', 'currency')->whereBetween('created_at', [$request->from, $request->to])->where('doctor_id', $request->doctor_id)->get();
            }
        }

        return response()->json($invoices);
    }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function referalMarketing(Request $request)
    {
        // Validate form data
        $rules = array(
            'from' => 'required|date',
            'to' => 'required|date|after:from',
            'doctor_id' => 'required',
        );

        $validator = Validator::make ( $request->all(), $rules);

        if ($validator->fails()){
            return response()->json(array('errors'=> $validator->getMessageBag()->toarray()));
        }

        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if (auth()->user()->role_id == 3) {
            $patients = User::whereBetween('created_at', [$request->from, $request->to])->where('user_id', auth()->id())->where('referral_source', '!=', null)->where('role_id', 5)->count();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    if ($request->doctor_id == 'all') {
                        $patients = User::whereBetween('created_at', [$request->from, $request->to])->whereIn('user_id', $company->users->pluck('id'))->where('referral_source', '!=', null)->where('role_id', 5)->count();
                    } else {
                        $patients = User::whereBetween('created_at', [$request->from, $request->to])->where('user_id', $request->doctor_id)->where('referral_source', '!=', null)->where('role_id', 5)->count();
                    }
                }
            } else {
                if ($request->doctor_id == 'all') {
                    $patients = collect();
                } else {
                    $patients = collect();
                }
            }
        } elseif (auth()->user()->role_id == 6) {
            if ($request->doctor_id == 'all') {
                $patients = User::whereBetween('created_at', [$request->from, $request->to])->whereIn('user_id', auth()->user()->company->users->pluck('id'))->where('referral_source', '!=', null)->where('role_id', 5)->count();
            } else {
                $patients = User::whereBetween('created_at', [$request->from, $request->to])->where('user_id', $request->doctor_id)->where('referral_source', '!=', null)->where('role_id', 5)->count();
            }
        } else {
            if ($request->doctor_id == 'all') {
                $patients = User::whereBetween('created_at', [$request->from, $request->to])->where('referral_source', '!=', null)->where('role_id', 5)->count();
            } else {
                $patients = User::whereBetween('created_at', [$request->from, $request->to])->where('user_id', $request->doctor_id)->where('referral_source', '!=', null)->where('role_id', 5)->count();
            }
        }

        return response()->json($patients);
    }
}
