@extends('templates.front.full_layout')

@section('content')

    <div id="content" class="news-detail row">

        {{-- parallax img --}}
        <div class="parallax_img">
            @if($news->image)
                <div class="background_responsive_img fill" data-background-image="{{ $news->imagePath($news->image, 'image') }}"></div>
            @endif
        </div>
        
        <div class="text-content">
            <div class="container">

                <h2><i class="fa fa-newspaper-o"></i> {!! $news->title !!}</h2>
                <div class="date">
                    <i class="fa fa-clock-o"></i> {{ trans('news.page.label.released_at') }} : {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $news->released_at)->format('d/m/Y H:i:s') }}
                </div>
                <div class="category">
                    <i class="fa fa-cube"></i> {{ trans('news.page.label.category') }} : {{ trans('news.config.category.' . config('news.category.' . $news->category_id)) }}
                </div>

                <hr>

                <div>
                    {!! $news->content !!}
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