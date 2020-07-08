<?php

namespace Mnemosine;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mnemosine\Traits\ActionBy;

class Document extends Model
{
    use SoftDeletes, ActionBy;

    protected $fillable = ['name', 'file_name', 'size', 'mime_type', 'piece_id', 'module_id', 'created_at', 'updated_at'];
}
