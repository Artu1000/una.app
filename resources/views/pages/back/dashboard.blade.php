@extends('templates.back.full_layout')

@section('content')

    <div id="content" class="dashboard">

        <div class="text-content">

            <div class="col-sm-12">

                <h2>{{ trans('dashboard.welcome') }}</h2>

                <hr>

                @if(Sentinel::getUser()->hasAccess('dashboard.statistics'))
                    {{--stats title --}}
                    <h2>{{ trans('dashboard.statistics.title.main') }}</h2>

                    {{-- period picker --}}
                    <form id="period_form" role="form" method="GET" class="form-inline" action="{{ route('dashboard.stats') }}">
                        <h4>{{ trans('dashboard.statistics.pre_defined_periods.title') }}</h4>
                        <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('dashboard.statistics.info.period') }}</p>
                        <div class="form-group">
                            <select class="form-control" id="pre_defined_period">
                                <option value="" disabled="disabled">{{ trans('dashboard.statistics.pre_defined_periods.placeholder') }}</option>
                                @foreach($pre_formatted_periods as $key => $pre_formatted_period)
                                    <option value="{{ $key }}">{{ trans('dashboard.statistics.pre_defined_periods.' . $key) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <input id="start_date" class="datetimepicker form-control" name="period_start" type="text" value="{{ array_first($pre_formatted_periods)['start_date'] }}" placeholder="{{ trans('dashboard.pre_defined_periods.start') }}">
                        </div>
                        <div class="form-group">
                            <input id="end_date" class="datetimepicker form-control" name="period_stop" type="text" value="{{ array_first($pre_formatted_periods)['end_date'] }}" placeholder="{{ trans('dashboard.pre_defined_periods.stop') }}">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-bar-chart" aria-hidden="true"></i> {{ trans('dashboard.actions.validate_period') }}</button>
                        </div>
                    </form>

                    {{-- google analytics stats --}}
                    <h3 class="chart hidden">{{ trans('dashboard.statistics.title.google_analytics') }}</h3>
                    <div class="row">
                        {{-- visitors --}}
                        <div class="col-lg-12 chart hidden">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">{{ trans('dashboard.statistics.visitors.title') }}</h3>
                                </div>
                                <div id="visitors" class="graph">
                                    <div class="loader display-table">
                                        <div class="table-cell text-center">
                                            {!! config('settings.loading_spinner') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- top browsers --}}
                        <div class="col-lg-6 chart hidden">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">{{ trans('dashboard.statistics.top_browsers.title') }}</h3>
                                </div>
                                <div id="top_browsers" class="graph">
                                    <div class="loader display-table">
                                        <div class="table-cell text-center">
                                            {!! config('settings.loading_spinner') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- top referrers --}}
                        <div class="col-lg-6 chart hidden">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">{{ trans('dashboard.statistics.top_referrers.title') }}</h3>
                                </div>
                                <div id="top_referrers" class="graph">
                                    <div class="loader display-table">
                                        <div class="table-cell text-center">
                                            {!! config('settings.loading_spinner') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- most visited pages --}}
                        <div class="col-lg-12 chart hidden">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">{{ trans('dashboard.statistics.most_visited_pages.title') }}</h3>
                                </div>
                                <div id="most_visited_pages" class="graph padding">
                                    <div class="loader display-table">
                                        <div class="table-cell text-center">
                                            {!! config('settings.loading_spinner') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- other stats --}}
                    <h3 class="chart hidden">{{ trans('dashboard.statistics.title.other_stats') }}</h3>
                    <div class="row">
                        {{-- chart --}}
                        <div class="col-lg-12 chart hidden">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">{{ trans('dashboard.statistics.other_stats.title') }}</h3>
                                </div>
                                <div id="other_stats" class="graph">
                                    <div class="loader display-table">
                                        <div class="table-cell text-center">
                                            {!! config('settings.loading_spinner') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>

    </div>

@endsection