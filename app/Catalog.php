<?php

namespace Mnemosine;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mnemosine\Traits\ActionBy;

class Catalog extends Model
{
    use SoftDeletes, ActionBy;

    protected $table = 'catalogs';
    protected $fillable = ['code', 'title', 'description', 'deleted_at', 'created_at', 'updated_at'];
}
