<?php

namespace App\Models;

class QrCodeScan extends _BaseModel
{
    /**
     * News constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
        
        // we define the table name
        $this->table = 'qr_code_scans';
        
        // we define the fillable attributes
        $this->fillable = [
            //
        ];
    }
}
