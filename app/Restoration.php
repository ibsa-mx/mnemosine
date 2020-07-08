<?php

namespace Mnemosine;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mnemosine\Traits\ActionBy;

class Restoration extends Model
{
    use SoftDeletes, ActionBy;

    protected $fillable = ['preliminary_examination', 'laboratory_analysis', 'proposal_of_treatment', 'treatment_description', 'results', 'observations', 'treatment_date', 'piece_id', 'user_id', 'created_at', 'updated_at', 'base_or_frame', 'height', 'width', 'depth', 'diameter', 'height_with_base', 'width_with_base', 'depth_with_base', 'diameter_with_base', 'responsible_restorer'];
    protected $dates = [
        'treatment_date',
    ];
}
