<html>

    {{-- head inclusion--}}
    <head>
        @include('layouts.front.partials.head')
    </head>

    {{-- body inclusion--}}
    <body>
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

    {{-- content inclusion --}}
    @yield('content')

    {{-- alerts management --}}
    @if(Session::get('alert'))
        @include('composers.modalAlert')
    @endif
    @if(Session::get('confirm'))
        @include('composers.modalConfirm')
    @endif

    </body>

    {{-- end file inclusion --}}
    @include('layouts.front.partials.end')

</html>