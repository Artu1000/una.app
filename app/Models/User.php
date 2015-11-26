<?php

namespace App\Models;

use Cartalyst\Sentinel\Users\EloquentUser as SentinelUser;

class User extends SentinelUser
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'photo',
        'gender',
        'last_name',
        'first_name',
        'birth_date',
        'phone_number',
        'email',
        'address',
        'zip_code',
        'city',
        'country',
        'status',
        'board',
        'password',
        'permissions',
    ];

    public function availableSizes()
    {
        return [
            'admin'   => [80, 80],
            'picture' => [145, 160],
        ];
    }

    public function size($key)
    {
        if (!empty($sizes = $this->availableSizes())) {
            return $sizes[$key];
        }

        return null;
    }

    public function storagePath()
    {
        if(!is_dir($storage_path = storage_path('app/user'))){
            mkdir($storage_path);
        }
        return $storage_path;
    }

    public function imageName()
    {
        return $this->id . '_photo';
    }
}
