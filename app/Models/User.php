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
     * The available sizes of the model images.
     *
     * @var array
     */
    protected $sizes = [
        'photo' => [
            'admin'   => [40, 40],
            'picture' => [145, 160],
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
     * @return string
     */
    public function imagePath($file_name, $key = null, $size = null)
    {
        // if the key / size are not given
        if (!$key || !$size) {
            // we return the original image path
            return url($this->public_path . '/' . $file_name);
        }
        try {
            // we return the sized image path
            list($name, $ext) = explode('.', $file_name);

            return url($this->public_path . '/' . $name . '_' . $size . '.' . $ext);
        } catch (\Exception $e) {
            \Log::error($e);

            return 'error';
        }
    }

    /**
     * @return array
     */
    public function availableSizes($key)
    {
        return $this->sizes[$key];
    }

    /**
     * @param $key
     * @return null
     */
    public function size($key, $size)
    {
        if (!empty($sizes = $this->availableSizes($key))) {
            return $sizes[$size];
        }

        return null;
    }

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
     * @return string
     */
    public function imageName($key)
    {
        if (!array_key_exists($key, $this->sizes)) {
            throw new \InvalidArgumentException('The key must be declared into the eloquent object sizes.');
        };

        return $this->id . '_' . $key;
    }
}
