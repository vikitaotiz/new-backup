<?php

namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class InvoicesExport implements FromView, WithTitle
{
    public function view(): View
    {
        if (auth()->user()->role_id == 3) {
            if (auth()->user()->company) {
                $invoices = collect();

                foreach (auth()->user()->company->users as $user) {
                    $invoice = Invoice::latest()->where('doctor_id', $user->id)->get();

                    if (count($invoice)) {
                        $invoices->push($invoice);
                    }
                }

                $invoices = $invoices->collapse();
            } else {
                $invoices = auth()->user()->invoices;
            }
        } else {
            $invoices = Invoice::all();
        }

        return view('inc.patients.single_patient.invoices_export', [
            'invoices' => $invoices
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Invoices';
    }
}
