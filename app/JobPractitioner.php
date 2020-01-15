<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobPractitioner extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sms_job_id', 'user_id'];
}
