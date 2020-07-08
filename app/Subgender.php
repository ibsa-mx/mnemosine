<?php

namespace Mnemosine;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mnemosine\Traits\ActionBy;

class Subgender extends Model
{
    use SoftDeletes, ActionBy;

    protected $table = 'subgenders';
    protected $fillable = ['title', 'description', 'gender_id', 'deleted_at', 'created_at', 'updated_at'];

     // public function set()
	 //   {
	 //   	return $this->belongsTo('Mnemosine\Gender');
	 //   }
     //
	 //   public static function subgenders($id)
	 //   {
	 //   	return Subgender::where('gender_id', '=', $id)->get();
	 //   }
}
