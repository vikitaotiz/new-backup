<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DayBreak extends Model
{
    /**
     * Get the timing that owns the break.
     */
    public function timing()
    {
        return $this->belongsTo('App\Timing');
    }

}
