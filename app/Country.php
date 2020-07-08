<?php

namespace Mnemosine;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    //use SoftDeletes;
    protected $table = 'countries';
    protected $fillable = ['name', 'iso3', 'iso2', 'phonecode', 'capital', 'currency'];
}
