<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsJob extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['template', 'company_detail_id', 'reminder_period', 'reminder_time_from', 'reminder_time_to', 'reminder_type'];

    /**
     * The template that owns the job.
     */
    public function template()
    {
        return $this->belongsTo('App\SmsTemplate');
    }

    /**
     * The company that owns the job.
     */
    public function company()
    {
        return $this->belongsTo('App\CompanyDetail', 'company_detail_id');
    }

    /**
     * The users record associated with the job.
     */
    public function users()
    {
        return $this->hasMany('App\JobClient');
    }

    /**
     * The doctors record associated with the job.
     */
    public function doctors()
    {
        return $this->hasMany('App\JobPractitioner');
    }
}
