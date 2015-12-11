<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
