{{--@extends('layouts.front.nude_layout')--}}

@section('content')

    <div id="content" class="error row fill">
        <div class="container fill">

            <div class="display-table fill">

                <div class="v-center text-center table-cell">

                    <div class="image">
                        <img width="300" height="280" src="{{ env('SITE_LOGO') }}" alt="Logo {{ env('SITE_NAME') }}">
                    </div>
                    <div class="title">
                        <h1>Oups !</h1>
                        <h2>Erreur 404 / Page introuvable</h2>
                    </div>
                    <div class="details">
                        <p class="quote">La page demandée n'existe pas.</p>
                    </div>
                    <div class="actions">
                        <a href="{{ url() }}" title="Revenir à l'accueil de {{ env('SITE_NAME') }}" class="btn btn-primary btn-lg">
                            <i class="fa fa-home"></i> Revenir à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop