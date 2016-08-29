<?php

namespace App\Repositories\Libraries;

use App\Models\LibraryImage;
use App\Repositories\BaseRepository;

class LibraryImageRepository extends BaseRepository implements LibraryImageRepositoryInterface
{
    /**
     * LibraryImageRepository constructor.
     */
    public function __construct()
    {
        $this->model = new LibraryImage();
    }
}