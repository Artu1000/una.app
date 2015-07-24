@extends('templates.carportal.nude-template')

@section('content')

    <div class="container error">
        <div class="row">
            <div class="col-md-12">
                <div class="error-template">
                    <h1>Oups !</h1>
                    <h2>Erreur 404 / Page introuvable</h2>
                    <div class="error-details">
                        La page demandée n'existe pas.
                    </div>
                    <div class="error-actions">
                        <a href="{{ url('/') }}" class="btn btn-primary btn-lg">
                            <span class="glyphicon glyphicon-home"></span> Revenir à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop