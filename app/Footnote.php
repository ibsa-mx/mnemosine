<?php

namespace Mnemosine;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mnemosine\Traits\ActionBy;

class Footnote extends Model
{
    use SoftDeletes, ActionBy;

    protected $fillable = ['title', 'author', 'article', 'chapter', 'editorial', 'vol_no', 'city_country', 'pages', 'publication_date', 'research_id', 'description', 'created_at', 'updated_at'];
}
