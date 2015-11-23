<html>

    {{-- head inclusion--}}
    <head>
        @include('templates.back.partials.head')
    </head>

    @include('templates.common.partials.javascript')

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
            @include('templates.back.partials.header')

            {{-- content inclusion --}}
            <div id="wrapper" class="row">
                @yield('content')
            </div>

            {{-- footer inclusion --}}
            @include('templates.back.partials.footer')

            {{-- alerts and confirm management --}}
            @if(isset($alert) && !empty($alert))
                @include('templates.common.modals.alert')
            @endif
            @if(isset($confirm) && !empty($confirm))
                @include('templates.common.modals.confirm')
            @endif

        </div>

    </body>

    {{-- end file inclusion --}}
    @include('templates.back.partials.end')

</html>