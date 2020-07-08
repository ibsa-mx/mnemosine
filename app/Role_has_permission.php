<?php

namespace Mnemosine;

use Illuminate\Database\Eloquent\Model;

class Role_has_permission extends Model
{
    protected $table = 'role_has_permissions';
    protected $fillable = ['permission_id', 'role_id'];
}
