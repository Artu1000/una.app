@extends('layouts.front.full_layout')

@section('content')

    <div id="content" class="palmares row">

        {{-- parallax img --}}
        <div class="parallax_img">
            <div class="background_responsive_img fill" data-background-image="{{url()}}/img/palmares/una-palmares.jpg"></div>
        </div>

        <div class="text-content">
            <div class="container">

                <h2><i class="glyphicon fa fa-trophy"></i> Le palmarès du club Université Nantes Aviron (UNA)</h2>
                <hr>

                <ul class="timeline">

                    {{-- for each palmares category --}}
                    <?php $ii = 0; ?>
                    @foreach($palmares as $p)
                        @if(!empty($p['events']))
                            <li class="timeline-head @if($ii % 2 == 1)timeline-inverted @endif">
                                <div class="timeline-badge hidden-xs
                                @if($ii % 5 === 0 || $ii === 0) blue
                                @elseif($ii % 6 === 0 || $ii === 1) yellow
                                @elseif($ii % 7 === 0 || $ii === 2) black
                                @elseif($ii % 3 === 0) green
                                @elseif($ii % 4 === 0) red
                                {{--@elseif($key % 2 === 0 ) yellow--}}
                                @endif">
                                    <i class="fa fa-star"></i>
                                </div>
                                <div class="timeline-panel title-panel display-table">
                                    <div class="logo fill table-cell">
                                        <div class="text-center table-cell">
                                            <img width="40" src="{{ url('/').'/'.$p['category']['logo'] }}" alt="{{ $p['category']['title'] }}">
                                        </div>
                                    </div>
                                    <div class="title table-cell text-left">
                                        <h3>{{ $p['category']['title'] }}</h3>
                                    </div>
                                </div>
                            </li>

                            {{-- for each event --}}
                            @foreach($p['events'] as $event)
                                <li @if($ii % 2 == 1) class="timeline-inverted" @endif>
                                    <div class="timeline-panel result-panel">
                                        <h4>{{ $event->location }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d', $event->date)->format('m/d/Y') }}</h4>

                                        <table class="table table-striped table-hover table-condensed table-responsive">
                                            <tbody>
                                                {{-- for each result --}}
                                                @foreach($event->results as $result)
                                                    <tr>
                                                        <td>
                                                            @if($result->position <= 3)
                                                                <div class="medal text-center">
                                                                    <i class="fa fa-trophy
                                                                    @if($result->position === 1)gold
                                                                    @elseif($result->position === 2)silver
                                                                    @elseif($result->position === 3)bronze
                                                                    @endif"></i>
                                                                </div>
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
                            <?php $ii++; ?>
                        @endif
                    @endforeach

                </ul>
            </div>
        </div>
    </div>

@endsection