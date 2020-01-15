<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Contact;
use App\User;

class ContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if (auth()->user()->role_id == 3) {
            $contacts = Contact::where('creator_id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $contacts = Contact::whereIn('creator_id', $company->users->pluck('id'))->get();
                }
            } else {
                $contacts = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $contacts = Contact::whereIn('creator_id', $company->users->pluck('id'))->get();
            }
        } else {
            $contacts = Contact::all();
        }

        return view('contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if (auth()->user()->role_id == 3) {
            $users = User::latest()->where(['role_id' => 5, 'user_id' => auth()->id()])->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $users = User::latest()->where('role_id', 5)->whereIn('user_id', $company->users->pluck('id'))->get();
                }
            } else {
                $users = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $users = User::latest()->where('role_id', 5)->whereIn('user_id', $company->users->pluck('id'))->get();
            }
        } else {
            $users = User::latest()->where('role_id', 5)->get();
        }

        return view('contacts.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $contact = $this->validateRequest();

        if($request->hasFile('profile_photo')) {

            $profile_photo = $request->profile_photo->store('public/patient_contact_profile_photos');
            $profile_photo = str_replace("public/", "", $profile_photo);

            $contact->profile_photo = $profile_photo;

            $contact->save();
        }

        $contact = new Contact;

        $contact->relative_name = $request->relative_name;
        $contact->more_info = $request->more_info;
        $contact->phone = $request->phone;
        $contact->nhs_number = $request->nhs_number;
        $contact->date_of_birth = $request->date_of_birth;
        $contact->email = $request->email;
        $contact->medical_history = $request->medical_history;
        $contact->relationship_type = $request->relationship_type;
        $contact->user_id = $request->user_id;
        $contact->creator_id = auth()->user()->id;

        $contact->save();

        return redirect('contacts')->with('success', 'Contact Created Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contact = Contact::find($id);

        return view('contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contact = Contact::find($id);

        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if (auth()->user()->role_id == 3) {
            $users = User::latest()->where(['role_id' => 5, 'user_id' => auth()->id()])->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $users = User::latest()->where('role_id', 5)->whereIn('user_id', $company->users->pluck('id'))->get();
                }
            } else {
                $users = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $users = User::latest()->where('role_id', 5)->whereIn('user_id', $company->users->pluck('id'))->get();
            }
        } else {
            $users = User::latest()->where('role_id', 5)->get();
        }

        return view('contacts.edit', compact('contact', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $contact = $this->validateRequest();

        $contact = Contact::find($id);

        if($request->hasFile('profile_photo')) {

            $profile_photo = $request->profile_photo->store('public/patient_contact_profile_photos');
            $profile_photo = str_replace("public/", "", $profile_photo);

            $contact->profile_photo = $profile_photo;

            $contact->save();
        }

        $contact->relative_name = $request->relative_name;
        $contact->more_info = $request->more_info;
        $contact->phone = $request->phone;
        $contact->nhs_number = $request->nhs_number;
        $contact->date_of_birth = $request->date_of_birth;
        $contact->email = $request->email;
        $contact->medical_history = $request->medical_history;
        $contact->relationship_type = $request->relationship_type;
        $contact->user_id = $request->user_id;
        $contact->creator_id = auth()->user()->id;

        $contact->save();

        return redirect()->route('contacts.show', $contact->id)->with('success', 'Contact Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = Contact::find($id);

        $contact->delete();

        return redirect('contacts')->with('success', 'Contact Deleted Successfully.');

    }

    public function validateRequest()
    {
        return request()->validate([

            'relative_name' => 'required',
            'more_info' => '',
            'phone' => '',
            'date_of_birth' => 'required',
            'nhs_number' => '',
            'email' => '',
            'relationship_type' => 'required',
            'user_id' => 'required',
            'profile_photo' => 'sometimes|file|image|max:10000'

        ]);

    }

}
