<?php

namespace App\Repositories\Registration;

use App\Models\QrCodeScan;
use App\Models\RegistrationFormDownload;
use App\Repositories\BaseRepository;

class QrCodeScanRepository extends BaseRepository implements RegistrationFormDownloadRepositoryInterface
{
    
    /**
     * QrCodeScanRepository constructor.
     */
    public function __construct()
    {
        $this->model = new RegistrationFormDownload();
    }
    
}