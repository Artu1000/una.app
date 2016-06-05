@extends('templates.back.full_layout')

@section('content')

    <div id="content" class="videos edit">

        <div class="text-content">

            <div class="col-sm-12">

                {{-- Title--}}
                <h2>
                    <i class="fa fa-file-video-o" aria-hidden="true"></i>
                    @if(isset($video))
                        {!! trans('videos.page.title.edit', ['album' => $video->title]) !!}
                    @else
                        {{ trans('videos.page.title.create') }}
                    @endif
                </h2>

                <hr>

                <form role="form" method="POST" action="@if(isset($video)){{ route('videos.update', ['id' => $video->id]) }} @else{{ route('videos.store') }} @endif" enctype="multipart/form-data">

                    {{-- crsf token --}}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    {{-- we include the form legend --}}
                    @include('templates.back.partials.form-legend.required')

                    {{-- add update inputs if we are in update mode --}}
                    @if(isset($video))
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="_id" value="{{ $video->id }}">
                    @endif

                    {{-- data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('videos.page.title.data') }}</h3>
                        </div>
                        <div class="panel-body">

                            {{-- image --}}
                            <label for="input_cover">{{ trans('videos.page.label.cover') }}<span class="required">*</span></label>
                            @if(isset($video) && $video->cover)
                                <div class="form-group image">
                                    <div class="form-group">
                                        <a class="img-thumbnail" href="{{ $video->imagePath($video->cover, 'cover', 'front') }}" data-lity>
                                            <img src="{{ $video->imagePath($video->cover, 'cover', 'admin') }}" alt="{{ $video->title }}">
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-primary btn-file">
                                            <i class="fa fa-picture-o"></i> {{ trans('global.action.browse') }} <input type="file" name="cover">
                                        </span>
                                    </span>
                                    <input id="input_cover" type="text" class="form-control" readonly="">
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('videos.page.info.cover') }}</p>
                            </div>

                            {{-- title --}}
                            <label for="input_title">{{ trans('videos.page.label.title') }}<span class="required">*</span></label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_title"><i class="fa fa-tag"></i></span>
                                    <input id="input_title" class="form-control capitalize-first-letter" type="text" name="title" value="{{ old('title') ? old('title') : (isset($video) && $video->title ? $video->title : null) }}" placeholder="{{ trans('videos.page.label.title') }}">
                                </div>
                            </div>

                            {{-- link --}}
                            <label for="input_link">{{ trans('videos.page.label.link') }}<span class="required">*</span></label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_link"><i class="fa fa-tag"></i></span>
                                    <input id="input_link" class="form-control capitalize-first-letter" type="text" name="link" value="{{ old('link') ? old('link') : (isset($video) && $video->link ? $video->link : null) }}" placeholder="{{ trans('videos.page.label.link') }}">
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('videos.page.info.link') }}</p>
                            </div>

                            {{-- date --}}
                            <label for="input_date">{{ trans('videos.page.label.date') }}<span class="required">*</span></label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_date"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                    <input id="input_date" type='text' class="form-control datepicker" name="date" value="{{ old('date') ? old('date') : (isset($video) && $video->date ? $video->date : \Carbon\Carbon::now()->format('Y')) }}" placeholder="{{ trans('videos.page.label.date') }}">
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('global.info.date.format') }}</p>
                            </div>

                        </div>
                    </div>

                    {{-- release data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('videos.page.title.release') }}</h3>
                        </div>
                        <div class="panel-body">

                            {{-- activation --}}
                            <label for="input_active">{{ trans('videos.page.label.activation') }}</label>
                            <div class="form-group">
                                <div class="input-group swipe-group">
                                    <span class="input-group-addon" for="input_active"><i class="fa fa-power-off"></i></span>
                                <span class="form-control swipe-label" readonly="">
                                    {{ trans('videos.page.label.activation_placeholder') }}
                                </span>
                                    <input class="swipe" id="input_active" type="checkbox" name="active"
                                           @if(old('active'))checked @elseif(isset($video) && $video->active)checked @endif>
                                    <label class="swipe-btn" for="input_active"></label>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- submit login --}}
                    @if(isset($video))
                        <button class="btn btn-primary spin-on-click" type="submit">
                            <i class="fa fa-pencil-square"></i> {{ trans('videos.page.action.update') }}
                        </button>
                        <a href="{{ route('videos.page.edit') }}" class="btn btn-default spin-on-click" title="{{ trans('global.action.back') }}">
                            <i class="fa fa-undo"></i> {{ trans('global.action.back') }}
                        </a>
                    @else
                        <button class="btn btn-success spin-on-click" type="submit">
                            <i class="fa fa-plus-circle"></i> {{ trans('videos.page.action.create') }}
                        </button>
                        <a href="{{ route('videos.page.edit') }}" class="btn btn-default spin-on-click" title="{{ trans('global.action.cancel') }}">
                            <i class="fa fa-ban"></i> {{ trans('global.action.cancel') }}
                        </a>
                    @endif
                </form>

            </div>
        </div>
    </div>

@endsection