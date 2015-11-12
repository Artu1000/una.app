@extends('templates.emails.layout')

@section('content')

    <h3>Activez votre compte</h3>

    <p>
        Bonjour {{ $user->first_name }} {{ $user->last_name }},
    </p>

    <p>
        Félicitations, votre compte personnel a bien été créé.<br/>
        Pour l'activer et vous y connecter, cliquez sur le bouton ci-dessous.
    </p>


    <p>
        <a href="{{ route('activate_account', [
            'email' => $user->email,
            'token' => $token
        ]) }}">
            <button class="btn">
                Activer mon compte
            </button>
        </a>
    </p>

    <p>&nbsp;</p>

    <p>
        En cas de problème, contactez le service support de l'application à l'adresse&nbsp;: <a href="mailto:{{ config('settings.support_email') }}">{{ config('settings.support_email') }}</a>
    </p>

@endsection