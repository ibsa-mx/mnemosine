<?php

namespace Mnemosine;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mnemosine\Traits\ActionBy;

class Institution extends Model
{
	use SoftDeletes, ActionBy;
    protected $table = 'institutions';
    protected $fillable = ['name', 'address', 'city', 'state_id', 'country_id', 'postal_code', 'phone', 'phone2', 'fax', 'email', 'web_site', 'business_activity', 'rfc', 'status', 'updated_at'];
}
