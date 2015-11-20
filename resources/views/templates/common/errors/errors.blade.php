@extends('templates.front.nude_layout')

@section('content')

    <div id="content" class="error row fill">

        <div class="container fill">

            <div class="display-table fill">

                <div class="v-center text-center table-cell">

                    <div class="col-sm-offset-4 col-sm-4">

                        <a class="logo text-center" href="{{ route('home') }}" title="{{ config('settings.app_name') }}">
                            <img width="300" height="280" src="{{ url(env('LOGO_LIGHT')) }}" alt="{{ config('settings.app_name') }}">
                        </a>

                        <h1><i class="fa fa-bullhorn"></i> Oups !!!</h1>
                        <h2>Erreur {{ $code }}<br/> {{ trans('errors.messages.'.$code) }}</h2>

                        <a href="{{ route('home') }}" title="Revenir à l'accueil de {{ config('settings.app_name') }}" class="btn btn-primary btn-lg">
                            <i class="fa fa-home"></i> Revenir à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop