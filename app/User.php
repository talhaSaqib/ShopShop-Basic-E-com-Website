<?php

namespace App;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements Authenticatable
{
    use \Illuminate\Auth\Authenticatable;

    public function products()
    {
        return $this->hasMany('App\Products');
    }
    public function comments()
    {
        return $this->hasMany('App\Comments');
    }
}
