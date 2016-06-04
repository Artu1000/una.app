<?php

namespace App\Models;

class Photo extends _BaseModel
{
    /**
     * News constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;

        // we define the table name
        $this->table = 'photos';

        // we define the fillable attributes
        $this->fillable = [
            'title',
            'link',
            'cover',
            'active',
            'year',
        ];

        // we define the image(s) size(s)
        $this->sizes = [
            'cover' => [
                'admin' => [40, 40],
                'front' => [220, 220],
            ],
        ];

        // we define the public path to retrieve files
        $this->public_path = 'img/photos';

        // we define the storage path to store files
        $this->storage_path = 'app/photos';
    }
}
