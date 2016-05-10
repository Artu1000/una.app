<!DOCTYPE html>

    {{-- head inclusion--}}
    <head>
        @include('templates.front.partials.head')
    </head>

    {{-- body inclusion--}}
    <body id="top">

        {{-- dynamic javascript inclusion --}}
        @include('templates.common.partials.javascript')

        <div id="layout" class="container-fluid">

            {{-- no script specifications --}}
            <noscript>
                <div class="container-fluid noscript text-muted row">
                    <div class="col-lg-offset-2 col-lg-8">
                        <h3>
                            <i class="fa fa-exclamation-triangle"></i> {{ trans('global.message.javascript.deactivated.title') }}
                        </h3>
                        {!! trans('global.message.javascript.deactivated.message') !!}
                    </div>
                </div>
            </noscript>

            {{-- header inclusion --}}
            @include('templates.front.partials.header')

            {{-- content inclusion --}}
            @yield('content')

            {{-- partners inclusion --}}
            @include('templates.front.partials.partners')

            {{-- footer inclusion --}}
            @include('templates.front.partials.footer')

            {{-- alerts management --}}
            @if(isset($alert) && !empty($alert))
                @include('templates.common.modals.alert')
            @endif
            @if(isset($confirm) && !empty($confirm))
                @include('templates.common.modals.confirm')
            @endif

        </div>

    </body>

    {{-- end file inclusion --}}
    @include('templates.front.partials.end')

</html>