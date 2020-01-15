<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientTreatmentNote extends Model
{
    /**
     * The template that owns the note.
     */
    public function template()
    {
        return $this->belongsTo('App\Template');
    }

    /**
     * The user that owns the note.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The appointment that owns the note.
     */
    public function appointment()
    {
        return $this->belongsTo('App\Appointment');
    }
}
