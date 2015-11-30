@extends('templates.back.full_layout')

@section('content')

    <div id="content" class="home row">

        <div class="text-content">

            <div class="col-sm-12">

                {{-- Title--}}
                <h2><i class="fa fa-home"></i> {{ trans('home.page.title.management') }}</h2>

                <hr>

                <form role="form" method="POST" action="@if(isset($user)){{ route('users.update') }} @else{{ route('users.index') }} @endif" enctype="multipart/form-data">

                    {{-- crsf token --}}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">

                    {{-- home settings --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('home.page.title.settings') }}</h3>
                        </div>
                        <div class="panel-body">

                            {{-- news activation --}}
                            <label for="swipe_news_activation">{{ trans('home.page.label.news_activation.title') }}</label>
                            <div class="form-group">
                                <div class="input-group swipe-group">
                                    <span class="input-group-addon" for="swipe_rss"><i class="fa fa-paper-plane"></i></span>
                                    <span class="form-control swipe-label" readonly="">
                                        {{ trans('home.page.label.news_activation.show') }}
                                    </span>
                                    <input class="swipe" id="swipe_news_activation" type="checkbox" name="news_activation"
                                           @if(old('news_activation')) checked
                                           @elseif($news_activation) checked
                                            @endif>
                                    <label class="swipe-btn" for="swipe_news_activation"></label>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- home description --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('home.page.title.content') }}</h3>
                        </div>
                        <div class="panel-body">

                            <label for="input_description">{{ trans('home.page.label.description') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_zip_code"><i class="fa fa-paragraph"></i></span>
                                    <textarea id="input_description" class="form-control" name="description" placeholder="{{ trans('home.page.label.description') }}">{{ old('description') ? old('description') : $description }}</textarea>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- home slides --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('home.page.title.slide_list') }}</h3>
                        </div>
                        <div class="panel-body table-responsive">

                            @include('templates.back.partials.table-list')

                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

@endsection