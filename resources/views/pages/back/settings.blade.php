@extends('templates.back.full_layout')

@section('content')

    <div id="content" class="config row">

        <div class="text-content">

            <div class="col-sm-12">

                {{-- Title--}}
                <h2><i class="fa fa-cogs"></i> Configuration du site</h2>

                <hr>

                <form role="form" method="POST" action="{{ route('back.settings') }}" enctype="multipart/form-data">

                    {{-- crsf token --}}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    {{-- app data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Général</h3>
                        </div>
                        <div class="panel-body">

                            {{-- site name --}}
                            <label for="input_app_name">Nom du site</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_app_name"><i class="fa fa-sitemap"></i></span>
                                    <input id="input_app_name" class="form-control" type="text" name="app_name" value="{{ old('app_name') ? old('app_name') : config('settings.app_name') }}" placeholder="Nom du site">
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- personal data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Contact</h3>
                        </div>
                        <div class="panel-body">

                            {{-- phone number --}}
                            <label for="input_phone_number">Numéro de téléphone</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_phone_number"><i class="fa fa-phone"></i></span>
                                    <input id="input_phone_number" class="form-control" type="tel" name="phone_number" value="{{ old('phone_number') ? old('phone_number') : config('settings.phone_number') }}" placeholder="Numéro de téléphone">
                                </div>
                            </div>

                            {{-- contact email --}}
                            <label for="input_contact_email">Adresse e-mail de contact</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_contact_email"><i class="fa fa-at"></i></span>
                                    <input id="input_contact_email" class="form-control" type="email" name="contact_email" value="{{ old('contact_email') ? old('contact_email') : config('settings.contact_email') }}" placeholder="Adresse e-mail de contact">
                                </div>
                            </div>

                            {{-- sspport email --}}
                            <label for="input_support_email">Adresse e-mail de support</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_support_email"><i class="fa fa-at"></i></span>
                                    <input id="input_support_email" class="form-control" type="email" name="support_email" value="{{ old('support_email') ? old('support_email') : config('settings.support_email') }}" placeholder="Adresse e-mail de support">
                                </div>
                            </div>

                            {{-- address --}}
                            <label for="input_address">Adresse postale</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_address"><i class="fa fa-envelope"></i></span>
                                    <input id="input_address" class="form-control" type="text" name="address" value="{{ old('address') ? old('address') : config('settings.address') }}" placeholder="Adresse postale">
                                </div>
                            </div>

                            {{-- zip code --}}
                            <label for="input_zip_code">Code postal</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_zip_code"><i class="fa fa-paper-plane"></i></span>
                                    <input id="input_zip_code" class="form-control" type="number" name="zip_code" value="{{ old('zip_code') ? old('zip_code') : config('settings.zip_code') }}" placeholder="Code postal">
                                </div>
                            </div>

                            {{-- city --}}
                            <label for="input_city">Ville</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_city"><i class="fa fa-map-marker"></i></span>
                                    <input id="input_city" class="form-control" type="text" name="city" value="{{ old('city') ? old('city') : config('settings.city') }}" placeholder="Ville">
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- social data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Social</h3>
                        </div>
                        <div class="panel-body">

                            {{-- facebook --}}
                            <label for="input_facebook">Page Facebook</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_facebook"><i class="fa fa-facebook"></i></span>
                                    <input id="input_facebook" class="form-control" type="tel" name="facebook" value="{{ old('facebook') ? old('facebook') : config('settings.facebook') }}" placeholder="Facebook">
                                </div>
                            </div>

                            {{-- twitter --}}
                            <label for="input_twitter">Page Twitter</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_twitter"><i class="fa fa-twitter"></i></span>
                                    <input id="input_twitter" class="form-control" type="text" name="twitter" value="{{ old('twitter') ? old('twitter') : config('settings.twitter') }}" placeholder="Twitter">
                                </div>
                            </div>

                            {{-- google+ --}}
                            <label for="input_google_plus">Page Google+</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_google_plus"><i class="fa fa-google-plus"></i></span>
                                    <input id="input_google_plus" class="form-control" type="text" name="google_plus" value="{{ old('google_plus') ? old('google_plus') : config('settings.google_plus') }}" placeholder="Google+">
                                </div>
                            </div>

                            {{-- youtube --}}
                            <label for="input_youtube">Chaîne Youtube</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_youtube"><i class="fa fa-youtube"></i></span>
                                    <input id="input_youtube" class="form-control" type="text" name="youtube" value="{{ old('youtube') ? old('youtube') : config('settings.youtube') }}" placeholder="Youtube">
                                </div>
                            </div>

                            {{-- rss --}}
                            <label for="swipe_rss">Flux RSS</label>
                            <div class="form-group">
                                <div class="input-group swipe-group">
                                    <span class="input-group-addon" for="swipe_rss"><i class="fa fa-rss"></i></span>
                                    <span class="form-control swipe-label" readonly="">
                                        Activation
                                    </span>
                                    <input class="swipe" id="swipe_rss" type="checkbox" name="rss"
                                           @if(old('rss')) checked
                                           @elseif(config('settings.youtube')) checked
                                           @endif>
                                    <label class="swipe-btn" for="swipe_rss"></label>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- appearance data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Apparence</h3>
                        </div>
                        <div class="panel-body">

                            {{-- loading spinner --}}
                            <label for="input_loading_spinner">Icône de chargement</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_loading_spinner"><i class="fa fa-spinner"></i></span>
                                    <input id="input_loading_spinner" class="form-control" type="loading_spinner" name="loading_spinner" placeholder="Icône de chargement" value="{{ old('loading_spinner') ? old('loading_spinner') : config('settings.loading_spinner') }}">
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- SEO data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Référencement</h3>
                        </div>
                        <div class="panel-body">

                            {{-- google analytics code --}}
                            <label for="input_google_analytics">Code Google Analytics</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_google_analytics"><i class="fa fa-code"></i></span>
                                    <textarea id="input_google_analytics" class="form-control" type="google_analytics" name="google_analytics" placeholder="Code Google Analytics">{{ old('google_analytics') ? old('google_analytics') : config('settings.google_analytics') }}</textarea>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- submit login --}}
                    <button class="btn btn-lg btn-primary spin-on-click" type="submit">
                        <i class="fa fa-floppy-o"></i> Enregistrer les modifications
                    </button>
                </form>

            </div>
        </div>
    </div>

@endsection