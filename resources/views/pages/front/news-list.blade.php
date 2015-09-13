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
                                        <a href="{{ route('front.news.detail', $news->id) }}" title="{{ $news->title }}">{{ $news->title }}</a>
                                    </h3>
                                    <div class="date">
                                        {{ Carbon\Carbon::createFromFormat('Y-m-d', $news->released_at)->format('d/m/Y') }}
                                    </div>
                                    <div class="sum_up">
                                        {{ str_limit(strip_tags($news->content), 250) }}
                                    </div>
                                    <div class="button mobile visible-xs">
                                        <a href="{{ route('front.news.detail', $news->id) }}" title="{{ $news->title }}">
                                            <button class="btn btn-lg btn-default btn-block" role="button">
                                                <i class="fa fa-chevron-circle-right"></i> Lire plus
                                            </button>
                                        </a>
                                    </div>
                                </td>
                                <td class="button hidden-xs">
                                    <a href="{{ route('front.news.detail', $news->id) }}" title="{{ $news->title }}">
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

@endsection