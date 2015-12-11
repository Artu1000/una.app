<?php

namespace App\Models;

class Partner extends _BaseModel
{

    public function __construct()
    {
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
                'logo'  => [null, 300],
            ],
        ];

        // we define the public path to retrieve files
        $this->public_path = 'img/partners';

        // we define the storage path to store files
        $this->storage_path = 'app/partners';
    }

}
