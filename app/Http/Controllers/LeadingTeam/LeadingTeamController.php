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
        $this->seo_meta['page_title'] = trans('seo.front.leading_team.title');
        $this->seo_meta['meta_desc'] = trans('seo.front.leading_team.description');
        $this->seo_meta['meta_keywords'] = trans('seo.front.leading_team.keywords');

        // og meta settings
        $this->og_meta['og:title'] = trans('seo.front.leading_team.title');
        $this->og_meta['og:description'] = trans('seo.front.leading_team.description');
        $this->og_meta['og:type'] = 'article';
        $this->og_meta['og:url'] = route('front.leading_team');

        // we get the activated members of the leading team and the employees
        $team = $this->repository->getModel()
            ->whereHas('activations', function ($query) {
                $query->where('activations.completed', true);
            })->where(function ($query) {
                $query->whereIn('board_id', config('user.board_key'));
                $query->orWhere('status_id', config('user.status_key.employee'));
            })
            ->orderBy('status_id', 'asc')
            ->get();

        // prepare data for the view
        $data = [
            'seo_meta' => $this->seo_meta,
            'og_meta'  => $this->og_meta,
            'team'     => $team,
            'css'      => elixir('css/app.leading-team.css'),
            'js'       => elixir('js/app.leading-team.js'),
        ];

        // return the view with data
        return view('pages.front.leading-team')->with($data);
    }

}
