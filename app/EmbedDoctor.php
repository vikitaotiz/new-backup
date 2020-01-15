<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmbedDoctor extends Model
{
    /**
     * Get the user that owns the doctor.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
