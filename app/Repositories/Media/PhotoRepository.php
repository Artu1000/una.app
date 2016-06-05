<?php

namespace App\Repositories\Media;

use App\Models\Photo;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

class PhotoRepository extends BaseRepository implements PhotoRepositoryInterface
{
    /**
     * PhotoRepository constructor.
     */
    public function __construct()
    {
        $this->model = new Photo();
    }

    /**
     * @return array
     */
    public function getAvailableYears()
    {
        // we get all the photos
        $photos = $this->model->all();
        // we add each year found the years array
        $years = [];
        foreach ($photos as $photo) {
            $photo_year = Carbon::createFromFormat('Y-m-d', $photo->date)->format('Y');
            if (!in_array($photo_year, $years)) {
                $years[] = $photo_year;
            }
        }
        // we sort the array
        array_sort($years, function ($year) {
            return $year;
        });

        return $years;
    }
}