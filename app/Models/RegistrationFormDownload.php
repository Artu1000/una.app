<?php

namespace App\Models;

class RegistrationFormDownload extends _BaseModel
{
    /**
     * News constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
        
        // we define the table name
        $this->table = 'registration_form_downloads';
        
        // we define the fillable attributes
        $this->fillable = [
            //
        ];
    }
}
