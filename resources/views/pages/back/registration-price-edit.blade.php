@extends('templates.back.full_layout')

@section('content')

    <div id="content" class="price edit">

        <div class="text-content">

            <div class="col-sm-12">

                {{-- Title--}}
                <h2>
                    <i class="fa fa-user"></i>
                    @if(isset($price))
                        {!! trans('registration.page.title.price.edit', ['price' => $price->label]) !!}
                    @else
                        {{ trans('registration.page.title.price.create') }}
                    @endif
                </h2>

                <hr>

                <form role="form" method="POST" action="@if(isset($price)){{ route('registration.prices.update', ['id' => $price->id]) }} @else{{ route('registration.prices.store') }} @endif" enctype="multipart/form-data">

                    {{-- crsf token --}}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    {{-- add update inputs if we are in update mode --}}
                    @if(isset($price))
                        <input type="hidden" name="_method" value="PUT">
                    @endif

                    @include('templates.back.partials.form-legend.required')

                    {{-- personal data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('registration.page.title.price.data') }}</h3>
                        </div>
                        <div class="panel-body">

                            {{-- label --}}
                            <label for="input_label" class="required">{{ trans('registration.page.label.price.label') }}<span class="required">*</span></label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_label"><i class="fa fa-tag"></i></span>
                                    <input id="input_label" class="form-control capitalize-first-letter" type="text" name="label" value="{{ old('label') ? old('label') : (isset($price) && $price->label ? $price->label : null) }}" placeholder="{{ trans('registration.page.label.price.label') }}">
                                </div>
                            </div>

                            {{-- price --}}
                            <label for="input_price">{{ trans('registration.page.label.price.price') }}<span class="required">*</span></label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_price"><i class="fa fa-eur" aria-hidden="true"></i></span>
                                    <input id="input_price" class="form-control" type="number" step="0.1" name="price" value="{{  isset($price) && $price->price ? $price->price : null }}" placeholder="{{ trans('registration.page.label.price.price') }}">
                                </div>
                            </div>

                            {{-- activation --}}
                            <label for="input_active">{{ trans('registration.page.label.price.activation') }}</label>
                            <div class="form-group">
                                <div class="input-group swipe-group">
                                    <span class="input-group-addon" for="input_active"><i class="fa fa-power-off"></i></span>
                                <span class="form-control swipe-label" readonly="">
                                    {{ trans('partners.page.label.activation_placeholder') }}
                                </span>
                                    <input class="swipe" id="input_active" type="checkbox" name="active"
                                           @if(old('active'))checked
                                           @elseif(is_null(old('active')) && isset($price) && $price->active)checked
                                           @endif>
                                    <label class="swipe-btn" for="input_active"></label>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- submit login --}}
                    @if(isset($price))
                        <button class="btn btn-primary spin-on-click" type="submit">
                            <i class="fa fa-pencil-square"></i> {{ trans('registration.page.action.price.update') }}
                        </button>
                        <a href="{{ route('registration.page.edit') }}" class="btn btn-default spin-on-click" title="{{ trans('global.action.back') }}">
                            <i class="fa fa-undo"></i> {{ trans('global.action.back') }}
                        </a>
                    @else
                        <button class="btn btn-success spin-on-click" type="submit">
                            <i class="fa fa-plus-circle"></i> {{ trans('registration.page.action.price.create') }}
                        </button>
                        <a href="{{ route('registration.page.edit') }}" class="btn btn-default spin-on-click" title="{{ trans('global.action.cancel') }}">
                            <i class="fa fa-ban"></i> {{ trans('global.action.cancel') }}
                        </a>
                    @endif
                </form>

            </div>
        </div>
    </div>

@endsection