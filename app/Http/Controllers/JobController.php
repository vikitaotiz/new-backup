<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\CompanyDetail;
use App\JobPractitioner;
use App\Sms;
use App\SmsJob;
use App\JobClient;
use App\SmsTemplate;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        try {
            $jobs = SmsJob::latest()->get();

            return view('job.index', compact('jobs'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        try {
            // Check role
            if (auth()->user() != null && isset(auth()->user()->company->company)) {
                auth()->user()->company = auth()->user()->company->company;
            }

            if (auth()->user()->role_id == 6) {
                if (auth()->user()->company) {
                    $patients = User::latest()->where('role_id', 5)->whereIn('user_id', auth()->user()->company->users->pluck('id'))->get();
                    $doctors = User::latest()->where('role_id', 3)->whereIn('user_id', auth()->user()->company->users->pluck('id'))->get();
                    $companies = CompanyDetail::where('id', auth()->user()->company->id)->get();
                } else {
                    $patients = collect();
                    $doctors = collect();
                    $companies = collect();
                }
            } elseif (auth()->user()->role_id == 3 || auth()->user()->role_id == 4 || auth()->user()->role_id == 5) {
                return redirect()->back()->with('error', 'Permission denied!');
            } else {
                $patients = User::latest()->where('role_id', 5)->get();
                $doctors = User::latest()->where('role_id', 3)->get();
                $companies = CompanyDetail::latest()->get();
            }

            $smsTemplates = SmsTemplate::latest()->get();

            return view('job.create', compact('smsTemplates', 'patients', 'doctors', 'companies'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
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
            'message' => 'required|string',
            'user_id' => 'required|integer',
            //'user_id.*' => 'required|integer',
            'doctor_id' => 'required|integer',
            //'doctor_id.*' => 'required|integer',
            'company_detail_id' => 'required|integer',
            'reminder_period' => 'nullable|integer|min:1',
            'reminder_time_from' => 'nullable|date_format:H:i',
            'reminder_time_to' => 'nullable|date_format:H:i|after:reminder_time_from',
            'reminder_type' => 'nullable|integer',
        ]);

        try {
            $job = SmsJob::create([
                'template' => $request->message,
                'company_detail_id' => $request->company_detail_id,
                'reminder_period' => $request->reminder_period,
                'reminder_time_from' => $request->reminder_time_from,
                'reminder_time_to' => $request->reminder_time_to,
                'reminder_type' => $request->reminder_type,
            ]);

            JobClient::create([
                'user_id' => $request->user_id,
                'sms_job_id' => $job->id,
            ]);

            JobPractitioner::create([
                'user_id' => $request->doctor_id,
                'sms_job_id' => $job->id,
            ]);

            Appointment::whereDate('appointment_date', '>', date('Y-m-d H:i:s'))->whereIn('user_id', [$request->user_id, $request->doctor_id])->update(['send_sms' => 1, 'send_mail' => 1]);

            /*if (isset($request->user_id) && is_array($request->user_id)) {
                foreach ($request->user_id as $id) {
                    $client[] = array(
                        'user_id' => $id,
                        'sms_job_id' => $job->id,
                    );
                }
            }

            if (isset($client) && count($client)) {
                JobClient::insert($client);// Eloquent approach
            }

            if (isset($request->doctor_id) && is_array($request->doctor_id)) {
                foreach ($request->doctor_id as $id) {
                    $doctor[] = array(
                        'user_id' => $id,
                        'sms_job_id' => $job->id,
                    );
                }
            }

            if (isset($doctor) && count($doctor)) {
                JobPractitioner::insert($doctor);// Eloquent approach
            }*/

            return redirect()->back()->with('success', 'SmsJob created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SmsJob  $job
     * @return \Illuminate\Http\Response
     */
    public function show(SmsJob $job)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SmsJob  $job
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(SmsJob $job)
    {
        try {
            // Check role
            if (auth()->user() != null && isset(auth()->user()->company->company)) {
                auth()->user()->company = auth()->user()->company->company;
            }

            if (auth()->user()->role_id == 6) {
                if (auth()->user()->company) {
                    $patients = User::latest()->where('role_id', 5)->whereIn('user_id', auth()->user()->company->users->pluck('id'))->get();
                    $doctors = User::latest()->where('role_id', 3)->whereIn('user_id', auth()->user()->company->users->pluck('id'))->get();
                    $companies = CompanyDetail::where('id', auth()->user()->company->id)->get();
                } else {
                    $patients = collect();
                    $doctors = collect();
                    $companies = collect();
                }
            } elseif (auth()->user()->role_id == 3 || auth()->user()->role_id == 4 || auth()->user()->role_id == 5) {
                return redirect()->back()->with('error', 'Permission denied!');
            } else {
                $patients = User::latest()->where('role_id', 5)->get();
                $doctors = User::latest()->where('role_id', 3)->get();
                $companies = CompanyDetail::latest()->get();
            }

            $smsTemplates = SmsTemplate::latest()->get();
            $job = SmsJob::find($job->id);

            return view('job.edit', compact('job', 'smsTemplates', 'patients', 'doctors', 'companies'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SmsJob  $job
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, SmsJob $job)
    {
        // Validate form data
        $request->validate([
            'message' => 'required|string',
            'user_id' => 'required|integer',
            //'user_id.*' => 'required|integer',
            'doctor_id' => 'required|integer',
            //'doctor_id.*' => 'required|integer',
            'company_detail_id' => 'required|integer',
            'reminder_period' => 'nullable|integer|min:1',
            'reminder_time_from' => 'nullable|date_format:H:i',
            'reminder_time_to' => 'nullable|date_format:H:i|after:reminder_time_from',
            'reminder_type' => 'nullable|integer',
        ]);

        try {
            $job = SmsJob::find($job->id);
            $job->template = $request->message;
            $job->company_detail_id = $request->company_detail_id;
            $job->reminder_period = $request->reminder_period;
            $job->reminder_time_from = $request->reminder_time_from;
            $job->reminder_time_to = $request->reminder_time_to;
            $job->reminder_type = $request->reminder_type;
            $job->save();

            JobClient::where('sms_job_id', $job->id)->update(['user_id' => $request->user_id]);
            JobPractitioner::where('sms_job_id', $job->id)->update(['user_id' => $request->doctor_id]);

            /*JobClient::whereNotIn('user_id', $request->user_id)->delete();

            if (isset($request->user_id) && is_array($request->user_id)) {
                foreach ($request->user_id as $id) {
                    if (JobClient::where('user_id', $id)->first() == null) {
                        $client[] = array(
                            'user_id' => $id,
                            'sms_job_id' => $job->id,
                        );
                    }
                }
            }

            if (isset($client) && count($client)) {
                JobClient::insert($client);// Eloquent approach
            }

            JobPractitioner::whereNotIn('user_id', $request->doctor_id)->delete();

            if (isset($request->doctor_id) && is_array($request->doctor_id)) {
                foreach ($request->doctor_id as $id) {
                    if (JobPractitioner::where('user_id', $id)->first() == null) {
                        $doctor[] = array(
                            'user_id' => $id,
                            'sms_job_id' => $job->id,
                        );
                    }
                }
            }

            if (isset($doctor) && count($doctor)) {
                JobPractitioner::insert($doctor);// Eloquent approach
            }*/

            return redirect()->back()->with('success', 'SmsJob updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SmsJob  $job
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(SmsJob $job)
    {
        try {
            SmsJob::destroy($job->id);

            return redirect()->back()->with('success', 'SmsJob deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Sends appointment schedule before specified datetime.
     *
     * @param  \App\SmsJob  $job
     * @return \Illuminate\Http\RedirectResponse
     */
    public function scheduledJob()
    {
        try {
            // Your Account SID and Auth Token from twilio.com/console
            $sid    = env( 'TWILIO_SID' );
            $token  = env( 'TWILIO_TOKEN' );
            $client = new Client( $sid, $token );

            $jobs = SmsJob::latest()->get();

            foreach ($jobs as $job) {
                if ($job->reminder_type !== 0) {
                    $now = strtotime(date('H:i:s'));

                    if ($job->reminder_time_from && $job->reminder_time_to) {
                        if ($now > strtotime($job->reminder_time_from) && $now < strtotime($job->reminder_time_to)) {
                            $this->run($job, $client);
                        }
                    } else {
                        $this->run($job, $client);
                    }
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Sends appointment schedule before specified datetime.
     *
     * @param  \App\SmsJob  $job
     * @return \Illuminate\Http\RedirectResponse
     */
    public function run($job, $client){
        try {
            foreach ($job->users as $user) {
                if ($user->user->phone) {
                    $date = date('Y-m-d', strtotime(now()->addDays($job->reminder_period)));
                    $appointments = Appointment::whereDate('appointment_date', $date)->where(['user_id' => $user->user->id, 'send_sms' => 1])->get();

                    foreach ($appointments as $appointment) {
                        $body = str_replace('{{Business.Name}}', $job->company->name, $job->template);
                        $body = str_replace('{{Appointment.Date}}', date('Y-m-d', strtotime($appointment->appointment_date)), $body);
                        $body = str_replace('{{Appointment.StartTime}}', date('h:iA', strtotime($appointment->from)), $body);
                        $body = str_replace('{{Practitioner.FullNameWithTitle}}', $user->user->doctor ? $user->user->doctor->firstname . ' ' . $user->user->doctor->lastname : '', $body);

                        $client->messages->create(
                            $user->user->phone,
                            [
                                'from' => env('TWILIO_FROM'),
                                'body' => $body,
                            ]
                        );

                        // Insert into DB
                        $sms[] = array(
                            'from' => env('TWILIO_FROM'),
                            'to' => $user->user->phone,
                            'message' => $body,
                            'user_id' => $user->user_id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        );

                        // Send the mail
                        if ($job->reminder_type == 2) {
                            Mail::send([], [], function ($message) use ($user, $body) {
                                $message->from('no-replay@hospitalnote.com');
                                $message->to($user->user->email);
                                $message->subject('Appointment');
                                $message->setBody($body, 'text/html');
                            });
                        }
                    }

                    if (isset($sms) && count($sms)) {
                        Sms::insert($sms);// Eloquent approach
                    }

                    if ($job->reminder_type == 2) {
                        Appointment::whereDate('appointment_date', $date)->where(['user_id' => $user->user->id, 'send_mail' => 1])->update(['send_mail' => 2]);
                    }

                    Appointment::whereDate('appointment_date', $date)->where(['user_id' => $user->user->id, 'send_sms' => 1])->update(['send_sms' => 2]);
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
