@extends('templates.emails.layout')

@section('content')

    <h3>Réinitialisation de votre mot de passe</h3>

    <p>
        Accédez à la page de réinitialisation de votre mot de passe en cliquant sur le bouton ci-dessous.
    </p>

    <p>
        <a href="{{ route('password_recovery', [
            'email' => $user->email,
            'token' => $token
        ]) }}">
            <button class="btn">
                Réinitialiser mon mot de passe
            </button>
        </a>
    </p>

    <p>
        En cas de problème, contactez le service support de l'application à l'adresse&nbsp;: <a href="mailto:{{ config('settings.support_email') }}">{{ config('settings.support_email') }}</a>
    </p>

@endsection