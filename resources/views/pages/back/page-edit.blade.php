@extends('templates.back.full_layout')

@section('content')

    <div id="content" class="user edit">

        <div class="text-content">

            <div class="col-sm-12">

                {{-- Title--}}
                <h2>
                    <i class="fa fa-user"></i>
                    @if(isset($page))
                        {!! trans('pages.page.title.edit', ['page' => $page->title]) !!}
                    @else
                        {{ trans('pages.page.title.create') }}
                    @endif
                </h2>

                <hr>

                <form role="form" method="POST" action="@if(isset($page)){{ route('pages.update', ['id' => $page->id]) }} @else{{ route('pages.store') }} @endif" enctype="multipart/form-data">

                    {{-- crsf token --}}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    {{-- if the app is multilingual--}}
                    @if(config('settings.multilingual'))
                        {{-- we include the form multilingual legend --}}
                        @include('templates.back.partials.form-legend.required-language')
                        {{-- we include the inputs language selector --}}
                        @include('templates.back.partials.inputs-language-selector')
                    @else
                        {{-- we include the simple form legend --}}
                        @include('templates.back.partials.form-legend.required')
                    @endif

                    {{-- add update inputs if we are in update mode --}}
                    @if(isset($page))
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="_id" value="{{ $page->id }}">
                    @endif

                    {{-- personal data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('pages.page.title.data') }}</h3>
                        </div>
                        <div class="panel-body">

                            {{-- image --}}
                            <label for="input_image">{{ trans('pages.page.label.image') }}</label>
                            @if(isset($page) && $page->image)
                                <div class="form-group image">
                                    <div class="form-group">
                                        <a class="img-thumbnail" href="{{ $page->imagePath($page->image, 'image', '767') }}" data-lity>
                                            <img src="{{ $page->imagePath($page->image, 'image', 'admin') }}" alt="{{ $page->title }}">
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-primary btn-file">
                                            <i class="fa fa-picture-o"></i> {{ trans('global.action.browse') }} <input type="file" name="image">
                                        </span>
                                    </span>
                                    <input id="input_image" type="text" class="form-control" readonly="">
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('pages.page.info.image') }}</p>
                            </div>

                            {{-- title --}}
                            <label for="input_title">{{ trans('pages.page.label.title') }}<span class="required">*</span></label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_title"><i class="fa fa-tag"></i></span>
                                    <input id="input_title" class="form-control capitalize-first-letter" type="text" name="title" value="{{ !empty(old('title' )) ? old('title') : (isset($page) && isset($page->title) ? $page->title : null) }}" placeholder="{{ trans('pages.page.label.title') }}">
                                </div>
                            </div>

                            @if(Sentinel::getUser()->hasAccess('pages.slug'))
                                {{-- slug --}}
                                <label for="input_slug">{{ trans('pages.page.label.slug') }}<span class="required">*</span></label>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon" for="input_slug"><i class="fa fa-key" aria-hidden="true"></i></span>
                                        <input id="input_slug" class="form-control" type="text" name="slug" value="{{ !empty(old('slug' )) ? old('slug') : (isset($page) && isset($page->slug) ? $page->slug : null) }}" placeholder="{{ trans('pages.page.label.slug') }}">
                                    </div>
                                </div>
                            @endif

                            {{-- content --}}
                            <label for="input_content">{{ trans('pages.page.label.content') }}</label>
                            <div class="form-group textarea">
                                <textarea id="input_content" class="form-control capitalize-first-letter markdown" name="content" placeholder="{{ trans('pages.page.label.content') }}">{{ old('content') ? old('content') : (isset($page) && $page->content ? $page->content : null) }}</textarea>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('pages.page.info.content') }}</p>
                            </div>

                        </div>
                    </div>

                    {{-- seo data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('pages.page.title.seo') }}</h3>
                        </div>
                        <div class="panel-body">

                            {{-- meta title --}}
                            <label for="input_meta_title">{{ trans('pages.page.label.meta_title') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_meta_title"><i class="fa fa-tag"></i></span>
                                    <input id="input_meta_title" class="form-control capitalize-first-letter" type="text" name="meta_title" value="{{ !empty(old('meta_title')) ? old('meta_title') : (isset($page) && isset($page->meta_title) ? $page->meta_title : null) }}" placeholder="{{ trans('pages.page.label.meta_title') }}">
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('pages.page.info.meta_title') }}</p>
                            </div>

                            {{-- meta description --}}
                            <label for="input_meta_description">{{ trans('pages.page.label.meta_description') }}</label>
                            <div class="form-group textarea">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_meta_description"><i class="fa fa-align-left" aria-hidden="true"></i></span>
                                    <textarea id="input_meta_description" class="form-control capitalize-first-letter" name="meta_description" placeholder="{{ trans('pages.page.label.meta_description') }}">{{ old('meta_description') ? old('meta_description') : (isset($page) && $page->meta_description ? $page->meta_description : null) }}</textarea>
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('pages.page.info.meta_description') }}</p>
                            </div>

                            {{-- meta keywords --}}
                            <label for="input_meta_keywords">{{ trans('pages.page.label.meta_keywords') }}</label>
                            <div class="form-group textarea">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_meta_keywords"><i class="fa fa-key" aria-hidden="true"></i></span>
                                    <textarea id="input_meta_keywords" class="form-control capitalize-first-letter" name="meta_keywords" placeholder="{{ trans('pages.page.label.meta_keywords') }}">{{ old('meta_keywords') ? old('meta_keywords') : (isset($page) && $page->meta_keywords ? $page->meta_keywords : null) }}</textarea>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- release data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('pages.page.title.release') }}</h3>
                        </div>
                        <div class="panel-body">

                            {{-- activation --}}
                            <label for="input_active">{{ trans('pages.page.label.activation') }}</label>
                            <div class="form-group">
                                <div class="input-group swipe-group">
                                    <span class="input-group-addon" for="input_active"><i class="fa fa-power-off"></i></span>
                                <span class="form-control swipe-label" readonly="">
                                    {{ trans('pages.page.label.activation_placeholder') }}
                                </span>
                                    <input class="swipe" id="input_active" type="checkbox" name="active"
                                           @if(old('active'))checked @elseif(isset($page) && $page->active)checked @endif>
                                    <label class="swipe-btn" for="input_active"></label>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- submit login --}}
                    @if(isset($page))
                        <button class="btn btn-primary spin-on-click" type="submit">
                            <i class="fa fa-pencil-square"></i> {{ trans('pages.page.action.update') }}
                        </button>
                        <a href="{{ route('pages.index') }}" class="btn btn-default spin-on-click" title="{{ trans('global.action.back') }}">
                            <i class="fa fa-undo"></i> {{ trans('global.action.back') }}
                        </a>
                    @else
                        <button class="btn btn-success spin-on-click" type="submit">
                            <i class="fa fa-plus-circle"></i> {{ trans('pages.page.action.create') }}
                        </button>
                        <a href="{{ route('pages.index') }}" class="btn btn-default spin-on-click" title="{{ trans('global.action.cancel') }}">
                            <i class="fa fa-ban"></i> {{ trans('global.action.cancel') }}
                        </a>
                    @endif
                </form>

            </div>
        </div>
    </div>

@endsection