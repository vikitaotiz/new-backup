<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $guarded = [];

    protected $dates = ['date_of_birth'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
