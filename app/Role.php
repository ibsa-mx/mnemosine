<?php

namespace Mnemosine;

use Illuminate\Database\Eloquent\Model;

class Role extends \Spatie\Permission\Models\Role
{
    protected $table = 'roles';
    protected $fillable = ['name', 'guard_name'];
}
