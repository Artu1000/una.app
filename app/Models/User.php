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

    /**
     * @return array
     */
    public function availableSizes()
    {
        return [
            'admin'   => [40, 40],
            'picture' => [145, 160],
            'large' => [750, null]
        ];
    }

    /**
     * @param $key
     * @return null
     */
    public function size($key)
    {
        if (!empty($sizes = $this->availableSizes())) {
            return $sizes[$key];
        }

        return null;
    }

    /**
     * @return string
     */
    public function storagePath()
    {
        if(!is_dir($storage_path = storage_path('app/user'))){
            mkdir($storage_path);
        }
        return $storage_path;
    }

    /**
     * @return string
     */
    public function imageName()
    {
        return $this->id . '_photo';
    }
}
