@extends('templates.back.full_layout')

@section('content')

    <div id="content" class="user edit">

        <div class="text-content">

            <div class="col-sm-12">

                {{-- Title--}}
                <h2>
                    <i class="fa fa-user"></i>
                    @if(isset($user) && (\Sentinel::getUser()->id === $user->id))
                        {{ trans('users.page.title.profile') }}
                    @elseif(isset($user) && !(\Sentinel::getUser()->id === $user->id))
                        {!! trans('users.page.title.edit', ['user' => $user->first_name . ' ' . $user->last_name]) !!}
                    @else
                        {{ trans('users.page.title.create') }}
                    @endif
                </h2>

                <hr>

                <form role="form" method="POST" action="@if(isset($user)){{ route('users.update', ['id' => $user->id]) }} @else{{ route('users.store', ['id' => 0]) }} @endif" enctype="multipart/form-data">

                    {{-- crsf token --}}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    {{-- add update inputs if we are in update mode --}}
                    @if(isset($user))
                        <input type="hidden" name="_method" value="PUT">
                    @endif

                    {{-- we include the form legend --}}
                    @include('templates.back.partials.form-legend.required')

                    {{-- personal data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('users.page.title.personal_data') }}</h3>
                        </div>
                        <div class="panel-body">

                            {{-- photo --}}
                            <label for="input_photo">{{ trans('users.page.label.photo') }}</label>
                            <div class="form-group">
                                @if(isset($user) && $user->photo)
                                    <div class="form-group">
                                        <a href="{{ $user->imagePath($user->photo, 'photo', 'picture') }}" title="{{ $user->first_name }} {{ $user->last_name }}" data-lity>
                                            <img src="{{ $user->imagePath($user->photo, 'photo', 'picture') }}" alt="{{ $user->first_name }} {{ $user->last_name }}">
                                        </a>
                                    </div>
                                @endif
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-primary btn-file">
                                            <i class="fa fa-picture-o"></i> {{ trans('global.action.browse') }} <input type="file" name="photo">
                                        </span>
                                    </span>
                                    <input id="input_photo" type="text" class="form-control" readonly="">
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('users.page.info.photo') }}</p>
                            </div>

                            {{-- gender --}}
                            <label>{{ trans('users.page.label.gender') }}</label>
                            <div class="form-group">
                                <div class="btn-group" data-toggle="buttons">

                                    @foreach(config('user.gender') as $id => $gender)
                                        <label class="btn toggle
                                        @if(old('gender') == $id)active
                                        @elseif(is_null(old('gender')) && isset($user->gender) && $user->gender == $id)active
                                        @endif">
                                            <input type="radio" name="gender" value="{{ $id }}" autocomplete="off"
                                            @if(old('gender') == $id)checked
                                            @elseif(is_null(old('gender')) && isset($user->gender) && $user->gender == $id)checked
                                            @endif>
                                            {!! trans('users.config.gender.' . $gender) !!}
                                        </label>
                                    @endforeach

                                </div>
                            </div>

                            {{-- lastname --}}
                            <label for="input_lastname">{{ trans('users.page.label.last_name') }}<span class="required">*</span></label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_lastname"><i class="fa fa-user"></i></span>
                                    <input id="input_lastname" class="form-control capitalize" type="text" name="last_name" value="{{ old('last_name') ? old('last_name') : (isset($user) && $user->last_name ? $user->last_name : null) }}" placeholder="{{ trans('users.page.label.last_name') }}">
                                </div>
                            </div>

                            {{-- firstname --}}
                            <label for="input_firstname">{{ trans('users.page.label.first_name') }}<span class="required">*</span></label></label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_firstname"><i class="fa fa-user"></i></span>
                                    <input id="input_firstname" class="form-control capitalize-first-letter" type="text" name="first_name" value="{{ old('first_name') ? old('first_name') : (isset($user) && $user->first_name ? $user->first_name : null) }}" placeholder="{{ trans('users.page.label.first_name') }}">
                                </div>
                            </div>

                            {{-- birth date --}}
                            <label for="input_birth_date">{{ trans('users.page.label.birth_date') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_birth_date"><i class="fa fa-birthday-cake"></i></span>
                                    <input id="input_birth_date" type='text' class="form-control datepicker" name="birth_date" value="{{ old('birth_date') ? old('birth_date') : (isset($user) && $user->birth_date ? $user->birth_date : null) }}" placeholder="{{ trans('users.page.label.birth_date') }}">
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('global.info.date.format') }}</p>
                            </div>

                        </div>
                    </div>

                    {{-- don't show for personnal account edition --}}
                    @if(!isset($user) || !(\Sentinel::getUser()->id === $user->id))

                        {{-- club informations --}}
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">{{ trans('users.page.title.club') }}</h3>
                            </div>
                            <div class="panel-body">

                                {{-- status --}}
                                <label for="input_status_id">{{ trans('users.page.label.status_id') }} <span class="required">*</span></label></label>
                                <div class="form-group">
                                    <select class="form-control" name="status_id" id="input_status_id">
                                        <option value="" disabled>{{ trans('users.page.label.status_id_placeholder') }}</option>
                                        @foreach($statuses as $id => $status)
                                            <option value="{{ $id }}"
                                                @if(old('status_id') == $id)selected
                                                @elseif(is_null(old('status_id')) && isset($user->status_id) && $user->status_id === $id)selected
                                                @elseif(is_null(old('status_id')) && !isset($user->status_id) && $id === config('user.status_key.user'))selected
                                                @endif>{{ trans('users.config.status.' . $status) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- board --}}
                                <label for="input_board_id">{{ trans('users.page.label.board_id') }}<span class="required">*</span></label></label>
                                <div class="form-group">
                                    <select class="form-control" name="board_id" id="input_board_id">
                                        <option value="" disabled>{{ trans('users.page.label.board_id_placeholder') }}</option>
                                        <option value="" @if(is_null(old('board_id')) && !isset($user->board_id))selected @endif>{{ trans('users.page.label.no_board') }}</option>
                                        @foreach($boards as $id => $board)
                                            <option value="{{ $id }}"
                                                @if(old('board_id') == $id)selected
                                                @elseif(is_null(old('board_id')) && isset($user->board_id) && $user->board_id === $id) selected
                                                @endif>{{ trans('users.config.board.' . $board) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    @endif

                    {{-- contact data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('users.page.title.contact') }}</h3>
                        </div>
                        <div class="panel-body">

                            {{-- phone number --}}
                            <label for="input_phone_number">{{ trans('users.page.label.phone_number') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_phone_number"><i class="fa fa-phone"></i></span>
                                    <input id="input_phone_number" class="form-control" type="tel" name="phone_number" value="{{ old('phone_number') ? old('phone_number') : (isset($user) && $user->phone_number ? $user->phone_number : null) }}" placeholder="{{ trans('users.page.label.phone_number') }}">
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('users.page.label.phone_number') }}</p>
                            </div>

                            {{-- email --}}
                            <label for="input_email">{{ trans('users.page.label.email') }}<span class="required">*</span></label></label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_email"><i class="fa fa-at"></i></span>
                                    <input id="input_email" class="form-control" type="email" name="email" value="{{ old('email') ? old('email') : (isset($user) && $user->email ? $user->email : null) }}" placeholder="{{ trans('users.page.label.email') }}">
                                </div>
                            </div>

                            {{-- address --}}
                            <label for="input_address">{{ trans('users.page.label.address') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_address"><i class="fa fa-envelope"></i></span>
                                    <input id="input_address" class="form-control" type="text" name="address" value="{{ old('address') ? old('address') : (isset($user) && $user->address ? $user->address : null) }}" placeholder="{{ trans('users.page.label.address') }}">
                                </div>
                            </div>

                            {{-- zip code --}}
                            <label for="input_zip_code">{{ trans('users.page.label.zip_code') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_zip_code"><i class="fa fa-paper-plane"></i></span>
                                    <input id="input_zip_code" class="form-control" type="number" name="zip_code" value="{{ old('zip_code') ? old('zip_code') : (isset($user) && $user->zip_code ? $user->zip_code : null) }}" placeholder="{{ trans('users.page.label.zip_code') }}">
                                </div>
                            </div>

                            {{-- city --}}
                            <label for="input_city">{{ trans('users.page.label.city') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_city"><i class="fa fa-map-marker"></i></span>
                                    <input id="input_city" class="form-control capitalize-first-letter" type="text" name="city" value="{{ old('city') ? old('city') : (isset($user) && $user->city ? $user->city : null) }}" placeholder="{{ trans('users.page.label.city') }}">
                                </div>
                            </div>

                            {{-- country --}}
                            <label for="input_country">{{ trans('users.page.label.country') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_country"><i class="fa fa-globe"></i></span>
                                    <input id="input_country" class="form-control capitalize" type="text" name="country" value="{{ old('country') ? old('country') : (isset($user) && $user->country ? $user->country : null) }}" placeholder="{{ trans('users.page.label.country') }}">
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- security data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('users.page.title.security') }}</h3>
                        </div>
                        <div class="panel-body">

                            {{-- don't show for personnal account edition --}}
                            @if(!isset($user) || !(\Sentinel::getUser()->id === $user->id))

                                {{-- role --}}
                                <label>{{ trans('users.page.label.role') }}<span class="required">*</span></label>
                                <div class="form-group">
                                    <div class="btn-group" data-toggle="buttons">
                                        @foreach($roles as $role)
                                            <label class="btn toggle
                                            @if(!isset($user) && old('role') == $role->id)active
                                            @elseif(isset($user) && isset($user->roles()->first()->id) && $user->roles()->first()->id === $role->id)active
                                            @elseif(((isset($user) && !isset($user->roles()->first()->id)) || !isset($user)) && $role->slug == 'user')active
                                            @endif">
                                            <input type="radio" name="role" value="{{ $role->id }}" autocomplete="off"
                                            @if(!isset($user) && old('role') == $role->id)checked
                                            @elseif(isset($user) && isset($user->roles()->first()->id) && $user->roles()->first()->id === $role->id)checked
                                            @elseif(((isset($user) && !isset($user->roles()->first()->id)) || !isset($user)) && $role->slug == 'user')checked
                                            @endif>{{ $role->name }}</label>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- activation --}}
                                <label for="input_active">{{ trans('users.page.label.active') }}</label>
                                <div class="form-group">
                                    <div class="input-group swipe-group">
                                        <span class="input-group-addon" for="input_active"><i class="fa fa-power-off"></i></span>
                                    <span class="form-control swipe-label" readonly="">
                                        {{ trans('users.page.label.account') }}
                                    </span>
                                        <input class="swipe" id="input_active" type="checkbox" name="active"
                                            @if(old('active'))checked
                                            @elseif(is_null(old('active')) && isset($user) && \Activation::completed($user))checked
                                            @elseif(is_null(old('active')) && !isset($user))checked
                                            @endif>
                                        <label class="swipe-btn" for="input_active"></label>
                                    </div>
                                </div>
                            @endif

                            {{-- password input--}}
                            <label for="input_password">{{ trans('users.page.label.new_password') }}@if(!isset($user))<span class="required">*</span> @endif</label>
                            <div class="form-group">
                                <div class="input-group">
                                        <span class="input-group-addon" for="input_password">
                                            <i class="fa fa-unlock-alt"></i>
                                        </span>
                                    <input type="password" id="input_password" class="form-control" name="password" value="{{ old('password') }}" placeholder="{{ trans('users.page.label.new_password') }}">
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('global.info.password.length') }}</p>
                                @if(isset($user))
                                    <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('users.page.info.password') }}</p>
                                @endif
                            </div>

                            {{-- password confirmation input --}}
                            <label for="input_password_confirmation">{{ trans('users.page.label.password_confirm') }}@if(!isset($user))<span class="required">*</span> @endif</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_password_confirmation"><i class="fa fa-unlock-alt"></i></span>
                                    <input id="input_password_confirmation" class="form-control" type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="{{ trans('users.page.label.password_confirm') }}">
                                </div>
                                @if(isset($user))
                                    <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('users.page.info.password') }}</p>
                                @endif
                            </div>

                        </div>
                    </div>

                    {{-- submit login --}}
                    @if(isset($user) && (\Sentinel::getUser()->id === $user->id))
                        <button class="btn btn-primary spin-on-click" type="submit">
                            <i class="fa fa-floppy-o"></i> {{ trans('global.action.save') }}
                        </button>
                    @elseif(isset($user) && !(\Sentinel::getUser()->id === $user->id))
                        <button class="btn btn-primary spin-on-click" type="submit">
                            <i class="fa fa-pencil-square"></i> {{ trans('users.page.action.update') }}
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-default spin-on-click" title="{{ trans('global.action.back') }}">
                            <i class="fa fa-undo"></i> {{ trans('global.action.back') }}
                        </a>
                    @else
                        <button class="btn btn-success spin-on-click" type="submit">
                            <i class="fa fa-plus-circle"></i> {{ trans('users.page.action.create') }}
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-default spin-on-click" title="{{ trans('global.action.cancel') }}">
                            <i class="fa fa-ban"></i> {{ trans('global.action.cancel') }}
                        </a>
                    @endif
                </form>

            </div>
        </div>
    </div>

@endsection