<?php

namespace App\Http\Controllers;

use App\EmbedUrl;
use Illuminate\Http\Request;

use App\Http\Requests\Appointments\CreateAppointment;
use App\Http\Requests\Appointments\UpdateAppointment;

use Carbon\Carbon;
use App\Appointment;
use App\Service;
use App\User;
use App\CompanyDetail;
use Calendar;
use Illuminate\Support\Facades\Hash;

class AppointmentsController extends Controller
{

    public function patientName($id){
        $user = User::withTrashed()->findOrFail($id);

        if ($user) {
            return $user->firstname . ' ' . $user->lastname;
        } else {
            return '';
        }
    }

    public function __construct()
    {
        $this->middleware(['servicesCount'])->only(['create', 'store']);
    }

    public function fullCalendar()
    {
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        // set appointment from-to null data to appropiate
        $nullAppointments = Appointment::whereNull('from')->get();
        foreach ($nullAppointments as $appointment) {
            $addTime = $appointment->doctor ? $appointment->doctor->slot : 0;
            $appointment->from = date('H:i:s', strtotime($appointment->appointment_date));
            $appointment->to = date('H:i:s', strtotime('+'.$addTime.' minutes',  strtotime($appointment->appointment_date)));
            $appointment->update();
        }

        if(auth()->user()->role_id == 5) {
            $patients = User::where(['id' => auth()->id()])->get();
            if (auth()->user()->doctor) {
                if (count(auth()->user()->doctor->companies)) {
                    foreach (auth()->user()->doctor->companies as $company) {
                        $services = Service::whereIn('user_id', $company->users->pluck('id'))
                            ->get();
                        $users = User::whereIn('id', $company->users->pluck('id'))
                            ->where('role_id', '!=', 5)
                            ->where('role_id', '!=', 1)
                            ->where('role_id', '!=', 2)
                            ->where('role_id', '!=', 6)
                            ->where('availability', 1)
                            ->get();
                    }
                } else {
                    $users = User::where('role_id', '!=', 5)
                        ->where('role_id', '!=', 1)
                        ->where('role_id', '!=', 2)
                        ->where('role_id', '!=', 6)
                        ->where('availability', 1)
                        ->get();
                    $services = Service::all();
                }
            } else {
                $users = User::where('role_id', '!=', 5)
                    ->where('role_id', '!=', 1)
                    ->where('role_id', '!=', 2)
                    ->where('role_id', '!=', 6)
                    ->where('availability', 1)
                    ->get();
                $services = Service::all();
            }
        } elseif (auth()->user()->role_id == 3) {
            $patients = User::where(['role_id' => 5, 'user_id' => auth()->id()])->get();
            $users = User::where('id', auth()->id())
                ->get();
            $services = Service::where('user_id', auth()->id())
                ->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $patients = User::where(['role_id' => 5])->whereIn('user_id', $company->users->pluck('id'))->get();
                    $services = Service::whereIn('user_id', $company->users->pluck('id'))
                        ->get();
                    $users = $company->users;
                }
            } else {
                $patients = collect();
                $users = collect();
                $services = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $patients = User::where(['role_id' => 5])->whereIn('user_id', $company->users->pluck('id'))->get();
                $services = Service::whereIn('user_id', $company->users->pluck('id'))
                    ->get();
                $users = $company->users;
            }
        } else {
            $patients = User::latest()->where('role_id', 5)->get();
            $users = User::where('role_id', '!=', 5)
                ->where('role_id', '!=', 1)
                ->where('role_id', '!=', 2)
                ->where('availability', 1)
                ->get();
            $services = Service::all();
        }

        $events = [];


        if(auth()->user()->role_id == 5) {
            $data = auth()->user()->appointments;
        } elseif (auth()->user()->role_id == 3) {
            $data = Appointment::where(['doctor_id' => auth()->id()])->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $data = $company->users->map->doctorAppointments->collapse();
                }
            } else {
                $data = User::with('appointments')->get()->map->appointments->collapse();
            }
        } elseif (auth()->user()->role_id == 6) {
            $data = auth()->user()->company->users->map->doctorAppointments->collapse();
        } else {
            $data = User::with('appointments')->get()->map->appointments->collapse();
        }

        if($data->count()) {
            foreach ($data as $key => $value) {
                defaultTimedEventDuration : '00:10:00';
                // $end_date = $value->end_date."24:00:00";

                $events[] = Calendar::event(
                    $value->name,
                    false,
                    new \DateTime(date('Y-m-d', strtotime($value->appointment_date)) . $value->from),
                    new \DateTime(date('Y-m-d', strtotime($value->appointment_date)) . $value->to),
                    $value->id,
                    [
                        'color' => $value->color,
                        'title' => $this->patientName($value->user_id),
                        //'slotDuration' => '00:10' // 10 minutes
                        //'slots' => [(object) ['start' => '07:35', 'end' => '08:20'], (object) ['start' => '08:25', 'end' => '09:10']]
                    ]
                );
            }
        }


        $calendar = Calendar::addEvents($events)
        ->setOptions([
            'slotDuration' => '00:10'
        ])
        ->setCallbacks([
            'dayClick' => 'function createEvent(date){
                return addEvent(date);
            }',

            'eventRender' => 'function(event, element) {
                $(element).tooltip({title: event.title});
            }',

            'eventClick' => 'function(event) {
                window.location.replace("./appointments/"+event.id);

              }'


        ]);

        return view('appointments.calendar',
               compact('calendar', 'users', 'patients', 'services'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if(auth()->user()->role_id == 5) {
            $appointments = auth()->user()->appointments;
            $patients = User::where(['id' => auth()->id()])->get();
            if (auth()->user()->doctor) {
                if (count(auth()->user()->doctor->companies)) {
                    foreach (auth()->user()->doctor->companies as $company) {
                        $services = Service::whereIn('user_id', $company->users->pluck('id'))
                            ->get();
                        $users = User::whereIn('id', $company->users->pluck('id'))
                            ->where('role_id', '!=', 5)
                            ->where('role_id', '!=', 1)
                            ->where('role_id', '!=', 2)
                            ->where('role_id', '!=', 6)
                            ->where('availability', 1)
                            ->get();
                    }
                } else {
                    $users = User::where('role_id', '!=', 5)
                        ->where('role_id', '!=', 1)
                        ->where('role_id', '!=', 2)
                        ->where('role_id', '!=', 6)
                        ->where('availability', 1)
                        ->get();
                    $services = Service::all();
                }
            } else {
                $users = User::where('role_id', '!=', 5)
                    ->where('role_id', '!=', 1)
                    ->where('role_id', '!=', 2)
                    ->where('role_id', '!=', 6)
                    ->where('availability', 1)
                    ->get();
                $services = Service::all();
            }
        } elseif (auth()->user()->role_id == 3) {
            $appointments = Appointment::latest()->where('doctor_id', auth()->id())->get();
            $patients = User::where(['role_id' => 5, 'user_id' => auth()->id()])->get();
            $users = User::where('id', auth()->id())
                ->get();
            $services = Service::where('user_id', auth()->id())
                ->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $patients = User::where(['role_id' => 5])->whereIn('user_id', $company->users->pluck('id'))->get();
                    $services = Service::whereIn('user_id', $company->users->pluck('id'))
                        ->get();
                    $users = User::whereIn('id', $company->users->pluck('id'))
                        ->where('role_id', '!=', 5)
                        ->where('role_id', '!=', 1)
                        ->where('role_id', '!=', 2)
                        ->where('role_id', '!=', 6)
                        ->where('availability', 1)
                        ->get();
                }
            } else {
                $appointments = collect();
                $patients = collect();
                $users = collect();
                $services = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $patients = User::where(['role_id' => 5])->whereIn('user_id', $company->users->pluck('id'))->get();
                $services = Service::whereIn('user_id', $company->users->pluck('id'))
                    ->get();
                $users = User::whereIn('id', $company->users->pluck('id'))
                    ->where('role_id', '!=', 5)
                    ->where('role_id', '!=', 1)
                    ->where('role_id', '!=', 2)
                    ->where('role_id', '!=', 6)
                    ->where('availability', 1)
                    ->get();
            }
        } else {
            $appointments = Appointment::all();
            $patients = User::latest()->where('role_id', 5)->get();
            $users = User::where('role_id', '!=', 5)
                ->where('role_id', '!=', 1)
                ->where('role_id', '!=', 2)
                ->where('role_id', '!=', 6)
                ->where('availability', 1)
                ->get();
            $services = Service::all();
        }

        return view('appointments.index', compact('appointments', 'patients', 'users', 'services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $patients = User::latest()->where('role_id', 5)->get();

        $users = User::where('role_id', '!=', 5)
                      ->where('role_id', '!=', 1)
                      ->where('role_id', '!=', 2)
                      ->where('availability', 1)
                      ->get();

        $services = Service::all();

        return view('appointments.create', compact('patients', 'users', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        // Validate form data
        $request->validate([
            'description' => 'nullable|string',
            'appointment_date' => 'required|date_format:Y-m-d|after:yesterday',
            'from' => 'required|date_format:h:iA',
            'service_id' => 'required|integer',
            'doctor_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);

        $appointment = new Appointment;

        $appointment->service_id = $request->service_id;
        $appointment->appointment_date = date('Y-m-d H:i:s', strtotime($request->appointment_date));
        $appointment->end_time = $request->end_time;
        $appointment->from = date('H:i:s', strtotime($request->from));

        if ($slot = User::find($request->doctor_id)->slot) {
            $appointment->to = date('H:i:s', strtotime($request->from) + $slot * 60);
        } else {
            $appointment->to = date('H:i:s', strtotime($request->from) + 900);
        }

        $appointment->status = 'open';
        $appointment->description = $request->description;

        $appointment->user_id = $request->user_id;
        $appointment->doctor_id = $request->doctor_id;
        $appointment->creator_id = auth()->user()->id;

        $appointment->save();

        return redirect('appointments')->with('success', 'Appointment Updated Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $appointment = Appointment::findOrfail($id);

        $company = $this->activeCompany();

        return view('appointments.show', compact('appointment', 'company'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $appointment = Appointment::findOrfail($id);

        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if(auth()->user()->role_id == 5) {
            $patients = User::where(['id' => auth()->id()])->get();
            if (auth()->user()->doctor) {
                if (count(auth()->user()->doctor->companies)) {
                    foreach (auth()->user()->doctor->companies as $company) {
                        $patients = User::where(['role_id' => 5])->whereIn('user_id', $company->users->pluck('id'))->get();
                        $services = Service::whereIn('user_id', $company->users->pluck('id'))
                            ->get();
                        $users = User::whereIn('id', $company->users->pluck('id'))
                            ->whereNotIn('role_id', [1, 2, 5, 6])
                            ->where('availability', 1)
                            ->get();
                    }
                } else {
                    $patients = User::latest()->where('role_id', 5)->get();
                    $users = User::whereNotIn('role_id', [1, 2, 5, 6])
                        ->where('availability', 1)
                        ->get();
                    $services = Service::all();
                }
            } else {
                $patients = User::latest()->where('role_id', 5)->get();
                $users = User::whereNotIn('role_id', [1, 2, 5, 6])
                    ->where('availability', 1)
                    ->get();
                $services = Service::all();
            }
        } elseif (auth()->user()->role_id == 3) {
            $patients = User::where(['role_id' => 5, 'user_id' => auth()->id()])->get();
            $users = User::where('id', auth()->id())
                ->get();
            $services = Service::where('user_id', auth()->id())
                ->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $patients = User::where(['role_id' => 5])->whereIn('user_id', $company->users->pluck('id'))->get();
                    $services = Service::whereIn('user_id', $company->users->pluck('id'))
                        ->get();
                    $users = User::whereIn('id', $company->users->pluck('id'))
                        ->whereNotIn('role_id', [1, 2, 5, 6])
                        ->where('availability', 1)
                        ->get();
                }
            } else {
                $patients = collect();
                $users = collect();
                $services = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            if (auth()->user()->company) {
                $patients = User::where(['role_id' => 5])->whereIn('user_id', auth()->user()->company->users->pluck('id'))->get();
                $services = Service::whereIn('user_id', auth()->user()->company->users->pluck('id'))
                    ->get();
                $users = User::whereIn('id', auth()->user()->company->users->pluck('id'))
                    ->whereNotIn('role_id', [1, 2, 5, 6])
                    ->where('availability', 1)
                    ->get();
            } else {
                $patients = collect();
                $services = collect();
                $users = collect();
            }
        } else {
            $patients = User::latest()->where('role_id', 5)->get();
            $users = User::whereNotIn('role_id', [1, 2, 5, 6])
                ->get();
            $services = Service::all();
        }

        return view('appointments.edit', compact('appointment', 'users', 'services', 'patients'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateAppointment $request, $id)
    {
        $appointment = Appointment::findOrfail($id);
        $appointment->service_id = $request->service_id;
        $appointment->appointment_date = date('Y-m-d H:i:s', strtotime($request->appointment_date));
        $appointment->end_time = $request->end_time;
        $appointment->from = date('H:i:s', strtotime($request->from));

        if ($slot = User::find($request->doctor_id)->slot) {
            $appointment->to = date('H:i:s', strtotime($request->from) + $slot * 60);
        } else {
            $appointment->to = date('H:i:s', strtotime($request->from) + 900);
        }

        $appointment->description = $request->description;
        $appointment->user_id = $request->user_id;
        $appointment->doctor_id = $request->doctor_id;
        $appointment->save();

        return redirect()->route('appointments.show', $appointment->id)->with('success', 'Appointment Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $appointment = Appointment::findOrfail($id);

        $appointment->delete();

        return redirect('appointments')->with('success', 'Appointment Deleted Successfully');
    }

    public function close(Request $request, $id)
    {
        $appointment = Appointment::findOrfail($id);

        $appointment->end_time = $request->end_time;

        $appointment->status = 'close';

        $appointment->save();

        return redirect()->back()->with('success', 'Appointment closed successfully');
    }

    public function open($id)
    {
        $appointment = Appointment::findOrfail($id);

        $appointment->status = 'open';

        $appointment->save();

        return redirect()->back()->with('success', 'Appointment opened successfully');
    }

    public function progress(Request $request, $id)
    {
        $appointment = Appointment::findOrfail($id);

        $appointment->progress = $request->progress;

        $appointment->save();

        return redirect()->back()->with('success', 'Appointment progress changed successfully');
    }


    public function activeCompany()
    {
        $companies = CompanyDetail::all();

        foreach ($companies as $company) {
            if ($company->status == 1) {
               return $company->first();
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createAppointment(Request $request)
    {
        if (isset($request->id)) {
            $url = EmbedUrl::find($request->id);
            if ($url) {
                $clinic = CompanyDetail::find($url->company_detail_id);

                if ($clinic) {
                    if (count($url->services)) {
                        $services = Service::whereIn('id', $url->services->pluck('service_id'))->get();
                    } else {
                        $services = Service::whereIn('user_id', $clinic->users->pluck('id'))->get();
                    }
                } else {
                    abort(403);
                }
            } else {
                abort(403);
            }
        } elseif (isset($request->clinic)) {
            if (!$request->clinic) abort(403);
            $clinic = CompanyDetail::where('uuid', $request->clinic)->first();
            if (!$clinic || !$clinic->owner) abort(403);
            $services = Service::whereIn('user_id', $clinic->users->pluck('id'))->get();
        } else {
            abort(403);
        }

        return view('appointments.book', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function appointmentBook(Request $request)
    {
        // Validate form data
        $request->validate([
            'description' => 'nullable|string',
            'appointment_date' => 'required|date_format:Y-m-d|after:yesterday',
            'from' => 'required|date_format:h:iA',
            'service_id' => 'required|integer',
            'doctor_id' => 'required|integer',
            'firstname' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            'gender' => 'required|string|max:191',
            'email' => 'required|string|max:191',
            'phone' => 'required|string|max:191',
            'dob_day' => 'required|integer',
            'dob_month' => 'required|integer',
            'dob_year' => 'required|integer',
            'more_info' => 'nullable|string',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $user = new User();
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->gender = $request->gender;
            $user->date_of_birth = date('Y-m-d', strtotime($request->dob_year . '-' .$request->dob_month . '-' .$request->dob_day));
            $user->more_info = $request->more_info;
            $user->password = Hash::make('12345678');
            $user->save();
        }

        $appointment = new Appointment;

        $appointment->service_id = $request->service_id;
        $appointment->appointment_date = date('Y-m-d H:i:s', strtotime($request->appointment_date));
        $appointment->end_time = $request->end_time;
        $appointment->color = $request->color;
        $appointment->from = date('H:i:s', strtotime($request->from));

        if ($slot = User::find($request->doctor_id)->slot) {
            $appointment->to = date('H:i:s', strtotime($request->from) + $slot * 60);
        } else {
            $appointment->to = date('H:i:s', strtotime($request->from) + 900);
        }

        $appointment->status = 'open';
        $appointment->description = $request->description;

        $appointment->user_id = $user->id;
        $appointment->doctor_id = $request->doctor_id;
        $appointment->creator_id = 2;

        $appointment->save();

        return redirect()->back()->with('success', 'Appointment Created Successfully');
    }

    public function pngToBase64(Request $request)
    {
        $image64 = base64_encode(file_get_contents($request->link));
        return response(['success'=>true, 'image64'=>$image64]);
    }

    public function base64ToPng(Request $request)
    {
        $data = base64_decode($request['imgData']);
        $destinationPath = public_path('/');
        $file_path = rand(00000000, 99999999) . '.png';
        $folder = 'images/template';
        $file = $destinationPath.$folder.'/'.$file_path;
        if (!file_exists($destinationPath.$folder)) {
            mkdir($destinationPath.$folder, 0777, true);
        }
        file_put_contents($file, $data);
        return response(['success'=>true, 'imageSrc'=>$folder.'/'.$file_path]);
    }

    public function allAppointments(Request $request)
    {
        try {
            if(auth()->user() != null && isset(auth()->user()->company->company)){
                auth()->user()->company = auth()->user()->company->company;
            }

            $input = $request->input();
            $input['keyword'] = isset($input['keyword']) ? $input['keyword'] : '';
            $pageNo = isset($input['pageNo']) && $input['pageNo'] > 0 ? $input['pageNo'] : 1;
            $limit = isset($input['perPage']) ? $input['perPage'] : 10;
            $skip = $limit * ($pageNo - 1);
            $sort_by = isset($input['sort_by']) ? $input['sort_by'] : 'id';
            $order_by = isset($input['order_by']) ? $input['order_by'] : 'desc';

            if (auth()->user()->role_id == 6) {
                $input['doctors'] = auth()->user()->company->users->pluck('id');
            } elseif (auth()->user()->role_id == 4) {
                if (count(auth()->user()->companies)) {
                    foreach (auth()->user()->companies as $company) {
                        $input['doctors'] = $company->users->pluck('id');
                    }
                } else {
                    $input['doctors'] = [];
                }
            }

            $total = Appointment::select('appointments.id')
                ->leftjoin('services','services.id','=','appointments.service_id')
                ->leftjoin('users AS A','A.id','=','appointments.user_id')
                ->leftjoin('users AS B','B.id','=','appointments.doctor_id')
                ->where(function ($query) use ($input) {
                    if (auth()->user()->role_id == 3 || auth()->user()->role_id == 6 || auth()->user()->role_id == 4) {
                        if (isset($input['doctors'])) {
                            $query->where(function ($q) use ($input) {
                                $q->whereIn('appointments.doctor_id', $input['doctors']);
                            });
                        } else {
                            $query->where(function ($q) use ($input) {
                                $q->where('appointments.doctor_id', auth()->user()->id);
                            });
                        }
                    }
                    if (auth()->user()->role_id == 5) {
                        $query->where(function ($q) use ($input) {
                            $q->where('appointments.user_id', auth()->user()->id);
                        });
                    }
                    $query->where(function ($q) use ($input) {
                        $q->where('appointments.progress', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('appointments.status', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('appointments.appointment_date', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('appointments.from', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('services.name', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere(\DB::raw("CONCAT(A.firstname, ' ', A.lastname)"), 'like', '%' . $input['keyword'] . '%')
                            ->orWhere(\DB::raw("CONCAT(B.firstname, ' ', B.lastname)"), 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('appointments.to', 'like', '%' . $input['keyword'] . '%');
                    });
                    if (isset($input['appointment_date']) && isset($input['end_date'])) {
                        $query->whereBetween('appointments.appointment_date', [$input['appointment_date'], $input['end_date']]);
                    }
                    elseif (isset($input['appointment_date'])) {
                        $query->whereDate('appointments.appointment_date', $input['appointment_date']);
                    }
                    if (isset($input['doctor_id']) && $input['doctor_id'] != 'all') {
                        $query->where('appointments.doctor_id', $input['doctor_id']);
                    }
                })->count();

            $sql = Appointment::select('appointments.*', 'services.name as service_name',
                \DB::raw("CONCAT(A.firstname, ' ', A.lastname) as patient_name"),
                \DB::raw("CONCAT(B.firstname, ' ', B.lastname) as doctor_name"))
                ->leftjoin('services','services.id','=','appointments.service_id')
                ->leftjoin('users AS A','A.id','=','appointments.user_id')
                ->leftjoin('users AS B','B.id','=','appointments.doctor_id')
                ->where(function ($query) use ($input) {
                    if (auth()->user()->role_id == 3 || auth()->user()->role_id == 6 || auth()->user()->role_id == 4) {
                        if (isset($input['doctors'])) {
                            $query->where(function ($q) use ($input) {
                                $q->whereIn('appointments.doctor_id', $input['doctors']);
                            });
                        } else {
                            $query->where(function ($q) use ($input) {
                                $q->where('appointments.doctor_id', auth()->user()->id);
                            });
                        }
                    }
                    if (auth()->user()->role_id == 5) {
                        $query->where(function ($q) use ($input) {
                            $q->where('appointments.user_id', auth()->user()->id);
                        });
                    }
                    $query->where(function ($q) use ($input) {
                        $q->where('appointments.progress', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('appointments.status', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('appointments.appointment_date', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('appointments.from', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('services.name', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere(\DB::raw("CONCAT(A.firstname, ' ', A.lastname)"), 'like', '%' . $input['keyword'] . '%')
                            ->orWhere(\DB::raw("CONCAT(B.firstname, ' ', B.lastname)"), 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('appointments.to', 'like', '%' . $input['keyword'] . '%');
                    });
                    if (isset($input['appointment_date']) && isset($input['end_date'])) {
                        $query->whereBetween('appointments.appointment_date', [$input['appointment_date'], $input['end_date']]);
                    }
                    elseif (isset($input['appointment_date'])) {
                        $query->whereDate('appointments.appointment_date', $input['appointment_date']);
                    }
                    if (isset($input['doctor_id']) && $input['doctor_id'] != 'all') {
                        $query->where('appointments.doctor_id', $input['doctor_id']);
                    }
                });

            if ($sort_by == 'patient') {
                $sql->orderBy(\DB::raw("CONCAT(A.firstname, ' ', A.lastname)"), $order_by);
            } elseif ($sort_by == 'service') {
                $sql->orderBy(\DB::raw('services.name'), $order_by);
            } else {
                $sql->orderBy($sort_by, $order_by);
            }

            $appointments = $sql->skip($skip)->take($limit)->get();

            return response()->json(['status' => 200, 'data' => ['appointments' => $appointments, 'counts' => $total]], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'errors' => $e->getMessage()], 200);
        }
    }
}
