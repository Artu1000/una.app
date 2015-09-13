@extends('layouts.front.full_layout')

@section('content')

    <div id="content" class="news-detail row">

        {{-- parallax img --}}
        <div class="parallax_img">
            @if($news->image)
                <div class="background_responsive_img fill" data-background-image="{{ url('/') . '/' . $news->image }}"></div>
            @endif
        </div>
        
        <div class="text-content">
            <div class="container">
                <h2>{!! $news->title !!}</h2>
                <hr>
                <div>
                    {!! $news->content !!}
                </div>
            </div>
        </div>
    </div>

@endsection