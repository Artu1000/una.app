@extends('layouts.front.full_layout')

@section('content')

    <div id="content" class="registration row">

        {{-- parallax img --}}
        <div class="parallax_img">
            {{--<div class="background_responsive_img fill" data-background-image="{{ $page->image }}"></div>--}}
        </div>

        <div class="text-content">
            <div class="container">
                <h2><i class="fa fa-sign-in"></i> S'inscrire au club Université Nantes Aviron (UNA)</h2>
                <hr>

                <div class="online">
                    <h3>Inscription en ligne</h3>
                    <p>Réalisez votre inscription en ligne en cliquant sur le bouton correspondant à votre situation ci-dessous.</p>
                    <a href="http://prototype.una-club.fr/una-backoffice-site/#/registrator" title="Première inscription">
                        <button class="btn btn-lg btn-info"><i class="fa fa-play"></i> Première inscription</button>
                    </a>
                    <a href="http://prototype.una-club.fr/una-backoffice-site/" title="Renouvellement">
                        <button class="btn btn-lg btn-info"><i class="fa fa-repeat"></i> Renouvellement</button>
                    </a>
                </div>

                <div class="pricing">
                    <h3>Nos tarifs</h3>
                    <table class="table table-striped table-hover">
                        @foreach($prices as $price)
                        <tr @if($price->price < 0)
                                class="info"
                            @endif>
                            <td>{{ $price->label }}</td>
                            <td>{{ $price->price }}</td>
                        </tr>
                        @endforeach
                    </table>
                    <ul>
                        <p class="text-info"><i class="fa fa-info-circle"></i> A noter :</p>
                        <li>Pour la <b>catégorie "Tous publics" uniquement</b>, une licence mi-saison est possible, valable de mai à septembre.</li>
                        <li>Pour les autres catégories, l'inscription en cours d'année ne donne droit à <b>aucune réduction tarifaire</b>.</li>
                    </ul>
                </div>

                <div class="pieces">
                    <h3>Pièces à joindre à votre inscription</h3>
                    <ul>
                        <li>Votre fiche d'inscription dûment remplie (autorisation parentale signée pour les mineurs).</li>
                        <li>Un certificat médical de non contre-indication à la pratique de l'aviron en compétition.</li>
                        <li>Une photo d'identité.</li>
                        <li>Votre règlement en liquide ou par chèque à l'ordre du club "Université Nantes Aviron".</li>
                        <li>Une photocopie de votre carte d'étudiant (étudiants uniquement).</li>
                        <li>Un brevet de natation de 50m minimum (Ecole d Aviron uniquement).</li>
                    </ul>
                </div>

                <div class="pass">
                    <h3>Réduction Pass Sport</h3>
                    <p>Le club Université Nantes Aviron accepte les Pass Sport de la saison sportive en cours (du 01/09 de l'année N au 31/08 de l'année N+1). Les Pass de la saison sportive écoulée ne sont pas admis.</p>
                    <ul>
                        <p>Les Pass de la saison en cours n'étant parfois pas encore livrés, voici le fonctionnement à suivre pour bénéficier de la	réduction du Pass Sport :</p>
                        <li>Lors de son inscription à l’UNA, indiquer si le Pass Sport a été commandé.</li>
                        <li>Un seul Pass Sport est accepté par inscription.</li>
                        <li>Régler une cotisation déduite du montant du Pass Sport.</li>
                        <li>Fournir un chèque de caution du montant du Pass Sport, non-encaissé, qui sera restitué ou détruit lorsque le Pass Sport nous sera remis.</li>
                    </ul>
                </div>

                <div>
                    <h3>Autres informations</h3>
                    <ul>
                        <li>Il vous est possible de vous inscrire tout au long de l'année à partir du formulaire d'inscription en ligne ou directement au club.</li>
                        <li>Tout dépot de dossier d'inscription doit être effectué <b>au début d'un créneau d'encadrement</b>, auprès de l'équipe d'encadrement.</li>
                        <li>Vous pouvez démarrer vos entraînements dès que votre dossier d'inscription est complet et déposé.</li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

@endsection