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
        'permissions'
    ];

    public $image_sizes = [
        'picture' => [145, 160]
    ];

    /**
     * @param $size
     * @return mixed
     */
    public function size($size)
    {
        if(!empty($this->image_sizes[$size])){
            return $this->image_sizes[$size];
        }
        return null;
    }
}
