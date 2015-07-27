@extends('layouts.front.nude_layout')

@section('content')

    <div class="container error text-center">
        <div class="row">
            <div class="col-md-12">
                <div class="error_container row">
                    <div class="error_image">
                        <img width="300" height="280" src="{{ env('SITE_LOGO') }}" alt="Logo {{ env('SITE_NAME') }}">
                    </div>
                    <div class="error_title">
                        <h1>Oups !</h1>
                        <h2>Erreur 404 / Page introuvable</h2>
                    </div>
                    <div class="error_details">
                        <p class="quote">La page demandée n'existe pas.</p>
                    </div>
                    <div class="error_actions">
                        <a href="{{ url() }}" title="Revenir à l'accueil de {{ env('SITE_NAME') }}" class="btn btn-primary btn-lg">
                            <i class="fa fa-home"></i> Revenir à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop