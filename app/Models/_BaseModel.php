<?php

namespace App\Models;

use Cartalyst\Sentinel\Users\EloquentUser as SentinelUser;

abstract class _BaseModel extends SentinelUser
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = '';

    /**
     * The attributes that are not assignable.
     *
     * @var string
     */
    protected $guarded = [
        'id'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The available sizes of the model images.
     *
     * @var array
     */
    protected $sizes = [];

    /**
     * The public image folder to target
     *
     * @var array
     */
    protected $public_path = '';

    /**
     * The public image folder to target
     *
     * @var array
     */
    protected $storage_path = '';


    /**
     * @param $file_name
     * @return string
     */
    public function imagePath($file_name)
    {
        return url($this->public_path . '/' . $file_name);
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
        if(!in_array($key, $this->sizes)){
            throw new \InvalidArgumentException('The key must be declared into the eloquent object sizes.');
        };
        return $this->id . '_' . $key;
    }
}
