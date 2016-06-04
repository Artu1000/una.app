<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use ImageManager;
use InvalidArgumentException;

abstract class _BaseModel extends Model
{

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
    protected $public_path;

    /**
     * The public image folder to target
     *
     * @var array
     */
    protected $storage_path;

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
     * @return null
     */
    public function size($key, $size)
    {
        if (!empty($sizes = $this->availableSizes($key))) {
            return $sizes[$size];
        }

        return null;
    }

    /**
     * @return array
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
     * @param $type
     * @return mixed
     */
    public function imageName($type)
    {
        if (!array_key_exists($type, $this->sizes)) {
            throw new InvalidArgumentException('The key must be declared into the eloquent object sizes.');
        };

        return str_slug(config('image.prefix') . $this->id . '-' . $type);
    }
}
