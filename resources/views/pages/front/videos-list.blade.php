@extends('templates.front.full_layout')

@section('content')

    {{-- top background img --}}
    @if($background_image)
        <div class="top_background_image row">
            <div class="background_responsive_img fill" data-background-image="{{ ImageManager::imagePath(config('image.videos.public_path'), $background_image) }}"></div>
        </div>
    @else
        <div class="no_top_background_image"></div>
    @endif

    <div id="content" class="videos row">

        <div class="text-content">
            <div class="container">

                <h1>
                    <i class="fa fa-video-camera" aria-hidden="true"></i>
                    {{ $title }}
                </h1>

                {!! $description !!}

                <hr>

                <div class="years">
                    <form method="GET" action="{{ route('videos.index') }}">
                        <i class="fa fa-search" aria-hidden="true"></i> {{ trans('videos.page.action.year') }} :
                        <select class="form-control autosubmit" name="year" id="input_year" title="{{ trans('videos.page.label.year_placeholder') }}">
                            <option value="" disabled>{{ trans('videos.page.label.year_placeholder') }}</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" @if($year == $selected_year)selected="selected" @endif>{{ $year }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="vids">
                    @foreach($videos_list as $video)
                        <a class="video" href="{{ $video->link }}" title="{{ $video->title }}" data-lity>
                            <h4>{{ ucfirst(\Carbon\Carbon::createFromFormat('Y-m-d', $video->date)->formatLocalized('%A %d %B %Y')) }}</h4>
                            <div class="icon">
                                <i class="fa fa-youtube-square" aria-hidden="true"></i>
                            </div>
                            <img width="220" height="220" src="{{ $video->imagePath($video->cover, 'cover', 'front') }}" alt="{{ $video->title }}">
                            <h3>
                                <span>
                                    {{ str_limit($video->title, 60) }}
                                </span>
                            </h3>
                        </a>
                    @endforeach
                </div>

            </div>
        </div>
    </div>

@endsection