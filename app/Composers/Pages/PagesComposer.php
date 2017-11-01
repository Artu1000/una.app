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
        $view->registration = app(PageRepositoryInterface::class)
            ->getModel()
            ->where('slug', 'inscription')
            ->where('active', true)
            ->first();
        $view->practical_info = app(PageRepositoryInterface::class)
            ->getModel()
            ->where('slug', 'infos-pratiques')
            ->where('active', true)
            ->first();
        $view->terms_and_conditions = app(PageRepositoryInterface::class)
            ->getModel()
            ->where('slug', 'mentions-legales')
            ->where('active', true)
            ->first();
        $view->juniors = app(PageRepositoryInterface::class)
            ->getModel()
            ->where('slug', 'juniors')
            ->where('active', true)
            ->first();
        $view->university = app(PageRepositoryInterface::class)
            ->getModel()
            ->where('slug', 'aviron-universitaire')
            ->where('active', true)
            ->first();
        $view->masters = app(PageRepositoryInterface::class)
            ->getModel()
            ->where('slug', 'aviron-loisirs-masters')
            ->where('active', true)
            ->first();
        $view->openday = app(PageRepositoryInterface::class)
            ->getModel()
            ->where('slug', 'portes-ouvertes')
            ->where('active', true)
            ->first();
        $view->summercamp = app(PageRepositoryInterface::class)
            ->getModel()
            ->where('slug', 'stage-d-ete')
            ->where('active', true)
            ->first();
        $view->trainingcamp = app(PageRepositoryInterface::class)
            ->getModel()
            ->where('slug', 'stage-club')
            ->where('active', true)
            ->first();
        $view->indoor_rowing = app(PageRepositoryInterface::class)
            ->getModel()
            ->where('slug', 'aviron-indoor')
            ->where('active', true)
            ->first();
        $view->crossfit = app(PageRepositoryInterface::class)
            ->getModel()
            ->where('slug', 'aviron-crossfit')
            ->where('active', true)
            ->first();
        $view->schools = app(PageRepositoryInterface::class)
            ->getModel()
            ->where('slug', 'scolaires')
            ->where('active', true)
            ->first();
        $view->corporate = app(PageRepositoryInterface::class)
            ->getModel()
            ->where('slug', 'aviron-entreprise')
            ->where('active', true)
            ->first();
        $view->custom = app(PageRepositoryInterface::class)
            ->getModel()
            ->where('slug', 'aviron-a-la-demande')
            ->where('active', true)
            ->first();
    }
}
