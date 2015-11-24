@extends('templates.back.full_layout')

@section('content')

    <div id="content" class="permissions creation row">

        <div class="text-content">

            <div class="col-sm-12">

                {{-- Title--}}
                <h2>
                    <i class="fa fa-gavel"></i>
                    @if(isset($user) && (\Sentinel::getUser()->id === $user->id))
                        {{ trans('users.view.title.profile') }}
                    @elseif(isset($user) && !(\Sentinel::getUser()->id === $user->id))
                        {{ trans('users.view.title.edit') }}
                    @else
                        {{ trans('users.view.title.create') }}
                    @endif
                </h2>

                <hr>

                <form role="form" method="POST" action="@if(isset($user)){{ route('users.update') }} @else{{ route('users.index') }} @endif">

                    {{-- crsf token --}}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    {{-- add update inputs if we are in update mode --}}
                    @if(isset($user))
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="_id" value="{{ $user->id }}">
                    @endif

                    {{-- personal data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('users.view.title.personal_data') }}</h3>
                        </div>
                        <div class="panel-body">

                            {{-- photo --}}
                            <label for="input_photo">{{ trans('users.view.label.photo') }}</label>
                            <div class="form-group">
                                @if(isset($user) && $user->photo)
                                    <div class="form-group">
                                        <img width="145" height="160" src="{{ route('image', ['filename' => $user->photo, 'folder' => 'user', 'size' => $user->size('picture')]) }}" alt="{{ $user->first_name }} {{ $user->last_name }}">
                                    </div>
                                @endif
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-primary btn-file">
                                            <i class="fa fa-picture-o"></i> {{ trans('actions.browse') }} <input type="file" name="photo">
                                        </span>
                                    </span>
                                    <input id="input_photo" type="text" class="form-control" readonly="">
                                </div>
                                <p class="help-block quote"><i class="fa fa-info-circle"></i> {{ trans('users.view.info.photo') }}</p>
                            </div>

                            {{-- gender --}}
                            <label>{{ trans('users.view.label.gender') }}</label>
                            <div class="form-group">
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn toggle
                                    @if(isset($user) && $user->gender == config('user.gender_key.female.id'))active @endif">
                                        <input type="radio" name="gender" value="{{ config('user.gender_key.female.id') }}" autocomplete="off" @if(isset($user) && $user->gender == config('user.gender_key.female.id'))checked @endif>
                                        <i class="fa fa-female"></i>
                                        {{ config('user.gender_key.female.title') }}
                                    </label>
                                    <label class="btn toggle
                                    @if(isset($user) && $user->gender == config('user.gender_key.male.id'))active @endif">
                                        <input type="radio" name="gender" value="{{ config('user.gender_key.male.id') }}" autocomplete="off" @if(isset($user) && $user->gender == config('user.gender_key.male.id'))checked @endif>
                                        <i class="fa fa-male"></i>
                                        {{ config('user.gender_key.male.title') }}
                                    </label>
                                </div>
                            </div>

                            {{-- lastname --}}
                            <label for="input_lastname">{{ trans('users.view.label.last_name') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_lastname"><i class="fa fa-user"></i></span>
                                    <input id="input_lastname" class="form-control capitalize" type="text" name="last_name" value="{{ old('last_name') ? old('last_name') : (isset($user) && $user->last_name ? $user->last_name : null) }}" placeholder="{{ trans('users.view.label.last_name') }}">
                                </div>
                            </div>

                            {{-- firstname --}}
                            <label for="input_firstname">{{ trans('users.view.label.first_name') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_firstname"><i class="fa fa-user"></i></span>
                                    <input id="input_firstname" class="form-control capitalize-first-letter" type="text" name="first_name" value="{{ old('first_name') ? old('first_name') : (isset($user) && $user->first_name ? $user->first_name : null) }}" placeholder="{{ trans('users.view.label.first_name') }}">
                                </div>
                            </div>

                            {{-- birth date --}}
                            <label for="input_birth_date">{{ trans('users.view.label.birth_date') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_birth_date"><i class="fa fa-birthday-cake"></i></span>
                                    <input id="input_birth_date" type='text' class="form-control datepicker" name="birth_date" value="{{ old('birth_date') ? old('birth_date') : (isset($user) && $user->birth_date ? $user->birth_date : null) }}" placeholder="{{ trans('users.view.label.birth_date') }}">
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- contact data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('users.view.title.contact') }}</h3>
                        </div>
                        <div class="panel-body">

                            {{-- phone number --}}
                            <label for="input_phone_number">{{ trans('users.view.label.phone_number') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_phone_number"><i class="fa fa-phone"></i></span>
                                    <input id="input_phone_number" class="form-control" type="tel" name="phone_number" value="{{ old('phone_number') ? old('phone_number') : (isset($user) && $user->phone_number ? $user->phone_number : null) }}" placeholder="{{ trans('users.view.label.phone_number') }}">
                                </div>
                                <p class="help-block quote"><i class="fa fa-info-circle"></i> {{ trans('users.view.label.phone_number') }}</p>
                            </div>

                            {{-- email --}}
                            <label for="input_email">{{ trans('users.view.label.email') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_email"><i class="fa fa-at"></i></span>
                                    <input id="input_email" class="form-control" type="email" name="email" value="{{ old('email') ? old('email') : (isset($user) && $user->email ? $user->email : null) }}" placeholder="{{ trans('users.view.label.email') }}">
                                </div>
                            </div>

                            {{-- address --}}
                            <label for="input_address">{{ trans('users.view.label.address') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_address"><i class="fa fa-envelope"></i></span>
                                    <input id="input_address" class="form-control" type="text" name="address" value="{{ old('address') ? old('address') : (isset($user) && $user->address ? $user->address : null) }}" placeholder="{{ trans('users.view.label.address') }}">
                                </div>
                            </div>

                            {{-- zip code --}}
                            <label for="input_zip_code">{{ trans('users.view.label.zip_code') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_zip_code"><i class="fa fa-paper-plane"></i></span>
                                    <input id="input_zip_code" class="form-control" type="number" name="zip_code" value="{{ old('zip_code') ? old('zip_code') : (isset($user) && $user->zip_code ? $user->zip_code : null) }}" placeholder="{{ trans('users.view.label.zip_code') }}">
                                </div>
                            </div>

                            {{-- city --}}
                            <label for="input_city">{{ trans('users.view.label.city') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_city"><i class="fa fa-map-marker"></i></span>
                                    <input id="input_city" class="form-control capitalize-first-letter" type="text" name="city" value="{{ old('city') ? old('city') : (isset($user) && $user->city ? $user->city : null) }}" placeholder="{{ trans('users.view.label.city') }}">
                                </div>
                            </div>

                            {{-- country --}}
                            <label for="input_country">{{ trans('users.view.label.country') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_country"><i class="fa fa-globe"></i></span>
                                    <input id="input_country" class="form-control capitalize" type="text" name="country" value="{{ old('country') ? old('country') : (isset($user) && $user->country ? $user->country : null) }}" placeholder="{{ trans('users.view.label.country') }}">
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- security data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('users.view.title.security') }}</h3>
                        </div>
                        <div class="panel-body">

                            {{-- don't for personnal account edition --}}
                            @if(!(\Sentinel::getUser()->id === $user->id))
                                {{-- role --}}
                                <label>{{ trans('users.view.label.role') }}</label>
                                <div class="form-group">
                                    <div class="btn-group" data-toggle="buttons">
                                        @foreach($roles as $role)
                                            <label class="btn toggle @if(isset($user) && isset($user->roles()->first()->id) && $user->roles()->first()->id === $role->id)active @endif">
                                                <input type="radio" name="role" value="{{ $role->id }}" autocomplete="off" @if(isset($user) && isset($user->roles()->first()->id) && $user->roles()->first()->id === $role->id)checked @endif>
                                                {{ $role->name }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- activation --}}
                                <label for="input_activation">{{ trans('users.view.label.activation') }}</label>
                                <div class="form-group">
                                    <div class="input-group swipe-group">
                                        <span class="input-group-addon" for="input_activation"><i class="fa fa-power-off"></i></span>
                                    <span class="form-control swipe-label" readonly="">
                                        {{ trans('users.view.label.account') }}
                                    </span>
                                        <input class="swipe" id="input_activation" type="checkbox" name="activation"
                                               @if(old('activation'))checked @elseif(!empty($user->activations()->whereNotNull('completed_at')->first()))checked @endif>
                                        <label class="swipe-btn" for="input_activation"></label>
                                    </div>
                                </div>
                            @endif

                            {{-- password input--}}
                            <label for="input_password">{{ trans('users.view.label.new_password') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                        <span class="input-group-addon" for="input_password">
                                            <i class="fa fa-unlock-alt"></i>
                                        </span>
                                    <input type="password" id="input_password" class="form-control" name="password" placeholder="{{ trans('users.view.label.new_password') }}">
                                </div>
                                @if(isset($user))
                                    <p class="help-block quote"><i class="fa fa-info-circle"></i> {{ trans('users.view.info.password') }}</p>
                                @endif
                            </div>

                            {{-- password confirmation input --}}
                            <label for="input_password_confirmation">{{ trans('users.view.label.password_confirm') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_password_confirmation"><i class="fa fa-unlock-alt"></i></span>
                                    <input id="input_password_confirmation" class="form-control" type="password" name="password_confirmation" placeholder="{{ trans('users.view.label.password_confirm') }}">
                                </div>
                                @if(isset($user))
                                    <p class="help-block quote"><i class="fa fa-info-circle"></i> {{ trans('users.view.info.password') }}</p>
                                @endif
                            </div>

                        </div>
                    </div>

                    {{-- submit login --}}
                    @if(isset($user) && (\Sentinel::getUser()->id === $user->id))
                        <button class="btn btn-primary spin-on-click" type="submit">
                            <i class="fa fa-floppy-o"></i> {{ trans('users.view.action.save') }}
                        </button>
                    @elseif(isset($user) && !(\Sentinel::getUser()->id === $user->id))
                        <button class="btn btn-primary spin-on-click" type="submit">
                            <i class="fa fa-pencil-square"></i> {{ trans('users.view.action.update') }}
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-default spin-on-click" title="Retour">
                            <i class="fa fa-undo"></i> {{ trans('actions.back') }}
                        </a>
                    @else
                        <button class="btn btn-success spin-on-click" type="submit">
                            <i class="fa fa-plus-circle"></i> {{ trans('users.view.action.create') }}
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-default spin-on-click" title="Retour">
                            <i class="fa fa-ban"></i> {{ trans('actions.cancel') }}
                        </a>
                    @endif
                </form>

            </div>
        </div>
    </div>

@endsection