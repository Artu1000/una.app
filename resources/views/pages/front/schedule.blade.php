@extends('layouts.front.full_layout')

@section('content')

    <div id="content" class="schedule row">

        {{-- parallax img --}}
        <div class="parallax_img">
            {{--<div class="background_responsive_img fill" data-background-image="{{ $page->image }}"></div>--}}
        </div>

        <div class="text-content">
            <div class="container">
                <h2>Horaires et séances encadrées sur l'eau</h2>
                <hr>
                <div class="schedules">

                    <table class="legend">
                        <tr>
                            <td class="title">Légende : </td>
                            @foreach(config('schedule.public_category') as $id => $category)
                                <td>
                                    <table>
                                        <tr>
                                            <td class="slot {{ $category['key'] }}"></td>
                                            <td class="name">{{ $category['title'] }}</td>
                                        </tr>
                                    </table>
                                </td>
                            @endforeach
                        </tr>
                    </table>

                    <div class="schedule-grid display-table">
                            <div class="table-cell day">
                                <table class="table table-condensed table-striped">
                                    <tr>
                                        <td class="slot">&nbsp;</td>
                                    </tr>
                                    @foreach($hours as $hour)
                                        <tr>
                                            <td class="slot">
                                                {{ $hour }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        @foreach($days as $day)
                            <div class="table-cell day">
                                <table class="table table-condensed table-striped text-center">
                                    <tr>
                                        <td colspan="{{ empty($columns[$day['title']]) ? 1 : sizeof($columns[$day['title']]) }}" class="slot">{{ $day['title'] }}</td>
                                    </tr>
                                    @foreach($hours as $hour)
                                        <tr>
                                            @if(!empty($columns[$day['title']]))
                                                @foreach($columns[$day['title']] as $public_category_id => $column)

                                                    @if(!empty($schedules[$hour][$day['title']][$public_category_id]))
                                                        <td class="slot
                                                        {{ config('schedule.public_category.' . $schedules[$hour][$day['title']][$public_category_id]['public_category_id'] . '.key') }}
                                                        {{ $schedules[$hour][$day['title']][$public_category_id]['status'] or ''}}">
                                                        </td>
                                                    @else
                                                        <td class="slot"></td>
                                                    @endif

                                                @endforeach
                                            @else
                                                <td class="slot"></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        @endforeach
                    </div>

                    <div class="informations">
                        <ul>
                            <p class="text-info"><i class="fa fa-info-circle"></i> A noter :</p>
                            <li>Il est important d'<b>être en tenue et prêt à ramer</b> à lors du début d'un créneau d'encadrement, afin de profiter au maximum de l'amplitude horaire et de ne pas pénaliser ses équipiers.</li>
                            <li>La pratique en autonomie est <b>strictement interdite</b>, sauf dérogations nominatives délivrées par le Comité , approuvées par l'équipe d'encadrement.</li>
                            <li>Les horaires des séances d'encadrement <b>évoluent en cours d'année</b>, merci de visiter régulièrement cette page.</li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection