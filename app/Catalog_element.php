<?php

namespace Mnemosine;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mnemosine\Traits\ActionBy;

class Catalog_element extends Model
{
	use SoftDeletes, ActionBy;
    protected $table = 'catalog_elements';
    protected $fillable = ['code', 'title', 'description', 'catalog_id', 'deleted_at', 'created_at', 'updated_at'];
	protected $casts = [
        'id' => 'integer',
    ];
}
