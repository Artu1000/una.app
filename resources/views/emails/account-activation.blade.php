@extends('templates.emails.layout')

@section('content')

    <h3>{{ trans('emails.account_activation.title') }}</h3>

    <p>
        {!! trans('emails.account_activation.hello', ['name' => $user->first_name . ' ' . $user->last_name]) !!}
    </p>

    <p>
        {!! trans('emails.account_activation.content') !!}
    </p>


    <p>
        <a href="{{ route('account.activate', [
            'email' => $user->email,
            'token' => $token
        ]) }}">
            <button class="btn">
                {{ trans('emails.account_activation.button') }}
            </button>
        </a>
    </p>

@endsection