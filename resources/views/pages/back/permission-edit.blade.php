@extends('templates.back.full_layout')

@section('content')

    <div id="content" class="permissions edit">

        <div class="text-content">

            <div class="col-sm-12">

                {{-- Title--}}
                <h2>
                    <i class="fa fa-gavel"></i>
                    @if(isset($role)){{ trans('permissions.page.title.edit') }} @else{{ trans('permissions.page.title.create') }} @endif
                </h2>

                <hr>

                <form role="form" method="POST" action="@if(isset($role)){{ route('permissions.update') }} @else{{ route('permissions.store') }}@endif">

                    {{-- crsf token --}}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    {{-- add update inputs if we are in update mode --}}
                    @if(isset($role))
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="_id" value="{{ $role->id }}">
                    @endif

                    {{-- language choice --}}
                    @if(config('settings.multilingual'))
                        <ul class="nav nav-tabs model_trans_manager">
                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                <li role="presentation" @if($localeCode === config('app.locale'))class="active" @endif>
                                    <a href="{{ $localeCode }}" title="{{ $properties['native'] }}">
                                        <div class="display-table">
                                            <div class="table-cell flag">
                                                <img width="20" height="20" class="img-circle" src="{{ url('img/flag/' . $localeCode . '.png') }}" alt="{{ $localeCode }}">
                                            </div>
                                            &nbsp;
                                            <div class="table-cell">
                                                {{ $properties['native'] }}
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="panel panel-default">

                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('permissions.page.title.info') }}</h3>
                        </div>

                        <div class="panel-body">

                            {{-- name --}}
                            <label for="input_name" class="required">{{ trans('permissions.page.label.name') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_name"><i class="fa fa-font"></i></span>
                                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                        <input id="input_name" class="form-control capitalize-first-letter model_trans_input {{ $localeCode }} @if($localeCode !== config('app.locale'))hidden @endif" type="text" name="name_{{ $localeCode }}" value="{{ !empty(old('name_' . $localeCode)) ? old('name_' . $localeCode) : (isset($role) && isset($role->translate($localeCode)->name) ? $role->translate($localeCode)->name : null) }}" placeholder="{{ trans('permissions.page.label.name') }}">
                                    @endforeach
                                </div>
                            </div>

                            {{-- slug --}}
                            <label for="input_slug" class="required">{{ trans('permissions.page.label.slug') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_name"><i class="fa fa-key"></i></span>
                                    <input id="input_slug" class="form-control" type="text" name="slug" value="{{ !empty(old('slug')) ? old('slug') : ((isset($role->slug)) ? $role->slug : null) }}" placeholder="{{ trans('permissions.page.label.slug') }}">
                                </div>
                            </div>

                            {{-- rank --}}
                            <label for="input_rank">{{ trans('permissions.page.label.rank') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_rank"><i class="fa fa-sort"></i></span>
                                    <input id="input_rank" class="form-control" type="text" name="slug" value="{{ !empty(old('rank')) ? old('rank') : ((isset($role->rank)) ? $role->rank : null) }}" placeholder="{{ trans('permissions.page.label.rank') }}" disabled>
                                </div>
                            </div>

                            {{-- parent role --}}
                            <label for="input_parent_role" class="required">{{ trans('permissions.page.label.parent_role') }}</label>
                            <div class="form-group">
                                <select class="form-control" name="parent_role_id" id="input_parent_role">
                                    <option value="" disabled selected>{{ trans('permissions.page.label.placeholder') }}</option>
                                    @foreach($role_list as $r)
                                        <option value="{{ $r->id }}"
                                            @if(old('parent_role_id') == $r->id)selected
                                            @elseif(isset($parent_role) && $parent_role->id === $r->id)selected
                                            @elseif($r->id === 0)selected
                                            @endif>
                                            @if(!isset($r->rank))
                                                X - {{ $r->name }}
                                            @else
                                                {{ $r->rank }} - {{ $r->name }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('permissions.page.info.rank') }}</p>
                            </div>

                        </div>
                    </div>

                    <div class="panel panel-default">

                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('permissions.page.title.permissions') }}</h3>
                        </div>

                        <div class="panel-body">
                            @foreach(array_dot(config('permissions')) as $permission => $value)
                                <div class="checkbox permission @if(!strpos($permission, '.'))parent @endif">
                                    <label for="{{ $permission }}"><input id="{{ $permission }}" type="checkbox" name="{{ $permission }}" @if(old($permission, (isset($role->permissions[$permission]) ? $role->permissions[$permission] : ''))) checked @endif>{!! trans('permissions.' . $permission) !!}</label>
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