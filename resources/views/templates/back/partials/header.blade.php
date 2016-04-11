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

        {{-- top menu --}}
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
                                <a class="spin-on-click" rel="alternate" hreflang="{{$localeCode}}" href="{{ LaravelLocalization::getLocalizedURL($localeCode) }}">
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
            <li class="dropdown @if(\Route::current()->getName() === 'users.profile')active open @endif">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i>
                    <span class="hidden-xs">{{ \Sentinel::getUser()->first_name }} {{ \Sentinel::getUser()->last_name }}</span>
                    <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu">
                    <li class="@if(\Route::current()->getName() === 'users.profile')active @endif">
                        <a class="spin-on-click" href="{{ route('users.profile') }}"><i class="fa fa-user fa-fw"></i>
                            {{ trans('template.back.header.my_profile') }}
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a class="spin-on-click" href="{{ route('logout') }}">
                            <i class="fa fa-power-off"></i>
                            {{ trans('template.back.header.logout') }}
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

        {{-- lateral menu --}}
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav side-nav">

                {{-- dashboard --}}
                <li class="@if(Route::current()->getName() === 'dashboard.index')active @endif">
                    <a href="{{ route('dashboard.index') }}"><i class="fa fa-fw fa-dashboard"></i> {{ trans('template.back.header.dashboard') }}</a>
                </li>

                {{-- admin pannel --}}
                @if(Sentinel::getUser()->hasAccess('settings.view')
                || Sentinel::getUser()->hasAccess('permissions.list')
                || Sentinel::getUser()->hasAccess('users.list'))

                    <li class="@if(Route::current()->getName() === 'settings.index'
                    || Route::current()->getName() === 'permissions.index'
                    || Route::current()->getName() === 'permissions.create'
                    || Route::current()->getName() === 'permissions.edit'
                    || Route::current()->getName() === 'users.index'
                    || Route::current()->getName() === 'users.create'
                    || Route::current()->getName() === 'users.edit')active @endif">
                        <a href="#" data-toggle="collapse" data-target="#admin">
                            <i class="fa fa-cogs"></i> {{ trans('template.back.header.admin') }}
                            <i class="fa fa-fw fa-caret-down"></i>
                        </a>
                        <ul id="admin" class="collapse @if(Route::current()->getName() === 'settings.index'
                        || Route::current()->getName() === 'permissions.index'
                        || Route::current()->getName() === 'permissions.create'
                        || Route::current()->getName() === 'permissions.edit'
                        || Route::current()->getName() === 'users.index'
                        || Route::current()->getName() === 'users.create'
                        || Route::current()->getName() === 'users.edit')in @endif">

                            {{-- settings --}}
                            @if(Sentinel::getUser()->hasAccess('settings.view'))
                                <li class="@if(Route::current()->getName() === 'settings.index')active @endif">
                                    <a class="spin-on-click" href="{{ route('settings.index') }}"><i class="fa fa-wrench"></i> {{ trans('template.back.header.settings') }}</a>
                                </li>
                            @endif

                            {{-- permissions --}}
                            @if(Sentinel::getUser()->hasAccess('permissions.list'))
                                <li class="@if(\Route::current()->getName() === 'permissions.index'
                                    || Route::current()->getName() === 'permissions.create'
                                    || Route::current()->getName() === 'permissions.edit')active @endif">
                                    <a class="spin-on-click" href="{{ route('permissions.index') }}"><i class="fa fa-gavel"></i> {{ trans('template.back.header.permissions') }}</a>
                                </li>
                            @endif

                            {{-- users --}}
                            @if(Sentinel::getUser()->hasAccess('users.list'))
                                <li class="@if(\Route::current()->getName() === 'users.index'
                                    || Route::current()->getName() === 'users.create'
                                    || Route::current()->getName() === 'users.edit')active @endif">
                                    <a class="spin-on-click" href="{{ route('users.index') }}"><i class="fa fa-users"></i> {{ trans('template.back.header.users') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>

                @endif

                <li class="divider"></li>

                {{-- contents pannel --}}
                @if(Sentinel::getUser()->hasAccess('home.view')
                || Sentinel::getUser()->hasAccess('news.list')
                || Sentinel::getUser()->hasAccess('schedules.list')
                || Sentinel::getUser()->hasAccess('registration.page.view')
                || Sentinel::getUser()->hasAccess('partners.list'))

                    <li class="@if(Route::current()->getName() === 'home.edit'
                    || Route::current()->getName() === 'slides.create'
                    || Route::current()->getName() === 'slides.edit'
                    || Route::current()->getName() === 'news.list'
                    || Route::current()->getName() === 'news.create'
                    || Route::current()->getName() === 'news.edit'
                    || Route::current()->getName() === 'schedules.list'
                    || Route::current()->getName() === 'schedules.create'
                    || Route::current()->getName() === 'schedules.edit'
                    || Route::current()->getName() === 'registration.page_edit'
                    || Route::current()->getName() === 'partners.index'
                    || Route::current()->getName() === 'partners.create'
                    || Route::current()->getName() === 'partners.edit')active @endif">
                        <a href="#" data-toggle="collapse" data-target="#contents">
                            <i class="fa fa-list-alt"></i> {{ trans('template.back.header.contents') }}
                            <i class="fa fa-fw fa-caret-down"></i>
                        </a>
                        <ul id="contents" class="collapse @if(\Route::current()->getName() === 'home.edit'
                        || Route::current()->getName() === 'slides.create'
                        || Route::current()->getName() === 'slides.edit'
                        || Route::current()->getName() === 'news.list'
                        || Route::current()->getName() === 'news.create'
                        || Route::current()->getName() === 'news.edit'
                        || Route::current()->getName() === 'schedules.list'
                        || Route::current()->getName() === 'schedules.create'
                        || Route::current()->getName() === 'schedules.edit'
                        || Route::current()->getName() === 'partners.index'
                        || Route::current()->getName() === 'partners.create'
                        || Route::current()->getName() === 'partners.edit')in @endif">

                            {{-- home --}}
                            @if(Sentinel::getUser()->hasAccess('home.view'))
                                <li class="@if(\Route::current()->getName() === 'home.edit'
                                || Route::current()->getName() === 'slides.create'
                                || Route::current()->getName() === 'slides.edit')active @endif">
                                    <a class="spin-on-click" href="{{ route('home.edit') }}"><i class="fa fa-home"></i> {{ trans('template.back.header.home') }}</a>
                                </li>
                            @endif

                            {{-- news --}}
                            @if(Sentinel::getUser()->hasAccess('news.list'))
                                <li class="@if(\Route::current()->getName() === 'news.list'
                                || Route::current()->getName() === 'news.create'
                                || Route::current()->getName() === 'news.edit')active @endif">
                                    <a class="spin-on-click" href="{{ route('news.list') }}"><i class="fa fa-paper-plane"></i> {{ trans('template.back.header.news') }}</a>
                                </li>
                            @endif

                            {{-- schedules --}}
                            @if(Sentinel::getUser()->hasAccess('schedules.list'))
                                <li class="@if(Route::current()->getName() === 'schedules.list'
                                || Route::current()->getName() === 'schedules.create'
                                || Route::current()->getName() === 'schedules.edit')active @endif">
                                    <a class="spin-on-click" href="{{ route('schedules.list') }}"><i class="fa fa-clock-o"></i> {{ trans('template.back.header.schedules') }}</a>
                                </li>
                            @endif

                            {{-- registration --}}
                            @if(Sentinel::getUser()->hasAccess('registration.page.view'))
                                <li class="@if(
                                    Route::current()->getName() === 'registration.page_edit'
                                )active
                                @endif">
                                    <a class="spin-on-click" href="{{ route('registration.page_edit') }}"><i class="fa fa-sign-in"></i> {{ trans('template.back.header.registration') }}</a>
                                </li>
                            @endif

                            {{-- partners --}}
                            @if(Sentinel::getUser()->hasAccess('partners.list'))
                                <li class="@if(Route::current()->getName() === 'partners.index'
                                || Route::current()->getName() === 'partners.create'
                                || Route::current()->getName() === 'partners.edit')active @endif">
                                    <a class="spin-on-click" href="{{ route('partners.index') }}"><i class="fa fa-life-ring"></i> {{ trans('template.back.header.partners') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>

                @endif

                <li class="divider"></li>

                {{-- back to front--}}
                <li>
                    <a class="new_window" href="{{ route('home') }}"><i class="fa fa-home"></i> {{ trans('template.back.header.back') }}</a>
                </li>
            </ul>
        </div>
    </nav>
</header>
