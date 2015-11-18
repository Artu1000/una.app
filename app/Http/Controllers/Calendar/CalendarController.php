<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class CalendarController extends Controller
{

    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return $this
     */
    public function index(){

        // SEO Meta settings
        $this->seoMeta['page_title'] = 'Calendrier';
        $this->seoMeta['meta_desc'] = 'Découvrez tous les articles du club Université Nantes Aviron (UNA)
        proposés à la vente.';
        $this->seoMeta['meta_keywords'] = 'club, universite, nantes, aviron, sport, universitaire, etudiant, calendrier';

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'css' => url(elixir('css/app.calendar.css'))
        ];

        // return the view with data
        return view('pages.front.calendar')->with($data);
    }

}
