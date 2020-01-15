<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /**
     * Get the answer record associated with the question.
     */
    public function answers()
    {
        return $this->hasMany('App\Answer');
    }

    /**
     * Get the answer record associated with the question.
     */
    public function answer()
    {
        return $this->hasOne('App\PatientTreatmentNote');
    }
}
