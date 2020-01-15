<?php

namespace App\Exports;

use App\Task;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class TasksExport implements FromView, WithTitle
{
    public function view(): View
    {
        // check user role
        if (auth()->user()->role_id == 3) {
            if (auth()->user()->company) {
                $tasks = collect();

                foreach (auth()->user()->company->users as $user) {
                    $task = Task::where('doctor_id', $user->id)->get();

                    if (count($task)) {
                        $tasks->push($task);
                    }
                }

                $tasks = $tasks->collapse();
            } else {
                $tasks = Task::where('doctor_id', auth()->id())->get();
            }
        } else {
            $tasks = Task::all();
        }

        return view('inc.patients.single_patient.tasks_export', [
            'tasks' => $tasks
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Tasks';
    }
}
