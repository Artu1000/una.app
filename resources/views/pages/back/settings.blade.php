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
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_phone_number"><i class="fa fa-phone"></i></span>
                                    <input id="input_phone_number" class="form-control" type="tel" name="phone_number" value="{{ old('phone_number') ? old('phone_number') : config('settings.phone_number') }}" placeholder="Numéro de téléphone">
                                </div>
                            </div>

                            {{-- contact email --}}
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_contact_email"><i class="fa fa-at"></i></span>
                                    <input id="input_contact_email" class="form-control" type="email" name="contact_email" value="{{ old('contact_email') ? old('contact_email') : config('settings.contact_email') }}" placeholder="Adresse e-mail de contact">
                                </div>
                            </div>

                            {{-- sspport email --}}
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_support_email"><i class="fa fa-at"></i></span>
                                    <input id="input_support_email" class="form-control" type="email" name="support_email" value="{{ old('support_email') ? old('support_email') : config('settings.support_email') }}" placeholder="Adresse e-mail de support">
                                </div>
                            </div>

                            {{-- address --}}
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_address"><i class="fa fa-envelope"></i></span>
                                    <input id="input_address" class="form-control" type="text" name="address" value="{{ old('address') ? old('address') : config('settings.address') }}" placeholder="Adresse postale">
                                </div>
                            </div>

                            {{-- zip code --}}
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_zip_code"><i class="fa fa-paper-plane"></i></span>
                                    <input id="input_zip_code" class="form-control" type="number" name="zip_code" value="{{ old('zip_code') ? old('zip_code') : config('settings.zip_code') }}" placeholder="Code postal">
                                </div>
                            </div>

                            {{-- city --}}
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
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_facebook"><i class="fa fa-facebook"></i></span>
                                    <input id="input_facebook" class="form-control" type="tel" name="facebook" value="{{ old('facebook') ? old('facebook') : config('settings.facebook') }}" placeholder="Facebook">
                                </div>
                            </div>

                            {{-- twitter --}}
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_twitter"><i class="fa fa-twitter"></i></span>
                                    <input id="input_twitter" class="form-control" type="text" name="twitter" value="{{ old('twitter') ? old('twitter') : config('settings.twitter') }}" placeholder="Twitter">
                                </div>
                            </div>

                            {{-- google+ --}}
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_google_plus"><i class="fa fa-google-plus"></i></span>
                                    <input id="input_google_plus" class="form-control" type="text" name="google_plus" value="{{ old('google_plus') ? old('google_plus') : config('settings.google_plus') }}" placeholder="Google+">
                                </div>
                            </div>

                            {{-- youtube --}}
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_youtube"><i class="fa fa-youtube"></i></span>
                                    <input id="input_youtube" class="form-control" type="text" name="youtube" value="{{ old('youtube') ? old('youtube') : config('settings.youtube') }}" placeholder="Youtube">
                                </div>
                            </div>

                            {{-- rss --}}
                            <div class="form-group">
                                <div class="input-group swipe-group">
                                    <span class="input-group-addon"><i class="fa fa-rss"></i></span>
                                    {{--<input class="form-control" type="text" value="Flux RSS" readonly>--}}
                                    <span class="form-control swipe-label" readonly="">
                                        Flux RSS
                                    </span>
                                    <span>
                                        <input class="swipe" id="swipe_rss" type="checkbox" name="rss"
                                               @if(old('rss')) checked
                                               @elseif(config('settings.youtube')) checked
                                                @endif>
                                        <label class="swipe-btn" for="swipe_rss"></label>
                                    </span>
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