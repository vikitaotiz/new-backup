<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timing extends Model
{
    /**
     * Get the break record associated with the timing.
     */
    public function break()
    {
        return $this->hasMany('App\DayBreak');
    }
}
