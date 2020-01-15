<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\File;

class PatientDocumentController extends Controller
{
    public function create($id)
    {
        $user = User::find($id);

        return view('files.patientdocument', compact('user'));
    }

    public function store(Request $request)
    {
        // dd($request);

        $this->validate($request, [
            'name' => 'required',
            'filename' => 'required|file',
            'user_id' => 'required'
        ]);

        $user_id = $request->user_id;

        $filename = $request->filename->store('public/patient_files');
        $filename = str_replace("public/", "", $filename);

        $file = new File;

        $file->name = $request->name;

        $file->filename = $filename;

        $file->user_id = $request->user_id;

        $file->creator_id = auth()->user()->id;

        $file->save();

        return redirect()->route('patients.show', [$user_id, '#documents'])->with('success', 'File Added Successfully');
    }
}
