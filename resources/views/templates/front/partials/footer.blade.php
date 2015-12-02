{{-- google map --}}
<div id="map-canvas" class="row">
</div>

{{-- footer --}}
<div id="footer_layout" class="row">

    <div id="footer_background"></div>

    <footer id="contact">
        <div class="container">
            <div class="left_part">
                <div class="contact">
                    <p>
                        <i class="fa fa-phone-square"></i> {{ config('settings.phone_number') }}
                    </p>
                    <p>
                        <a href="mailto:{{ config('settings.contact_email') }}">
                            <i class="fa fa-envelope"></i> {!! str_replace('@', '<i class="fa fa-at"></i>', config('settings.contact_email')) !!}
                        </a>
                    </p>
                    <p>
                        <i class="fa fa-bus"></i> {{ config('settings.address') }} <span class="hidden-sm hidden-xs"> {{ config('settings.zip_code') }} {{ config('settings.city') }}</span>
                    </p>
                </div>
                <div class="logo hidden-xs">
                    <a href="{{ url('/') }}#top" title="Revenir en haut de la page">
                        @if(config('settings.logo_light'))
                            <img width="70" src="{{ route('image', ['filename' => config('settings.logo_light'), 'storage_path' => storage_path('app/config'), 'size' => 'header']) }}" alt="Logo {{ config('settings.app_name') }}">
                        @endif
                    </a>
                </div>
            </div>
            <div class="delimiter hidden-xs hidden-sm">
                <span></span>
            </div>
            <div class="right_part">
                <div class="social">
                    @if(config('settings.facebook'))
                        <a class="new_window" href="{{ config('settings.facebook') }}" title="Facebook {{ config('settings.app_name') }}">
                            <i class="fa fa-facebook-square fa-3"></i>
                        </a>
                    @endif
                    @if(config('settings.twitter'))
                        <a class="new_window" href="{{ config('settings.twitter') }}" title="Twitter du {{ config('settings.app_name') }}">
                            <i class="fa fa-twitter-square fa-3"></i>
                        </a>
                    @endif
                    @if(config('settings.google_plus'))
                        <a class="new_window" rel=publisher" href="{{ config('settings.google_plus') }}" title="Google+ {{ config('settings.app_name') }}">
                            <i class="fa fa-google-plus-square fa-3"></i>
                        </a>
                    @endif
                    @if(config('settings.linkedin'))
                        <a class="new_window" href="{{ config('settings.linkedin') }}" title="Linkedin {{ config('settings.app_name') }}">
                            <i class="fa fa-linkedin-square fa-3"></i>
                        </a>
                    @endif
                    @if(config('settings.pinterest'))
                        <a class="new_window" rel=publisher" href="{{ config('settings.pinterest') }}" title="Pinterest {{ config('settings.app_name') }}">
                            <i class="fa pinterest-square fa-3"></i>
                        </a>
                    @endif
                    @if(config('settings.youtube'))
                        <a class="new_window" href="{{ config('settings.youtube') }}" title="Youtube {{ config('settings.app_name') }}">
                            <i class="fa fa-youtube-square fa-3"></i>
                        </a>
                    @endif
                    @if(config('settings.rss'))
                        <a class="new_window" href="{{ url('rss') }}" title="Flux RSS des actualités - {{ config('settings.app_name') }}">
                            <i class="fa fa-rss-square fa-3"></i>
                        </a>
                    @endif
                </div>
                <div class="copyright hidden-xs">
                    <p><i class="fa fa-copyright"></i> 2015  - <span class="hidden-sm">Club</span> Université Nantes Aviron<span class="hidden-sm"> (UNA)</span></p>
                </div>
            </div>
        </div>
    </footer>

    <div id="footer_color_fill"></div>

</div>