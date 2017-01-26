<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Routines extends Model
{
    protected $fillable = ['routine'];

    public function routines()
    {
        return $this->hasMany('App\Routines');
    }
}
