<?php

namespace App\Http\Controllers\Palmares;

use App\Http\Controllers\Controller;
use App\Repositories\Palmares\PalmaresEventRepositoryInterface;


class PalmaresController extends Controller
{

    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct(PalmaresEventRepositoryInterface $event)
    {
        $this->repository = $event;
        $this->loadBaseJs();
    }

    /**
     * @return $this
     */
    public function index()
    {
        // we get the palmares list
        $palmares = $this->repository->eventsWithResultsSortedByCategory();

        // SEO Meta settings
        $this->seoMeta['page_title'] = 'Palmares';
        $this->seoMeta['meta_desc'] = 'Découvrez le palmares du club Université Nantes Aviron,
        depuis sa création jusqu\'a aujourd\'hui...';
        $this->seoMeta['meta_keywords'] = 'club, université, nantes, aviron, palmares, resultats';

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'palmares' => $palmares,
            'css' => elixir('css/app.palmares.css')
        ];

        // return the view with data
        return view('pages.front.palmares')->with($data);
    }

}
