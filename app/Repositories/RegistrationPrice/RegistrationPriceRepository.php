<?php

namespace App\Repositories\RegistrationPrice;

use App\Models\RegistrationPrice;
use App\Repositories\BaseRepository;

class RegistrationPriceRepository extends BaseRepository implements RegistrationPriceRepositoryInterface
{

    public function __construct()
    {
        $this->model = new RegistrationPrice();
    }

}