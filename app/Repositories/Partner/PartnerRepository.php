<?php

namespace App\Repositories\Partner;

use App\Models\Partner;
use App\Repositories\BaseRepository;

class PartnerRepository extends BaseRepository implements PartnerRepositoryInterface
{

    public function __construct()
    {
        $this->model = new Partner();
    }

}