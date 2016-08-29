<?php

namespace App\Repositories\Libraries;

use App\Models\LibraryFile;
use App\Repositories\BaseRepository;

class LibraryFileRepository extends BaseRepository implements LibraryFileRepositoryInterface
{
    public function __construct()
    {
        $this->model = new LibraryFile();
    }
}