<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['from', 'to', 'message', 'user_id', 'created_at', 'updated_at'];

    /**
     * The patient that owns the sms.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
