<?php

namespace App\Http\Controllers;

use App\PatientTreatmentNote;
use App\Service;
use App\Sms;
use App\SmsTemplate;
use App\Template;
use Dotenv\Regex\Regex;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests\Patients\CreatePatients;
use App\Http\Requests\Patients\UpdatePatients;

use App\Notifications\NewUserNotification;

use App\User;
use App\Role;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class PatientsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('patients.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        // check user role
        if(auth()->user()->role_id == 3) {
            $users = User::where('id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $users = User::where('role_id', 3)
                        ->whereIn('id', $company->users->pluck('id'))
                        ->get();
                }
            } else {
                $users = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $users = User::where('role_id', 3)
                    ->whereIn('id', $company->users->pluck('id'))
                    ->get();
            }
        } else {
            $users = User::where('role_id', 3)
                ->get();
        }

        return view('patients.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreatePatients $request)
    {
        $lastInsertedPatient = User::latest()->first();

        $patient = User::create([
            'title' => $request->title,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => strtolower($request->firstname) . ($lastInsertedPatient->id + 1),
            'phone' => $request->phone,
            'email' => $request->email,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'nhs_number' => $request->nhs_number,
            'emergency_contact' => $request->emergency_contact,
            'gp_details' => $request->gp_details,

            'occupation' => $request->occupation,
            'height' => $request->height,
            'weight' => $request->weight,
            'blood_type' => $request->blood_type,
            'referral_source' => $request->referral_source,
            'medication_allergies' => $request->medication_allergies,
            'current_medication' => $request->current_medication,

            'address' => $request->address,
            'password' => Hash::make($request->firstname),
            'more_info' => $request->more_info,

            'communication_preference' => $request->communication_preference,
            'privacy_policy' => $request->privacy_policy,
            'patient_note' => $request->patient_note,

            'user_id' => $request->user_id ? $request->user_id : auth()->user()->id,
            'creator_id' => auth()->user()->id
            ]
        );

        if ($request->hasFile('profile_photo')) {

            $profile_photo = $request->profile_photo->store('public/patient_profile_photos');
            $profile_photo = str_replace("public/", "", $profile_photo);

            $patient->profile_photo = $profile_photo;

            $patient->save();
        }

        User::find(auth()->user()->id)->notify(new NewUserNotification());

        return redirect('patients')->with('success', 'Patient Created Successfully.');

    }

    public function appointment_user(CreatePatients $request)
    {
        User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'phone' => $request->phone,
                'email' => $request->email,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,

                'password' => Hash::make($request->password),

                'user_id' => $request->user_id,
                'creator_id' => auth()->user()->id
            ]
        );

        User::find(auth()->user()->id)->notify(new NewUserNotification());

        return redirect()->back()->with('success', 'Patient Created Successfully.');

    }

    public function patient_ajax(Request $request)
    {
        User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'phone' => $request->phone,
            'email' => $request->email,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'password' => Hash::make($request->password),
            'user_id' => $request->user_id ? $request->user_id : auth()->user()->id,
            'creator_id' => auth()->user()->id
            ]
        );

        return redirect()->back()->with('success', 'Patient Created Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if(auth()->user()->role_id == 3) {
            $users = User::where('id', auth()->id())
                ->get();
            $services = Service::where('user_id', auth()->id())
                ->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $users = User::where('role_id', '!=', 5)
                        ->where('role_id', '!=', 1)
                        ->where('role_id', '!=', 2)
                        ->where('role_id', '!=', 6)
                        ->whereIn('id', $company->users->pluck('id'))
                        ->get();
                    $services = Service::whereIn('user_id', $company->users->pluck('id'))
                        ->get();
                }
            } else {
                $users = collect();
                $services = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $users = User::where('role_id', '!=', 5)
                    ->where('role_id', '!=', 1)
                    ->where('role_id', '!=', 2)
                    ->where('role_id', '!=', 6)
                    ->whereIn('id', $company->users->pluck('id'))
                    ->get();
                $services = Service::whereIn('user_id', $company->users->pluck('id'))
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

        $patient = User::find($id);
        $patient->patient_note_text = $patient->patient_note;
        $patient->patient_note_text = strip_tags($patient->patient_note_text);
        $patient->patient_note_text = preg_replace('/&nbsp;/', '', $patient->patient_note_text);
        $patient->patient_note_text = preg_replace('/\s+/', '', $patient->patient_note_text);

        /*if (auth()->user()->role_id == 3) {
            if (auth()->user()->company) {
                $templates = auth()->user()->templates;
            } else {
                if (auth()->user()->companies) {
                    foreach (auth()->user()->companies as $company){
                        $templates = Template::where('user_id', $company->id)->get();
                    }
                } else {
                    $templates = Template::where('user_id', auth()->id())->get();
                }
            }
        } elseif (auth()->user()->role_id == 4) {
            if (auth()->user()->companies) {
                foreach (auth()->user()->companies as $company){
                    $templates = Template::where('user_id', $company->id)->get();
                }
            } else {
                $templates = Template::where('user_id', auth()->id())->get();
            }
        } else {
            $templates = Template::all();
        }*/

        $templates = Template::all();
        $smsTemplates = SmsTemplate::all();

        return view('patients.show', compact('patient', 'services', 'users', 'templates', 'smsTemplates'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        $patient = User::find($id);

        $roles = Role::where('id', '!=', 1)->get();

        // check user role
        if(auth()->user()->role_id == 3) {
            $users = User::where('id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $users = User::where('role_id', 3)
                        ->whereIn('id', $company->users->pluck('id'))
                        ->get();
                }
            } else {
                $users = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $users = User::where('role_id', 3)
                    ->whereIn('id', $company->users->pluck('id'))
                    ->get();
            }
        } else {
            $users = User::where('role_id', 3)
                ->get();
        }

        return view('patients.edit', compact('patient', 'users', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validate form data
        $request->validate([
            'title' => 'nullable|string|max:191',
            'firstname' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            'username' => "required|string|max:191|unique:users,username,$id",
            'gender' => 'nullable|string|max:191',
            'date_of_birth' => 'required|date',
            'email' => 'nullable|email|max:191',
            'phone' => 'nullable|string|max:191',
            'profile_photo' => 'nullable|image',
            'nhs_number' => 'nullable|string|max:191',
            'address' => 'nullable|string|max:191',
            'emergency_contact' => 'nullable|string|max:191',
            'gp_details' => 'nullable|string|max:191',
            'occupation' => 'nullable|string|max:191',
            'height' => 'nullable|string|max:191',
            'weight' => 'nullable|string|max:191',
            'blood_type' => 'nullable|string|max:191',
            'gmc_no' => 'nullable|string|max:191',
            'referral_source' => 'nullable|string',
            'current_medication' => 'nullable|string',
            'more_info' => 'nullable|string',
            'password' => 'nullable|string|min:8',
            'privacy_policy' => 'nullable|integer',
            'communication_preference' => 'nullable|integer',
        ]);

        $patient = User::find($id);

        if ($request->hasFile('profile_photo')) {

            $profile_photo = $request->profile_photo->store('public/patient_profile_photos');
            $profile_photo = str_replace("public/", "", $profile_photo);

            if ($patient->profile_photo) {
                Storage::delete($patient->profile_photo);
            }

            $patient->profile_photo = $profile_photo;

            $patient->save();
        }

        if ($request->password) {
            $patient->password = Hash::make($request->password);
            $patient->save();
        }

        if ($request->user_id) {
            $patient->user_id = $request->user_id;
            $patient->save();
        }

        $patient->update([
            'title' => $request->title,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'phone' => $request->phone,
            'email' => $request->email,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'nhs_number' => $request->nhs_number,
            'emergency_contact' => $request->emergency_contact,
            'gp_details' => $request->gp_details,
            'occupation' => $request->occupation,
            'height' => $request->height,
            'weight' => $request->weight,
            'blood_type' => $request->blood_type,
            'referral_source' => $request->referral_source,
            'medication_allergies' => $request->medication_allergies,
            'current_medication' => $request->current_medication,
            'address' => $request->address,
            'more_info' => $request->more_info,
            'user_id' => $request->user_id,
            'communication_preference' => $request->communication_preference,
            'privacy_policy' => $request->privacy_policy,
            'patient_note' => $request->patient_note,
            ]
        );

        return redirect()->route('patients.show', $patient->id)->with('success', 'Patient Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $patient = User::withTrashed()
            ->where('id', $id)
            ->firstOrFail();

        if ($patient->trashed()) {

            if ($patient->profile_photo) {
                Storage::delete('public/' . $patient->profile_photo);
            }

            $patient->forceDelete();

        } else {

            $patient->delete();
        }

        return redirect('patients')->with('success', 'Patient Deleted Successfully.');
    }

    public function trashed()
    {
        return view('patients.trashed');
    }

    public function restore($id)
    {
        $patient = User::withTrashed()
            ->where('id', $id)
            ->firstOrFail();

        $patient->restore();

        return redirect()->back()->with('success', 'Patient Restored Successfully.');
    }

    public function newTemplate(Request $request, $id)
    {
        $type = $request->type ?? 'note';
        $type = $type == 'letter' ? $type : 'note';
        $patient = User::find($id);
        $templates = Template::all();
        return view('patients.newTemplate', compact('patient', 'templates', 'type'));
    }

    public function allPatients(Request $request)
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
            } elseif (auth()->user()->role_id == 3) {
                if (count(auth()->user()->companies)) {
                    foreach (auth()->user()->companies as $company) {
                        $input['doctors'] = $company->users->pluck('id');
                    }
                }
            }

            $total = User::select('id')
                ->where('role_id', 5)
                ->where(function ($query) use ($input) {
                    if (auth()->user()->role_id == 3 || auth()->user()->role_id == 6 || auth()->user()->role_id == 4) {
                        if (isset($input['doctors'])) {
                            $query->where(function ($q) use ($input) {
                                $q->whereIn('user_id', $input['doctors']);
                            });
                        } else {
                            $query->where(function ($q) use ($input) {
                                $q->where('user_id', auth()->user()->id);
                            });
                        }
                    }
                    $query->where(function ($q) use ($input) {
                        $q->where('nhs_number', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere(\DB::raw("CONCAT(firstname, ' ', lastname)"), 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('gender', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('date_of_birth', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('created_at', 'like', '%' . $input['keyword'] . '%');
                    });
                })->count();

            $patients = User::with('creator')
                ->where('role_id', 5)
                ->where(function ($query) use ($input) {
                    if (auth()->user()->role_id == 3 || auth()->user()->role_id == 6 || auth()->user()->role_id == 4) {
                        if (isset($input['doctors'])) {
                            $query->where(function ($q) use ($input) {
                                $q->whereIn('user_id', $input['doctors']);
                            });
                        } else {
                            $query->where(function ($q) use ($input) {
                                $q->where('user_id', auth()->user()->id);
                            });
                        }
                    }
                    $query->where(function ($q) use ($input) {
                        $q->where('nhs_number', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere(\DB::raw("CONCAT(firstname, ' ', lastname)"), 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('gender', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('date_of_birth', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('created_at', 'like', '%' . $input['keyword'] . '%');
                    });
                })->orderBy($sort_by, $order_by)->skip($skip)->take($limit)->get();

            return response()->json(['status' => 200, 'data' => ['patients' => $patients, 'counts' => $total]], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'errors' => $e->getMessage()], 200);
        }
    }

    public function trashedPatients(Request $request)
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

            $total = User::onlyTrashed()->select('id')
                ->where(function ($query) use ($input) {
                    if (auth()->user()->role_id == 3 || auth()->user()->role_id == 6 || auth()->user()->role_id == 4) {
                        if (isset($input['doctors'])) {
                            $query->where(function ($q) use ($input) {
                                $q->whereIn('user_id', $input['doctors']);
                            });
                        } else {
                            $query->where(function ($q) use ($input) {
                                $q->where('user_id', auth()->user()->id);
                            });
                        }
                    }
                    $query->where(function ($q) use ($input) {
                        $q->where('nhs_number', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('firstname', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('lastname', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('gender', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('date_of_birth', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('created_at', 'like', '%' . $input['keyword'] . '%');
                    });
                })->count();

            $patients = User::onlyTrashed()->with('creator')
                ->where(function ($query) use ($input) {
                    if (auth()->user()->role_id == 3 || auth()->user()->role_id == 6 || auth()->user()->role_id == 4) {
                        if (isset($input['doctors'])) {
                            $query->where(function ($q) use ($input) {
                                $q->whereIn('user_id', $input['doctors']);
                            });
                        } else {
                            $query->where(function ($q) use ($input) {
                                $q->where('user_id', auth()->user()->id);
                            });
                        }
                    }
                    $query->where(function ($q) use ($input) {
                        $q->where('nhs_number', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('firstname', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('lastname', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('gender', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('date_of_birth', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('created_at', 'like', '%' . $input['keyword'] . '%');
                    });
                })->orderBy($sort_by, $order_by)->skip($skip)->take($limit)->get();

            return response()->json(['status' => 200, 'data' => ['patients' => $patients, 'counts' => $total]], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'errors' => $e->getMessage()], 200);
        }
    }

    public function sendSMS(Request $request)
    {
        // Validate form data
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
            'user_id' => 'required|integer',
        ]);

        if (isset($request->save_as_template)) {
            // Validate form data
            $request->validate([
                'template_title' => 'required|string|max:191',
            ]);
        }

        try {
            // Your Account SID and Auth Token from twilio.com/console
            $sid    = env( 'TWILIO_SID' );
            $token  = env( 'TWILIO_TOKEN' );
            $client = new Client( $sid, $token );

            // Send the message
            $client->messages->create(
                $request->phone,
                [
                    'from' => env('TWILIO_FROM'),
                    'body' => $request->message,
                ]
            );

            // Insert into DB
            Sms::create([
                'from' => env('TWILIO_FROM'),
                'to' => $request->phone,
                'message' => $request->message,
                'user_id' => $request->user_id,
            ]);

            // If save as template
            if (isset($request->save_as_template)) {
                SmsTemplate::create([
                    'title' => $request->template_title,
                    'body' => $request->message,
                ]);
            }

            return redirect()->back()->with('success', 'SMS Sent Successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
