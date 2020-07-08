<?php

namespace Mnemosine;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mnemosine\Traits\ActionBy;

class Contact extends Model
{
   	use SoftDeletes, ActionBy;
   	protected $table = 'contacts';
   	protected $fillable = ['name', 'last_name', 'm_last_name', 'treatment_title', 'position', 'department', 'phone', 'phone2', 'email', 'institution_id', 'updated_at'];
}
