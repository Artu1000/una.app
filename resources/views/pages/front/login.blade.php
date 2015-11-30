@extends('templates.front.nude_layout')

@section('content')

    <div id="content" class="login row fill">

        <div class="container fill">

            <div class="display-table fill">

                <div class="form_container v-center table-cell">

                    <div class="form_capsule col-sm-offset-4 col-sm-4">

                        <form class="form-signin" role="form" method="POST" action="{{ route('login.login') }}">

                            {{-- crsf token --}}
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            {{-- logo / icon --}}
                            <a class="logo text-center" href="" title="{{ config('settings.app_name') }}">
                                <img width="300" height="280" src="{{ url(env('LOGO_LIGHT')) }}" alt="{{ config('settings.app_name') }}">
                            </a>

                            {{-- Title--}}
                            <h1><i class="fa fa-sign-in"></i> {{ trans('auth.login.title') }}</h1>

                            {{-- email input --}}
                            <label class="sr-only" for="input_email">{{ trans('auth.login.label.email') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_email"><i class="fa fa-at"></i></span>
                                    <input id="input_email" class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="{{ trans('auth.login.label.email') }}" autofocus>
                                </div>
                            </div>

                            {{-- password input--}}
                            <label for="input_password" class="sr-only">{{ trans('auth.login.label.password') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_password">
                                        <i class="fa fa-unlock-alt"></i>
                                    </span>
                                    <input type="password" id="input_password" class="form-control" name="password" value="{{ old('password') }}" placeholder="{{ trans('auth.login.label.password') }}">
                                </div>
                            </div>

                            {{-- remember me checkbox --}}
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember" @if(old('remember'))checked @endif> {{ trans('auth.login.label.remember_me') }}
                                </label>
                            </div>

                            {{-- submit login --}}
                            <button class="btn btn-lg btn-primary btn-block spin-on-click" type="submit">
                                <i class="fa fa-thumbs-up"></i> {{ trans('auth.login.action.login') }}
                            </button>

                            {{-- forgotten password / create account --}}
                            <div class="form-group others_actions">
                                <a href="{{ route('forgotten_password', ['email' => old('email')]) }}"> {{ trans('auth.login.label.forgotten_password') }}</a>
                                <a href="{{ route('account.create') }}" class="pull-right"> {{ trans('auth.login.label.create_account') }}</a>
                            </div>
                        </form>

                        <a href="{{ route('home') }}" class="pull-right cancel" title="Retour au site">
                            <button class="btn btn-lg btn-default">
                                <i class="fa fa-undo"></i> {{ trans('auth.login.action.back') }}
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection