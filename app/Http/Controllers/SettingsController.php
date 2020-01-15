<?php

namespace App\Http\Controllers;

use App\Charge;
use App\Currency;
use App\Medication;
use App\Paymentmethod;
use App\Service;
use App\Task;
use App\CompanyDetail;
use App\Tax;
use App\User;

class SettingsController extends Controller
{
    public function index()
    {
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        // check user role
        if(auth()->user()->role_id == 3) {
            $companydetails = collect();
            $tasks = Task::where('doctor_id', auth()->id())->count();
            $doctors = 1;
            $patients = User::where(['role_id' => 5, 'user_id' => auth()->id()])->count();
            $services = Service::where('user_id', auth()->id())->count();
            $taxes = Tax::where('user_id', auth()->id())->count();
            $charges = Charge::where('user_id', auth()->id())->count();
            $currencies = Currency::where('user_id', auth()->id())->count();
            $paymentmethods = Paymentmethod::where('user_id', auth()->id())->count();
            //$medications = Medication::where('user_id', auth()->id())->count();
            $trashed = User::onlyTrashed()->where('user_id', auth()->id())->count();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $patients = User::where(['role_id' => 5])->whereIn('user_id', $company->users->pluck('id'))->count();
                    $tasks = Task::whereIn('doctor_id', $company->users->pluck('id'))->count();
                    $services = Service::whereIn('user_id', $company->users->pluck('id'))->count();
                    $taxes = Tax::whereIn('user_id', $company->users->pluck('id'))->count();
                    $charges = Charge::whereIn('user_id', $company->users->pluck('id'))->count();
                    $currencies = Currency::whereIn('user_id', $company->users->pluck('id'))->count();
                    $paymentmethods = Paymentmethod::whereIn('user_id', $company->users->pluck('id'))->count();
                    //$medications = Medication::whereIn('user_id', $company->users->pluck('id'))->count();

                    $companydetails = CompanyDetail::where('user_id', auth()->id())->get();
                    $doctors = User::whereIn('id', $company->users->pluck('id'))
                        ->where('role_id', '!=', 6)
                        ->count();
                    $trashed = User::onlyTrashed()->whereIn('user_id', $company->users->pluck('id'))->count();
                }
            } else {
                $tasks = 0;
                $doctors = 0;
                $patients = 0;
                $companydetails = collect();
                $services = 0;
                $taxes = 0;
                $charges = 0;
                $currencies = 0;
                $paymentmethods = 0;
                //$medications = Medication::all()->count();
                $trashed = 0;
            }
        } elseif (auth()->user()->role_id == 6) {
            $trashed = User::onlyTrashed()->whereIn('user_id', auth()->user()->company->users->pluck('id'))->count();
            foreach (auth()->user()->companies as $company) {
                $patients = User::where(['role_id' => 5])->whereIn('user_id', $company->users->pluck('id'))->count();
                $tasks = Task::whereIn('doctor_id', $company->users->pluck('id'))->count();
                $services = Service::whereIn('user_id', $company->users->pluck('id'))->count();
                $taxes = Tax::whereIn('user_id', $company->users->pluck('id'))->count();
                $charges = Charge::whereIn('user_id', $company->users->pluck('id'))->count();
                $currencies = Currency::whereIn('user_id', $company->users->pluck('id'))->count();
                $paymentmethods = Paymentmethod::whereIn('user_id', $company->users->pluck('id'))->count();
                //$medications = Medication::whereIn('user_id', $company->users->pluck('id'))->count();

                $companydetails = CompanyDetail::where('id', $company->id)->get();
                $doctors = User::whereIn('id', auth()->user()->company->users->pluck('id'))
                    ->where('role_id', '!=', 6)
                    ->count();
            }
        } else {
            $tasks = Task::all()->count();
            $doctors = User::whereNotIn('role_id', [1, 2, 5])
                ->count();
            $patients = User::where('role_id', 5)->count();
            $companydetails = CompanyDetail::latest()->get();
            $services = Service::all()->count();
            $taxes = Tax::all()->count();
            $charges = Charge::all()->count();
            $currencies = Currency::all()->count();
            $paymentmethods = Paymentmethod::all()->count();
            //$medications = Medication::all()->count();
            $trashed = User::onlyTrashed()->count();
        }

        $medications = Medication::all()->count();

        return view('settings.index', compact('companydetails', 'tasks', 'patients', 'doctors', 'services', 'taxes', 'charges', 'currencies', 'paymentmethods', 'medications', 'trashed'));
    }
}
