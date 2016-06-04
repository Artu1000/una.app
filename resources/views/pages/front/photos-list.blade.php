@extends('templates.front.full_layout')

@section('content')

    {{-- top background img --}}
    @if($background_image)
        <div class="top_background_image row">
            <div class="background_responsive_img fill" data-background-image="{{ ImageManager::imagePath(config('image.news.public_path'), $background_image) }}"></div>
        </div>
    @else
        <div class="no_top_background_image"></div>
    @endif

    <div id="content" class="photos row">

        <div class="text-content">
            <div class="container">

                <h1>
                    <i class="fa fa-picture-o" aria-hidden="true"></i>
                    {{ $title }}
                </h1>

                {!! $description !!}

                <hr>

                <div class="years">
                    <form method="GET" action="{{ route('photos.index') }}">
                        <i class="fa fa-calendar" aria-hidden="true"></i> {{ trans('photos.page.action.year') }} :
                        <select class="form-control autosubmit" name="year" id="input_year" title="{{ trans('photos.page.label.year_placeholder') }}">
                            <option value="" disabled>{{ trans('photos.page.label.year_placeholder') }}</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" @if($year == $selected_year)selected="selected" @endif>{{ $year }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="albums">
                    @foreach($photos_list as $album)
                        <a class="album new_window" href="{{ $album->link }}" title="{{ $album->title }}">
                            <img width="220" height="220" src="{{ $album->imagePath($album->cover, 'cover', 'front') }}" alt="{{ $album->title }}">
                            <h3>
                                <span>
                                    {{ str_limit($album->title, 50) }}
                                </span>
                            </h3>
                        </a>
                    @endforeach
                </div>

            </div>
        </div>
    </div>

@endsection