<?php

namespace App\Composers\Pages;

use App\Repositories\Page\PageRepositoryInterface;

class PagesComposer
{

    /**
     * PagesComposer constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param $view
     */
    public function compose($view)
    {
        // we get the pages data
        $view->history = app(PageRepositoryInterface::class)
            ->getModel()
            ->where('slug', 'historique')
            ->where('active', true)
            ->first();
        $view->statuses = app(PageRepositoryInterface::class)
            ->getModel()
            ->where('slug', 'statuts')
            ->where('active', true)
            ->first();
        $view->rules = app(PageRepositoryInterface::class)
            ->getModel()
            ->where('slug', 'reglement-interieur')
            ->where('active', true)
            ->first();
        $view->terms_and_conditions = app(PageRepositoryInterface::class)
            ->getModel()
            ->where('slug', 'mentions-legales')
            ->where('active', true)
            ->first();
    }
}