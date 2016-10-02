@extends('templates.back.full_layout')

@section('content')

    <div id="content" class="settings">

        <div class="text-content">

            <div class="col-sm-12">

                {{-- Title--}}
                <h2><i class="fa fa-cogs"></i> {{ trans('settings.page.title.settings') }}</h2>

                <hr>

                <form role="form" method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">

                    {{-- crsf token --}}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">

                    {{-- language choice --}}
                    @if(config('settings.multilingual'))
                        <ul class="nav nav-tabs model_trans_manager">
                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                <li role="presentation" @if($localeCode === config('app.locale'))class="active" @endif>
                                    <a href="{{ $localeCode }}" title="{{ $properties['native'] }}">
                                        <div class="display-table">
                                            <div class="table-cell flag">
                                                <img width="20" height="20" class="img-circle" src="{{ url('img/flag/' . $localeCode . '.png') }}" alt="{{ $localeCode }}">
                                            </div>
                                            &nbsp;
                                            <div class="table-cell">
                                                {{ $properties['native'] }}
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    {{-- app data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('settings.page.title.identity') }}</h3>
                        </div>
                        <div class="panel-body">

                            {{-- app name --}}
                            <label for="input_app_name" class="required">{{ trans('settings.page.label.app_name') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_app_name"><i class="fa fa-sitemap"></i></span>
                                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                        <input id="input_app_name_{{ $localeCode }}" class="form-control capitalize-first-letter model_trans_input {{ $localeCode }} @if($localeCode !== config('app.locale'))hidden @endif" type="text" name="app_name_{{ $localeCode }}" value="{{ !empty(old('app_name_' . $localeCode)) ? old('app_name_' . $localeCode) : config('settings.app_name_' . $localeCode) }}" placeholder="{{ trans('settings.page.label.app_name') }}">
                                    @endforeach
                                </div>
                            </div>

                            {{-- app slogan --}}
                            <label for="input_app_slogan">{{ trans('settings.page.label.app_slogan') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_app_slogan"><i class="fa fa-flag"></i></span>
                                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                        <input id="input_app_slogan_{{ $localeCode }}" class="form-control capitalize-first-letter model_trans_input {{ $localeCode }} @if($localeCode !== config('app.locale'))hidden @endif" type="text" name="app_slogan_{{ $localeCode }}" value="{{ old('app_slogan_' . $localeCode) ? old('app_slogan_' . $localeCode) : config('settings.app_slogan_' . $localeCode) }}" placeholder="{{ trans('settings.page.label.app_slogan') }}">
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- navigation data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('settings.page.title.navigation') }}</h3>
                        </div>
                        <div class="panel-body">

                            {{-- breadcrumbs --}}
                            <label for="input_breadcrumbs">{{ trans('settings.page.label.breadcrumbs') }}</label>
                            <div class="form-group">
                                <div class="input-group swipe-group">
                                    <span class="input-group-addon" for="input_breadcrumbs"><i class="fa fa-compass"></i></span>
                                    <span class="form-control swipe-label" readonly="">
                                        {{ trans('global.action.activate') }}
                                    </span>
                                    <input class="swipe" id="input_breadcrumbs" type="checkbox" name="breadcrumbs"
                                           @if(old('breadcrumbs')) checked
                                           @elseif(config('settings.breadcrumbs')) checked
                                            @endif>
                                    <label class="swipe-btn" for="input_breadcrumbs"></label>
                                </div>
                            </div>

                            {{-- multilingual --}}
                            <label for="input_multilingual">{{ trans('settings.page.label.multilingual') }}</label>
                            <div class="form-group">
                                <div class="input-group swipe-group">
                                    <span class="input-group-addon" for="input_multilingual"><i class="fa fa-globe"></i></span>
                                    <span class="form-control swipe-label" readonly="">
                                        {{ trans('global.action.activate') }}
                                    </span>
                                    <input class="swipe" id="input_multilingual" type="checkbox" name="multilingual"
                                           @if(old('multilingual')) checked
                                           @elseif(config('settings.multilingual')) checked
                                            @endif>
                                    <label class="swipe-btn" for="input_multilingual"></label>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- contact data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('settings.page.title.contact') }}</h3>
                        </div>
                        <div class="panel-body">

                            {{-- phone number --}}
                            <label for="input_phone_number">{{ trans('settings.page.label.phone_number') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_phone_number"><i class="fa fa-phone"></i></span>
                                    <input id="input_phone_number" class="form-control" type="tel" name="phone_number" value="{{ old('phone_number') ? old('phone_number') : config('settings.phone_number') }}" placeholder="{{ trans('settings.page.label.phone_number') }}">
                                </div>
                            </div>

                            {{-- contact email --}}
                            <label for="input_contact_email">{{ trans('settings.page.label.contact_email') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_contact_email"><i class="fa fa-at"></i></span>
                                    <input id="input_contact_email" class="form-control" type="email" name="contact_email" value="{{ old('contact_email') ? old('contact_email') : config('settings.contact_email') }}" placeholder="{{ trans('settings.page.label.contact_email') }}">
                                </div>
                            </div>

                            {{-- sspport email --}}
                            <label for="input_support_email">{{ trans('settings.page.label.support_email') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_support_email"><i class="fa fa-at"></i></span>
                                    <input id="input_support_email" class="form-control" type="email" name="support_email" value="{{ old('support_email') ? old('support_email') : config('settings.support_email') }}" placeholder="{{ trans('settings.page.label.support_email') }}">
                                </div>
                            </div>

                            {{-- address --}}
                            <label for="input_address">{{ trans('settings.page.label.address') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_address"><i class="fa fa-envelope"></i></span>
                                    <input id="input_address" class="form-control" type="text" name="address" value="{{ old('address') ? old('address') : config('settings.address') }}" placeholder="{{ trans('settings.page.label.address') }}">
                                </div>
                            </div>

                            {{-- zip code --}}
                            <label for="input_zip_code">{{ trans('settings.page.label.zip_code') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_zip_code"><i class="fa fa-paper-plane"></i></span>
                                    <input id="input_zip_code" class="form-control" type="number" name="zip_code" value="{{ old('zip_code') ? old('zip_code') : config('settings.zip_code') }}" placeholder="{{ trans('settings.page.label.zip_code') }}">
                                </div>
                            </div>

                            {{-- city --}}
                            <label for="input_city">{{ trans('settings.page.label.city') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_city"><i class="fa fa-map-marker"></i></span>
                                    <input id="input_city" class="form-control" type="text" name="city" value="{{ old('city') ? old('city') : config('settings.city') }}" placeholder="{{ trans('settings.page.label.city') }}">
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- social data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('settings.page.title.social') }}</h3>
                        </div>
                        <div class="panel-body">

                            {{-- facebook --}}
                            <label for="input_facebook">{{ trans('settings.page.label.facebook') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_facebook"><i class="fa fa-facebook"></i></span>
                                    <input id="input_facebook" class="form-control" type="tel" name="facebook" value="{{ old('facebook') ? old('facebook') : config('settings.facebook') }}" placeholder="{{ trans('settings.page.label.facebook') }}">
                                </div>
                            </div>

                            {{-- twitter --}}
                            <label for="input_twitter">{{ trans('settings.page.label.twitter') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_twitter"><i class="fa fa-twitter"></i></span>
                                    <input id="input_twitter" class="form-control" type="text" name="twitter" value="{{ old('twitter') ? old('twitter') : config('settings.twitter') }}" placeholder="{{ trans('settings.page.label.twitter') }}">
                                </div>
                            </div>

                            {{-- google+ --}}
                            <label for="input_google_plus">{{ trans('settings.page.label.google+') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_google_plus"><i class="fa fa-google-plus"></i></span>
                                    <input id="input_google_plus" class="form-control" type="text" name="google_plus" value="{{ old('google_plus') ? old('google_plus') : config('settings.google_plus') }}" placeholder="{{ trans('settings.page.label.google+') }}">
                                </div>
                            </div>

                            {{-- linkedin --}}
                            <label for="input_linkedin">{{ trans('settings.page.label.linkedin') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_linkedin"><i class="fa fa-linkedin"></i></span>
                                    <input id="input_linkedin" class="form-control" type="text" name="linkedin" value="{{ old('linkedin') ? old('linkedin') : config('settings.linkedin') }}" placeholder="{{ trans('settings.page.label.linkedin') }}">
                                </div>
                            </div>

                            {{-- pinterest --}}
                            <label for="input_pinterest">{{ trans('settings.page.label.pinterest') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_pinterest"><i class="fa fa-pinterest-p"></i></span>
                                    <input id="input_pinterest" class="form-control" type="text" name="pinterest" value="{{ old('pinterest') ? old('pinterest') : config('settings.pinterest') }}" placeholder="{{ trans('settings.page.label.pinterest') }}">
                                </div>
                            </div>

                            {{-- youtube --}}
                            <label for="input_youtube">{{ trans('settings.page.label.youtube') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_youtube"><i class="fa fa-youtube"></i></span>
                                    <input id="input_youtube" class="form-control" type="text" name="youtube" value="{{ old('youtube') ? old('youtube') : config('settings.youtube') }}" placeholder="{{ trans('settings.page.label.youtube') }}">
                                </div>
                            </div>

                            {{-- rss --}}
                            <label for="swipe_rss">{{ trans('settings.page.label.rss.title') }}</label>
                            <div class="form-group">
                                <div class="input-group swipe-group">
                                    <span class="input-group-addon" for="swipe_rss"><i class="fa fa-rss"></i></span>
                                    <span class="form-control swipe-label" readonly="">
                                        {{ trans('settings.page.label.rss.news') }}
                                    </span>
                                    <input class="swipe" id="swipe_rss" type="checkbox" name="rss"
                                           @if(old('rss')) checked
                                           @elseif(config('settings.youtube')) checked
                                           @endif>
                                    <label class="swipe-btn" for="swipe_rss"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- appearance data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('settings.page.title.design') }}</h3>
                        </div>
                        <div class="panel-body">

                            {{-- favicon --}}
                            <label for="input_favicon">{{ trans('settings.page.label.favicon') }}</label>
                            @if(is_file('./favicon.ico'))
                                <div class="form-group image">
                                    <div class="logo img-rounded">
                                        <img src="{{ url('favicon.ico') }}" alt="Favicon {{ config('settings.app_name_' . config('app.locale')) }}">
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-primary btn-file">
                                            <i class="fa fa-picture-o"></i> {{ trans('global.action.browse') }} <input type="file" name="favicon">
                                        </span>
                                    </span>
                                    <input id="input_favicon" type="text" class="form-control" readonly="">
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('settings.page.info.image') }}</p>
                            </div>

                            {{-- logo light --}}
                            <label for="input_logo_light">{{ trans('settings.page.label.logo_light') }}</label>
                            @if(config('settings.logo_light'))
                                <div class="form-group image">
                                    <a class="img-thumbnail bg-dark" href="{{ ImageManager::imagePath(config('image.settings.public_path'), config('settings.logo_light'), 'logo', 'large') }}" data-lity>
                                        <img src="{{ ImageManager::imagePath(config('image.settings.public_path'), config('settings.logo_light'), 'logo', 'admin') }}" alt="{{ trans('settings.page.label.logo_light') }}">
                                    </a>
                                </div>
                            @endif
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-primary btn-file">
                                            <i class="fa fa-picture-o"></i> {{ trans('global.action.browse') }} <input type="file" name="logo_light">
                                        </span>
                                    </span>
                                    <input id="input_logo_light" type="text" class="form-control" readonly="">
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('settings.page.info.logo') }}</p>
                            </div>

                            {{-- logo dark --}}
                            <label for="input_logo_dark">{{ trans('settings.page.label.logo_dark') }}</label>
                            @if(config('settings.logo_dark'))
                                <div class="form-group image">
                                    <a class="img-thumbnail" href="{{ ImageManager::imagePath(config('image.settings.public_path'), config('settings.logo_dark'), 'logo', 'large') }}" data-lity>
                                        <img src="{{ ImageManager::imagePath(config('image.settings.public_path'), config('settings.logo_dark'), 'logo', 'admin') }}" alt="{{ trans('settings.page.label.logo_dark') }}">
                                    </a>
                                </div>
                            @endif
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-primary btn-file">
                                            <i class="fa fa-picture-o"></i> {{ trans('global.action.browse') }} <input type="file" name="logo_dark">
                                        </span>
                                    </span>
                                    <input id="input_logo_dark" type="text" class="form-control" readonly="">
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('settings.page.info.logo') }}</p>
                            </div>

                            {{-- loading spinner --}}
                            <label for="input_loading_spinner">{{ trans('settings.page.label.loading_spinner') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_loading_spinner">{!! config('settings.loading_spinner') !!}</span>
                                    <input id="input_loading_spinner" class="form-control" type="text" name="loading_spinner" placeholder="{{ trans('settings.page.label.loading_spinner') }}" value="{{ old('loading_spinner') ? old('loading_spinner') : config('settings.loading_spinner') }}">
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('settings.page.info.loading_spinner') }}</p>
                            </div>

                            {{-- success icon --}}
                            <label for="input_success_icon">{{ trans('settings.page.label.success_icon') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_success_icon">{!! config('settings.success_icon') !!}</span>
                                    <input id="input_success_icon" class="form-control" type="text" name="success_icon" placeholder="{{ trans('settings.page.label.success_icon') }}" value="{{ old('success_icon') ? old('success_icon') : config('settings.success_icon') }}">
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('settings.page.info.success_icon') }}</p>
                            </div>

                            {{-- error icon --}}
                            <label for="input_error_icon">{{ trans('settings.page.label.error_icon') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_error_icon">{!! config('settings.error_icon') !!}</span>
                                    <input id="input_error_icon" class="form-control" type="text" name="error_icon" placeholder="{{ trans('settings.page.label.error_icon') }}" value="{{ old('error_icon') ? old('error_icon') : config('settings.error_icon') }}">
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('settings.page.info.error_icon') }}</p>
                            </div>

                            {{-- info icon --}}
                            <label for="input_info_icon">{{ trans('settings.page.label.info_icon') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_info_icon">{!! config('settings.info_icon') !!}</span>
                                    <input id="input_info_icon" class="form-control" type="text" name="info_icon" placeholder="{{ trans('settings.page.label.info_icon') }}" value="{{ old('info_icon') ? old('info_icon') : config('settings.info_icon') }}">
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {{ trans('settings.page.info.info_icon') }}</p>
                            </div>

                        </div>
                    </div>

                    {{-- SEO data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('settings.page.title.seo') }}</h3>
                        </div>
                        <div class="panel-body">

                            {{-- google analytics script --}}
                            <label for="input_google_analytics_script">{{ trans('settings.page.label.google_analytics_script') }}</label>
                            <div class="form-group textarea">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_google_analytics_script"><i class="fa fa-code"></i></span>
                                    <textarea id="input_google_analytics_script" class="form-control" name="google_analytics_script" placeholder="{{ trans('settings.page.label.google_analytics_script') }}">{{ old('google_analytics_script') ? old('google_analytics_script') : config('settings.google_analytics_script') }}</textarea>
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {!! trans('settings.page.info.google_analytics_script') !!}</p>
                            </div>

                            {{-- google analytics view id --}}
                            <label for="input_google_analytics_view_id">{{ trans('settings.page.label.google_analytics_view_id') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_google_analytics_view_id"><i class="fa fa-key" aria-hidden="true"></i></span>
                                    <input id="input_google_analytics_view_id" class="form-control" type="text" name="google_analytics_view_id" placeholder="{{ trans('settings.page.label.google_analytics_view_id') }}" value="{{ old('google_analytics_view_id') ? old('google_analytics_view_id') : env('ANALYTICS_VIEW_ID') }}">
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {!! trans('settings.page.info.google_analytics_view_id') !!}</p>
                            </div>

                            {{-- google analytics credentials json --}}
                            <label for="input_google_analytics_credentials_json">{{ trans('settings.page.label.google_analytics_credentials_json') }}</label>
                            @if(env('ANALYTICS_CREDENTIALS_JSON'))
                                <div class="form-group">
                                    <a href="{{ FileManager::download(config('file.settings.storage_path'), env('ANALYTICS_CREDENTIALS_JSON')) }}">
                                        <i class="fa fa-file-code-o" aria-hidden="true"></i> {{ env('ANALYTICS_CREDENTIALS_JSON') }}
                                    </a>
                                </div>
                            @endif
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-primary btn-file">
                                            <i class="fa fa-picture-o"></i> {{ trans('global.action.browse') }} <input type="file" name="google_analytics_credentials_json">
                                        </span>
                                    </span>
                                    <input id="input_google_analytics_credentials_json" type="text" class="form-control" readonly="">
                                </div>
                                <p class="help-block quote">{!! config('settings.info_icon') !!} {!! trans('settings.page.info.google_analytics_credentials_json') !!}</p>
                            </div>

                        </div>
                    </div>

                    {{-- submit login --}}
                    <button class="btn btn-primary spin-on-click" type="submit">
                        <i class="fa fa-floppy-o"></i> {{ trans('settings.page.action.save') }}
                    </button>
                </form>

            </div>
        </div>
    </div>

@endsection