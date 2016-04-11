<div id="header_color_fill" class="row"></div>

<header>
    <!-- HEADER -->
    <nav class="navbar navbar-inverse" role="navigation">
        <div class="container">

            <div class="navbar-header">

                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <a class="navbar-brand" title="Retour à l'accueil" href="{{ route('home') }}">
                    <span class="logo">
                        @if(config('settings.logo_light'))
                            <img width="70" src="{{ \ImageManager::imagePath(config('image.settings.public_path'), config('image.settings.logo.name.light') . '.' . config('image.settings.logo.extension'), 'logo', 'header') }}" alt="Logo {{ config('settings.app_name_' . config('app.locale')) }}">
                        @endif
                    </span>
                    <h1 @if(\Route::current()->getName() === 'home') class="active" @endif>
                        <span>Université</span>
                        <span>Nantes Aviron</span>
                    </h1>
                </a>

            </div>

            <div id="navbar" class="navbar-collapse collapse">

                <ul class="nav navbar-nav">
                    <li class="menu_tab
                        @if(\Route::current()->getName() === 'news.index') active
                        @elseif(\Route::current()->getName() === 'news.show') active
                        @endif">
                        <a href="{{ route('news.index') }}" title="Actualités">
                            <i class="fa fa-paper-plane"></i> {{ trans('template.front.header.news') }}
                        </a>
                    </li>

                    <li class="dropdown
                            @if(\Request::path() === 'page/historique') active
                            @elseif(\Route::current()->getName() === 'front.leading_team') active
                            @elseif(\Route::current()->getName() === 'palmares.index') active
                            @elseif(\Route::current()->getName() === 'front.leading_team') active
                            @elseif(\Request::path() === 'page/statuts') active
                            @elseif(\Request::path() === 'page/reglement-interieur') active
                            @endif">
                        <a title="Le club" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fa fa-bookmark"></i> {{ trans('template.front.header.club') }}<span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="menu_tab">
                                <a href="{{ url('/') }}#una-club" title="{{ trans('template.front.header.presentation') }}">
                                    <i class="fa fa-comments"></i> {{ trans('template.front.header.presentation') }}
                                </a>
                            </li>
                            <li class="menu_tab @if(\Request::path() === 'page/historique') active @endif">
                                <a href="{{ route('page.show', 'historique') }}" title="{{ trans('template.front.header.history') }}">
                                    <i class="fa fa-university"></i> {{ trans('template.front.header.history') }}
                                </a>
                            </li>
                            {{--<li class="menu_tab @if(\Route::current()->getName() === 'palmares.index') active @endif">--}}
                                {{--<a href="{{ route('palmares.index') }}" title="{{ trans('template.front.header.palmares') }}">--}}
                                    {{--<i class="fa fa-trophy"></i> {{ trans('template.front.header.palmares') }}--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            <li class="divider"></li>
                            <li class="menu_tab @if(\Route::current()->getName() === 'front.leading_team') active @endif">
                                <a href="{{ route('front.leading_team') }}" title="{{ trans('template.front.header.leading_team') }}">
                                    <i class="fa fa-cogs"></i> {{ trans('template.front.header.leading_team') }}
                                </a>
                            </li>
                            <li class="menu_tab @if(\Request::path() === 'page/statuts') active @endif">
                                <a href="{{ route('page.show', 'statuts') }}" title="St{{ trans('template.front.header.statuses') }}">
                                    <i class="fa fa-compass"></i> {{ trans('template.front.header.statuses') }}
                                </a>
                            </li>
                            <li class="menu_tab @if(\Request::path() === 'page/reglement-interieur') active @endif">
                                <a href="{{ route('page.show', 'reglement-interieur') }}" title="{{ trans('template.front.header.rules') }}">
                                    <i class="fa fa-gavel"></i> {{ trans('template.front.header.rules') }}
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="dropdown
                            @if(\Route::current()->getName() === 'registration.index') active
                            @elseif(\Route::current()->getName() === 'schedules.index') active
                            @elseif(\Route::current()->getName() === 'calendar.index') active
                            @elseif(\Route::current()->getName() === 'e-shop.index') active
                            @endif">
                        <a title="Infos" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fa fa-anchor"></i> {{ trans('template.front.header.rowing') }}<span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="menu_tab @if(\Route::current()->getName() === 'registration.index') active @endif">
                                <a href="{{ route('registration.index') }}" title="Inscription">
                                    <i class="fa fa-sign-in"></i> {{ trans('template.front.header.registration') }}
                                </a>
                            </li>
                            <li class="menu_tab @if(\Route::current()->getName() === 'schedules.index') active @endif">
                                <a href="{{ route('schedules.index') }}" title="Horaires">
                                    <i class="fa fa-clock-o"></i> {{ trans('template.front.header.schedules') }}
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li class="menu_tab @if(\Route::current()->getName() === 'calendar.index') active @endif">
                                <a href="{{ route('calendar.index') }}" title="Calendrier">
                                    <i class="fa fa-calendar"></i> {{ trans('template.front.header.calendar') }}
                                </a>
                            </li>
                            {{--<li class="menu_tab @if(\Route::current()->getName() === 'e-shop.index') active @endif">--}}
                                {{--<a href="{{ route('e-shop.index') }}" title="Boutique">--}}
                                    {{--<i class="fa fa-shopping-cart"></i> {{ trans('template.front.header.e_shop') }}--}}
                                {{--</a>--}}
                            {{--</li>--}}
                        </ul>
                    </li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li class="menu_tab">
                        <a title="Mon compte" href="{{ route('dashboard.index') }}">
                            @if($user = Sentinel::check())
                                <span class="text-success"><i class="fa fa-user text-success"></i></span>
                            @else
                                <span class="text-danger"><i class="fa fa-user"></i></span>
                            @endif
                            {{ trans('template.front.header.my_account') }}
                        </a>
                    </li>
                    <li class="menu_tab">
                        <a title="Contact" href="#contact">
                            <i class="fa fa-pencil-square"></i> {{ trans('template.front.header.contact') }}
                        </a>
                    </li>
                </ul>

            </div><!--/.nav-collapse -->

        </div>
    </nav>
</header>

<div id="header_background" class="row"></div>