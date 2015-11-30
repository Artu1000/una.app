<?php

namespace App\Repositories\Slide;

use App\Models\Slide;
use App\Repositories\BaseRepository;

class SlideRepository extends BaseRepository implements SlideRepositoryInterface
{
    public function __construct()
    {
        $this->model = new Slide();
    }
}