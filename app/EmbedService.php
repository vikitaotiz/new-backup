<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmbedService extends Model
{
    /**
     * Get the service that owns the embedService.
     */
    public function service()
    {
        return $this->belongsTo('App\Service');
    }
}
