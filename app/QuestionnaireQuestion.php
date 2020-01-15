<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionnaireQuestion extends Model
{
    /**
     * Get the answer record associated with the question.
     */
    public function answers()
    {
        return $this->hasMany('App\QuestionnaireAnswer');
    }
}
