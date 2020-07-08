<?php

namespace Mnemosine;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mnemosine\Traits\ActionBy;
use Mnemosine\User;

class Report extends Model
{
    use SoftDeletes, ActionBy;

    protected $table = "reports";
    protected $fillable = ['name', 'description', 'pieces_ids', 'institution', 'exhibition', 'exhibition_date_ini', 'exhibition_date_fin', 'module', 'columns', 'select_type', 'lending_list'];
    protected $dates = [
        'exhibition_date_ini',
        'exhibition_date_fin'
    ];
    protected $casts = [
        'lending_list' => 'boolean',
    ];

    public function creator(){
        return $this->hasOne('Mnemosine\User', 'id', 'created_by');
    }

    public function updater(){
        return $this->hasOne('Mnemosine\User', 'id', 'updated_by');
    }
}
