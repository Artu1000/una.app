<!DOCTYPE html>

    {{-- head inclusion--}}
    <head>
        @include('templates.back.partials.head')
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
            @include('templates.back.partials.header')

            {{-- content inclusion --}}
            <div id="wrapper" class="row">
                @if(config('breadcrumbs'))
                    {!! Breadcrumbs::renderIfExists(\Request::route()->getName(), (isset($breadcrumbs_data) && !empty($breadcrumbs_data) ? $breadcrumbs_data : [])) !!}
                @endif
                @yield('content')
            </div>

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