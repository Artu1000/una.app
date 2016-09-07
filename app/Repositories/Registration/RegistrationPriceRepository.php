<?php

namespace App\Repositories\Registration;

use App\Models\RegistrationPrice;
use App\Repositories\BaseRepository;

class RegistrationPriceRepository extends BaseRepository implements RegistrationPriceRepositoryInterface
{

    public function __construct()
    {
        $this->model = new RegistrationPrice();
    }

}