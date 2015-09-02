<html>

    {{-- head inclusion--}}
    <head>
        @include('layouts.front.partials.head')
    </head>

    {{-- body inclusion--}}
    <body id="top">

        <div id="layout" class="container-fluid">

            {{-- no script specifications --}}
            <noscript>
                <div class="container-fluid noscript text-muted">
                    <div class="col-lg-offset-2 col-lg-8">
                        <h3>
                            <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
                            Attention
                            <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
                        </h3>
                        Le Javascript de votre navigateur est désactivé et vous naviguez actuellement en version dégradée.<br/>
                        Merci de réactiver votre Javascript pour bénéficier de l'ensemble des fonctionnalités de l'application.
                    </div>
                </div>
            </noscript>

            {{-- header inclusion --}}
            @include('layouts.front.partials.header')

            {{-- content inclusion --}}
            @yield('content')

            {{-- partners inclusion --}}
            @include('layouts.front.partials.partners')

            {{-- footer inclusion --}}
            @include('layouts.front.partials.footer')

            {{-- alerts management --}}
            @if(Session::get('alert'))
                @include('composers.modalAlert')
            @endif

            @if(Session::get('confirm'))
                @include('composers.modalConfirm')
            @endif

        </div>

    </body>

    {{-- end file inclusion --}}
    @include('layouts.front.partials.end')

</html>