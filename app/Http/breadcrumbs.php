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
        $breadcrumbs->push(trans('breadcrumbs.permissions.edit'), route('permissions.edit'));

        // we personalize the breadcrumb on edition
        if (!empty($data)) {
            foreach ($data as $additionnal_breadcrumb) {
                $breadcrumbs->push($additionnal_breadcrumb, '');
            }
        }
    });

    // users
    \Breadcrumbs::register('users.index', function ($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(trans('breadcrumbs.users.index'), route('users.index'));
    });
    \Breadcrumbs::register('users.create', function ($breadcrumbs) {
        $breadcrumbs->parent('users.index');
        $breadcrumbs->push(trans('breadcrumbs.users.create'), route('users.create'));
    });

    \Breadcrumbs::register('users.edit', function ($breadcrumbs, array $data) {
        $breadcrumbs->parent('users.index');
        $breadcrumbs->push(trans('breadcrumbs.users.edit'), '');

        // we personalize the breadcrumb on edition
        if (!empty($data)) {
            foreach ($data as $additionnal_breadcrumb) {
                $breadcrumbs->push($additionnal_breadcrumb, '');
            }
        }
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
        $breadcrumbs->push(trans('breadcrumbs.slides.edit'), route('slides.edit'));

        // we personalize the breadcrumb on edition
        if (!empty($data)) {
            foreach ($data as $additionnal_breadcrumb) {
                $breadcrumbs->push($additionnal_breadcrumb, '');
            }
        }
    });
}