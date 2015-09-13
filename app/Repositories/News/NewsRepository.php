<?php

namespace App\Repositories\News;

use App\Models\News;
use App\Repositories\BaseRepository;

class NewsRepository extends BaseRepository implements NewsRepositoryInterface
{

    public function __construct()
    {
        $this->model = new News();
    }

}