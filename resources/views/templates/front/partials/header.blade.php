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
                            <img width="70" src="{{ ImageManager::imagePath(config('image.settings.public_path'), config('settings.logo_light'), 'logo', 'header') }}" alt="Logo {{ config('settings.app_name_' . config('app.locale')) }}">
                        @endif
                    </span>
                    @if(Route::current()->getName() === 'home')
                        <h1 class="active">
                            <span>Université</span>
                            <span>Nantes Aviron</span>
                        </h1>
                    @else
                        <h2>
                            <span>Université</span>
                            <span>Nantes Aviron</span>
                        </h2>
                    @endif
                </a>

            </div>

            <div id="navbar" class="navbar-collapse collapse">

                <ul class="nav navbar-nav">
                    <li class="menu_tab
                        @if(Route::current()->getName() === 'news.index') active
                        @elseif(Route::current()->getName() === 'news.show') active
                        @endif">
                        <a href="{{ route('news.index') }}" title="Actualités">
                            <i class="fa fa-paper-plane"></i> {{ trans('template.front.header.news') }}
                        </a>
                    </li>

                    <li class="dropdown
                            @if(Request::path() === 'page/historique') active
                            @elseif(Route::current()->getName() === 'front.leading_team') active
                            @elseif(Route::current()->getName() === 'palmares.index') active
                            @elseif(Route::current()->getName() === 'front.leading_team') active
                            @elseif(Request::path() === 'page/statuts') active
                            @elseif(Request::path() === 'page/reglement-interieur') active
                            @endif">
                        <a title="Le club" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="icon icon-boathouse"></i> {{ trans('template.front.header.club') }}<span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="menu_tab @if(Route::current()->getName() === 'registration.index') active @endif">
                                <a href="{{ route('registration.index') }}" title="Inscription">
                                    {{ trans('template.front.header.registration') }}
                                </a>
                            </li>
                            <li class="menu_tab @if(Route::current()->getName() === 'schedules.index') active @endif">
                                <a href="{{ route('schedules.index') }}" title="Horaires">
                                    {{ trans('template.front.header.schedules') }}
                                </a>
                            </li>
                            <li class="menu_tab @if(Route::current()->getName() === 'calendar.index') active @endif">
                                <a href="{{ route('calendar.index') }}" title="Calendrier">
                                    {{ trans('template.front.header.calendar') }}
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li class="menu_tab @if(Route::current()->getName() === 'photos.index') active @endif">
                                <a href="{{ route('photos.index') }}" title="{{ trans('template.front.header.photos') }}">
                                    {{ trans('template.front.header.photos') }}
                                </a>
                            </li>
                            <li class="menu_tab @if(Route::current()->getName() === 'videos.index') active @endif">
                                <a href="{{ route('videos.index') }}" title="{{ trans('template.front.header.videos') }}">
                                    {{ trans('template.front.header.videos') }}
                                </a>
                            </li>
                            {{--<li class="menu_tab @if(\Route::current()->getName() === 'e-shop.index') active @endif">--}}
                                {{--<a href="{{ route('e-shop.index') }}" title="Boutique">--}}
                                    {{--<i class="fa fa-shopping-cart"></i> {{ trans('template.front.header.e_shop') }}--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            <li class="divider"></li>
                            <li class="menu_tab">
                                <a href="{{ url('/') }}#una-club" title="{{ trans('template.front.header.presentation') }}">
                                    {{ trans('template.front.header.presentation') }}
                                </a>
                            </li>
                            @if(isset($history) && $history instanceof App\Models\Page)
                                <li class="menu_tab @if(Request::path() === route('page.show', ['slug' => $history->slug])) active @endif">
                                    <a href="{{ route('page.show', ['slug' => $history->slug]) }}" title="{{ trans('template.front.header.history') }}">
                                      {{ trans('template.front.header.history') }}
                                    </a>
                                </li>
                            @endif
                            {{--<li class="menu_tab @if(\Route::current()->getName() === 'palmares.index') active @endif">--}}
                                {{--<a href="{{ route('palmares.index') }}" title="{{ trans('template.front.header.palmares') }}">--}}
                                    {{-- {{ trans('template.front.header.palmares') }}--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            <li class="divider"></li>
                            <li class="menu_tab @if(Route::current()->getName() === 'front.leading_team') active @endif">
                                <a href="{{ route('front.leading_team') }}" title="{{ trans('template.front.header.leading_team') }}">
                                  {{ trans('template.front.header.leading_team') }}
                                </a>
                            </li>
                            @if(isset($statuses) && $statuses instanceof App\Models\Page)
                                <li class="menu_tab @if(Request::path() === route('page.show', ['slug' => $statuses->slug])) active @endif">
                                    <a href="{{ route('page.show', ['slug' => $statuses->slug]) }}" title="{{ trans('template.front.header.history') }}">
                                        {{ trans('template.front.header.statuses') }}
                                    </a>
                                </li>
                            @endif
                            @if(isset($rules) && $rules instanceof App\Models\Page)
                                <li class="menu_tab @if(Request::path() === route('page.show', ['slug' => $rules->slug])) active @endif">
                                    <a href="{{ route('page.show', ['slug' => $history->slug]) }}" title="{{ trans('template.front.header.history') }}">
                                        {{ trans('template.front.header.rules') }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>

                    <li class="dropdown
                            @if(Route::current()->getName() === 'page/juniors') active
                            @elseif(Route::current()->getName() === 'page/university') active
                            @elseif(Route::current()->getName() === 'page/masters') active
                            @endif">
                        <a title="{{ trans('template.front.header.categories') }}" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="icon icon-rowing"></i> {{ trans('template.front.header.categories') }}<span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                          <li class="menu_tab @if(Request::path() === route('page.show', ['slug' => $juniors->slug])) active @endif">
                              <a href="{{ route('page.show', ['slug' => $juniors->slug]) }}#j10" title="{{ trans('template.front.header.j10') }}">
                                {{ trans('template.front.header.j10') }}
                              </a>
                          </li>
                          <li class="menu_tab ">
                              <a href="{{ route('page.show', ['slug' => $juniors->slug]) }}#j13" title="{{ trans('template.front.header.j13') }}">
                                  {{ trans('template.front.header.j13') }}
                              </a>
                          </li>
                          <li class="menu_tab ">
                              <a href="{{ route('page.show', ['slug' => $juniors->slug]) }}#j15" title="{{ trans('template.front.header.j15') }}">
                                  {{ trans('template.front.header.j15') }}
                              </a>
                          </li>
                          <li class="menu_tab">
                              <a href="{{ route('page.show', ['slug' => $juniors->slug]) }}#j17" title="{{ trans('template.front.header.j17') }}">
                                  {{ trans('template.front.header.j17') }}
                              </a>
                          </li>
                          <li class="divider"></li>
                          <li class="menu_tab @if(Request::path() === route('page.show', ['slug' => $university->slug])) active @endif">
                              <a href="{{ route('page.show', ['slug' => $university->slug]) }}" title="{{ trans('template.front.header.university') }}">
                                  {{ trans('template.front.header.university') }}
                              </a>
                          </li>
                          <li class="menu_tab @if(Request::path() === route('page.show', ['slug' => $masters->slug])) active @endif">
                              <a href="{{ route('page.show', ['slug' => $master->slug]) }}" title="{{ trans('template.front.header.masters') }}">
                                  {{ trans('template.front.header.masters') }}
                              </a>
                          </li>
                        </ul>
                    </li>
                    <li class="dropdown
                            @if(Route::current()->getName() === 'page/openday') active
                            @elseif(Route::current()->getName() === 'page/summercamp') active
                            @elseif(Route::current()->getName() === 'page/trainingcamp') active
                            @endif">
                        <a title="{{ trans('template.front.header.explore') }}" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="icon icon-new"></i> {{ trans('template.front.header.explore') }}<span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="menu_tab @if(Request::path() === route('page.show', ['slug' => $openday->slug])) active @endif">
                                <a href="{{ route('page.show', ['slug' => $openday->slug]) }}" title="{{ trans('template.front.header.openday') }}">
                                    {{ trans('template.front.header.openday') }}
                                </a>
                            </li>
                            <li class="menu_tab @if(Request::path() === route('page.show', ['slug' => $summercamp->slug])) active @endif">
                                <a href="{{ route('page.show', ['slug' => $summercamp->slug]) }}" title="{{ trans('template.front.header.summercamp') }}">
                                    {{ trans('template.front.header.summercamp') }}
                                </a>
                            </li>
                            <li class="menu_tab @if(Request::path() === route('page.show', ['slug' => $trainingcamp->slug])) active @endif">
                                <a href="{{ route('page.show', ['slug' => $trainingcamp->slug]) }}" title="{{ trans('template.front.header.trainingcamp') }}">
                                    {{ trans('template.front.header.trainingcamp') }}
                                </a>
                            </li>
                        </ul>
                    </li>


                    <li class="dropdown
                            @if(Route::current()->getName() === 'page/indoor_rowing') active
                            @elseif(Route::current()->getName() === 'page/crossfit') active
                            @endif">
                        <a title="{{ trans('template.front.header.indoor') }}" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="icon icon-indoor"></i> {{ trans('template.front.header.indoor') }}<span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="menu_tab @if(Request::path() === route('page.show', ['slug' => $indoor_rowing->slug])) active @endif">
                                <a href="{{ route('page.show', ['slug' => $indoor_rowing->slug]) }}" title="{{ trans('template.front.header.indoor_rowing') }}">
                                    {{ trans('template.front.header.indoor_rowing') }}
                                </a>
                            </li>
                            <li class="menu_tab @if(Request::path() === route('page.show', ['slug' => $crossfit->slug])) active @endif">
                                <a href="{{ route('page.show', ['slug' => $crossfit->slug]) }}" title="{{ trans('template.front.header.crossfit') }}">
                                    {{ trans('template.front.header.crossfit') }}
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu_tab
                        @if(Route::current()->getName() === 'page/schools') active
                        @endif">
                        <a href="{{ route('page.show', ['slug' => $schools->slug]) }}" title="{{ trans('template.front.header.scholars') }}">
                            <i class="icon icon-school"></i> {{ trans('template.front.header.schools') }}
                        </a>
                    </li>

                    <li class="menu_tab
                        @if(Route::current()->getName() === 'page/corporate') active
                        @endif">
                        <a href="{{ route('page.show', ['slug' => $corporate->slug]) }}" title="{{ trans('template.front.header.corporate') }}">
                            <i class="icon icon-corporate"></i> {{ trans('template.front.header.corporate') }}
                        </a>
                    </li>

                    <li class="menu_tab
                        @if(Route::current()->getName() === 'page/custom') active
                        @endif">
                        <a href="{{ route('page.show', ['slug' => $custom->slug]) }}" title="{{ trans('template.front.header.custom') }}">
                            <i class="icon icon-custom"></i> {{ trans('template.front.header.custom') }}
                        </a>
                    </li>


                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li class="menu_tab">
                        <a title="Mon compte" href="{{ route('dashboard.index') }}" rel="nofollow">
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
