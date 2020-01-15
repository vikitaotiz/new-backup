<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    /**
     * Get the question record associated with the section.
     */
    public function questions()
    {
        return $this->hasMany('App\Question');
    }
}
