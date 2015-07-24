@extends('layouts.back.nude_layout')

@section('content')

    <div class="container login">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <form class="form-signin" role="form" method="POST" action="{{ url() }}/auth/login">

                    {{-- Token --}}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    {{-- Logo / icon --}}
                    <div id="carportal_icon">
                        <span class="glyphicon glyphicon-off"></span>
                    </div>

                    {{-- Title--}}
                    <h1>Université Nantes Aviron (UNA)</h1>
                    <h2 class="form-signin-heading">Espace connexion</h2>

                    {{-- Email input --}}
                    <label class="sr-only" for="inputEmail">Adresse email</label>
                    <div id="emailFormGroup" class="form-group submit-blocker show-alert">
                        <div class="input-group">
                            <span class="input-group-addon" for="inputEmail">
                                <span class="glyphicon glyphicon-envelope"></span>
                            </span>
                            <input id="inputEmail" class="form-control" type="email" name="email"
                                   aria-describedby="inputEmailStatus" value="{{ old('email') }}"
                                   placeholder="Adresse email..." required autofocus>
                        </div>
                    </div>

                    {{-- Password input--}}
                    <label for="inputPassword" class="sr-only">Password</label>
                    <div id="passwordFormGroup" class="form-group submit-blocker show-alert">
                        <div class="input-group">
                            <span class="input-group-addon" for="inputPassword">
                                <span class="glyphicon glyphicon-lock"></span>
                            </span>
                            <input type="password" id="inputPassword" class="form-control" name="password"
                                   placeholder="Mot de passe..." title="Mot de passe" required>
                        </div>
                    </div>

                    {{-- Remember checkbox --}}
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember" checked> Se souvenir de moi
                        </label>
                    </div>

                    {{-- Submit Login request --}}
                    <button id="loginSubmit" class="btn btn-lg btn-primary btn-block disabled" type="submit">
                        <span class="glyphicon glyphicon-play-circle"></span> Connexion
                    </button>

                </form>

                {{-- Forgotten password form--}}
                <form class="forgotten-pswd" role="form" method="POST" action="{{ url() }}/password/email">

                    {{-- Token --}}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    {{-- Email input --}}
                    <div id="emailResetFormGroup" class="form-group submit-blocker">
                        <input type="email" id="forgotten_pswd_email" value="" name="email" required>
                    </div>

                    {{-- Submit Forgotten email request --}}
                    <button id="resetPasswordSubmit" type="submit" class="btn disabled"
                            title="Mot de passe oublié ?">Mot de passe oublié ?</button>

                </form>
            </div>
        </div>
    </div>

@endsection