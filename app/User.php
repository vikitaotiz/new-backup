<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    use softDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function doctorAppointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function drugs()
    {
        return $this->hasMany(Drug::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function doctorInvoices()
    {
        return $this->hasMany(Invoice::class, 'doctor_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function sicknotes()
    {
        return $this->hasMany(Sicknote::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class);
    }

    public function paymentmethods()
    {
        return $this->hasMany(Paymentmethod::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function initialconsultations()
    {
        return $this->hasMany(InitialConsultation::class);
    }

    public function followupconsultations()
    {
        return $this->hasMany(FollowupConsultation::class);
    }

    public function vitals()
    {
        return $this->hasMany(Vital::class);
    }

    public function timings()
    {
        return $this->hasMany(Doctortiming::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function timing()
    {
        return $this->hasMany(Timing::class);
    }

    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    /**
     * The companies that belong to the user.
     */
    public function companies()
    {
        return $this->belongsToMany('App\CompanyDetail');
    }

    /**
     * The company that belong to the user.
     */
    public function company()
    {
        $company = CompanyDetail::where('user_id', auth()->user()->id)->get()->first();
        if($company != null){
            $company = $this->hasOne('App\CompanyDetail');
        } else {
            /*$companyRel = DB::table('company_detail_user')->where('user_id', auth()->user()->id)->get()->first();
            $company = $this->test()->where('id', $companyRel->company_detail_id);*/
            $company = $this->hasOne(CompanyDetailUser::Class, 'user_id', 'id');
        }
        return $company;
    }

    public function test()
    {
        return $this->hasOne('App\CompanyDetail');
    }

    /**
     * The template record associated with the user.
     */
    public function templates()
    {
        return $this->hasMany('App\Template');
    }

    /**
     * The notes record associated with the user.
     */
    public function notes()
    {
        return $this->hasMany('App\PatientTreatmentNote');
    }

    /**
     * The notes record associated with the user.
     */
    public function treatmentNote()
    {
        return $this->hasMany('App\PatientTreatment');
    }

    /**
     * The creator that owns the user.
     */
    public function creator()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * The doctor that owns the user.
     */
    public function doctor()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * The sms record associated with the user.
     */
    public function smss()
    {
        return $this->hasMany('App\Sms');
    }
}
