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

		<p> L'inscription s'effectue en ligne, y compris pour
		les étudiants qui ne sont pas de l'Université de
		Nantes.  Si vous êtes étudiant ou personnel de
		l'Université de Nantes vous devez également passer par
		le SUAPS.  </p>

                <h3>Attention aux certificats médicaux</h3>
                <ul>
                    <li>Si vous avez fourni un certificat il y a moins d'un an, il est désormais valable 3 saisons.</li>
                    <li>En revanche, vous devez répondre au questionnaire <a href="https://www.formulaires.modernisation.gouv.fr/gf/cerfa_15699.do" target="">cerfa en ligne</li>
                    <li>Et joindre <a href="http://univ-nantes-aviron.fr/libraries/files/universite-nantes-aviron-una-file-5481261731.docx">l'attestation</a> signée lors de l'inscription.</li>
                </ul>
                
                <h3>Décharge de responsabilité</h3>
		<p>Veuillez télécharger et signer <a href="http://univ-nantes-aviron.fr/libraries/files/universite-nantes-aviron-una-file-9672610108.pdf" target="_blank">la décharge de responsabilité</a>, et ensuite la joindre au formulaire.  Toutes les parties n'appliquent pas à tout le monde.</p>
                
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
