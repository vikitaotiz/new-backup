<?php

namespace App;

use App\CompanyDetail;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(CompanyDetail::class);
    }
}
