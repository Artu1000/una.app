<?php

namespace App\Http\Controllers\LeadingTeam;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepositoryInterface;

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
    public function index()
    {
        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.front.leading_team.index');
        $this->seoMeta['meta_desc'] = trans('seo.front.leading_team.description');
        $this->seoMeta['meta_keywords'] = trans('seo.front.leading_team.keywords');

        // we get the activated members of the leading team and the employees
        $team = $this->repository->getModel()
            ->whereHas('activations', function ($query) {
                $query->where('activations.completed', true);
            })->where(function($query){
                $query->whereIn('board_id', config('user.board_key'));
                $query->orWhere('status_id', config('user.status_key.employee'));
            })
            ->orderBy('status_id', 'asc')
            ->get();

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'team'    => $team,
            'css'     => url(elixir('css/app.leading-team.css')),
        ];

        // return the view with data
        return view('pages.front.leading-team')->with($data);
    }

}
