<?php

namespace Mnemosine;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mnemosine\Traits\ActionBy;

class Dimension extends Model
{
    use SoftDeletes, ActionBy;

    protected $table = 'dimensions';
    protected $fillable = ['height_with_base', 'width_with_base', 'depth_with_base', 'diameter_with_base', 'height', 'width', 'depth', 'diameter', 'piece_id', 'deleted_at', 'created_at', 'updated_at'];

   //   public function piece()
   // {
   // 	return $this->belongsTo('Mnemosine\Piece');
   // }
}
