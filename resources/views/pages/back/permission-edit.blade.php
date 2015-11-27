@extends('templates.back.full_layout')

@section('content')

    <div id="content" class="permissions creation row">

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

                    <div class="panel panel-default">

                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('permissions.page.title.info') }}</h3>
                        </div>

                        <div class="panel-body">

                            {{-- name --}}
                            <label for="input_name">{{ trans('permissions.page.label.name') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_name"><i class="fa fa-font"></i></span>
                                    <input id="input_name" class="form-control capitalize-first-letter" type="text" name="name" value="{{ !empty(old('name')) ? old('name') : ((isset($role->name)) ? $role->name : null) }}" placeholder="{{ trans('permissions.page.label.name') }}">
                                </div>
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