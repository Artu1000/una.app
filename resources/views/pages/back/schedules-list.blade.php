@extends('templates.back.full_layout')

@section('content')

    <div id="content" class="schedules">

        <div class="text-content">

            <div class="col-sm-12">

                {{-- Title--}}
                <h2><i class="fa fa-clock-o"></i> {{ trans('schedules.page.title.management') }}</h2>

                <hr>

                <form role="form" method="POST" action="{{ route('schedules.data.update') }}">

                    {{-- crsf token --}}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">

                    {{-- home settings --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('schedules.page.title.data') }}</h3>
                        </div>
                        <div class="panel-body">

                            <label for="input_title">{{ trans('home.page.label.title') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_title"><i class="fa fa-paragraph"></i></span>
                                    <input id="input_title" class="form-control capitalize-first-letter" name="title" placeholder="{{ trans('home.page.label.title') }}" value="{{ old('title') ? old('title') : $title }}">
                                </div>
                            </div>

                            {{-- background image --}}
                            <label for="input_image">{{ trans('news.page.label.image') }}</label>
                            @if($background_image)
                                <div class="form-group image">
                                    <div class="form-group">
                                        <a class="img-thumbnail" href="" data-lity>
                                            <img src="">
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-primary btn-file">
                                            <i class="fa fa-picture-o"></i> {{ trans('global.action.browse') }} <input type="file" name="image">
                                        </span>
                                    </span>
                                    <input id="input_image" type="text" class="form-control" readonly="">
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('news.page.info.image') }}</p>
                            </div>

                            {{-- description --}}
                            <label for="input_description">{{ trans('home.page.label.description') }}</label>
                            <div class="form-group textarea">
                                <textarea id="input_description" class="form-control markdown" name="description" placeholder="{{ trans('home.page.label.description') }}">{{ old('description') ? old('description') : $description }}</textarea>
                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary spin-on-click" type="submit">
                                    <i class="fa fa-floppy-o"></i> {{ trans('global.action.save') }}
                                </button>
                            </div>

                        </div>
                    </div>

                </form>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ trans('schedules.page.title.list') }}</h3>
                    </div>
                    <div class="panel-body table-responsive">

                        @include('templates.back.partials.table-list')

                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection