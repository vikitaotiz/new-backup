<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    /**
     * Get the question record associated with the template.
     */
    public function questions()
    {
        return $this->hasMany('App\QuestionnaireQuestion');
    }

    /**
     * Get the answer record associated with the template.
     */
    // public function answers()
    // {
    //     return $this->hasMany('App\QuestionnaireAnswer');
    // }
}
