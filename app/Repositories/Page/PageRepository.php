<?php

namespace App\Repositories\Page;

use App\Models\Page;
use App\Repositories\BaseRepository;

class PageRepository extends BaseRepository implements PageRepositoryInterface
{

    public function __construct()
    {
        $this->model = new Page();
    }

}