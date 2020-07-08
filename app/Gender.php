<?php

namespace Mnemosine;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mnemosine\Traits\ActionBy;

class Gender extends Model
{
	use SoftDeletes, ActionBy;

    protected $table = 'genders';
    protected $fillable = ['title', 'description', 'set_id', 'deleted_at', 'created_at', 'updated_at'];

}
