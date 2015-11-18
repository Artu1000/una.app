@extends('templates.front.nude_layout')

@section('content')

    <div id="content" class="login row fill">

        <div class="container fill">

            <div class="display-table fill">

                <div class="form_container v-center table-cell">

                    <div class="form_capsule col-sm-offset-4 col-sm-4">

                        <form class="form-signin" role="form" method="POST" action="{{ route('password_reset', 'reinitialisation') }}">

                            {{-- crsf token --}}
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_email" value="{{ $email }}">
                            <input type="hidden" name="_reminder" value="{{ $reminder }}">

                            {{-- logo / icon --}}
                            <a class="logo text-center" href="" title="{{ config('settings.app_name') }}">
                                <img width="300" height="280" src="{{ url(env('LOGO_LIGHT')) }}" alt="{{ config('settings.app_name') }}">
                            </a>

                            {{-- Title--}}
                            <h1><i class="fa fa-refresh"></i> Réinitialisation</h1>

                            {{-- password input --}}
                            <label class="sr-only" for="input_password">Mot de passe</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_password"><i class="fa fa-unlock-alt"></i></span>
                                    <input id="input_password" class="form-control" type="password" name="password" placeholder="Mot de passe" autofocus>
                                </div>
                            </div>

                            {{-- password confirmation input --}}
                            <label class="sr-only" for="input_password_confirmation">Confirmation du mot de passe</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_password_confirmation"><i class="fa fa-unlock-alt"></i></span>
                                    <input id="input_password_confirmation" class="form-control" type="password" name="password_confirmation" placeholder="Confirmation du mot de passe">
                                </div>
                            </div>

                            <p class="quote">Saisissez votre nouveau mot de passe.</p>

                            {{-- submit login --}}
                            <button class="btn btn-lg btn-primary btn-block spin-on-click" type="submit">
                                <i class="fa fa-magic"></i> Enregistrer
                            </button>

                        </form>

                        <a href="{{ route('login') }}" class="pull-right cancel" title="Retour">
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