<?php

namespace Mnemosine;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mnemosine\Traits\ActionBy;

class Bibliography extends Model
{
    use SoftDeletes, ActionBy;

    protected $table = 'bibliographies';
    protected $fillable = ['reference_type_id', 'title', 'author', 'article', 'chapter', 'editorial', 'vol_no', 'city_country', 'pages', 'publication_date', 'webpage', 'identifier', 'editor', 'research_id', 'created_at', 'updated_at'];
}
