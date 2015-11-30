@extends('templates.front.nude_layout')

@section('content')

    <div id="content" class="login row fill">

        <div class="container fill">

            <div class="display-table fill">

                <div class="form_container v-center table-cell">

                    <div class="form_capsule col-sm-offset-4 col-sm-4">

                        <form class="form-signin" role="form" method="POST" action="{{ route('forgotten_password') }}">

                            {{-- crsf token --}}
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            {{-- logo / icon --}}
                            <a class="logo text-center" href="" title="{{ config('settings.app_name') }}">
                                <img width="300" height="280" src="{{ url(env('LOGO_LIGHT')) }}" alt="{{ config('settings.app_name') }}">
                            </a>

                            {{-- Title--}}
                            <h1><i class="fa fa-unlock-alt"></i> Mot de passe oublié</h1>

                            {{-- email input --}}
                            <label class="sr-only" for="input_email">Adresse e-mail</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_email"><i class="fa fa-at"></i></span>
                                    <input id="input_email" class="form-control" type="email" name="email" value="{{ $email or old('email') }}" placeholder="Adresse e-mail" autofocus>
                                </div>
                            </div>

                            <p class="quote">Renseignez votre e-mail pour y recevoir les instructions de réinitialisation de votre mot de passe.</p>

                            {{-- submit login --}}
                            <button class="btn btn-lg btn-primary btn-block spin-on-click" type="submit">
                                <i class="fa fa-paper-plane"></i> Envoyer l'e-mail
                            </button>

                        </form>

                        <a href="{{ route('login.index') }}" class="pull-right cancel" title="Retour">
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