@extends('layouts.front.full_layout')

@section('content')

    <div id="content" class="news-list row">

        {{-- parallax img --}}
        <div class="parallax_img">
            {{--<div class="background_responsive_img fill" data-background-image="{{ url('/') }}/img/news/una-news-list.jpg"></div>--}}
        </div>

        <div class="text-content">
            <div class="container">

                <h2><i class="fa fa-paper-plane"></i> Les actualités du club Université Nantes Aviron (UNA)</h2>

                <hr>

                <div class="categories">
                    <i class="fa fa-cubes"></i> Trier par catégorie :
                    @foreach(config('news.categories') as $id => $cat)
                        <a class="{{ $cat['key'] }}
                            @if($current_category == $id)
                                selected
                            @endif"
                           href="{{ route('front.news', ['category' => $id]) }}"
                           title="{{ $cat['title'] }}">{{ $cat['title'] }}</a>
                    @endforeach
                    @if($current_category)<a href="{{ route('front.news') }}" title="Tout afficher">Tout afficher</a>
                    @endif
                </div>

                <table class="table table-striped table-hover">
                    <tbody>
                        @foreach($news_list as $news)
                            <tr class="news">
                                <td class="img hidden-xs">
                                    <a class="btn btn-default" href="{{ route('front.news.detail', $news->id) }}" role="button" title="{{ $news->title }}">
                                        <img width="150" height="150" src="{{ url('/') . '/' . $news->image }}" alt="{{ $news->title }}">
                                    </a>
                                </td>
                                <td class="content">
                                    <h3>
                                        <a href="{{ route('front.news.detail', $news->key) }}" title="{{ $news->title }}"><i class="fa fa-newspaper-o"></i> {{ $news->title }}</a>
                                    </h3>
                                    <div class="date">
                                        <i class="fa fa-clock-o"></i> {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $news->released_at)->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="category {{ \config('news.categories.' . $news->category_id . '.key') }}" >
                                        <i class="fa fa-cube"></i> {{ \config('news.categories.' . $news->category_id . '.title') }}
                                    </div>
                                    <div class="comments">
                                        <i class="fa fa-comments"></i> <a href="{{ route('front.news.detail', $news->key) }}#disqus_thread" title="Commentaires"></a>
                                    </div>
                                    <div class="sum_up">
                                        {{ str_limit(strip_tags($news->content), 250) }}
                                    </div>
                                    <div class="button mobile visible-xs">
                                        <a href="{{ route('front.news.detail', $news->key) }}" title="{{ $news->title }}">
                                            <button class="btn btn-lg btn-default btn-block" role="button">
                                                <i class="fa fa-chevron-circle-right"></i> Lire plus
                                            </button>
                                        </a>
                                    </div>
                                </td>
                                <td class="button hidden-xs">
                                    <a href="{{ route('front.news.detail', $news->key) }}" title="{{ $news->title }}">
                                        <button class="btn btn-default" role="button">
                                            <i class="fa fa-chevron-circle-right"></i>
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="pagination-container">
                            <td colspan="3" class="text-right">
                                {!! $news_list->render() !!}
                            </td>
                        </tr>
                    </tfoot>
                </table>
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