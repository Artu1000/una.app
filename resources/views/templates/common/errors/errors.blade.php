@extends('templates.front.nude_layout')

@section('content')

    <div id="content" class="error row fill">

        <div class="container fill">

            <div class="display-table fill">

                <div class="v-center text-center table-cell">

                    <div class="col-sm-offset-4 col-sm-4">

                        {{-- logo --}}
                        @if(config('settings.logo_light'))
                            <div class="logo display-table">
                                <div class="text-center table-cell fill">
                                    <img width="300" src="{{ ImageManager::imagePath(config('image.settings.public_path'), config('image.settings.logo.name.light') . '.' . config('image.settings.logo.extension'), 'logo', 'large') }}" alt="Logo {{ config('settings.app_name_' . config('app.locale')) }}">
                                </div>
                            </div>
                        @endif

                        <h1>{!! config('settings.info_icon') !!} {{ trans('errors.title') }}</h1>
                        <h2>
                            {{ trans('errors.'.$code.'.title') }} :<br/>
                            {{ trans('errors.'.$code.'.message') }}
                        </h2>

                        <a href="{{ route('home') }}" title="Revenir à l'accueil de {{ config('settings.app_name_' . config('app.locale')) }}" class="btn btn-primary">
                            <i class="fa fa-home"></i> {{ trans('errors.nav.home') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop