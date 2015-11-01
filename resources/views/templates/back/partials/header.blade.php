<header class="row">
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">
                <h1>
                    {{ config('app.name') }}
                </h1>
            </a>
        </div>
        <ul class="nav navbar-right top-nav">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i>
                    {{ \Sentinel::getUser()->first_name }} {{ \Sentinel::getUser()->last_name }}
                    <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li>
                        <a href="{{ route('account') }}"><i class="fa fa-user fa-fw"></i>
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
                            <i class="fa fa-power-off"></i>
                            Déconnexion
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav side-nav">
                <li class="active">
                    <a href="{{ route('back.dashboard') }}"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                </li>
                <li>
                    <a href="charts.html"><i class="fa fa-fw fa-bar-chart-o"></i> Charts</a>
                </li>
                <li>
                    <a href="tables.html"><i class="fa fa-fw fa-table"></i> Tables</a>
                </li>
                <li>
                    <a href="forms.html"><i class="fa fa-fw fa-edit"></i> Forms</a>
                </li>
                <li>
                    <a href="bootstrap-elements.html"><i class="fa fa-fw fa-desktop"></i> Bootstrap Elements</a>
                </li>
                <li>
                    <a href="bootstrap-grid.html"><i class="fa fa-fw fa-wrench"></i> Bootstrap Grid</a>
                </li>
                <li>
                    <a href="#" data-toggle="collapse" data-target="#demo">
                        <i class="fa fa-fw fa-arrows-v"></i>
                        Dropdown
                        <i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="demo" class="collapse">
                        <li>
                            <a href="#">Dropdown Item</a>
                        </li>
                        <li>
                            <a href="#">Dropdown Item</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="blank-page.html"><i class="fa fa-fw fa-file"></i> Blank Page</a>
                </li>
                <li>
                    <a href="index-rtl.html"><i class="fa fa-fw fa-dashboard"></i> RTL Dashboard</a>
                </li>
            </ul>
        </div>
    </nav>
</header>
