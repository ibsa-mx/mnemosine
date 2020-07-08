<?php

namespace Mnemosine;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mnemosine\Traits\ActionBy;

class Photography extends Model
{
    use SoftDeletes, ActionBy;

    protected $table = 'photographs';
    protected $fillable = ['photographed_at', 'photographer', 'description', 'file_name', 'size', 'mime_type', 'piece_id', 'module_id', 'created_at', 'updated_at'];
    protected $dates = [
        'photographed_at',
    ];
    protected $casts = [
        'photographed_at' => 'date',
    ];
}
