<?php

namespace App\Repositories\Palmares;

use App\Models\PalmaresResult;
use App\Repositories\BaseRepository;

class PalmaresResultRepository extends BaseRepository implements PalmaresResultRepositoryInterface
{

    public function __construct()
    {
        $this->model = new PalmaresResult();
    }
}