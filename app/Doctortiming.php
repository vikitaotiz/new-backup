<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctortiming extends Model
{
    protected $guarded = [];

    protected $dates = ['from', 'to'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }
}
