<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyDetail extends Model
{
    protected $guarded = [];

    public function owner(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    public function user(){
        return $this->hasMany(User::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Particitpation::class);
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class);
    }

    public function sicknotes()
    {
        return $this->hasMany(Sicknote::class);
    }

    public function consultations()
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

    /**
     * The users that belong to the company.
     */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
