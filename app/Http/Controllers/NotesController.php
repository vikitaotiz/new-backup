<?php

namespace App\Http\Controllers;

use App\FollowupConsultation;
use App\InitialConsultation;
use App\Vital;
use Illuminate\Http\Request;

class NotesController extends Controller
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
            $initialnotes = InitialConsultation::where('creator_id', auth()->id())->count();
            $followupnotes = FollowupConsultation::where('creator_id', auth()->id())->count();
            $vitals = Vital::where('creator_id', auth()->id())->count();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $initialnotes = InitialConsultation::where('company_id', $company->id)->count();
                    $followupnotes = FollowupConsultation::where('company_id', $company->id)->count();
                    $vitals = Vital::where('company_id', $company->id)->count();
                }
            } else {
                $initialnotes = 0;
                $followupnotes = 0;
                $vitals = 0;
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $initialnotes = InitialConsultation::where('company_id', $company->id)->count();
                $followupnotes = FollowupConsultation::where('company_id', $company->id)->count();
                $vitals = Vital::where('company_id', $company->id)->count();
            }
        } else {
            $initialnotes = InitialConsultation::all()->count();
            $followupnotes = FollowupConsultation::all()->count();
            $vitals = Vital::all()->count();
        }

        return view('notes.index', compact('initialnotes', 'followupnotes', 'vitals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
