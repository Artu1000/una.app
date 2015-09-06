@extends('layouts.front.full_layout')

@section('content')

    <style>

    </style>

    <div id="content" class="palmares row">

        {{-- parallax img --}}
        <div class="parallax_img">
            <div class="background_responsive_img fill" data-background-image=""></div>
        </div>

        <div class="text-content">
            <div class="container">

                <h2><i class="glyphicon fa fa-trophy"></i> Le palmarès du club Université Nantes Aviron (UNA)</h2>
                <hr>

                <ul class="timeline">

                    {{-- for each palmares category --}}
                    @foreach($palmares as $key => $p)
                        <li @if($key % 2 == 0) class="timeline-inverted" @endif>
                            <div class="timeline-badge {{ $p['category']['class'] }} hidden-xs">
                                <i class="fa fa-star"></i>
                            </div>
                            <div class="timeline-panel title-panel">
                                <div class="logo">
                                    <img width="40" src="{{ url('/').'/'.$p['category']['logo'] }}" alt="{{ $p['category']['title'] }}">
                                </div>
                                <div class="title">
                                    <h3>{{ $p['category']['title'] }}</h3>
                                </div>
                            </div>
                        </li>

                        {{-- for each event --}}
                        @foreach($p['events'] as $event)
                            <li @if($key % 2 == 0) class="timeline-inverted" @endif>
                                <div class="timeline-panel result-panel">
                                    <h4>{{ $event->location }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d', $event->date)->format('m/d/Y') }}</h4>

                                    <table class="table table-striped table-hover table-condensed table-responsive">
                                        <tbody>
                                            {{-- for each result --}}
                                            @foreach($event->results as $result)
                                                <tr>
                                                    <td>
                                                        @if($result->position <= 3)
                                                            <i class="fa fa-trophy
                                                            @if($result->position === 1)gold
                                                            @elseif($result->position === 2)silver
                                                            @elseif($result->position === 3)bronze
                                                            @endif"></i>
                                                        @endif
                                                    </td>
                                                    <td class="position">
                                                        {{ $result->position }}
                                                        @if($result->position === 1)er
                                                        @elseème
                                                        @endif
                                                    </td>
                                                    <td class="boat">{{ $result->boat }}</td>
                                                    <td>{{ $result->crew }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </li>
                        @endforeach
                    @endforeach

                </ul>
            </div>
        </div>
    </div>

@endsection