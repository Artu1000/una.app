<?php

if (config('settings.breadcrumbs')) {

    // home
    Breadcrumbs::register('home', function ($breadcrumbs) {
        $breadcrumbs->push('<i class="fa fa-cogs"></i> ' . trans('breadcrumbs.admin'), route('dashboard.index'));
    });

    // dashboard
    Breadcrumbs::register('dashboard.index', function ($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(trans('breadcrumbs.dashboard.index'), route('dashboard.index'));
    });

    // settings
    Breadcrumbs::register('settings.index', function ($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(trans('breadcrumbs.settings.index'), route('settings.index'));
    });

    // permissions
    Breadcrumbs::register('permissions.index', function ($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(trans('breadcrumbs.permissions.index'), route('permissions.index'));
    });
    Breadcrumbs::register('permissions.create', function ($breadcrumbs) {
        $breadcrumbs->parent('permissions.index');
        $breadcrumbs->push(trans('breadcrumbs.permissions.create'), route('permissions.create'));
    });
    Breadcrumbs::register('permissions.edit', function ($breadcrumbs, array $data) {
        $breadcrumbs->parent('permissions.index');
        $breadcrumbs->push(trans('breadcrumbs.permissions.edit', ['role' => $data['role']->name]), '');
    });

    // users
    Breadcrumbs::register('users.profile', function ($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(trans('breadcrumbs.users.profile'), '');
    });
    Breadcrumbs::register('users.index', function ($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(trans('breadcrumbs.users.index'), route('users.index'));
    });
    Breadcrumbs::register('users.create', function ($breadcrumbs) {
        $breadcrumbs->parent('users.index');
        $breadcrumbs->push(trans('breadcrumbs.users.create'), '');
    });
    Breadcrumbs::register('users.edit', function ($breadcrumbs, array $data) {
        $breadcrumbs->parent('users.index');
        $breadcrumbs->push(trans('breadcrumbs.users.edit', ['user' => $data['user']->first_name . ' ' . $data['user']->last_name]), '');
    });

    // home
    Breadcrumbs::register('home.page.edit', function ($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(trans('breadcrumbs.home.page.edit'), route('home.page.edit'));
    });
    Breadcrumbs::register('home.slides.create', function ($breadcrumbs) {
        $breadcrumbs->parent('home.page.edit');
        $breadcrumbs->push(trans('breadcrumbs.home.slides.create'), route('home.slides.create'));
    });
    Breadcrumbs::register('home.slides.edit', function ($breadcrumbs, array $data) {
        $breadcrumbs->parent('home.page.edit');
        $breadcrumbs->push(trans('breadcrumbs.home.slides.edit', ['slide' => $data['slide']->title]), '');
    });

    // news
    Breadcrumbs::register('news.page.edit', function ($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(trans('breadcrumbs.news.page.edit'), route('news.page.edit'));
    });
    Breadcrumbs::register('news.create', function ($breadcrumbs) {
        $breadcrumbs->parent('news.page.edit');
        $breadcrumbs->push(trans('breadcrumbs.news.create'), route('news.create'));
    });
    Breadcrumbs::register('news.edit', function ($breadcrumbs, array $data) {
        $breadcrumbs->parent('news.page.edit');
        $breadcrumbs->push(trans('breadcrumbs.news.edit', ['news' => $data['news']->title]), '');
    });

    // schedules
    Breadcrumbs::register('schedules.page.edit', function ($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(trans('breadcrumbs.schedules.page.edit'), route('schedules.page.edit'));
    });
    Breadcrumbs::register('schedules.create', function ($breadcrumbs) {
        $breadcrumbs->parent('schedules.page.edit');
        $breadcrumbs->push(trans('breadcrumbs.schedules.create'), route('schedules.create'));
    });
    Breadcrumbs::register('schedules.edit', function ($breadcrumbs, array $data) {
        $breadcrumbs->parent('schedules.page.edit');
        $breadcrumbs->push(trans('breadcrumbs.schedules.edit', ['schedule' => $data['schedule']->label]), '');
    });

    // registration
    Breadcrumbs::register('registration.page.edit', function ($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(trans('breadcrumbs.registration.page.edit'), route('registration.page.edit'));
    });
    Breadcrumbs::register('registration.prices.create', function ($breadcrumbs) {
        $breadcrumbs->parent('registration.page.edit');
        $breadcrumbs->push(trans('breadcrumbs.registration.prices.create'), route('registration.prices.create'));
    });
    Breadcrumbs::register('registration.prices.edit', function ($breadcrumbs, array $data) {
        $breadcrumbs->parent('registration.page.edit');
        $breadcrumbs->push(trans('breadcrumbs.registration.prices.edit', ['price' => $data['price']->label]), '');
    });

    // photos
    Breadcrumbs::register('photos.page.edit', function ($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(trans('breadcrumbs.photos.page.edit'), route('photos.page.edit'));
    });
    Breadcrumbs::register('photos.create', function ($breadcrumbs) {
        $breadcrumbs->parent('photos.page.edit');
        $breadcrumbs->push(trans('breadcrumbs.photos.create'), route('photos.create'));
    });
    Breadcrumbs::register('photos.edit', function ($breadcrumbs, array $data) {
        $breadcrumbs->parent('photos.page.edit');
        $breadcrumbs->push(trans('breadcrumbs.photos.edit', ['album' => $data['photo']->title]), '');
    });

    // videos
    Breadcrumbs::register('videos.page.edit', function ($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(trans('breadcrumbs.videos.page.edit'), route('videos.page.edit'));
    });
    Breadcrumbs::register('videos.create', function ($breadcrumbs) {
        $breadcrumbs->parent('videos.page.edit');
        $breadcrumbs->push(trans('breadcrumbs.videos.create'), route('videos.create'));
    });
    Breadcrumbs::register('videos.edit', function ($breadcrumbs, array $data) {
        $breadcrumbs->parent('videos.page.edit');
        $breadcrumbs->push(trans('breadcrumbs.videos.edit', ['video' => $data['video']->title]), '');
    });

    // partners
    Breadcrumbs::register('partners.index', function ($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(trans('breadcrumbs.partners.index'), route('partners.index'));
    });
    Breadcrumbs::register('partners.create', function ($breadcrumbs) {
        $breadcrumbs->parent('partners.index');
        $breadcrumbs->push(trans('breadcrumbs.partners.create'), route('partners.create'));
    });
    Breadcrumbs::register('partners.edit', function ($breadcrumbs, array $data) {
        $breadcrumbs->parent('partners.index');

        $breadcrumbs->push(trans('breadcrumbs.partners.edit', ['partner' => $data['partner']->name]), '');
    });
}