<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use App\Homepage;
use App\About;
use App\Offer;
use App\Team;

class WelcomeController extends Controller
{
    public function welcome()
    {
        $homepages = Homepage::all();
        $abouts = About::all();
        $services = Offer::all();
        $teams = Team::all();

        return view('welcome', compact('homepages', 'abouts', 'services', 'teams'));
    }

    public function contact_query(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required'
        ]);

        // dd($data['email']);

        Mail::to('info@hsopitalnote.com')->send(new ContactFormMail($data));

        toastr()->success('Email has been sent successfully!');

        return redirect()->back();

    }
}
