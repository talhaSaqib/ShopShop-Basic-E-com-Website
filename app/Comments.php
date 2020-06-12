<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    public function users()
    {
        return $this->belongsTo('App\User');
    }
    public function products()
    {
        return $this->belongsTo('App\Products');
    }
}
