<?php

namespace Mnemosine;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    //use SoftDeletes;
    protected $table = 'states';
    protected $fillable = ['name', 'country_id'];
}
