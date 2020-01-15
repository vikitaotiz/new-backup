<?php

namespace App\Http\Controllers;

use App\CompanyDetail;
use App\EmbedUrl;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests\Patients\CreatePatients;
use App\Http\Requests\Patients\UpdatePatients;

use App\User;
use App\Role;

class UsersController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['root', 'admin', 'doctor', 'staff'])->only('create', 'store');
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // check user role
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if(auth()->user()->role_id == 3) {
            $users = User::where('id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $users = User::whereIn('id', $company->users->pluck('id'))
                        ->where('role_id', '!=', 6)
                        ->get();
                }
            } else {
                $users = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $users = User::whereIn('id', $company->users->pluck('id'))
                    ->where('role_id', '!=', 6)
                    ->get();
            }
        } else {
            $users = User::whereNotIn('role_id', [1, 2, 5])
                ->get();
        }

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        // check user role
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if(auth()->user()->role_id == 3) {
            $services = Service::where('user_id', auth()->id())
                ->get();
            $roles = Role::where('id', 5)->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $services = Service::whereIn('user_id', $company->users->pluck('id'))
                        ->get();
                    $roles = Role::whereBetween('id', [3, 6])->get();
                }
            } else {
                $services = collect();
                $roles = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $services = Service::whereIn('user_id', $company->users->pluck('id'))
                    ->get();
                $roles = Role::whereBetween('id', [3, 6])->get();
            }
        } else {
            $services = Service::all();
            $roles = Role::all();
            $companies = CompanyDetail::latest()->get();
        }

        return view('users.create', compact('roles', 'services', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate form data
        $request->validate([
            'title' => 'nullable|string|max:191',
            'firstname' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            'username' => 'required|string|max:191|unique:users',
            'gender' => 'nullable|string|max:191',
            'date_of_birth' => 'required|date',
            'email' => 'nullable|email|max:255',
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
            'slot' => 'nullable|integer',
            'role_id' => 'required|integer',
            'company_id' => 'required|integer',
            'password' => 'required|string|min:8',
        ]);

        if ($request->role_id == 3) {
            $request->validate([
                'service_id.*' => 'required|integer',
            ]);
        }

        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        $user = new User;

        $user->title = $request->title;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->username = $request->username;
        $user->gender = $request->gender;
        $user->date_of_birth = $request->date_of_birth;
        $user->email = $request->email;
        $user->gmc_no = $request->gmc_no;
        $user->phone = $request->phone;

        if($request->hasFile('profile_photo')) {
            $profile_photo = $request->profile_photo->store('public/users_profile_photos');
            $profile_photo = str_replace("public/", "", $profile_photo);
            $user->profile_photo = $profile_photo;
        }

        $user->password = Hash::make($request->password);
        $user->nhs_number = $request->nhs_number;
        $user->weight = $request->weight;
        $user->height = $request->height;
        $user->blood_type = $request->blood_type;
        $user->referral_source = $request->referral_source;
        $user->medication_allergies = $request->medication_allergies;
        $user->current_medication = $request->current_medication;
        $user->nhs_number = $request->nhs_number;
        $user->role_id = $request->role_id;
        $user->emergency_contact = $request->emergency_contact;
        $user->address = $request->address;
        $user->more_info = $request->more_info;
        $user->slot = $request->slot;

        if ($request->role_id == 5) {
            $user->user_id = auth()->id();
        }

        $user->save();

        if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2) {
            if (isset($request->company_id)) {
                $user->companies()->attach($request->company_id);
            }
        }

        if (isset($request->service_id)){
            if ($request->role_id == 3) {
                foreach ($request->service_id as $service) {
                    $user->services()->attach($service);
                }
            }
        }

        // check user role
        if ($request->role_id != 5 && auth()->user()->role_id == 6) {
            if (auth()->user()->company) {
                $user->companies()->attach(auth()->user()->company->id);
            }
        }

        return redirect()->route('users.show', $user->id)->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $user = User::findOrfail($id);

        foreach ($user->timings as $timing) {

            $from_date = $timing->from->format('D, jS, M, Y');
            $now_date = now()->format('D, jS, M, Y');

            if($from_date == $now_date){

                $status = $timing->status;
                $from = $from_date;
                $to = $timing->to->format('D, jS, M, Y');

            }
        }

        $status = 'Not Set';
        $from = 'Not Set';
        $to = 'Not Set';

        if(auth()->user()->role_id == 3) {
            $services = Service::where('user_id', auth()->id())
                ->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $services = Service::whereIn('user_id', $company->users->pluck('id'))
                        ->get();
                }
            } else {
                $services = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $services = Service::whereIn('user_id', $company->users->pluck('id'))
                    ->get();
            }
        } else {
            $services = Service::all();
        }

        return view('users.show', compact('user', 'status', 'from', 'to', 'services'));
    }

    public function my_profile()
    {
        $user = auth()->user();

        if ($user->profile_photo) {

            $profile_photo = asset('storage/'.$user->profile_photo);

        } else {
            $profile_photo = asset('img/user.png');
        }

        return view('users.my_profile', compact('user', 'profile_photo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::findOrfail($id);

        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if(auth()->user()->role_id == 3) {
            $services = Service::where('user_id', auth()->id())
                ->get();
            $roles = Role::where('id', 5)->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $services = Service::whereIn('user_id', $company->users->pluck('id'))
                        ->get();
                    $roles = Role::whereBetween('id', [3, 6])->get();
                }
            } else {
                $services = collect();
                $roles = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $services = Service::whereIn('user_id', $company->users->pluck('id'))
                    ->get();
                $roles = Role::whereBetween('id', [3, 6])->get();
            }
        } else {
            $services = Service::all();
            $roles = Role::all();
            $companies = CompanyDetail::latest()->get();
        }

        return view('users.edit', compact('user', 'roles', 'services', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
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
            'gender' => 'required|string|max:191',
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
            'slot' => 'nullable|integer',
            'role_id' => 'nullable|integer',
            'company_id' => 'required|integer',
            'password' => 'nullable|string|min:8',
        ]);

        if ($request->role_id == 3) {
            $request->validate([
                'service_id.*' => 'required|integer',
            ]);
        }

        $user = User::findOrfail($id);


        if($request->hasFile('profile_photo')) {

            $profile_photo = $request->profile_photo->store('public/users_profile_photos');
            $profile_photo = str_replace("public/", "", $profile_photo);

            if($user->profile_photo){
                Storage::delete($user->profile_photo);
            }

            $user->profile_photo = $profile_photo;

            $user->save();
        }

        $user->title = $request->title;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->username = $request->username;
        $user->gender = $request->gender;
        $user->date_of_birth = $request->date_of_birth;
        $user->email = $request->email;
        $user->gmc_no = $request->gmc_no;
        $user->phone = $request->phone;

        if($request->password)
        {
            $user->password = Hash::make($request->password);
        }

        $user->nhs_number = $request->nhs_number;
        $user->weight = $request->weight;
        $user->height = $request->height;
        $user->blood_type = $request->blood_type;
        $user->referral_source = $request->referral_source;
        $user->medication_allergies = $request->medication_allergies;
        $user->current_medication = $request->current_medication;
        $user->nhs_number = $request->nhs_number;

        if (isset($request->role_id) && !empty($request->role_id)) {
            $user->role_id = $request->role_id;
        }

        $user->emergency_contact = $request->emergency_contact;
        $user->address = $request->address;
        $user->more_info = $request->more_info;
        $user->slot = $request->slot;

        $user->save();

        if (isset($request->company_id)){
            // Detach previous companies
            $user->companies()->detach();
            $user->companies()->attach($request->company_id);
        }

        // Detach previous services
        $user->services()->detach();

        if (isset($request->service_id)){
            if ($request->role_id == 3) {
                foreach ($request->service_id as $service) {
                    $user->services()->attach($service);
                }
            }
        }

        if (auth()->user()->role && auth()->user()->role->id == 5)
            return redirect()->route('users.my_profile')->with('success', 'User updated successfully.');

        return redirect()->route('users.show', $user->id)->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $user = User::withTrashed()
            ->where('id', $id)
            ->firstOrFail();

        if ($user->trashed()) {

            if ($user->profile_photo) {
                Storage::delete( 'public/' . $user->profile_photo);
            }

            $user->forceDelete();
        } else {
            $user->delete();
        }

        return redirect('users')->with('success', 'User Deleted Successfully.');
    }

    public function trashed()
    {
        $trashed = User::onlyTrashed()
            ->get();

        $users = User::all();

        return view('users.index')
            ->withUsers($users)
            ->withUsers($trashed);

    }

    public function restore($id)
    {
        $user = User::withTrashed()
            ->where('id', $id)
            ->firstOrFail();

        $user->restore();

        return redirect()->back()->with('success', 'User Restored Successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateTimings(Request $request, $id)
    {
        // Validate form data
        $request->validate([
            'slot' => 'required|integer|min:1'
        ]);

        $user = User::findOrfail($id);

        $user->slot = $request->slot;

        $user->save();

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    /**
     * Display the specified resource.
     *
     * * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function doctorByService(Request $request, $id)
    {
        if (isset($request->id) && !empty($request->id)) {
            $url = EmbedUrl::find($request->id);

            if ($url) {
                $service = Service::find($id);
                if (count($url->doctors)) {
                    $users = $service->users()->whereIn('id', $url->doctors->pluck('user_id'))->get();
                } else {
                    $users = $service->users;
                }
            } else {
                abort(403);
            }
        } else {
            $service = Service::find($id);
            $users = $service->users;
        }

        return response()->json($users);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function serviceByDoctor($id)
    {
        $doctor = User::find($id);

        return response()->json($doctor->services);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allUsers()
    {
        try {
            if(auth()->user() != null && isset(auth()->user()->company->company)){
                auth()->user()->company = auth()->user()->company->company;
            }

            $input = \request()->all();
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

            $total = User::select('users.id')
                ->leftjoin('roles AS role', 'role.id', '=', 'users.role_id')
                ->whereNotIn('role_id', [1, 2, 5])
                ->where(function ($query) use ($input) {
                    if (auth()->user()->role_id == 3 || auth()->user()->role_id == 6 || auth()->user()->role_id == 4) {
                        $query->where('role_id', '!=', 6);

                        if (isset($input['doctors'])) {
                            $query->where(function ($q) use ($input) {
                                $q->whereIn('users.id', $input['doctors']);
                            });
                        } else {
                            $query->where(function ($q) use ($input) {
                                $q->where('users.id', auth()->user()->id);
                            });
                        }
                    }
                    $query->where(function ($q) use ($input) {
                        $q->where('users.firstname', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('users.availability', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('users.email', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('users.phone', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere(\DB::raw('role.name'), 'like', '%' . $input['keyword'] . '%');
                    });
                })->count();

            $sql = User::select('users.*', 'role.name AS role_name')
                ->leftjoin('roles AS role', 'role.id', '=', 'users.role_id')
                ->whereNotIn('role_id', [1, 2, 5])
                ->where(function ($query) use ($input) {
                    if (auth()->user()->role_id == 3 || auth()->user()->role_id == 6 || auth()->user()->role_id == 4) {
                        $query->where('role_id', '!=', 6);

                        if (isset($input['doctors'])) {
                            $query->where(function ($q) use ($input) {
                                $q->whereIn('users.id', $input['doctors']);
                            });
                        } else {
                            $query->where(function ($q) use ($input) {
                                $q->where('users.id', auth()->user()->id);
                            });
                        }
                    }
                    $query->where(function ($q) use ($input) {
                        $q->where('users.firstname', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('users.availability', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('users.email', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('users.phone', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere(\DB::raw('role.name'), 'like', '%' . $input['keyword'] . '%');
                    });
                });

            if ($sort_by == 'role') {
                $sql->orderBy(\DB::raw('role.name'), $order_by);
            } else {
                $sql->orderBy($sort_by, $order_by);
            }

            $users = $sql->skip($skip)->take($limit)->get();

            return response()->json(['status' => 200, 'data' => ['users' => $users, 'counts' => $total]], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'errors' => $e->getMessage()], 200);
        }
    }
}
