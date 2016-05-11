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
                    {!! $news->content !!}
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