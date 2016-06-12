@extends('templates.back.full_layout')

@section('content')

    <div id="content" class="user edit">

        <div class="text-content">

            <div class="col-sm-12">

                {{-- Title--}}
                <h2>
                    <i class="fa fa-user"></i>
                    @if(isset($news))
                        {!! trans('news.page.title.edit', ['news' => $news->title]) !!}
                    @else
                        {{ trans('news.page.title.create') }}
                    @endif
                </h2>

                <hr>

                <form role="form" method="POST" action="@if(isset($news)){{ route('news.update', ['id' => $news->id]) }} @else{{ route('news.store') }} @endif" enctype="multipart/form-data">

                    {{-- crsf token --}}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    {{-- we include the form legend --}}
                    @include('templates.back.partials.form-legend.required')

                    {{-- add update inputs if we are in update mode --}}
                    @if(isset($news))
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="_id" value="{{ $news->id }}">
                    @endif

                    {{-- personal data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('news.page.title.data') }}</h3>
                        </div>
                        <div class="panel-body">

                            {{-- image --}}
                            <label for="input_image">{{ trans('news.page.label.image') }}</label>
                            @if(isset($news) && $news->image)
                                <div class="form-group image">
                                    <div class="form-group">
                                        <a class="img-thumbnail" href="{{ $news->imagePath($news->image, 'image', '767') }}" data-lity>
                                            <img src="{{ $news->imagePath($news->image, 'image', 'admin') }}" alt="{{ $news->title }}">
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
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('news.page.info.image') }}</p>
                            </div>

                            {{-- category --}}
                            <label>{{ trans('news.page.label.category') }}<span class="required">*</span></label>
                            <div class="form-group">
                                <div class="btn-group" data-toggle="buttons">
                                    @foreach($categories as $id => $category)
                                        <label class="btn toggle
                                        @if(!isset($news) && old('category_id') == $id)active
                                        @elseif(isset($news) && isset($news->category_id) && $news->category_id === $id)active
                                        @endif">
                                            <input type="radio" name="category_id" value="{{ $id }}" autocomplete="off"
                                                   @if(!isset($news) && old('category_id') == $id)checked
                                                   @elseif(isset($news) && isset($news->category_id) && $news->category_id === $id)checked
                                                   @endif>
                                            {{ trans('news.config.category.' . $category) }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- title --}}
                            <label for="input_title">{{ trans('news.page.label.title') }}<span class="required">*</span></label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_name"><i class="fa fa-tag"></i></span>
                                    <input id="input_title" class="form-control capitalize-first-letter" type="text" name="title" value="{{ old('title') ? old('title') : (isset($news) && $news->title ? $news->title : null) }}" placeholder="{{ trans('news.page.label.title') }}">
                                </div>
                            </div>

                            {{-- content --}}
                            <label for="input_content">{{ trans('news.page.label.content') }}</label>
                            <div class="form-group textarea">
                                <textarea id="input_content" class="form-control markdown" name="content" placeholder="{{ trans('news.page.label.content') }}">{{ old('content') ? old('content') : (isset($news) && $news->content ? $news->content : null) }}</textarea>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('news.page.info.content') }}</p>
                            </div>

                        </div>
                    </div>

                    {{-- media --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('news.page.title.media') }}</h3>
                        </div>
                        <div class="panel-body">

                            {{-- photo --}}
                            <label for="input_photo_album_id">{{ trans('news.page.label.photo_album_id') }}</label>
                            <div class="form-group">
                                <select class="form-control" name="photo_album_id" id="input_photo_album_id" title="{{ trans('news.page.label.photo_album_id_placeholder') }}">
                                    <option value="">{{ trans('news.page.label.photo_album_id_placeholder') }}</option>
                                    @foreach($photos as $photo_album)
                                        <option value="{{ $photo_album->id }}"
                                                @if(old('photo_album_id') == $photo_album->id)selected
                                                @elseif(is_null(old('photo_album_id')) && isset($news->photo_album_id) && $news->photo_album_id === $photo_album->id)selected
                                                @endif>{{ $photo_album->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- video --}}
                            <label for="input_video_id">{{ trans('news.page.label.video_id') }}</label>
                            <div class="form-group">
                                <select class="form-control" name="video_id" id="input_video_id" title="{{ trans('news.page.label.video_id_placeholder') }}">
                                    <option value="">{{ trans('news.page.label.video_id_placeholder') }}</option>
                                    @foreach($videos as $video)
                                        <option value="{{ $video->id }}"
                                                @if(old('video_id') == $video->id)selected
                                                @elseif(is_null(old('video_id')) && isset($news->video_id) && $news->video_id === $video->id)selected
                                                @endif>{{ $video->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>

                    {{-- seo data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('news.page.title.seo') }}</h3>
                        </div>
                        <div class="panel-body">

                            {{-- meta title --}}
                            <label for="input_meta_title">{{ trans('news.page.label.meta_title') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_meta_title"><i class="fa fa-tag"></i></span>
                                    <input id="input_meta_title" class="form-control" type="text" name="meta_title" value="{{ old('meta_title') ? old('meta_title') : (isset($news) && $news->meta_title ? $news->meta_title : null) }}" placeholder="{{ trans('news.page.label.meta_title') }}">
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('news.page.info.meta_title') }}</p>
                            </div>

                            {{-- meta description --}}
                            <label for="input_meta_description">{{ trans('news.page.label.meta_description') }}</label>
                            <div class="form-group textarea">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_meta_description"><i class="fa fa-align-left" aria-hidden="true"></i></span>
                                    <textarea id="input_meta_description" class="form-control" name="meta_description" placeholder="{{ trans('news.page.label.meta_description') }}">{{ old('meta_description') ? old('meta_description') : (isset($news) && $news->meta_description ? $news->meta_description : null) }}</textarea>
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('news.page.info.meta_description') }}</p>
                            </div>

                            {{-- meta keywords --}}
                            <label for="input_meta_keywords">{{ trans('news.page.label.meta_keywords') }}</label>
                            <div class="form-group textarea">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_meta_keywords"><i class="fa fa-key" aria-hidden="true"></i></span>
                                    <textarea id="input_meta_keywords" class="form-control" name="meta_keywords" placeholder="{{ trans('news.page.label.meta_keywords') }}">{{ old('meta_keywords') ? old('meta_keywords') : (isset($news) && $news->meta_keywords ? $news->meta_keywords : null) }}</textarea>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- release data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('news.page.title.release') }}</h3>
                        </div>
                        <div class="panel-body">

                            {{-- release date --}}
                            <label for="input_released_at">{{ trans('news.page.label.released_at') }}<span class="required">*</span></label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_released_at"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                    <input id="input_released_at" type='text' class="form-control datetimepicker" name="released_at" value="{{ old('released_at') ? old('released_at') : (isset($news) && $news->released_at ? $news->released_at : \Carbon\Carbon::now()->format('d/m/Y H:i')) }}" placeholder="{{ trans('news.page.label.released_at') }}">
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('global.info.datetime.format') }}</p>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('news.page.info.release_date') }}</p>
                            </div>

                            {{-- activation --}}
                            @if(Sentinel::getUser()->hasAccess('news.activate'))
                                <label for="input_active">{{ trans('news.page.label.activation') }}</label>
                                <div class="form-group">
                                    <div class="input-group swipe-group">
                                        <span class="input-group-addon" for="input_active"><i class="fa fa-power-off"></i></span>
                                    <span class="form-control swipe-label" readonly="">
                                        {{ trans('news.page.label.activation_placeholder') }}
                                    </span>
                                        <input class="swipe" id="input_active" type="checkbox" name="active"
                                               @if(old('active'))checked @elseif(isset($news) && $news->active)checked @endif>
                                        <label class="swipe-btn" for="input_active"></label>
                                    </div>
                                </div>
                            @endif()

                            {{-- author --}}
                            @if(Sentinel::getUser()->hasAccess('news.author'))
                                <label for="input_author_id">{{ trans('news.page.label.author_id') }} <span class="required">*</span></label>
                                <div class="form-group">
                                    <select class="form-control" name="author_id" id="input_author_id" title="{{ trans('news.page.label.author_id_placeholder') }}">
                                        <option value="" disabled>{{ trans('news.page.label.author_id_placeholder') }}</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}"
                                                    @if(old('author_id') == $user->id)selected
                                                    @elseif(is_null(old('author_id')) && isset($news->author_id) && $news->author_id === $user->id)selected
                                                    @elseif(!isset($news) && Sentinel::getUser()->id === $user->id)selected
                                                    @endif>{{ $user->last_name . ' ' . $user->first_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif()

                        </div>
                    </div>

                    {{-- submit login --}}
                    @if(isset($news))
                        <button class="btn btn-primary spin-on-click" type="submit">
                            <i class="fa fa-pencil-square"></i> {{ trans('news.page.action.update') }}
                        </button>
                        <a href="{{ route('news.page.edit') }}" class="btn btn-default spin-on-click" title="{{ trans('global.action.back') }}">
                            <i class="fa fa-undo"></i> {{ trans('global.action.back') }}
                        </a>
                    @else
                        <button class="btn btn-success spin-on-click" type="submit">
                            <i class="fa fa-plus-circle"></i> {{ trans('news.page.action.create') }}
                        </button>
                        <a href="{{ route('news.page.edit') }}" class="btn btn-default spin-on-click" title="{{ trans('global.action.cancel') }}">
                            <i class="fa fa-ban"></i> {{ trans('global.action.cancel') }}
                        </a>
                    @endif
                </form>

            </div>
        </div>
    </div>

@endsection