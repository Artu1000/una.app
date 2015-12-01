@extends('templates.emails.layout')

@section('content')

    <h3>{{ trans('emails.password_reset.title') }}</h3>

    <p>
        {!! trans('emails.account_activation.hello', ['name' => $user->first_name . ' ' . $user->last_name]) !!}
    </p>

    <p>
        {!! trans('emails.password_reset.content') !!}
    </p>

    <p>
        <a href="{{ route('password.reset', ['email' => $user->email, 'token' => $token]) }}">
            <button class="btn">
                {{ trans('emails.password_reset.button') }}
            </button>
        </a>
    </p>

@endsection