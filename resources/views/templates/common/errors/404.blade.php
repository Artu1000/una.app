@extends('templates.front.nude_layout')

@section('content')

    <div id="content" class="error row fill">

        <div class="container fill">

            <div class="display-table fill">

                <div class="v-center text-center table-cell">

                    <div class="col-sm-offset-4 col-sm-4">

                        <a class="logo text-center" href="{{ route('home') }}" title="{{ config('app.name') }}">
                            <img width="300" height="280" src="{{ config('app.logo.light') }}" alt="{{ config('app.name') }}">
                        </a>

                        <h1><i class="fa fa-bullhorn"></i> Oups !!!</h1>
                        <h2>Erreur 404<br/>Page introuvable</h2>

                        <a href="{{ route('home') }}" title="Revenir à l'accueil de {{ config('app.name') }}" class="btn btn-primary btn-lg">
                            <i class="fa fa-home"></i> Revenir à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop