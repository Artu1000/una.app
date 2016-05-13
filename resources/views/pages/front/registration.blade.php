@extends('templates.front.full_layout')

@section('content')

    {{-- top background img --}}
    @if($background_image)
        <div class="top_background_image row">
            <div class="background_responsive_img fill" data-background-image="{{ ImageManager::imagePath(config('image.registration.public_path'), $background_image) }}"></div>
        </div>
    @else
        <div class="no_top_background_image"></div>
    @endif

    <div id="content" class="registration row">

        <div class="text-content">
            <div class="container">
                <h1><i class="fa fa-sign-in"></i> {{ $title }}</h1>

                <hr>

                <h2>{{ trans('registration.page.title.online_registration') }}</h2>
                <p>{{ trans('registration.page.label.online_registration') }}</p>
                <a href="http://prototype.una-club.fr/una-backoffice-site/#/registrator" title="{{ trans('registration.page.label.first_registration') }}">
                    <button class="btn btn-info"><i class="fa fa-play"></i> {{ trans('registration.page.label.first_registration') }}</button>
                </a>
                <a href="http://prototype.una-club.fr/una-backoffice-site/" title="{{ trans('registration.page.label.renewal_registration') }}">
                    <button class="btn btn-info"><i class="fa fa-repeat"></i> {{ trans('registration.page.label.renewal_registration') }}</button>
                </a>

                <h2>{{ trans('registration.page.title.prices') }}</h2>
                <table class="table table-striped table-hover">
                    @foreach($prices as $price)
                    <tr @if($price->price < 0)
                            class="info"
                        @endif>
                        <td>{{ $price->label }}</td>
                        <td class="text-right">{{ $price->price }}&nbsp;€</td>
                    </tr>
                    @endforeach
                </table>

                {!! $description !!}

            </div>
        </div>
    </div>

@endsection