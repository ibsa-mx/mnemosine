<?php

namespace Mnemosine;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mnemosine\Traits\ActionBy;

class Appraisal extends Model
{
    use SoftDeletes, ActionBy;

    protected $table = 'appraisals';
    protected $fillable = ['appraisal', 'piece_id'];
}
