@extends('templates.front.nude_layout')

@section('content')

    <div id="content" class="error row fill">

        <div class="container fill">

            <div class="display-table fill">

                <div class="v-center text-center table-cell">

                    <div class="col-sm-offset-4 col-sm-4">

                        {{-- logo --}}
                        @if(config('settings.logo_light'))
                            <a class="logo text-center" href="#">
                                <img width="300" src="{{ route('image', ['filename' => config('settings.logo_light'), 'storage_path' => storage_path('app/config'), 'size' => 'large']) }}" alt="Logo {{ config('settings.app_name') }}">
                            </a>
                        @endif

                        <h1><i class="fa fa-bullhorn"></i> {{ trans('errors.title') }}</h1>
                        <h2>
                            {{ trans('errors.'.$code.'.title') }}<br/>
                            {{ trans('errors.'.$code.'.message') }}
                        </h2>

                        <a href="{{ route('home') }}" title="Revenir à l'accueil de {{ config('settings.app_name') }}" class="btn btn-primary">
                            <i class="fa fa-home"></i> {{ trans('errors.nav.home') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop