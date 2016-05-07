@extends('templates.front.full_layout')

@section('content')

    {{-- top background img --}}
    @if($page->image)
        <div class="top_background_image row">
            <div class="background_responsive_img fill" data-background-image="{{ $page->image }}"></div>
        </div>
    @else
        <div class="no_top_background_image"></div>
    @endif

    <div id="content" class="page row">

        <div class="text-content">
            <div class="container">
                <h2>{!! $page->title !!}</h2>
                <hr>
                <div>
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>

@endsection