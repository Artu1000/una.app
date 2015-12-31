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
                            <img width="70" src="{{ route('image', ['filename' => config('settings.logo_light'), 'storage_path' => storage_path('app/config'), 'size' => 'header']) }}" alt="Logo {{ config('settings.app_name_' . config('app.locale')) }}">
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
                        @if(\Route::current()->getName() === 'front.news') active
                        @elseif(\Route::current()->getName() === 'front.news.show') active
                        @endif">
                        <a href="{{ URL::route('front.news') }}" title="Actualités">
                            <i class="fa fa-paper-plane"></i> Actus
                        </a>
                    </li>

                    <li class="dropdown
                            @if(\Request::path() === 'page/historique') active
                            @elseif(\Route::current()->getName() === 'front.leading_team') active
                            @elseif(\Route::current()->getName() === 'front.palmares') active
                            @elseif(\Route::current()->getName() === 'front.leading_team') active
                            @elseif(\Request::path() === 'page/statuts') active
                            @elseif(\Request::path() === 'page/reglement-interieur') active
                            @endif">
                        <a title="Le club" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fa fa-bookmark"></i> Le club<span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="menu_tab">
                                <a href="{{ url('/') }}#una-club" title="Présentation">
                                    <i class="fa fa-comments"></i> Présentation
                                </a>
                            </li>
                            <li class="menu_tab @if(\Request::path() === 'page/historique') active @endif">
                                <a href="{{ route('front.page', 'historique') }}" title="Historique">
                                    <i class="fa fa-university"></i> Historique
                                </a>
                            </li>
                            <li class="menu_tab @if(\Route::current()->getName() === 'front.palmares') active @endif">
                                <a href="{{ route('front.palmares') }}" title="Palmarès">
                                    <i class="fa fa-trophy"></i> Palmarès
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li class="menu_tab @if(\Route::current()->getName() === 'front.leading_team') active @endif">
                                <a href="{{ route('front.leading_team') }}" title="Equipe dirigeante">
                                    <i class="fa fa-cogs"></i> Equipe dirigeante
                                </a>
                            </li>
                            <li class="menu_tab @if(\Request::path() === 'page/statuts') active @endif">
                                <a href="{{ route('front.page', 'statuts') }}" title="Statuts">
                                    <i class="fa fa-compass"></i> Statuts
                                </a>
                            </li>
                            <li class="menu_tab @if(\Request::path() === 'page/reglement-interieur') active @endif">
                                <a href="{{ route('front.page', 'reglement-interieur') }}" title="Règlement intérieur">
                                    <i class="fa fa-gavel"></i> Règlement intérieur
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="dropdown
                            @if(\Route::current()->getName() === 'front.registration') active
                            @elseif(\Route::current()->getName() === 'front.schedule') active
                            @elseif(\Route::current()->getName() === 'front.calendar') active
                            @elseif(\Route::current()->getName() === 'front.e-shop') active
                            @endif">
                        <a title="Infos" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fa fa-anchor"></i> Aviron<span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="menu_tab @if(\Route::current()->getName() === 'front.registration') active @endif">
                                <a href="{{ route('front.registration') }}" title="Inscription">
                                    <i class="fa fa-sign-in"></i> Inscription
                                </a>
                            </li>
                            <li class="menu_tab @if(\Route::current()->getName() === 'front.schedule') active @endif">
                                <a href="{{ route('front.schedule') }}" title="Horaires">
                                    <i class="fa fa-clock-o"></i> Horaires
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li class="menu_tab @if(\Route::current()->getName() === 'front.calendar') active @endif">
                                <a href="{{ route('front.calendar') }}" title="Calendrier">
                                    <i class="fa fa-calendar"></i> Calendrier
                                </a>
                            </li>
                            <li class="menu_tab @if(\Route::current()->getName() === 'front.e-shop') active @endif">
                                <a href="{{ route('front.e-shop') }}" title="Boutique">
                                    <i class="fa fa-shopping-cart"></i> Boutique
                                </a>
                            </li>
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
                            Mon compte
                        </a>
                    </li>
                    <li class="menu_tab">
                        <a title="Contact" href="#contact">
                            <i class="fa fa-pencil-square"></i> Contact
                        </a>
                    </li>
                </ul>

            </div><!--/.nav-collapse -->

        </div>
    </nav>
</header>

<div id="header_background" class="row"></div>