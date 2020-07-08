<?php

namespace Mnemosine;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mnemosine\Traits\ActionBy;

class Exhibition extends Model
{
    use SoftDeletes, ActionBy;
    protected $table = 'exhibitions';
    protected $fillable = ['name', 'institution_id', 'contact_id', 'updated_at'];
}
