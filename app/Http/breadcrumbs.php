<?php

if (config('settings.breadcrumbs')) {

    // home
    \Breadcrumbs::register('home', function ($breadcrumbs) {
        $breadcrumbs->push('<i class="fa fa-cogs"></i> ' . trans('breadcrumbs.admin'), route('dashboard.index'));
    });

    // dashboard
    \Breadcrumbs::register('dashboard.index', function ($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(trans('breadcrumbs.dashboard.index'), route('dashboard.index'));
    });

    // settings
    \Breadcrumbs::register('settings.index', function ($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(trans('breadcrumbs.settings.index'), route('settings.index'));
    });

    // permissions
    \Breadcrumbs::register('permissions.index', function ($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(trans('breadcrumbs.permissions.index'), route('permissions.index'));
    });
    \Breadcrumbs::register('permissions.create', function ($breadcrumbs) {
        $breadcrumbs->parent('permissions.index');
        $breadcrumbs->push(trans('breadcrumbs.permissions.create'), route('permissions.create'));
    });
    \Breadcrumbs::register('permissions.edit', function ($breadcrumbs, array $data) {
        $breadcrumbs->parent('permissions.index');
        $breadcrumbs->push(trans('breadcrumbs.permissions.edit', ['role' => $data['role']->name]), '');
    });

    // users
    \Breadcrumbs::register('users.profile', function ($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(trans('breadcrumbs.users.profile'), '');
    });
    \Breadcrumbs::register('users.index', function ($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(trans('breadcrumbs.users.index'), route('users.index'));
    });
    \Breadcrumbs::register('users.create', function ($breadcrumbs) {
        $breadcrumbs->parent('users.index');
        $breadcrumbs->push(trans('breadcrumbs.users.create'), '');
    });
    \Breadcrumbs::register('users.edit', function ($breadcrumbs, array $data) {
        $breadcrumbs->parent('users.index');
        $breadcrumbs->push(trans('breadcrumbs.users.edit', ['user' => $data['user']->first_name . ' ' . $data['user']->last_name]), '');
    });

    // home
    \Breadcrumbs::register('home.edit', function ($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(trans('breadcrumbs.home.edit'), route('home.edit'));
    });
    \Breadcrumbs::register('slides.create', function ($breadcrumbs) {
        $breadcrumbs->parent('home.edit');
        $breadcrumbs->push(trans('breadcrumbs.slides.create'), route('slides.create'));
    });
    \Breadcrumbs::register('slides.edit', function ($breadcrumbs, array $data) {
        $breadcrumbs->parent('home.edit');
        $breadcrumbs->push(trans('breadcrumbs.slides.edit', ['slide' => $data['slide']->title]), '');
    });

    // news
    \Breadcrumbs::register('news.list', function ($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(trans('breadcrumbs.news.list'), route('news.list'));
    });
    \Breadcrumbs::register('news.create', function ($breadcrumbs) {
        $breadcrumbs->parent('news.list');
        $breadcrumbs->push(trans('breadcrumbs.news.create'), route('news.create'));
    });
    \Breadcrumbs::register('news.edit', function ($breadcrumbs, array $data) {
        $breadcrumbs->parent('news.list');
        $breadcrumbs->push(trans('breadcrumbs.news.edit', ['news' => $data['news']->title]), '');
    });

    // partners
    \Breadcrumbs::register('partners.index', function ($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(trans('breadcrumbs.partners.index'), route('partners.index'));
    });
    \Breadcrumbs::register('partners.create', function ($breadcrumbs) {
        $breadcrumbs->parent('partners.index');
        $breadcrumbs->push(trans('breadcrumbs.partners.create'), route('partners.create'));
    });
    \Breadcrumbs::register('partners.edit', function ($breadcrumbs, array $data) {
        $breadcrumbs->parent('partners.index');

        $breadcrumbs->push(trans('breadcrumbs.partners.edit', ['partner' => $data['partner']->name]), '');
    });
}