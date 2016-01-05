@extends('templates.back.full_layout')

@section('content')

    <div id="content" class="user edit">

        <div class="text-content">

            <div class="col-sm-12">

                {{-- Title--}}
                <h2>
                    <i class="fa fa-user"></i>

                    @if(isset($partner))
                        {{ trans('partners.page.title.edit') }}
                    @else
                        {{ trans('partners.page.title.create') }}
                    @endif
                </h2>

                <hr>

                <form role="form" method="POST" action="@if(isset($partner)){{ route('partners.update') }} @else{{ route('partners.store') }} @endif" enctype="multipart/form-data">

                    {{-- crsf token --}}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    {{-- add update inputs if we are in update mode --}}
                    @if(isset($partner))
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="_id" value="{{ $partner->id }}">
                    @endif

                    {{-- personal data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('partners.page.title.data') }}</h3>
                        </div>
                        <div class="panel-body">

                            {{-- name --}}
                            <label for="input_name" class="required">{{ trans('partners.page.label.name') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_name"><i class="fa fa-font"></i></span>
                                    <input id="input_name" class="form-control capitalize-first-letter" type="text" name="name" value="{{ old('name') ? old('name') : (isset($partner) && $partner->name ? $partner->name : null) }}" placeholder="{{ trans('partners.page.label.name') }}">
                                </div>
                            </div>

                            {{-- logo --}}
                            <label for="input_photo">{{ trans('partners.page.label.logo') }}</label>
                            @if(isset($partner) && $partner->logo)
                                <div class="form-group image">
                                    <div class="form-group">
                                        <a class="img-thumbnail" href="{{ $partner->imagePath($partner->logo, 'logo', 'logo') }}" data-lity>
                                            <img src="{{ $partner->imagePath($partner->logo, 'logo', 'admin') }}" alt="{{ $partner->name }}">
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-primary btn-file">
                                            <i class="fa fa-picture-o"></i> {{ trans('global.action.browse') }} <input type="file" name="logo">
                                        </span>
                                    </span>
                                    <input id="input_photo" type="text" class="form-control" readonly="">
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('partners.page.info.logo') }}</p>
                            </div>

                            {{-- url --}}
                            <label for="input_url">{{ trans('partners.page.label.url') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_url"><i class="fa fa-link"></i></span>
                                    <input id="input_url" class="form-control" type="text" name="url" value="{{ old('url') ? old('url') : (isset($partner) && $partner->url ? $partner->url : null) }}" placeholder="{{ trans('partners.page.label.url') }}">
                                </div>
                            </div>

                            @if(isset($partner))
                            {{-- position --}}
                            <label for="input_position">{{ trans('home.page.label.position') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_position"><i class="fa fa-sort-numeric-asc"></i></span>
                                    <input id="input_position" class="form-control" type="number" name="position" value="{{  isset($partner) && $partner->position ? $partner->position : null }}" placeholder="{{ trans('home.page.label.position') }}" disabled>
                                </div>
                            </div>
                            @endif

                            {{-- previous partner --}}
                            <label for="input_previous_slide" class="required">{{ trans('partners.page.label.previous_partner') }}</label>
                            <div class="form-group">
                                <select class="form-control" name="previous_partner_id" id="input_previous_partner">
                                    <option value="" disabled selected>{{ trans('partners.page.label.previous_partner_placeholder') }}</option>
                                    @foreach($partner_list as $s)
                                        <option value="{{ $s->id }}"
                                                @if(old('previous_partner_id') == $s->id)selected
                                                @elseif(isset($previous_partner) && $previous_partner->id === $s->id)selected
                                                @elseif($s->id === 0)selected
                                                @endif>
                                            @if(!isset($s->position))
                                                X - {{ $s->name }}
                                            @else
                                                {{ $s->position }} - {{ $s->name }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('partners.page.info.previous_partner') }}</p>
                            </div>

                            {{-- activation --}}
                            <label for="input_active">{{ trans('partners.page.label.activation') }}</label>
                            <div class="form-group">
                                <div class="input-group swipe-group">
                                    <span class="input-group-addon" for="input_active"><i class="fa fa-power-off"></i></span>
                                <span class="form-control swipe-label" readonly="">
                                    {{ trans('partners.page.label.activation_placeholder') }}
                                </span>
                                    <input class="swipe" id="input_active" type="checkbox" name="active"
                                           @if(old('active'))checked @elseif(isset($partner) && $partner->active)checked @endif>
                                    <label class="swipe-btn" for="input_active"></label>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- submit login --}}
                    @if(isset($partner))
                        <button class="btn btn-primary spin-on-click" type="submit">
                            <i class="fa fa-pencil-square"></i> {{ trans('partners.page.action.update') }}
                        </button>
                        <a href="{{ route('partners.index') }}" class="btn btn-default spin-on-click" title="{{ trans('global.action.back') }}">
                            <i class="fa fa-undo"></i> {{ trans('global.action.back') }}
                        </a>
                    @else
                        <button class="btn btn-success spin-on-click" type="submit">
                            <i class="fa fa-plus-circle"></i> {{ trans('partners.page.action.create') }}
                        </button>
                        <a href="{{ route('partners.index') }}" class="btn btn-default spin-on-click" title="{{ trans('global.action.cancel') }}">
                            <i class="fa fa-ban"></i> {{ trans('global.action.cancel') }}
                        </a>
                    @endif
                </form>

            </div>
        </div>
    </div>

@endsection