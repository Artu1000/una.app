<?php

namespace App\Repositories\QrCodeScan;

use App\Models\QrCodeScan;
use App\Repositories\BaseRepository;

class QrCodeScanRepository extends BaseRepository implements QrCodeScanRepositoryInterface
{
    
    /**
     * QrCodeScanRepository constructor.
     */
    public function __construct()
    {
        $this->model = new QrCodeScan();
    }
    
}