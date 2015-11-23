<header class="row">
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand new_window" href="{{ route('home') }}">
                <h1>
                    {{ config('settings.app_name') }}
                </h1>
            </a>
        </div>
        <ul class="nav navbar-right top-nav">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i>
                    <span class="hidden-xs">{{ \Sentinel::getUser()->first_name }} {{ \Sentinel::getUser()->last_name }}</span>
                    <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li>
                        <a href="{{ route('account.index') }}"><i class="fa fa-user fa-fw"></i>
                            Mon profil
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
                <li @if(\Route::current()->getName() === 'dashboard.index') class="active" @endif>
                    <a href="{{ route('dashboard.index') }}"><i class="fa fa-fw fa-dashboard"></i> {{ trans('template.back.header.dashboard') }}</a>
                </li>
                @if(\Sentinel::getUser()->hasAccess('settings.view'))
                    <li @if(\Route::current()->getName() === 'settings') class="active" @endif>
                        <a href="{{ route('settings.index') }}"><i class="fa fa-cogs"></i> {{ trans('template.back.header.settings') }}</a>
                    </li>
                @endif
                @if(\Sentinel::getUser()->hasAccess('permissions.list'))
                    <li @if(\Route::current()->getName() === 'permissions.index') class="active"
                        @elseif(\Route::current()->getName() === 'permissions.create')) class="active"
                        @elseif(\Route::current()->getName() === 'permissions.show')) class="active"
                        @endif>
                        <a href="{{ route('permissions.index') }}"><i class="fa fa-gavel"></i> {{ trans('template.back.header.permissions') }}</a>
                    </li>
                @endif
                @if(\Sentinel::getUser()->hasAccess('users.list'))
                    <li @if(\Route::current()->getName() === 'users.index') class="active"
                        @elseif(\Route::current()->getName() === 'users.create')) class="active"
                        @elseif(\Route::current()->getName() === 'users.show')) class="active"
                        @endif>
                        <a href="{{ route('users.index') }}"><i class="fa fa-users"></i> {{ trans('template.back.header.users') }}</a>
                    </li>
                @endif
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
                <li class="divider"></li>
                <li>
                    <a class="new_window" href="{{ route('home') }}"><i class="fa fa-home"></i> Retour au site</a>
                </li>
            </ul>
        </div>
    </nav>
</header>
