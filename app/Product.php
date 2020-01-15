<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function creator(){
        return $this->belongsTo(User::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
