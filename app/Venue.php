<?php

namespace Mnemosine;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mnemosine\Traits\ActionBy;

class Venue extends Model
{
    use SoftDeletes, ActionBy;
    protected  $table = 'venues';
    protected  $fillable = ['name', 'address', 'institution_id', 'contact_id', 'exhibition_id', 'updated_at'];
}
