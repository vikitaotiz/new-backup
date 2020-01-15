<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Contact;

class PatientContactController extends Controller
{
    public function create($id)
    {
        $user = User::find($id);

        return view('contacts.patientcontact', compact('user'));
    }


    public function store(Request $request)
    {
        $user_id = $request->user_id;

        $contact = $this->validateRequest();

        $contact = new Contact;

        $contact->relative_name = $request->relative_name;
        $contact->more_info = $request->more_info;
        $contact->phone = $request->phone;
        $contact->profile_photo = $request->profile_photo;
        $contact->nhs_number = $request->nhs_number;
        $contact->date_of_birth = $request->date_of_birth;
        $contact->email = $request->email;
        $contact->medical_history = $request->medical_history;
        $contact->relationship_type = $request->relationship_type;
        $contact->user_id = $request->user_id;
        $contact->creator_id = auth()->user()->id;

        $contact->save();

        // $this->storeImage($contact);

        return redirect()->route('patients.show', [$user_id, '#contacts'])->with('success', 'Contact Created Successfully.');
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
