@extends('templates.back.full_layout')

@section('content')

    <div id="content" class="schedule edit">

        <div class="text-content">

            <div class="col-sm-12">

                {{-- Title--}}
                <h2>
                    <i class="fa fa-clock-o"></i>
                    @if(isset($schedule))
                        {!! trans('schedules.page.title.edit', ['schedule' => $schedule->label]) !!}
                    @else
                        {{ trans('schedules.page.title.create') }}
                    @endif
                </h2>

                <hr>

                <form role="form" method="POST" action="@if(isset($schedule)){{ route('schedules.update', ['id' => $schedule->id]) }} @else{{ route('schedules.store') }} @endif" enctype="multipart/form-data">

                    {{-- crsf token --}}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    {{-- add update inputs if we are in update mode --}}
                    @if(isset($schedule))
                        <input type="hidden" name="_method" value="PUT">
                    @endif

                    {{-- schedule data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('schedules.page.title.data') }}</h3>
                        </div>
                        <div class="panel-body">

                            {{-- day --}}
                            <label for="input_day" class="required">{{ trans('schedules.page.label.day_id') }}</label>
                            <div class="form-group">
                                <select class="form-control" name="day_id" id="input_day_id">
                                    <option value="" disabled selected>{{ trans('schedules.page.label.day_placeholder') }}</option>
                                    @foreach($days as $id => $day)
                                        <option value="{{ $id }}"
                                                @if(!isset($schedule) && old('day_id') == $id)selected
                                                @elseif(isset($schedule) && isset($schedule->day_id) && $schedule->day_id === $id)selected
                                                @endif>
                                            {{ trans('schedules.config.day_of_week.' . $day) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- time_start --}}
                            <label for="input_time_start" class="required">{{ trans('schedules.page.label.time_start') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_time_start"><i class="fa fa-play"></i></span>
                                    <input id="input_time_start" class="form-control timepicker" type="text" name="time_start" value="{{ old('time_start') ? old('time_start') : (isset($schedule) && $schedule->time_start ? $schedule->time_start : null) }}" placeholder="{{ trans('schedules.page.label.time_start') }}">
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('global.info.time.format') }}</p>
                            </div>

                            {{-- time_stop --}}
                            <label for="input_time_stop" class="required">{{ trans('schedules.page.label.time_stop') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_time_stop"><i class="fa fa-stop"></i></span>
                                    <input id="input_time_stop" class="form-control timepicker" type="text" name="time_stop" value="{{ old('time_stop') ? old('time_stop') : (isset($schedule) && $schedule->time_stop ? $schedule->time_stop : null) }}" placeholder="{{ trans('schedules.page.label.time_stop') }}">
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('global.info.time.format') }}</p>
                            </div>

                            {{-- public category --}}
                            <label for="input_public_category" class="required">{{ trans('schedules.page.label.public_category') }}</label>
                            <div class="form-group">
                                <select class="form-control" name="public_category" id="input_public_category">
                                    <option value="" disabled selected>{{ trans('schedules.page.label.public_category_placeholder') }}</option>
                                    @foreach($public_categories as $id => $public_category)
                                        <option value="{{ $id }}"
                                                @if(!isset($schedule) && old('public_category') == $id)selected
                                                @elseif(isset($schedule) && isset($schedule->public_category) && $schedule->public_category === $id)selected
                                                @endif>
                                            {{ trans('schedules.config.category.' . $public_category) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>

                    {{-- release data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('schedules.page.title.release') }}</h3>
                        </div>
                        <div class="panel-body">

                            {{-- activation --}}
                            <label for="input_active">{{ trans('schedules.page.label.activation') }}</label>
                            <div class="form-group">
                                <div class="input-group swipe-group">
                                    <span class="input-group-addon" for="input_active"><i class="fa fa-power-off"></i></span>
                                <span class="form-control swipe-label" readonly="">
                                    {{ trans('schedules.page.label.activation_placeholder') }}
                                </span>
                                    <input class="swipe" id="input_active" type="checkbox" name="active"
                                           @if(old('active'))checked @elseif(isset($schedule) && $schedule->active)checked @endif>
                                    <label class="swipe-btn" for="input_active"></label>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- submit login --}}
                    @if(isset($schedule))
                        <button class="btn btn-primary spin-on-click" type="submit">
                            <i class="fa fa-pencil-square"></i> {{ trans('schedules.page.action.update') }}
                        </button>
                        <a href="{{ route('schedules.list') }}" class="btn btn-default spin-on-click" title="{{ trans('global.action.back') }}">
                            <i class="fa fa-undo"></i> {{ trans('global.action.back') }}
                        </a>
                    @else
                        <button class="btn btn-success spin-on-click" type="submit">
                            <i class="fa fa-plus-circle"></i> {{ trans('schedules.page.action.create') }}
                        </button>
                        <a href="{{ route('schedules.list') }}" class="btn btn-default spin-on-click" title="{{ trans('global.action.cancel') }}">
                            <i class="fa fa-ban"></i> {{ trans('global.action.cancel') }}
                        </a>
                    @endif
                </form>

            </div>
        </div>
    </div>

@endsection