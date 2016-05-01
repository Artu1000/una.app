@extends('templates.front.full_layout')

@section('content')

    <div id="content" class="registration row">

        {{-- background img --}}
        <div class="parallax_img">
            @if($background_image)
                <div class="background_responsive_img fill" data-background-image="{{ ImageManager::imagePath(config('image.registration.public_path'), $background_image) }}"></div>
            @endif
        </div>

        <div class="text-content">
            <div class="container">
                <h2><i class="fa fa-sign-in"></i> {{ $title }}</h2>
                <hr>

                <div class="online">
                    <h3>{{ trans('registration.page.title.online_registration') }}</h3>
                    <p>{{ trans('registration.page.label.online_registration') }}</p>
                    <a href="http://prototype.una-club.fr/una-backoffice-site/#/registrator" title="{{ trans('registration.page.label.first_registration') }}">
                        <button class="btn btn-info"><i class="fa fa-play"></i> {{ trans('registration.page.label.first_registration') }}</button>
                    </a>
                    <a href="http://prototype.una-club.fr/una-backoffice-site/" title="{{ trans('registration.page.label.renewal_registration') }}">
                        <button class="btn btn-info"><i class="fa fa-repeat"></i> {{ trans('registration.page.label.renewal_registration') }}</button>
                    </a>
                </div>

                <div class="pricing">
                    <h3>{{ trans('registration.page.title.prices') }}</h3>
                    <table class="table table-striped table-hover">
                        @foreach($prices as $price)
                        <tr @if($price->price < 0)
                                class="info"
                            @endif>
                            <td>{{ $price->label }}</td>
                            <td class="text-right">{{ $price->price }} €</td>
                        </tr>
                        @endforeach
                    </table>
                </div>

                {!! $description !!}

            </div>
        </div>
    </div>

@endsection