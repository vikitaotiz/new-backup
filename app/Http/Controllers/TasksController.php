<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use App\User;
use App\CompanyDetail;;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allTasks()
    {
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        // check user role
        if(auth()->user()->role_id == 3) {
            $openTasks = Task::where(['doctor_id' => auth()->id(),'status' => 'open'])->count();
            $closeTasks = Task::where(['doctor_id' => auth()->id(),'status' => 'close'])->count();
            $tasks = Task::where('doctor_id', auth()->id())->count();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $openTasks = Task::where(['status' => 'open'])->whereIn('doctor_id', $company->users->pluck('id'))->count();
                    $closeTasks = Task::where(['status' => 'close'])->whereIn('doctor_id', $company->users->pluck('id'))->count();
                    $tasks = Task::whereIn('doctor_id', $company->users->pluck('id'))->count();
                }
            } else {
                $openTasks = 0;
                $closeTasks = 0;
                $tasks = 0;
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $openTasks = Task::where(['status' => 'open'])->whereIn('doctor_id', $company->users->pluck('id'))->count();
                $closeTasks = Task::where(['status' => 'close'])->whereIn('doctor_id', $company->users->pluck('id'))->count();
                $tasks = Task::whereIn('doctor_id', $company->users->pluck('id'))->count();
            }
        } else {
            $openTasks = Task::where('status','open')->count();
            $closeTasks = Task::where('status','close')->count();
            $tasks = Task::all()->count();
        }

        return view('tasks.alltasks', compact('tasks', 'openTasks', 'closeTasks'));
    }

    public function index(Request $request)
    {
        $patient_id = $request->patient_id ?? null;

        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        //All Tasks
        if(auth()->user()->role_id == 5) {
            $tasks = auth()->user()->tasks;
        } elseif(auth()->user()->role_id == 3) {
            $tasks = Task::where('doctor_id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $tasks = Task::where('doctor_id', $company->users->pluck('id'))->get();
                }
            } else {
                $tasks = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $tasks = Task::where('doctor_id', $company->users->pluck('id'))->get();
            }
        } else {
            $tasks = User::with('tasks')->get()->map->tasks->collapse();
        }

        return view('tasks.index', compact('tasks', 'patient_id'));
    }

    public function openTasks()
    {
        //All open tasks
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if(auth()->user()->role_id == 5) {
            $tasks = auth()->user()->tasks;
        } elseif(auth()->user()->role_id == 3) {
            $tasks = Task::where(['doctor_id' => auth()->id(),'status' => 'open'])->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $tasks = Task::where(['status' => 'open'])->whereIn('doctor_id', $company->users->pluck('id'))->get();
                }
            } else {
                $tasks = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $tasks = Task::where(['status' => 'open'])->whereIn('doctor_id', $company->users->pluck('id'))->get();
            }
        } else {
            $tasks = Task::where('status', 'open')->get();
        }

        return view('tasks.opentasks', compact('tasks'));
    }

    public function closedTasks()
    {
        //All Closed Tasks
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if(auth()->user()->role_id == 5) {
            $tasks = auth()->user()->tasks;
        } elseif(auth()->user()->role_id == 3) {
            $tasks = Task::where(['doctor_id' => auth()->id(),'status' => 'close'])->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $tasks = Task::where(['status' => 'close'])->whereIn('doctor_id', $company->users->pluck('id'))->get();
                }
            } else {
                $tasks = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $tasks = Task::where(['status' => 'close'])->whereIn('doctor_id', $company->users->pluck('id'))->get();
            }
        } else {
            $tasks = Task::where('status', 'close')->get();
        }

        return view('tasks.closedtasks', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // check user role
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if(auth()->user()->role_id == 3) {
            $users = User::where('id', auth()->id())->get();;
            $patients = User::where(['role_id' => 5, 'user_id' => auth()->id()])->get();
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
                    $patients = User::where('role_id', 5)->whereIn('user_id', $company->users->pluck('id'))->get();
                }
            } else {
                $users = collect();
                $patients = collect();
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
                $patients = User::where('role_id', 5)->whereIn('user_id', $company->users->pluck('id'))->get();
            }
        } else {
            $users = User::where('role_id', '!=', 5)
                ->where('role_id', '!=', 1)
                ->where('role_id', '!=', 2)
                ->where('role_id', '!=', 6)
                ->get();
            $patients = User::where('role_id', 5)->get();
        }

        return view('tasks.create', compact('users', 'patients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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

        return redirect('tasks')->with('success', 'Task Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::findOrfail($id);

        $company = $this->activeCompany();

        return view('tasks.show', compact('task', 'company'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::findOrfail($id);

        // check user role
        if(auth()->user()->role_id == 3) {
            $users = User::where('id', auth()->id())->get();;
            $patients = User::where(['role_id' => 5, 'user_id' => auth()->id()])->get();
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
                    $patients = User::where('role_id', 5)->whereIn('user_id', $company->users->pluck('id'))->get();
                }
            } else {
                $users = collect();
                $patients = collect();
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
                $patients = User::where('role_id', 5)->whereIn('user_id', $company->users->pluck('id'))->get();
            }
        } else {
            $users = User::where('role_id', '!=', 5)
                ->where('role_id', '!=', 1)
                ->where('role_id', '!=', 2)
                ->where('role_id', '!=', 6)
                ->get();
            $patients = User::where('role_id', 5)->get();
        }

        return view('tasks.edit', compact('task', 'users', 'patients'));
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
        $this->validate($request, [
            'name' => 'required',
            'deadline' => 'required',
            'description' => 'required',
            'user_id' => 'required'
        ]);

        $task = Task::findOrfail($id);

        $task->name = $request->name;
        $task->deadline = $request->deadline;
        $task->status = 'open';
        $task->description = $request->description;

        $task->doctor_id = $request->doctor_id;
        $task->user_id = $request->user_id;
        $task->creator_id = auth()->user()->id;

        $task->save();

        return redirect()->route('tasks.show', $task->id)->with('success', 'Task Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::findOrfail($id);

        $task->delete();

        return redirect('tasks')->with('success', 'Task Deleted Successfully');
    }

    public function close($id)
    {
        $task = Task::findOrfail($id);

        $task->status = 'close';

        $task->save();

        return redirect()->back()->with('success', 'Task closed successfully.');
    }


    public function open($id)
    {
        $task = Task::findOrfail($id);

        $task->status = 'open';

        $task->save();

        return redirect()->back()->with('success', 'Task opened successfully.');
    }


    public function activeCompany()
    {
        $companies = CompanyDetail::all();

        foreach ($companies as $company) {
            if ($company->status == 1) {
               return $company->where('status', 1)->first();
            }
        }
    }
}
