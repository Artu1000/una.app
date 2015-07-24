<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    private $seoMeta = [
        'page_title',
        'description',
        'keywords'
    ];

    public function getSeoMeta()
    {
        return $this->seoMeta;
    }

    public function setSeoMeta(array $seoMeta)
    {
        $this->seoMeta = $seoMeta;
    }
}
