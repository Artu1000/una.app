<?php

namespace App\Models;

class Partner extends _BaseModel
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
        $this->table = 'partners';

        // we define the fillable attributes
        $this->fillable = [
            'name',
            'logo',
            'url',
            'position',
            'active',
        ];

        // we define the image(s) size(s)
        $this->sizes = [
            'logo' => [
                'admin' => [40, 40],
                'logo'  => [null, 100],
            ],
        ];

        // we define the public path to retrieve files
        $this->public_path = 'img/partners';

        // we define the storage path to store files
        $this->storage_path = 'app/partners';
    }

}
