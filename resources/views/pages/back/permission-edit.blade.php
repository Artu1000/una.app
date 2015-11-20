@extends('templates.back.full_layout')

@section('content')

    <div id="content" class="permissions creation row">

        <div class="text-content">

            <div class="col-sm-12">

                {{-- Title--}}
                <h2>
                    <i class="fa fa-gavel"></i>
                    @if(isset($role))
                        Édition d'un rôle utilisateur
                    @else
                        Création d'un rôle utilisateur
                    @endif
                </h2>

                <hr>

                <form role="form" method="POST" action="
                      @if(isset($role))
                        {{ route('permissions.update') }}
                      @else
                        {{ route('permissions') }}
                      @endif ">

                    {{-- crsf token --}}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    {{-- add update inputs if we are in update mode --}}
                    @if(isset($role))
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="_id" value="{{ $role->id }}">
                    @endif

                    <div class="panel panel-default">

                        <div class="panel-heading">
                            <h3 class="panel-title">Informations du rôle</h3>
                        </div>

                        <div class="panel-body">

                            {{-- name --}}
                            <label for="input_name">Nom du rôle</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_name"><i class="fa fa-font"></i></span>
                                    <input id="input_name" class="form-control capitalize-first-letter" type="text" name="name" value="{{ !empty(old('name')) ? old('name') : ((isset($role->name)) ? $role->name : null) }}" placeholder="Nom du rôle">
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
                                    <label for="{{ $permission }}"><input id="{{ $permission }}" type="checkbox" name="{{ $permission }}" @if(old($permission, (isset($role->permissions[$permission]) ? $role->permissions[$permission] : ''))) checked @endif>{!! \Lang::get('permissions.' . $permission) !!}</label>
                                </div>
                            @endforeach

                        </div>
                    </div>

                    {{-- submit login --}}
                    @if(isset($role))
                        <button class="btn btn-primary spin-on-click" type="submit">
                            <i class="fa fa-pencil-square"></i> Éditer le rôle
                        </button>
                        <a href="{{ route('permissions') }}" class="btn btn-default spin-on-click" title="Retour">
                            <i class="fa fa-undo"></i> Retour
                        </a>
                    @else
                        <button class="btn btn-success spin-on-click" type="submit">
                            <i class="fa fa-plus-circle"></i> Créer le rôle
                        </button>
                        <a href="{{ route('permissions') }}" class="btn btn-default spin-on-click" title="Retour">
                            <i class="fa fa-ban"></i> Annuler
                        </a>
                    @endif
                </form>

            </div>
        </div>
    </div>

@endsection