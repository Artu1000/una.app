@extends('layouts.front.full_layout')

@section('content')

    <div id="content" class="e-shop row">

        {{-- parallax img --}}
        <div class="parallax_img">
            {{--<div class="background_responsive_img fill" data-background-image="{{ $page->image }}"></div>--}}
        </div>

        <div class="text-content">
            <div class="container">
                <h2><i class="fa fa-shopping-cart"></i> La boutique en ligne du club Université Nantes Aviron (UNA)</h2>
                <hr>
                <table class="table table-striped table-hover">
                    <tr>
                        <th>Articles</th>
                        <th>Détail</th>
                        <th>Tailles disponibles</th>
                        <th>Prix</th>
                    </tr>
                    @foreach($articles as $article)
                        <tr>
                            <td>
                                <h3 style="margin:0;">{{ $article->title }}</h3>
                                <img width="120" height="120" src="" alt="">
                                <h4 style="margin:0;">{{ config('e-shop.order_type.' . $article->order_type_id . '.title') }}</h4>
                            </td>
                            <td>
                                {{ $article->description }}
                            </td>
                            <td>
                                {{ $article->available_sizes }}
                            </td>
                            <td>
                                {{ $article->price }}€
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>

@endsection