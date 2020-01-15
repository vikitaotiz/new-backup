<?php

namespace App\Http\Controllers;

use App\EmbedDoctor;
use App\EmbedService;
use App\EmbedUrl;
use App\User;
use Illuminate\Http\Request;

class EmbedUrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate form data
        $request->validate([
            'company_detail_id' => 'required|integer',
            'uuid' => 'required|string',
            'service_id.*' => 'required|string',
            'doctor_id.*' => 'required|string',
        ]);

        $url = EmbedUrl::create([
            'company_detail_id' => $request->company_detail_id,
            'uuid' => $request->uuid,
        ]);

        if (isset($request->service_id) && !in_array('all', $request->service_id)) {
            $services = [];
            foreach ($request->service_id as $service) {
                $services[] = array(
                    'embed_url_id' => $url->id,
                    'service_id' => $service,
                );
            }
            EmbedService::insert($services);
        }

        if (isset($request->doctor_id) && !in_array('all', $request->doctor_id)) {
            $doctors = [];
            foreach ($request->doctor_id as $doctor) {
                $doctors[] = array(
                    'embed_url_id' => $url->id,
                    'user_id' => $doctor,
                );
            }
            EmbedDoctor::insert($doctors);
        }

        return redirect()->back()->with('success', 'Url created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EmbedUrl  $embedUrl
     * @return \Illuminate\Http\Response
     */
    public function show(EmbedUrl $embedUrl)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EmbedUrl  $embedUrl
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(EmbedUrl $embedUrl)
    {
        $embedUrl = EmbedUrl::find($embedUrl->id);

        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if (auth()->user()->role_id == 6) {
            if (auth()->user()->company) {
                $users = User::whereIn('id', auth()->user()->company->users->pluck('id'))
                    ->where('role_id', 3)
                    ->where('availability', 1)
                    ->get();
            } else {
                $users = collect();
            }
        } else {
            $users = User::where('role_id', 3)
                ->get();
        }

        return view('settings.urlEdit', compact('embedUrl', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmbedUrl  $embedUrl
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, EmbedUrl $embedUrl)
    {
        // Validate form data
        $request->validate([
            'service_id.*' => 'required|string',
            'doctor_id.*' => 'required|string',
        ]);

        $embedUrl = EmbedUrl::find($embedUrl->id);

        EmbedService::whereNotIn('service_id', $request->service_id)->where('embed_url_id', $embedUrl->id)->delete();

        if (isset($request->service_id) && !in_array('all', $request->service_id)) {
            $services = [];
            foreach ($request->service_id as $service) {
                if (!$embedUrl->services->contains('service_id', $service)) {
                    $services[] = array(
                        'embed_url_id' => $embedUrl->id,
                        'service_id' => $service,
                    );
                }
            }
            EmbedService::insert($services);
        }

        EmbedDoctor::whereNotIn('user_id', $request->doctor_id)->where('embed_url_id', $embedUrl->id)->delete();

        if (isset($request->doctor_id) && !in_array('all', $request->doctor_id)) {
            $doctors = [];
            foreach ($request->doctor_id as $doctor) {
                if (!$embedUrl->doctors->contains('doctor_id', $doctor)) {
                    $doctors[] = array(
                        'embed_url_id' => $embedUrl->id,
                        'user_id' => $doctor,
                    );
                }
            }
            EmbedDoctor::insert($doctors);
        }

        return redirect()->back()->with('success', 'Url updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmbedUrl  $embedUrl
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(EmbedUrl $embedUrl)
    {
        EmbedUrl::destroy($embedUrl->id);

        return redirect()->back()->with('success', 'Url deleted successfully.');
    }
}
