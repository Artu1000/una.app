<header class="row">
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('dashboard.index') }}">
                <h1>
                    {{ config('settings.app_name_' . config('app.locale')) }}
                </h1>
                <span class="hidden-xs">- {{ config('settings.app_slogan_' . config('app.locale')) }}</span>
            </a>
        </div>
        <ul class="nav navbar-right top-nav">
            @if(config('settings.multilingual'))
                <li>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-globe"></i>
                        <span class="hidden-xs">{{ trans('template.back.header.language') }}</span>
                        <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
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
            @endif
            <li class="dropdown @if(\Route::current()->getName() === 'users.profile')active @endif">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i>
                    <span class="hidden-xs">{{ \Sentinel::getUser()->first_name }} {{ \Sentinel::getUser()->last_name }}</span>
                    <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li class="@if(\Route::current()->getName() === 'users.profile')active @endif">
                        <a href="{{ route('users.profile') }}"><i class="fa fa-user fa-fw"></i>
                            {{ trans('template.back.header.my_profile') }}
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a id="login" href="{{ route('logout') }}">
                            <i class="fa fa-power-off"></i>
                            {{ trans('template.back.header.logout') }}
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav side-nav">
                <li @if(\Route::current()->getName() === 'dashboard.index') class="active" @endif>
                    <a href="{{ route('dashboard.index') }}"><i class="fa fa-fw fa-dashboard"></i> {{ trans('template.back.header.dashboard') }}</a>
                </li>
                @if(\Sentinel::getUser()->hasAccess('settings.view'))
                    <li @if(\Route::current()->getName() === 'settings.index') class="active" @endif>
                        <a href="{{ route('settings.index') }}"><i class="fa fa-wrench"></i> {{ trans('template.back.header.settings') }}</a>
                    </li>
                @endif
                @if(\Sentinel::getUser()->hasAccess('permissions.list'))
                    <li @if(\Route::current()->getName() === 'permissions.index') class="active"
                        @elseif(\Route::current()->getName() === 'permissions.create')) class="active"
                        @elseif(\Route::current()->getName() === 'permissions.edit')) class="active"
                        @endif>
                        <a href="{{ route('permissions.index') }}"><i class="fa fa-gavel"></i> {{ trans('template.back.header.permissions') }}</a>
                    </li>
                @endif
                @if(\Sentinel::getUser()->hasAccess('users.list'))
                    <li @if(\Route::current()->getName() === 'users.index') class="active"
                        @elseif(\Route::current()->getName() === 'users.create')) class="active"
                        @elseif(\Route::current()->getName() === 'users.edit')) class="active"
                        @endif>
                        <a href="{{ route('users.index') }}"><i class="fa fa-users"></i> {{ trans('template.back.header.users') }}</a>
                    </li>
                @endif

                {{--<li class="divider"></li>--}}

                {{--<li>--}}
                    {{--<a href="#" data-toggle="collapse" data-target="#contents">--}}
                        {{--<i class="fa fa-list-alt"></i> {{ trans('template.back.header.contents') }}--}}
                        {{--<i class="fa fa-fw fa-caret-down"></i>--}}
                    {{--</a>--}}
                    {{--<ul id="contents" class="collapse">--}}
                        {{--@if(\Sentinel::getUser()->hasAccess('home.update'))--}}
                            {{--<li>--}}
                                {{--<a href="{{ route('home.edit') }}"><i class="fa fa-home"></i> {{ trans('template.back.header.home') }}</a>--}}
                            {{--</li>--}}
                        {{--@endif--}}
                        {{--@if(\Sentinel::getUser()->hasAccess('partners.list'))--}}
                            {{--<li>--}}
                                {{--<a href="{{ route('partners.index') }}"><i class="fa fa-life-ring"></i> {{ trans('template.back.header.partners') }}</a>--}}
                            {{--</li>--}}
                        {{--@endif--}}
                    {{--</ul>--}}
                {{--</li>--}}

                {{--<li class="divider"></li>--}}

                {{--<li>--}}
                    {{--<a class="new_window" href="{{ route('home') }}"><i class="fa fa-home"></i> {{ trans('template.back.header.back') }}</a>--}}
                {{--</li>--}}
            </ul>
        </div>
    </nav>
</header>
