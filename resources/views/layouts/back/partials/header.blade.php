<header class="row">
    <nav class="navbar navbar-default navbar-static-top" role="navigation">
        <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">
                <h1>
                    {{ env('SITE_NAME') }}
                </h1>
            </a>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i>
                    <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li>
                        <a href="#"><i class="fa fa-user fa-fw"></i>
                            Mon profil
                        </a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-gear fa-fw"></i>
                            Préférences
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a id="login" href="{{ route('logout') }}">
                            <i class="fa fa-sign-in fa-fw"></i>
                            Déconnexion
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</header>
