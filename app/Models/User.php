<?php

namespace App\Models;

use Cartalyst\Sentinel\Users\EloquentUser as SentinelUser;
use ImageManager;
use InvalidArgumentException;

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
        'status_id',
        'board_id',
        'password',
        'permissions',
    ];

    /**
     * The available sizes of the model images.
     *
     * @var array
     */
    protected $sizes = [
        'photo' => [
            'admin'   => [40, 40],
            'picture' => [145, 160],
            'zoom'    => [null, 300],
        ],
    ];

    /**
     * The public image folder to target
     *
     * @var array
     */
    protected $public_path = 'img/users';

    /**
     * The public image folder to target
     *
     * @var array
     */
    protected $storage_path = 'app/users';

    /**
     * @param $file_name
     * @param null $key
     * @param null $size
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function imagePath($file_name, $key = null, $size = null)
    {
        return ImageManager::imagePath($this->public_path, $file_name, $key, $size);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function availableSizes($key)
    {
        return $this->sizes[$key];
    }

    /**
     * @param $key
     * @param $size
     * @return mixed|null
     */
    public function size($key, $size)
    {
        if (!empty($sizes = $this->availableSizes($key))) {
            return $sizes[$size];
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function publicPath()
    {
        return public_path($this->public_path);
    }

    /**
     * @return string
     */
    public function storagePath()
    {
        if (!is_dir($storage_path = storage_path($this->storage_path))) {
            mkdir($storage_path);
        }

        return $storage_path;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function imageName($key)
    {
        if (!array_key_exists($key, $this->sizes)) {
            throw new InvalidArgumentException('The key must be declared into the eloquent object sizes.');
        };

        return str_slug(config('image.prefix') . $this->id . '-' . $key);
    }
}
