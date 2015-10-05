<?php

namespace App\Repositories\Schedule;

use App\Models\Schedule;
use App\Repositories\BaseRepository;

class ScheduleRepository extends BaseRepository implements ScheduleRepositoryInterface
{

    public function __construct()
    {
        $this->model = new Schedule();
    }

}