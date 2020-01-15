<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'name', 'appointment_date', 'color', 'end_date', 'status', 'description', 'patient_id', 'service_id', 'doctor_id', 'user_id', 'creator_id'
    ];

    /*protected $dates = ['appointment_date', 'date_of_birth'];*/


    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
