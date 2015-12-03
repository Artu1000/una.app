@extends('templates.back.full_layout')

@section('content')

    <div id="content" class="home row">

        <div class="text-content">

            <div class="col-sm-12">

                {{-- Title--}}
                <h2><i class="fa fa-home"></i> {{ trans('home.page.title.management') }}</h2>

                <hr>

                <form role="form" method="POST" action="{{ route('home.update') }}">

                    {{-- crsf token --}}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">

                    {{-- home settings --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('home.page.title.content') }}</h3>
                        </div>
                        <div class="panel-body">

                            <label for="input_title">{{ trans('home.page.label.title') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_title"><i class="fa fa-paragraph"></i></span>
                                    <input id="input_title" class="form-control capitalize-first-letter" name="title" placeholder="{{ trans('home.page.label.title') }}" value="{{ old('title') ? old('title') : $title }}">
                                </div>
                            </div>

                            <label for="input_description">{{ trans('home.page.label.description') }}</label>
                            <div class="form-group textarea">
                                <textarea id="input_description" class="form-control markdown" name="description" placeholder="{{ trans('home.page.label.description') }}">{{ old('description') ? old('description') : $description }}</textarea>
                            </div>

                            <label for="input_video_link">{{ trans('home.page.label.video_link') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_video_link"><i class="fa fa-video-camera"></i></span>
                                    <input id="input_video_link" class="form-control" name="video_link" placeholder="{{ trans('home.page.label.video_link') }}" value="{{ old('video_link') ? old('video_link') : $video_link }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary spin-on-click" type="submit">
                                    <i class="fa fa-floppy-o"></i> {{ trans('global.action.save') }}
                                </button>
                            </div>

                        </div>
                    </div>

                </form>

                {{-- home slides --}}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ trans('home.page.title.slides') }}</h3>
                    </div>
                    <div class="panel-body table-responsive">

                        @include('templates.back.partials.table-list')

                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection