<?php

namespace App\Models;

class LibraryImage extends _BaseModel
{

    public function __construct(array $attributes = [])
    {
        // we set the object attributes
        $this->attributes = $attributes;

        // we define the table name
        $this->table = 'library_images';

        // we define the fillable attributes
        $this->fillable = [
            'src',
            'alias',
        ];

        // we define the public path to retrieve files
        $this->public_path = 'libraries/images';

        // we define the storage path to store files
        $this->storage_path = 'app/libraries/images';

        $this->sizes = [
            'image' => [
                'admin' => [40, 40],
            ],
        ];
    }
}
