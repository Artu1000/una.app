<html>

    <head>
        @include('layouts.common.head')
    </head>

    <body>

        {{-- no script specifications --}}
        <noscript>
            <div class="container noscript text-muted">
                <h3>Attention !</h3>
                Vous naviguez actuellement en version dégragée.<br/>
                Merci d'activer votre Javascript pour bénéficier de l'ensemble des fonctionnalités de l'application.
            </div>
        </noscript>

        @yield('content')
        @if(Session::get('alert'))
            @include('composers.modalAlert')
        @endif
        @if(Session::get('confirm'))
            @include('composers.modalConfirm')
        @endif
    </body>

    @include('layouts.back.pre_footer')

</html>