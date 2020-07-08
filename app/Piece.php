<?php

namespace Mnemosine;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mnemosine\Traits\ActionBy;
use Mnemosine\Research;
use Mnemosine\Gender;
use Mnemosine\Subgender;
use Mnemosine\Catalog_element;
use Mnemosine\Exhibition;

class Piece extends Model
{
     use SoftDeletes, ActionBy;

    protected $table = 'pieces';
    protected $fillable = ['inventory_number', 'origin_number', 'description_origin', 'gender_id', 'subgender_id', 'type_object_id', 'location_id', 'catalog_number', 'base_or_frame', 'appraisal', 'height', 'width', 'depth', 'diameter', 'height_with_base', 'width_with_base', 'depth_with_base', 'diameter_with_base', 'admitted_at'];
    protected $dates = [
        'admitted_at',
    ];
    protected $casts = [
        'appraisal' => 'decimal:2',
        'height' => 'decimal:2',
        'width' => 'decimal:2',
        'depth' => 'decimal:2',
        'diameter' => 'decimal:2',
        'with_base' => 'boolean',
        'research_info' => 'boolean',
        'restoration_info' => 'boolean',
        'in_exhibition' => 'boolean',
    ];

    protected $appends = ['tags2'];

    public function research(){
        return $this->hasOne('Mnemosine\Research');
    }

    public function gender(){
        return $this->hasOne('Mnemosine\Gender', 'id', 'gender_id');
    }

    public function subgender(){
        return $this->hasOne('Mnemosine\Subgender', 'id', 'subgender_id');
    }

    public function type_object(){
        return $this->hasOne('Mnemosine\Catalog_element', 'id', 'type_object_id');
    }

    public function photography(){
        return $this->hasMany('Mnemosine\Photography', 'piece_id', 'id');
    }

    public function documents(){
        return $this->hasMany('Mnemosine\Document', 'piece_id', 'id');
    }

    public function location(){
        return $this->hasOne('Mnemosine\Exhibition', 'id', 'location_id');
    }

    public function getTags2Attribute(){
        $tags = explode(",", $this->tags);
        return $tags;
    }
}
