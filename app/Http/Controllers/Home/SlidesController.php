<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Repositories\News\NewsRepositoryInterface;
use App\Repositories\Slide\SlideRepositoryInterface;
use Illuminate\Http\Request;

class SlidesController extends Controller
{
    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct(SlideRepositoryInterface $slide)
    {
        parent::__construct();
        $this->repository = $slide;
    }

    public function create(Request $request)
    {
        dd('create');
    }

    public function edit(Request $request)
    {
        dd('edit');
    }

    public function update(Request $request)
    {
        dd('update');
    }

    public function destroy(Request $request)
    {
        dd('destroy');
    }

}
