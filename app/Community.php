<?php

namespace Mnemosine;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Community extends Model
{
   use SoftDeletes;

   protected $table = 'communities';
   protected $fillable = ['user_id', 'description', 'deleted_at', 'created_at', 'updated_at'];

   // public function sets()
   // {
   // 	return $this->hasMany('Mnemosine\Set');
   // }
}
