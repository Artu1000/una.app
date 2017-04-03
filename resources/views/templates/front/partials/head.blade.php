{{-- page title --}}
<title>{{ $seo_meta['page_title'] }} - {{ config('settings.app_name_' . config('app.locale')) }}</title>

{{-- favicon --}}
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
<link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

{{-- meta --}}
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

{{-- canonical --}}
@if(Route::currentRouteName())
    <link rel="canonical" href="{{ Request::url() }}">
@endif

{{-- content language--}}
<meta http-equiv="content-language" content="{{ implode(',', LaravelLocalization::getSupportedLanguagesKeys()) }}">

{{-- hreflang --}}
@if(config('settings.multilingual'))
    @foreach(LaravelLocalization::getSupportedLocales() as $local_code => $properties)
        <link rel="alternate" hreflang="{{ $local_code }}"
              href="{{ LaravelLocalization::getLocalizedURL($local_code) }}">
    @endforeach
@endif

{{-- seo meta --}}
<meta name="viewport" content="width=device-width, initial-scale=1" />
@if(isset($seo_meta) && !empty($seo_meta))
    @foreach($seo_meta as $seo => $value)
        @if($seo != 'page_title')
            @if($value)
                <meta name="{{ $seo }}" content="{{ $value }}" />
            @endif
        @endif
    @endforeach
@endif

{{-- og meta --}}
@if(isset($og_meta) && !empty($og_meta))
    @foreach($og_meta as $og => $value)
        @if($value)
            <meta property="{{ $og }}" content="{{ $value }}" />
        @endif
    @endforeach
@endif

{{-- twitter meta --}}
@if(isset($twitter_meta) && !empty($twitter_meta))
    @foreach($twitter_meta as $twitter => $value)
        @if($value)
            <meta name="{{ $twitter }}" content="{{ $value }}" />
        @endif
    @endforeach
@endif

{{-- rss setup --}}
@if(config('settings.rss'))
    <link rel="alternate" type="application/rss+xml" href="{{ route('rss.index') }}"
          title="{{ trans('global.rss.flux', ['app' => config('settings.app_name_' . config('app.locale'))]) }}">
@endif

{{-- google recaptcha --}}
@if(config('settings.google_public_recaptcha_key') && config('settings.google_private_recaptcha_key'))
    <script src="https://www.google.com/recaptcha/api.js?hl={{ config('app.locale') }}" async defer></script>
@endif

{{-- csrf token --}}
<meta content="{{ csrf_token() }}" name="csrf-token" />

{{-- minified versionned css --}}
<link href="{{ $css or mix('css/app.front.css') }}" rel="stylesheet" />

{{-- google analytics code insertion --}}
@if($google_analytics_script = config('settings.google_analytics_script'))
    {!! $google_analytics_script !!}
@endif

{{-- dynamic javascript inclusion --}}
@include('templates.common.partials.javascript')