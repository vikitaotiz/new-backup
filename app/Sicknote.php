<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sicknote extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $dates = ['date_of_birth'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(CompanyDetail::class);
    }
}
