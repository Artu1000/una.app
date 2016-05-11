@extends('templates.front.full_layout')

@section('content')

    {{-- top background img --}}
    {{--@if($background_image)--}}
        {{--<div class="top_background_image row">--}}
            {{--<div class="background_responsive_img fill" data-background-image="{{ ImageManager::imagePath(config('image.registration.public_path'), $background_image) }}"></div>--}}
        {{--</div>--}}
    {{--@else--}}
        <div class="no_top_background_image"></div>
    {{--@endif--}}

    <div id="content" class="page row">

        <div class="text-content">
            <div class="container">
                <h1><i class="fa fa-calendar"></i> Le calendrier du club Université Nantes Aviron (UNA)</h1>
                <hr>

                <div class="embed-responsive embed-responsive-4by3 big-container">
                    <iframe src="https://www.google.com/calendar/embed?src=communication%40una-club.fr&ctz=Europe/Paris" frameborder="0" scrolling="no"></iframe>
                </div>

                <div class="responsive-iframe-container small-container">
                    <iframe src="http://www.google.com/calendar/embed?+showNav=0&showDate=0&showPrint=0&showCalendars=0&mode=AGENDA&height=600&wkst=1&bgcolor=%23FFFFFF&src=communication%40una-club.fr&ctz=Europe/Paris" frameborder="0"></iframe>
                </div>

            </div>
        </div>
    </div>

@endsection