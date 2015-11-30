<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'slides';

    /**
     * The attributes that are not assignable.
     *
     * @var string
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'quote',
        'picto',
        'background_image',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
    ];

    protected $sizes = [
        'background_image' => [
            'admin' => [40, 40],
            '767'   => [767, 0],
            '991'   => [991, 0],
            '1199'  => [1199, 0],
            '1919'  => [1919, 0],
            '2560'  => [2560, 1440],
        ],
        'picto'            => [
            [
                'admin' => [40, 40],
                'picto' => [300, 300],
            ],
        ],
    ];

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

    /**
     * @return string
     */
    public function storagePath()
    {
        if (!is_dir($storage_path = storage_path('app/home/slide'))) {
            if (!is_dir($path = storage_path('app/home'))) {
                mkdir($path);
            }
            mkdir($path . '/slide');
        }

        return $storage_path;
    }

    /**
     * @return string
     */
    public function imageName($key)
    {
        return $this->id . '_' . $key;
    }
}
