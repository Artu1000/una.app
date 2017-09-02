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
                
                <h3>Pour vous inscrire au club vous avez plusieurs options : </h3> 
                <ul>
                    <li>Pour les étudiants de l'Université de Nantes et le personnel de l'Université de Nantes vous devez passer par l'inscription au SUAPS (plus d'info <a href="http://www.univ-nantes.fr/les-activites-du-suaps/aviron-115148.kjsp?RH=1184590345081" target="_blank">ici</a>)</li>
                    <li>Pour tous les autres, vous pouvez-vous inscrire en ligne via le <a href="https://www.helloasso.com/associations/universite-de-nantes-aviron/adhesions/adhesions-saison-2017-2018" target="_blank">formulaire de Hello Asso</a></li>
                    <li style="color:#aaa">Pour les universitaires, vous pouvez aussi passer par <a href="https://www.helloasso.com/associations/universite-de-nantes-aviron/adhesions/adhesions-saison-2017-2018" target="_blank">Hello Asso</a> pour nous simplifier le travail de collecte de données et de vos certificats médicaux</li>
                </ul>

                <h3>Plusieurs moyens de paiement sont disponibles </h3>
                <ul>
                    <li>Vous pouvez payer directement lors de <a href="https://www.helloasso.com/associations/universite-de-nantes-aviron/adhesions/adhesions-saison-2017-2018" target="_blank">l'inscription en ligne</a></li>
                    <li>Si vous préférez vous pouvez payer au club par cheque ou en espèces : utilisez code promo CLUB lors de l'inscription</li>
                    <li>Vous pouvez également nous faire en virement : suivez les <a href="http://univ-nantes-aviron.fr/libraries/files/universite-nantes-aviron-una-file-1893796256.pdf">instructions</a> et utilisez code promo RIB lors de l'inscription </li>
                </ul>

                <h3>Des reductions sont proposées pour le tarif Tout public</h3>
                <ul>
                    <li> -20 euros pour les reinscriptions : code promo ANCIEN</li>
                    <li> -10 euros pour le deuxième membre de famille : code promo DOUBLE</li>
                    <li> -20 euros pour le troisième membre de famille : code promo TRIPLE</li>
                </ul>




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
                
                @if($registration_form_file)
                    <h2>{{ trans('registration.page.title.registration_form') }}</h2>
                    <p>{{ trans('registration.page.label.registration_form') }}</p>

                    <a href="{{ FileManager::download(FileManager::filePath(config('file.registration.public_path'), $registration_form_file)) }}" title="{{ trans('registration.page.label.registration_form_download') }}">
                        <button class="btn btn-info"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> {{ trans('registration.page.label.registration_form_download') }}</button>
                    </a>
                @endif
                
                {!! $description !!}

            </div>
        </div>
    </div>

@endsection
