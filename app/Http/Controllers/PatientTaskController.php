<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Task;

class PatientTaskController extends Controller
{
    public function create($id)
    {
        $patient = User::find($id);

        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if(auth()->user()->role_id == 3) {
            $users = User::where(['id' => auth()->id()])->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $users = User::where('role_id', 3)->whereIn('id', $company->users->pluck('id'))->get();
                }
            } else {
                $users = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $users = User::where('role_id', 3)->whereIn('id', $company->users->pluck('id'))->get();
            }
        } else {
            $users = User::where('role_id', 3)->get();
        }

        return view('tasks.patienttask', compact('users', 'patient'));
    }

    public function store(Request $request)
    {
        $user_id = $request->user_id;

        $this->validate($request, [
            'name' => 'required',
            'deadline' => 'required',
            'description' => 'required',
            'user_id' => 'required'
        ]);

        $task = new Task;

        $task->name = $request->name;
        $task->deadline = $request->deadline;
        $task->status = 'open';
        $task->description = $request->description;

        $task->doctor_id = $request->doctor_id;
        $task->user_id = $request->user_id;
        $task->creator_id = auth()->user()->id;

        $task->save();

        return redirect()->route('patients.show', [$user_id, '#tasks'])->with('success', 'Task Created Successfully');
    }
}
