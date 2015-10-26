@extends('templates.front.full_layout')

@section('content')

    <div id="content" class="page row">

        {{-- parallax img --}}
        <div class="parallax_img">
            @if($page->image)
                <div class="background_responsive_img fill" data-background-image="{{ $page->image }}"></div>
            @endif
        </div>

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