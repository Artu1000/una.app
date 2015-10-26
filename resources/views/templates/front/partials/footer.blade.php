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
                        <i class="fa fa-phone-square"></i> {{ config('app.phone') }}
                    </p>
                    <p>
                        <a href="mailto:{{ config('app.email.contact') }}">
                            <i class="fa fa-envelope"></i> {!! str_replace('@', '<i class="fa fa-at"></i>', config('app.email.contact')) !!}
                        </a>
                    </p>
                    <p>
                        <i class="fa fa-bus"></i> {{ config('app.address.number') }} {{ config('app.address.street') }} <span class="hidden-sm hidden-xs"> {{ config('app.address.postal_code') }} {{ config('app.address.city') }}</span>
                    </p>
                </div>
                <div class="logo hidden-xs">
                    <a href="{{ url('/') }}#top" title="Revenir en haut de la page">
                        <img width="70" height="66" src="{{ config('app.logo.small.light') }}" alt="Logo {{ config('app.name') }}">
                    </a>
                </div>
            </div>
            <div class="delimiter hidden-xs hidden-sm">
                <span></span>
            </div>
            <div class="right_part">
                <div class="social">
                    <a class="new_window" href="https://fr-fr.facebook.com/UniversiteNantesAviron" title="Facebook {{ config('app.name') }}">
                        <i class="fa fa-facebook-square fa-3"></i></a>
                    <a class="new_window" href="https://twitter.com/UNAClub" title="Twitter du {{ config('app.name') }}">
                        <i class="fa fa-twitter-square fa-3"></i>
                    </a>
                    <a class="new_window" rel=publisher" href="https://plus.google.com/+Univ-nantes-avironFr" title="Google+ {{ config('app.name') }}">
                        <i class="fa fa-google-plus-square fa-3"></i>
                    </a>
                    <a class="new_window" href="{{ url('rss') }}" title="Flux RSS des actualités - {{ config('app.name') }}">
                        <i class="fa fa-rss-square fa-3"></i>
                    </a>
                </div>
                <div class="copyright hidden-xs">
                    <p><i class="fa fa-copyright"></i> 2014  - <span class="hidden-sm">Club</span> Université Nantes Aviron<span class="hidden-sm"> (UNA)</span></p>
                </div>
            </div>
        </div>
    </footer>

    <div id="footer_color_fill"></div>

</div>