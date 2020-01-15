<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyDetailUser extends Model
{
    protected $guarded = [];
    protected $table = 'company_detail_user';

    public function company(){
        return $this->hasOne(CompanyDetail::class, 'id', 'company_detail_id');
    }
}
