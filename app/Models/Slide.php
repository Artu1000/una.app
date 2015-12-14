<?php

namespace App\Models;

class Slide extends _BaseModel
{

    /**
     * Partner constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        // we set the object attributes
        $this->attributes = $attributes;

        // we define the table name
        $this->table = 'slides';

        // we define the fillable attributes
        $this->fillable = [
            'title',
            'quote',
            'picto',
            'background_image',
            'position',
            'active',
        ];

        // we define the image(s) size(s)
        $this->sizes = [
            'picto'            => [
                'admin' => [40, 40],
                'picto' => [300, 300],
            ],
            'background_image' => [
                'admin' => [40, 40],
                '767'   => [767, null],
                '991'   => [991, null],
                '1199'  => [1199, null],
                '1919'  => [1919, null],
                '2560'  => [2560, 1440],
            ],
        ];

        // we define the public path to retrieve files
        $this->public_path = 'img/slides';

        // we define the storage path to store files
        $this->storage_path = 'app/home/slides';
    }

}
