<?php

namespace App\Models;

use FileManager;
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
     * The private storage path to target
     *
     * @var array
     */
    protected $storage_path;
    
    /**
     * @param string      $file_name
     * @param string|null $key
     * @param string|null $size
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function imagePath(string $file_name, string $key = null, string $size = null)
    {
        return ImageManager::imagePath($this->public_path, $file_name, $key, $size);
    }
    
    /**
     * @param string $file_name
     *
     * @return mixed
     */
    public function filePath(string $file_name)
    {
        return FileManager::filePath($this->public_path, $file_name);
    }
    
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function availableSizes(string $key)
    {
        return $this->sizes[$key];
    }
    
    /**
     * @param string $key
     * @param string $size
     *
     * @return null
     */
    public function size(string $key, string $size)
    {
        if (!empty($sizes = $this->availableSizes($key))) {
            return $sizes[$size];
        }
        
        return null;
    }
    
    /**
     * @param string $path
     * @param bool   $absolute
     *
     * @return array
     */
    public function publicPath(string $path = null, bool $absolute = true)
    {
        return $absolute ? public_path($this->public_path . ($path ? '/' . $path : '')) : $this->public_path . ($path ? '/' . $path : '');
    }
    
    /**
     * @param string $path
     *
     * @return string
     */
    public function storagePath(string $path = null)
    {
        if (!is_dir($storage_path = storage_path($this->storage_path))) {
            mkdir($storage_path, 0777, true);
        }
        
        return $storage_path . ($path ? '/' . $path : '');
    }
    
    /**
     * @param string $size
     *
     * @return mixed
     */
    public function imageName(string $size)
    {
        if (!array_key_exists($size, $this->sizes)) {
            throw new InvalidArgumentException('The key must be declared into the eloquent object sizes.');
        };
        
        return str_slug(config('image.prefix') . $this->id . '-' . $size);
    }
    
    /**
     * @param string|null $custom_name
     *
     * @return string
     */
    public function fileName(string $custom_name = null)
    {
        return str_slug(config('file.prefix') . $this->id . ($custom_name ? '-' . $custom_name : '-file'));
    }
}
