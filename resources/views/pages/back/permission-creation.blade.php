@extends('templates.back.full_layout')

@section('content')

    <div id="content" class="config row">

        <div class="text-content">

            <div class="col-sm-12">

                {{-- Title--}}
                <h2><i class="fa fa-gavel"></i> Création d'un rôle utilisateur</h2>

                <hr>

                <form role="form" method="POST" action="{{ route('permissions') }}">

                    {{-- crsf token --}}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="panel panel-default panel-responsive">

                        <div class="panel-heading">
                            <h3 class="panel-title">Informations du rôle</h3>
                        </div>

                        <div class="panel-body">

                            {{-- name --}}
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_name"><i class="fa fa-font"></i></span>
                                    <input id="input_name" class="form-control capitalize-first-letter" type="text" name="name" value="{{ old('name') }}" placeholder="Nom du rôle">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- submit login --}}
                    <button class="btn btn-primary spin-on-click" type="submit">
                        <i class="fa fa-floppy-o"></i> Créer le rôle
                    </button>
                    <a href="{{ route('permissions') }}" class="pull-left" title="Retour">
                        <button class="btn btn-default">
                            <i class="fa fa-ban"></i> Annuler
                        </button>
                    </a>
                </form>

            </div>
        </div>
    </div>

@endsection