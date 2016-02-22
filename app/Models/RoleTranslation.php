<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleTranslation extends Model
{

    protected $table = 'role_translations';

    public $timestamps = false;

    protected $fillable = [
        'role_id',
        'locale',
        'name',
    ];

}
