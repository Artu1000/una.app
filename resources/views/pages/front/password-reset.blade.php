@extends('templates.front.nude_layout')

@section('content')

    <div id="content" class="login row fill">

        <div class="container fill">

            <div class="display-table fill">

                <div class="form_container v-center table-cell">

                    <div class="form_capsule">

                        <form class="form-signin" role="form" method="POST" action="{{ route('password.update') }}">

                            {{-- crsf token --}}
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_email" value="{{ $email }}">
                            <input type="hidden" name="_reminder" value="{{ $reminder }}">

                            {{-- logo --}}
                            @if(config('settings.logo_light'))
                                <div class="logo display-table">
                                    <div class="text-center table-cell fill">
                                        <img width="300" height="300" src="{{ \ImageManager::imagePath(config('image.settings.public_path'), config('image.settings.logo.name.light') . '.' . config('image.settings.logo.extension'), 'logo', 'large') }}" alt="{{ config('settings.app_name_' . config('app.locale')) }}">
                                    </div>
                                </div>
                            @endif

                            {{-- Title--}}
                            <h1><i class="fa fa-refresh"></i> {{  trans('auth.password.reset.title') }}</h1>

                            {{-- password input --}}
                            <label class="sr-only" for="input_password">{{ trans('auth.password.reset.label.password') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_password"><i class="fa fa-unlock-alt"></i></span>
                                    <input id="input_password" class="form-control" type="password" name="password" placeholder="{{ trans('auth.password.reset.label.password') }}" autofocus>
                                </div>
                            </div>

                            {{-- password confirmation input --}}
                            <label class="sr-only" for="input_password_confirmation">{{ trans('auth.password.reset.label.password_confirmation') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_password_confirmation"><i class="fa fa-unlock-alt"></i></span>
                                    <input id="input_password_confirmation" class="form-control" type="password" name="password_confirmation" placeholder="{{ trans('auth.password.reset.label.password_confirmation') }}">
                                </div>
                            </div>

                            <p class="quote">{{ trans('auth.password.reset.info.instructions') }}</p>

                            {{-- submit login --}}
                            <button class="btn btn-primary btn-block spin-on-click" type="submit">
                                <i class="fa fa-magic"></i> {{ trans('auth.password.reset.action.save') }}
                            </button>

                        </form>

                        <a href="{{ route('login.index') }}" class="pull-right cancel btn btn-default spin-on-click" title="Retour">
                            <i class="fa fa-ban"></i> {{ trans('global.action.cancel') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection