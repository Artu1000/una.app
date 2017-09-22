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
                
                <h3>Pour vous inscrire au club</h3>

		<p>
		Si vous n'êtes pas étudiant à l'Université de Nantes, vous pouvez désormais vous inscrire en ligne <a href="https://www.helloasso.com/associations/universite-de-nantes-aviron/adhesions/adhesions-saison-2017-2018" target="_blank">ici</a>.
		</p>

		<p>
		(Notre prestataire de paiement est helloasso, dont le business model relève de demander un don directement à helloasso à la fin de la transaction.  N'hésitez pas, mais sachez également que le club fera un don au nom de tous nos sociétaires (et basé sur le montant qui passe par leur service).  Votre conscience est donc libre dans tous les cas.)</p>

                <p>Vous êtes étudiant de l'Université de Nantes ?  Passez alors par l'inscription au SUAPS (plus d'info <a href="http://www.univ-nantes.fr/les-activites-du-suaps/aviron-115148.kjsp?RH=1184590345081" target="_blank">ici</a>).  En parallèle, l'inscription UNA en ligne vous reste <a href="https://www.helloasso.com/associations/universite-de-nantes-aviron/adhesions/inscriptions-etudiantes" target="_blank">gratuit</a>, ce qui assure que nous avons toutes vos infos.</p>

                <h3>Plusieurs moyens de paiement sont disponibles </h3>

		<p>
		    Nous préférons que vous payiez en ligne (voir ci-haut).  Si toutefois ça vous pose un problème, vous pouvez payer par carte bancaire au club (disponible bientôt...) ou par chèque.  Ou, si vraiment nécessaire, en espèces.  Dans ces derniers cas, faites l'inscription en ligne avec code promo "<b>CLUB</b>", ce qui vous rendra l'inscription gratuite.  (Ça sert à nous fournir vos infos et documents.)  Dans tous les cas qui ne sont pas via helloasso (en ligne), le trésorier vous saurez gré d'un petit mot (même confidentiel) de l'empêchement.  Nous voudrions ne gêner personne pendant que nous optimisons l'inscription et la réinscription.</p>
                
                <h3>Attention aux nouveautés relatives aux certificats médicaux</h3>
                <ul>
                    <li>Si vous avez fourni un certificat il y a moins d'un an, il est désormais valable 3 saisons.</li>
                    <li>En revanche, vous devez répondre au questionnaire <a href="https://www.formulaires.modernisation.gouv.fr/gf/cerfa_15699.do" target="">cerfa en ligne</li>
                    <li>Et joindre <a href="http://univ-nantes-aviron.fr/libraries/files/universite-nantes-aviron-una-file-5481261731.docx">l'attestation</a> signée lors de l'inscription.</li>
                </ul>
                
                <h3>Décharge de responsabilité</h3>
		<p>Veuillez télécharger et signer <a href="http://univ-nantes-aviron.fr/libraries/files/universite-nantes-aviron-una-file-9672610108.pdf" target="_blank">la décharge de responsabilité</a>, et ensuite la joindre au formulaire.  Toutes les parties n'appliquent pas à tout le monde.</p>
                
                <h3>Des reductions sont proposées pour le tarif tout public</h3>
                <ul>
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
