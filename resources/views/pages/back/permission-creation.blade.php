@extends('templates.back.full_layout')

@section('content')

    <div id="content" class="permissions creation row">

        <div class="text-content">

            <div class="col-sm-12">

                {{-- Title--}}
                <h2><i class="fa fa-gavel"></i> Création d'un rôle utilisateur</h2>

                <hr>

                <form role="form" method="POST" action="{{ route('permissions') }}">

                    {{-- crsf token --}}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="panel panel-default">

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

                    <div class="panel panel-default">

                        <div class="panel-heading">
                            <h3 class="panel-title">Permissions du rôle</h3>
                        </div>

                        <div class="panel-body">

                            @foreach(array_dot(config('permissions')) as $permission => $value)
                                <div class="checkbox permission @if(!strpos($permission, '.'))parent @endif">
                                    <label for="{{ $permission }}"><input id="{{ $permission }}" type="checkbox" @if(strpos($permission, '.'))name="{{ $permission }}"@endif @if(old($permission)) "checked"@endif>{!!\Lang::get('permissions.' . $permission)!!}</label>
                                </div>
                            @endforeach

                        </div>
                    </div>

                    {{-- submit login --}}
                    <button class="btn btn-success spin-on-click" type="submit">
                        <i class="fa fa-plus-circle"></i> Créer le rôle
                    </button>
                    <a href="{{ route('permissions') }}" class="btn btn-default" title="Retour">
                        <i class="fa fa-ban"></i> Annuler
                    </a>
                </form>

            </div>
        </div>
    </div>

@endsection