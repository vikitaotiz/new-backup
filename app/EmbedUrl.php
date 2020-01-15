<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmbedUrl extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['company_detail_id', 'uuid'];

    /**
     * Get the doctors associated with url.
     */
    public function doctors()
    {
        return $this->hasMany('App\EmbedDoctor');
    }

    /**
     * Get the services associated with url.
     */
    public function services()
    {
        return $this->hasMany('App\EmbedService');
    }
}
