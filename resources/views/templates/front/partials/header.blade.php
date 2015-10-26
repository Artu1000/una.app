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
                        <img width="70" height="66" src="{{ config('app.logo.small.light') }}" alt="Logo Club Université Nantes Aviron (UNA)">
                    </span>
                    <h1>
                        <span>Université</span>
                        <span>Nantes Aviron</span>
                    </h1>
                </a>

            </div>

            <div id="navbar" class="navbar-collapse collapse">

                <ul class="nav navbar-nav">
                    <li class="menu_tab">
                        <a href="{{ URL::route('front.news') }}" title="Actualités">
                            <i class="fa fa-paper-plane"></i> Actus
                        </a>
                    </li>

                    <li class="dropdown">
                        <a title="Le club" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fa fa-bookmark"></i> Le club<span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="menu_tab">
                                <a href="{{ url('/') }}#una-club" title="Présentation">
                                    <i class="fa fa-comments"></i> Présentation
                                </a>
                            </li>
                            <li class="menu_tab">
                                <a href="{{ route('front.page', 'historique') }}" title="Historique">
                                    <i class="fa fa-university"></i> Historique
                                </a>
                            </li>
                            <li class="menu_tab">
                                <a href="{{ route('front.palmares') }}" title="Palmarès">
                                    <i class="fa fa-trophy"></i> Palmarès
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li class="menu_tab">
                                <a href="{{ route('front.leading_team') }}" title="Equipe dirigeante">
                                    <i class="fa fa-cogs"></i> Equipe dirigeante
                                </a>
                            </li>
                            <li class="menu_tab">
                                <a href="{{ route('front.page', 'statuts') }}" title="Statuts">
                                    <i class="fa fa-compass"></i> Statuts
                                </a>
                            </li>
                            <li class="menu_tab">
                                <a href="{{ route('front.page', 'reglement-interieur') }}" title="Règlement intérieur">
                                    <i class="fa fa-gavel"></i> Règlement intérieur
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a title="Infos" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fa fa-info-circle"></i> Aviron<span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="menu_tab">
                                <a href="{{ route('front.registration') }}" title="Inscription">
                                    <i class="fa fa-sign-in"></i> Inscription
                                </a>
                            </li>
                            <li class="menu_tab">
                                <a href="{{ route('front.schedule') }}" title="Horaires">
                                    <i class="fa fa-clock-o"></i> Horaires
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li class="menu_tab">
                                <a href="{{ route('front.calendar') }}" title="Calendrier">
                                    <i class="fa fa-calendar"></i> Calendrier
                                </a>
                            </li>
                            <li class="menu_tab">
                                <a href="{{ route('front.e-shop') }}" title="Boutique">
                                    <i class="fa fa-shopping-cart"></i> Boutique
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li class="menu_tab">
                        <a title="Mon compte" href="{{ route('back.account') }}">
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