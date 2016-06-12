@extends('templates.front.full_layout')

@section('content')

    {{-- top background img --}}
    @if($news->image)
        <div class="top_background_image row">
            <div class="background_responsive_img fill" data-background-image="{{ $news->imagePath($news->image, 'image') }}"></div>
        </div>
    @else
        <div class="no_top_background_image"></div>
    @endif

    <div id="content" class="news-detail row">

        <div class="text-content">
            <div class="container">

                <h1><i class="fa fa-newspaper-o"></i> {!! $news->title !!}</h1>
                <div class="date">
                    <i class="fa fa-clock-o"></i> {{ trans('news.page.label.released_at') }} : {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $news->released_at)->format('d/m/Y H:i') }}
                </div>
                <div class="category">
                    <i class="fa fa-cube"></i> {{ trans('news.page.label.category') }} : <b>{{ trans('news.config.category.' . config('news.category.' . $news->category_id)) }}</b>
                </div>

                <hr>

                <div>
                    {{-- content --}}
                    {!! $news->content !!}

                    {{-- media --}}
                    @if($news->photo_album_id || $news->video_id)
                        <h2>{{ trans('news.page.title.media') }}</h2>
                        <div class="media">
                            @if($photo_album = $news->photoAlbum)
                                <a class="photo_album new_window bg-info display-table" href="{{ $photo_album->link }}" title="{{ $photo_album->title }}">
                                    <div class="image table-cell">
                                        <img width="40" height="40" src="{{ $photo_album->imagePath($photo_album->cover, 'cover', 'front') }}" alt="{{ $photo_album->title }}">
                                    </div>
                                    <div class="detail table-cell">
                                        <h3><i class="fa fa-camera" aria-hidden="true"></i> {{ trans('news.page.title.photo_album') }}</h3>
                                        <h4>{{ str_limit($photo_album->title, 60) }}</h4>
                                    </div>
                                </a>
                            @endif
                            @if($video = $news->video)
                                <a class="video bg-info display-table" href="{{ $video->link }}" title="{{ $video->title }}" data-lity>
                                    <div class="image table-cell">
                                        <img width="40" height="40" src="{{ $video->imagePath($video->cover, 'cover', 'front') }}" alt="{{ $video->title }}">
                                    </div>
                                    <div class="detail table-cell">
                                        <h3><i class="fa fa-video-camera" aria-hidden="true"></i> {{ trans('news.page.title.video') }}</h3>
                                        <h4>{{ str_limit($video->title, 60) }}</h4>
                                    </div>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="author pull-right">
                    <b>{{ trans('news.page.label.author_id') }}</b> : {{ $news->author->first_name . ' ' . $news->author->last_name }}
                </div>

                <div class="return">
                    <a href="{{ route('news.index') }}">
                        <button class="btn btn-default"><i class="fa fa-chevron-circle-left"></i></button> <span>Retour</span>
                    </a>
                </div>

                <div id="disqus_thread"></div>

            </div>
        </div>
    </div>

@endsection