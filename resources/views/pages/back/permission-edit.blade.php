@extends('templates.back.full_layout')

@section('content')

    <div id="content" class="permissions edit">

        <div class="text-content">

            <div class="col-sm-12">

                {{-- Title--}}
                <h2>
                    <i class="fa fa-gavel"></i>
                    @if(isset($role)){!! trans('permissions.page.title.edit', ['role' => $role->name]) !!} @else{{ trans('permissions.page.title.create') }} @endif
                </h2>

                <hr>

                <form role="form" method="POST" action="@if(isset($role)){{ route('permissions.update', ['id' => $role->id]) }} @else{{ route('permissions.store') }}@endif">

                    {{-- crsf token --}}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    {{-- add update inputs if we are in update mode --}}
                    @if(isset($role))
                        <input type="hidden" name="_method" value="PUT">
                    @endif

                    {{-- if the app is multilingual--}}
                    @if(config('settings.multilingual'))

                        {{-- we include the form multilingual legend --}}
                        @include('templates.back.partials.form-legend.required-language')

                        {{-- we include the inputs language selector --}}
                        @include('templates.back.partials.inputs-language-selector')
                    @else

                        {{-- we include the simple form legend --}}
                        @include('templates.back.partials.form-legend.required')
                    @endif

                    <div class="panel panel-default">

                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('permissions.page.title.info') }}</h3>
                        </div>

                        <div class="panel-body">

                            {{-- name --}}
                            <label for="input_name_{{ config('app.locale') }}">{{ trans('permissions.page.label.name') }}
                                <span class="required">*</span>
                                @if(config('settings.multilingual'))
                                    <span class="translated">*</span></label>
                                @endif
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_name_{{ config('app.locale') }}"><i class="fa fa-tag"></i></span>
                                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                        <input id="input_name_{{ $localeCode }}" class="form-control capitalize-first-letter translated_input {{ $localeCode }} @if($localeCode !== config('app.locale'))hidden @endif" type="text" name="name_{{ $localeCode }}" value="{{ !empty(old('name_' . $localeCode)) ? old('name_' . $localeCode) : (isset($role) && isset($role->translate($localeCode)->name) ? $role->translate($localeCode)->name : null) }}" placeholder="{{ trans('permissions.page.label.name') }}">
                                    @endforeach
                                </div>
                            </div>

                            {{-- slug --}}
                            <label for="input_slug">{{ trans('permissions.page.label.slug') }}<span class="required">*</span></label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_name"><i class="fa fa-key"></i></span>
                                    <input id="input_slug" class="form-control" type="text" name="slug" value="{{ !empty(old('slug')) ? old('slug') : ((isset($role->slug)) ? $role->slug : null) }}" placeholder="{{ trans('permissions.page.label.slug') }}">
                                </div>
                            </div>

                            {{-- position --}}
                            <label for="input_position">{{ trans('permissions.page.label.position') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_position"><i class="fa fa-sort"></i></span>
                                    <input id="input_position" class="form-control" type="text" name="position" value="{{ !empty(old('position')) ? old('position') : ((isset($role->position)) ? $role->position : null) }}" placeholder="{{ trans('permissions.page.label.position') }}" disabled>
                                </div>
                            </div>

                            {{-- parent role --}}
                            <label for="input_parent_role">{{ trans('permissions.page.label.parent_role') }}<span class="required">*</span></label>
                            @if($parent_role)
                                <p>Parent : {{ $parent_role->id }}</p>
                            @endif
                            <div class="form-group">
                                <select class="form-control" name="parent_role_id" id="input_parent_role">
                                    <option value="" disabled>{{ trans('permissions.page.label.placeholder') }}</option>
                                    @foreach($role_list as $r)
                                        <option value="{{ $r->id }}"
                                            @if((!is_null(old('parent_role_id'))) && (old('parent_role_id') == $r->id))selected
                                            @elseif(is_null(old('parent_role_id')) && isset($parent_role) && ($parent_role->id === $r->id))selected
                                            @endif>@if(!isset($r->position))X - {{ $r->name }}@else{{ $r->position }} - {{ $r->name }}@endif</option>
                                    @endforeach
                                </select>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('permissions.page.info.position') }}</p>
                            </div>

                        </div>
                    </div>

                    <div class="panel panel-default">

                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('permissions.page.title.permissions') }}</h3>
                        </div>

                        <div class="panel-body">
                            @foreach(array_dot(array_except(config('permissions'), 'separator')) as $permission => $value)
                                <div class="checkbox permission @if(!strpos($permission, '.'))parent @endif">
                                    <label for="{{ str_replace('.', config('permissions.separator'), $permission) }}"><input id="{{ str_replace('.', config('permissions.separator'), $permission) }}" type="checkbox" name="{{ str_replace('.', '0', $permission) }}" @if(old($permission, (isset($role->permissions[$permission]) ? $role->permissions[$permission] : ''))) checked @endif>{!! trans('permissions.' . $permission) !!}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- submit login --}}
                    @if(isset($role))
                        <button class="btn btn-primary spin-on-click" type="submit">
                            <i class="fa fa-pencil-square"></i> {{ trans('permissions.page.action.edit') }}
                        </button>
                        <a href="{{ route('permissions.index') }}" class="btn btn-default spin-on-click" title="{{ trans('global.action.back') }}">
                            <i class="fa fa-undo"></i> {{ trans('global.action.back') }}
                        </a>
                    @else
                        <button class="btn btn-success spin-on-click" type="submit">
                            <i class="fa fa-plus-circle"></i> {{ trans('permissions.page.action.create') }}
                        </button>
                        <a href="{{ route('permissions.index') }}" class="btn btn-default spin-on-click" title="{{ trans('global.action.cancel') }}">
                            <i class="fa fa-ban"></i> {{ trans('global.action.cancel') }}
                        </a>
                    @endif
                </form>

            </div>
        </div>
    </div>

@endsection