<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $guarded = [];

    protected $dates = ['date_of_birth'];

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(CompanyDetail::class);
    }
    public function drugs()
    {
        return $this->hasMany(Drug::class);
    }
    
    
}
