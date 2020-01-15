<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function creator() {
        return $this->belongsTo(User::class);
    }

    public function medication() {
        return $this->belongsTo(Medication::class);
    }
}
