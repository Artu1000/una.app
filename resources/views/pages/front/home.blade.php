@extends('templates.front.full_layout')

@section('content')

    <div id="content" class="home row">

        {{-- slideshow --}}
        <div id="carousel" class="glide">
            <div class="glide__arrows">
                <span class="glide__arrow prev" data-glide-dir="<">prev</span>
                <span class="glide__arrow next" data-glide-dir=">">next</span>
            </div>
            <div class="glide__wrapper">
                <ul class="glide__track">

                    @foreach($slides as $key => $slide)
                        <li class="glide__slide fill">
                            <section class="fill">
                                <div class="fill slide_content text-center">
                                    <div class="fill">
                                        <div class="fill">
                                            @if($slide->picto)
                                                <img class="picto" src="{{ route('image', ['filename' => $slide->picto, 'storage_path' => $slide->storagePath(), 'size' => 'picto']) }}" alt="{{ $slide->title }}">
                                            @endif
                                            @if($slide->title)
                                                <h2 class="title">{{ $slide->title }}</h2>
                                            @endif
                                            @if($slide->quote)
                                                <p class="quote" class="quote">{!! $slide->quote !!}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if($key === 1)
                                    <div class="background_responsive_img fill" data-background-image="{{ $slide->background_image }}" style="background-image: url({{ $slide->background_image }})"></div>
                                @endif
                                <div class="background_responsive_img fill" data-background-image="{{ $slide->background_image }}"></div>
                            </section>
                        </li>
                    @endforeach
                </ul>
            </div>
            <ul class="glide__bullets"></ul>
        </div>

        <div id="last_news" class="text-content">
            <div class="container">
                <h2><i class="fa fa-paper-plane"></i> Dernières nouvelles</h2>
                <hr>
                <table class="table table-striped table-hover">
                    <tbody>
                        @foreach($last_news as $key => $news)
                            <tr class="news">
                                <td class="img hidden-xs">
                                    <a class="btn btn-default" href="{{ route('front.news.show', $news->key) }}" role="button" title="{{ $news->title }}">
                                        <img width="150" height="150" src="{{ url('/') . '/' . $news->image }}" alt="{{ $news->title }}">
                                    </a>
                                </td>
                                <td class="content">
                                    <h3>
                                        <a href="{{ route('front.news.show', $news->key) }}" title="{{ $news->title }}"><i class="fa fa-newspaper-o"></i> {{ $news->title }}</a>
                                    </h3>
                                    <div class="date">
                                        <i class="fa fa-clock-o"></i> {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $news->released_at)->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="category {{ \config('news.categories.' . $news->category_id . '.key') }}" >
                                        <i class="fa fa-cube"></i> {{ \config('news.categories.' . $news->category_id . '.title') }}
                                    </div>
                                    <div class="comments">
                                        <i class="fa fa-comments"></i> <a href="{{ route('front.news.show', $news->key) }}#disqus_thread" title="Commentaires"></a>
                                    </div>
                                    <div class="sum_up">
                                        {{ str_limit(strip_tags($news->content), 250) }}
                                    </div>
                                </td>
                                <td class="button hidden-xs">
                                    <a href="{{ route('front.news.show', $news->key) }}" title="{{ $news->title }}">
                                        <button class="btn btn-default" role="button">
                                            <i class="fa fa-chevron-circle-right"></i>
                                        </button>
                                    </a>
                                </td>
                            </tr>
                            <tr class="news visible-xs">
                                <td class="button mobile">
                                    <a href="{{ route('front.news.show', $news->key) }}" title="{{ $news->title }}">
                                        <button class="btn" role="button">
                                            <i class="fa fa-chevron-circle-right"></i> Lire plus
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div id="una-club" class="text-content">
            <div class="container">
                @if($title)
                    <h2><i class="fa fa-hand-spock-o"></i> {{ $title }}</h2>
                    <hr>
                @endif

                @if($description)
                    {!! $description !!}
                @endif

                    {{--Créé en 1985, le club Université Nantes Aviron (UNA) est **LE club d'aviron des étudiants nantais**.--}}

                    {{--Conventionné avec plusieurs écoles supérieures nantaises, l'UNA est lié à l'Université de Nantes et gère l'activité aviron au sein de la structure, en complément des autres activités sportives proposées par le SUAPS (Service Universitaire des Activités Physiques et Sportives).--}}
                    {{--Basé sur les rives de l'Erdre à Nantes, à proximité du pont de la Tortière et de la Faculté de Sciences, l'UNA est aujourd'hui **le plus grand club d'aviron universitaire de France**, avec près de 600 licenciés chaque année.--}}

                    {{--Orienté vers la compétition, le club est toutefois ouvert à toutes les formes de pratiques et donne aussi la possibilité de suivre des formations spécifiques à l'encadrement de l'aviron.--}}
                    {{--Outre son public d'étudiants, l'UNA favorise également l'encadrement pour un public de jeunes collegiens et de lycéens, au sein de l'Ecole d'Aviron (- de 18 ans). Le club accueille parallèlement un public de seniors loisir, pratiquant l'aviron dans un objectif plus récréatif.--}}

                    {{--A l'UNA, il est **possible de s'entrainer sans limitation de nombre de séances par semaine**. La pratique de l'aviron vous est proposée toute l'année, vacances incluses. Pour cela, l'association met à la disposition de ses membres, un parc à bateaux recensant plus de 150 coques de toutes catégories, mais aussi une salle d'ergomètres (machines à ramer) et une salle de musculation pour les compétiteurs.--}}
                    {{--Etant affilié à la Fédération Française d'Aviron (FFA), l'association donne la possibilité, en plus des activités proposées par la FFSU (Fédération Française des Sports Universitaires), de participer à toutes les activités civiles de la Fédération au travers de la licence fédérale incluse dans la cotisation. C'est ainsi que le club participe chaque année à des compétitions de tous niveaux en France et à l'étranger, aussi bien dans l'aviron fédéral que face aux autres universités et établissements de l'enseignement supérieur. Il est également l'organisateur depuis 1985 des <a href="http://regataiades.fr/" title="Regataïades Internationales de Nantes">Regataïades Internationales de Nantes</a>, reconnues comme **la plus importante régate internationale d'aviron universitaire en France**.--}}

                    {{--Club d'aviron universitaire à l'ambiance sportive et chaleureuse, l'UNA se base sur le modèle de ses confrères britanniques et americains pour contribuer au **développement du sport majestueux de glisse, de vitesse et d'endurance de force qu'est l'aviron**, auprès de la population étudiante française.--}}


                    {{--<span class="text-info"><i class="fa fa-exclamation-circle"></i> A noter :</span>--}}
                    {{--1. Pour les universitaires, l'inscription à l'aviron doit s'effectuer <b>directement au club Université Nantes Aviron (UNA)</b> pour bénéficier de l'ensemble des créneaux d'encadrement (**ne pas s'inscrire via le SUAPS**).--}}
                    {{--2. Des **tarifs préférenciels sont proposés pour tous les étudiants nantais**, sur présentation de justificatif. Des réductions plus avantageuses sont appliqués pour les membres de l'Université de Nantes (étudiants et salariés) ou d'écoles conventionnées avec l'UNA.--}}

                @if($video_link)
                <hr>

                <div class="video text-center display-table">
                    <a href="{{ $video_link }}" class="table-cell" title="Vidéo promotionnelle {{ config('settings.app_name') }}" data-lity>
                        <i class="fa fa-youtube-square"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script type="text/javascript">
        /* * * CONFIGURATION VARIABLES * * */
        var disqus_shortname = 'una-club';

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function () {
            var s = document.createElement('script'); s.async = true;
            s.type = 'text/javascript';
            s.src = '//' + disqus_shortname + '.disqus.com/count.js';
            (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
        }());
    </script>

@endsection