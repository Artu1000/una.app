<!DOCTYPE html>

{{-- head inclusion--}}
<head>
    @include('templates.front.partials.head')
</head>

{{-- body inclusion--}}
<body>

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

        @if(config('settings.multilingual'))
            <ul id="lang_switcher" class="list-unstyled">
                <li>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-globe"></i> {{ trans('template.back.header.language') }}
                        <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <li @if($localeCode === config('app.locale'))class="active" @endif>
                                <a rel="alternate" hreflang="{{$localeCode}}" href="{{ LaravelLocalization::getLocalizedURL($localeCode) }}">
                                    <div class="display-table">
                                        <div class="table-cell flag">
                                            <img width="20" height="20" class="img-circle" src="{{ url('img/flag/' . $localeCode . '.png') }}" alt="{{ $localeCode }}">
                                        </div>
                                        <div class="table-cell">
                                            {{ $properties['native'] }}
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            </ul>
        @endif

        {{-- content inclusion --}}
        @yield('content')

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