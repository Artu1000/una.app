@extends('templates.front.nude_layout')

@section('content')

    <div id="content" class="login row fill">

        <div class="container fill">

            <div class="display-table fill">

                <div class="form_container v-center table-cell">

                    <div class="form_capsule col-sm-offset-4 col-sm-4">

                        <form class="form-signin" role="form" method="POST" action="{{ route('account.store') }}">

                            {{-- crsf token --}}
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            {{-- logo --}}
                            @if(config('settings.logo_light'))
                                <a class="logo text-center" href="#">
                                     <img width="300" src="{{ route('image', ['filename' => config('settings.logo_light'), 'storage_path' => storage_patheaderp/config'), 'size' => 'large']) }}" alt="Logo {{ config('settings.app_name') }}">
                                </a>
                            @endif

                            {{-- Title--}}
                            <h1><i class="fa fa-user-plus"></i> {{ trans('auth.account_creation.title') }}</h1>

                            {{-- lastname input --}}
                            <label class="sr-only" for="input_lastname">{{ trans('auth.account_creation.label.last_name') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_lastname"><i class="fa fa-user"></i></span>
                                    <input id="input_lastname" class="form-control capitalize" type="text" name="last_name" value="{{ old('last_name') }}" placeholder="{{ trans('auth.account_creation.label.last_name') }}" autofocus>
                                </div>
                            </div>

                            {{-- firstname input --}}
                            <label class="sr-only" for="input_firstname">{{ trans('auth.account_creation.label.first_name') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_firstname"><i class="fa fa-user"></i></span>
                                    <input id="input_firstname" class="form-control capitalize-first-letter" type="text" name="first_name" value="{{ old('first_name') }}" placeholder="{{ trans('auth.account_creation.label.first_name') }}">
                                </div>
                            </div>

                            {{-- email input --}}
                            <label class="sr-only" for="input_email">{{ trans('auth.account_creation.label.email') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_email"><i class="fa fa-at"></i></span>
                                    <input id="input_email" class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="{{ trans('auth.account_creation.label.email') }}">
                                </div>
                            </div>

                            {{-- password input--}}
                            <label for="input_password" class="sr-only">{{ trans('auth.account_creation.label.password') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_password">
                                        <i class="fa fa-unlock-alt"></i>
                                    </span>
                                    <input type="password" id="input_password" class="form-control" name="password" placeholder="{{ trans('auth.account_creation.label.password') }}">
                                </div>
                            </div>

                            {{-- password confirmation input --}}
                            <label class="sr-only" for="input_password_confirmation">{{ trans('auth.account_creation.label.password_confirmation') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_password_confirmation"><i class="fa fa-unlock-alt"></i></span>
                                    <input id="input_password_confirmation" class="form-control" type="password" name="password_confirmation" placeholder="{{ trans('auth.account_creation.label.password_confirmation') }}">
                                </div>
                            </div>

                            {{-- submit login --}}
                            <button class="btn btn-primary btn-block spin-on-click" type="submit">
                                <i class="fa fa-thumbs-up"></i> {{ trans('auth.account_creation.action.create') }}
                            </button>
                        </form>

                        <a href="{{ route('login.index') }}" class="pull-right cancel spin-on-click" title="Annuler">
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