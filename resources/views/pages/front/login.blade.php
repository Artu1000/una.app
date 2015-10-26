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
                            <h1><i class="fa fa-sign-in"></i> Espace connexion</h1>

                            {{-- email input --}}
                            <label class="sr-only" for="inputEmail">Adresse email</label>
                            <div id="emailFormGroup" class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input-email"><i class="fa fa-at"></i></span>
                                    <input id="input-email" class="form-control" type="email" name="email"
                                           aria-describedby="inputEmailStatus" value="{{ old('email') }}"
                                           placeholder="Adresse email" autofocus>
                                </div>
                            </div>

                            {{-- password input--}}
                            <label for="inputPassword" class="sr-only">Password</label>
                            <div id="passwordFormGroup" class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input-password">
                                        <i class="fa fa-unlock-alt"></i>
                                    </span>
                                    <input type="password" id="input-password" class="form-control" name="password"
                                           value="{{ old('password') }}" placeholder="Mot de passe" title="Mot de passe">
                                </div>
                            </div>

                            {{-- remember me checkbox --}}
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Se souvenir de moi
                                </label>
                            </div>

                            {{-- submit login --}}
                            <button class="btn btn-lg btn-primary btn-block" type="submit">
                                <i class="fa fa-thumbs-up"></i> Me connecter
                            </button>

                            {{-- forgotten password / create account --}}
                            <div class="form-group others_actions">
                                <a href="{{ route('forgotten_password') }}"> Mot de passe oublié</a>
                                <a href="" class="pull-right"> Créer un compte</a>
                            </div>
                        </form>

                        <a href="{{ route('home') }}" class="pull-right cancel" title="Retour au site">
                            <button class="btn btn-lg btn-default">
                                <i class="fa fa-undo"></i> Retour
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection