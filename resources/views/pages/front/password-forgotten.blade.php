@extends('templates.front.nude_layout')

@section('content')

    <div id="content" class="login row fill">

        <div class="container fill">

            <div class="display-table fill">

                <div class="form_container v-center table-cell">

                    <div class="form_capsule col-sm-offset-4 col-sm-4">

                        <form class="form-signin" role="form" method="POST" action="{{ route('password.email') }}">

                            {{-- crsf token --}}
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            {{-- logo / icon --}}
                            <a class="logo text-center" href="" title="{{ config('settings.app_name') }}">
                                <img width="300" height="280" src="{{ url(env('LOGO_LIGHT')) }}" alt="{{ config('settings.app_name') }}">
                            </a>

                            {{-- Title--}}
                            <h1><i class="fa fa-unlock-alt"></i> {{ trans('auth.password.forgotten.title') }}</h1>

                            {{-- email input --}}
                            <label class="sr-only" for="input_email">{{ trans('auth.password.forgotten.label.email') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_email"><i class="fa fa-at"></i></span>
                                    <input id="input_email" class="form-control" type="email" name="email" value="{{ $email or old('email') }}" placeholder="{{ trans('auth.password.forgotten.label.email') }}" autofocus>
                                </div>
                            </div>

                            <p class="quote">{{ trans('auth.password.forgotten.info.instructions') }}</p>

                            {{-- submit login --}}
                            <button class="btn btn-primary btn-block spin-on-click" type="submit">
                                <i class="fa fa-paper-plane"></i> {{ trans('auth.password.forgotten.action.send') }}
                            </button>

                        </form>

                        <a href="{{ route('login.index') }}" class="pull-right cancel" title="Retour">
                            <button class="btn btn-default">
                                <i class="fa fa-ban"></i> {{ trans('global.action.cancel') }}
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection