<?php

namespace App\Exports;

use App\File;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class DocumentsExport implements FromView, WithTitle
{
    public function view(): View
    {
        if (auth()->user()->role_id == 3) {
            if (auth()->user()->company) {
                $files = collect();

                foreach (auth()->user()->company->users as $user) {
                    $file = File::latest()->where('creator_id', $user->id)->get();

                    if (count($file)) {
                        $files->push($file);
                    }
                }

                $files = $files->collapse();
            } else {
                $files = File::latest()->where('creator_id', auth()->id())->get();
            }
        }  else {
            $files = File::all();
        }

        return view('inc.patients.single_patient.documents_export', [
            'files' => $files
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Documents';
    }
}
