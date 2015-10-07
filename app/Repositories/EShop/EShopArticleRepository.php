<?php

namespace App\Repositories\EShop;

use App\Models\EShopArticle;
use App\Repositories\BaseRepository;

class EShopArticleRepository extends BaseRepository implements EShopArticleRepositoryInterface
{
    public function __construct()
    {
        $this->model = new EShopArticle();
    }
}