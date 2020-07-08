<?php

namespace Mnemosine;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mnemosine\Traits\ActionBy;

class FieldGroup extends Model
{
    use SoftDeletes, ActionBy;

    protected $fillable = ['label', 'active', 'order', 'module_id'];
}
