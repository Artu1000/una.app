<?php

namespace App\Repositories\Media;

use App\Models\Video;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

class VideoRepository extends BaseRepository implements VideoRepositoryInterface
{
    /**
     * PhotoRepository constructor.
     */
    public function __construct()
    {
        $this->model = new Video();
    }

    /**
     * @return array
     */
    public function getAvailableYears()
    {
        // we get all the photos
        $videos = $this->model->all();
        // we add each year found the years array
        $years = [];
        foreach ($videos as $video) {
            $video_year = Carbon::createFromFormat('Y-m-d', $video->date)->format('Y');
            if (!in_array($video_year, $years)) {
                $years[] = $video_year;
            }
        }
        // we sort the array
        rsort($years, SORT_NUMERIC);

        return $years;
    }
}