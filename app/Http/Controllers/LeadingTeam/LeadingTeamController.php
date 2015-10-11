<?php

namespace App\Http\Controllers\LeadingTeam;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepositoryInterface;
use Cartalyst\Sentinel\Users\EloquentUser;

class LeadingTeamController extends Controller
{

    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct(UserRepositoryInterface $user)
    {
        parent::__construct();
        $this->repository = $user;
    }

    /**
     * @return $this
     */
    public function index(){

        // SEO Meta settings
        $this->seoMeta['page_title'] = 'Equipe dirigeante';
        $this->seoMeta['meta_desc'] = 'Découvrez l\équipe dirigeante du club Université Nantes Aviron (UNA).';
        $this->seoMeta['meta_keywords'] = 'club, universite, nantes, aviron, sport, universitaire, etudiant, equipe, dirigeante';


        // we get the two last news
        $team = $this->repository->orderBy('status', 'asc')->get();

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'team' => $team,
            'css' => elixir('css/app.leading-team.css')
        ];

        // return the view with data
        return view('pages.front.leading-team')->with($data);
    }

}
