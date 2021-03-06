<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobClient extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sms_job_id', 'user_id'];

    /**
     * The user that owns the job.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
