<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $guarded = [];

    /*protected $dates = ['due_date', 'date_of_birth'];*/

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function company()
    {
        return $this->belongsTo(CompanyDetail::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'product_service');
    }

    public function charge()
    {
        return $this->belongsTo(Charge::class, 'charge_id', 'id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }
}
