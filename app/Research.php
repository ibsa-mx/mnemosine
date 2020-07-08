<?php

namespace Mnemosine;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mnemosine\Traits\ActionBy;
use Mnemosine\Catalog_element;

class Research extends Model
{
    use SoftDeletes, ActionBy;

    protected $table = 'researchs';
    protected $fillable = ['title', 'author_ids', 'set_id', 'technique', 'materials', 'period_id', 'place_of_creation_id', 'acquisition_form', 'acquisition_source', 'acquisition_date', 'firm', 'firm_description', 'short_description', 'formal_description', 'observation', 'publications', 'piece_id', 'creation_date', 'card'];
    protected $appends = ['authors'];

    // public function piece()
    // {
    //     return $this->belongsTo('Mnemosine\Piece');
    // }

    public function set(){
        return $this->hasOne('Mnemosine\Catalog_element', 'id', 'set_id');
    }

    public function period(){
        return $this->hasOne('Mnemosine\Catalog_element', 'id', 'period_id');
    }

    public function place_of_creation(){
        return $this->hasOne('Mnemosine\Catalog_element', 'id', 'place_of_creation_id');
    }

    public function getAuthorsAttribute(){
        $returnArray = array();
        if($this->author_ids == 'NULL' || is_null($this->author_ids)){
            return $returnArray;
        }
        $authorsIds = array_filter(explode(",", $this->author_ids), 'strlen');
        if(count($authorsIds) > 0){
            $returnArray = Catalog_element::find($authorsIds)->pluck('title')->toArray();
        }
        return $returnArray;
    }
}
