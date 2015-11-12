<?php

namespace App\Repositories\Configuration;

use App\Models\Configuration;
use App\Repositories\BaseRepository;

class ConfigurationRepository extends BaseRepository implements ConfigurationRepositoryInterface
{
    public function __construct()
    {
        $this->model = new Configuration();
    }
}