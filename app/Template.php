<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    /**
     * Get the section record associated with the template.
     */
    public function sections()
    {
        return $this->hasMany('App\Section');
    }

    /**
     * Get the question record associated with the template.
     */
    public function questions()
    {
        return $this->hasMany('App\Question');
    }

    /**
     * Get the answer record associated with the template.
     */
    public function answers()
    {
        return $this->hasMany('App\Answer');
    }

    /**
     * Get the user that owns the template.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
