@extends('templates.front.full_layout')

@section('content')

    <div id="content" class="schedule row">

        {{-- background image --}}
            <div class="parallax_img">
                @if($background_image)
                    <div class="background_responsive_img fill" data-background-image="{{ \ImageManager::imagePath(config('image.schedules.public_path'), $background_image, 'background_image') }}"></div>

                @endif
            </div>

        <div class="text-content">
            <div class="container">
                <h2>{{ $title }}</h2>
                <hr>
                <div class="schedules">

                    <div class="legend">
                            <div class="line"><b>{{ trans('schedules.page.label.legend') }} :</b></div>
                            @foreach(config('schedule.public_category') as $id => $category)
                                <div class="line">
                                    <div class="display-table">
                                        <div class="table-cell slot {{ $category }}"></div>
                                        <div class="table-cell name">{{ trans('schedules.config.category.' . $category) }}</div>
                                    </div>
                                </div>
                            @endforeach
                    </div>

                    <div class="schedule-grid display-table">
                            <div class="table-cell day">
                                <table class="table table-condensed table-striped">
                                    <tr>
                                        <td class="slot">&nbsp;</td>
                                    </tr>
                                    @foreach($hours as $hour)
                                        <tr>
                                            <td class="slot hour">
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
                                        <td colspan="{{ empty($columns[$day]) ? 1 : sizeof($columns[$day]) }}" class="slot">
                                            <span class="day">{{ trans('schedules.config.day_of_week.' . $day) }}</span>
                                        </td>
                                    </tr>
                                    @foreach($hours as $hour)
                                        <tr>
                                            @if(!empty($columns[$day]))
                                                @foreach($columns[$day] as $public_category_id => $column)

                                                    @if(!empty($schedules[$hour][$day][$public_category_id]))
                                                        <td class="slot
                                                        {{ config('schedule.public_category.' . $schedules[$hour][$day][$public_category_id]['public_category_id']) }}
                                                        {{ $schedules[$hour][$day][$public_category_id]['status'] or ''}}">
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
                        {!! $description !!}
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection