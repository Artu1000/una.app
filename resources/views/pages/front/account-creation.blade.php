@extends('templates.front.nude_layout')

@section('content')

    <div id="content" class="login row fill">

        <div class="container fill">

            <div class="display-table fill">

                <div class="form_container v-center table-cell">

                    <div class="form_capsule col-sm-offset-4 col-sm-4">

                        <form class="form-signin" role="form" method="POST" action="{{ route('login') }}">

                            {{-- crsf token --}}
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            {{-- logo / icon --}}
                            <a class="logo text-center" href="" title="{{ config('app.name') }}">
                                <img width="300" height="280" src="{{ config('app.logo.light') }}" alt="{{ config('app.name') }}">
                            </a>

                            {{-- Title--}}
                            <h1><i class="fa fa-user"></i> Créer un compte</h1>

                            {{-- lastname input --}}
                            <label class="sr-only" for="input_lastname">NOM</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_lastname"><i class="fa fa-at"></i></span>
                                    <input id="input_lastname" class="form-control" type="text" name="last_name" value="{{ old('last_name') }}" placeholder="NOM" autofocus>
                                </div>
                            </div>

                            {{-- firstname input --}}
                            <label class="sr-only" for="input_firstname">Prénom</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_firstname"><i class="fa fa-at"></i></span>
                                    <input id="input_firstname" class="form-control" type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Prénom">
                                </div>
                            </div>

                            {{-- firstname input --}}
                            <label class="sr-only" for="input_phone_number">Téléphone</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_phone_number"><i class="fa fa-phone"></i></span>
                                    <input id="input_phone_number" class="form-control" type="text" name="phone_number" value="{{ old('phone_number') }}" placeholder="Téléphone">
                                </div>
                            </div>

                            {{-- email input --}}
                            <label class="sr-only" for="input_email">Adresse e-mail</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_email"><i class="fa fa-at"></i></span>
                                    <input id="input_email" class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="Adresse e-mail" autofocus>
                                </div>
                            </div>

                            {{-- password input--}}
                            <label for="input_password" class="sr-only">Password</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_password">
                                        <i class="fa fa-unlock-alt"></i>
                                    </span>
                                    <input type="password" id="input_password" class="form-control" name="password" value="{{ old('password') }}" placeholder="Mot de passe" title="Mot de passe">
                                </div>
                            </div>

                            {{-- submit login --}}
                            <button class="btn btn-lg btn-primary btn-block" type="submit">
                                <i class="fa fa-thumbs-up"></i> Créer mon compte
                            </button>
                        </form>

                        <a href="{{ route('login') }}" class="pull-right cancel" title="Retour au site">
                            <button class="btn btn-lg btn-default">
                                <i class="fa fa-ban"></i> Annuler
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection