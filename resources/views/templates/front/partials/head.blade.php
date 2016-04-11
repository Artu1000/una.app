{{-- page title --}}
<title>{{ $seo_meta['page_title'] }} - {{ config('settings.app_name_' . config('app.locale')) }}</title>

{{-- favicon --}}
<link rel="icon" href="{{ url('favicon.ico') }}" />

{{-- meta --}}
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="author" content="Arthur LORENT" />

{{-- seo meta --}}
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="description" content="{{ $seo_meta['meta_desc'] }}" />
<meta name="keywords" content="{{ $seo_meta['meta_keywords'] }}" />

{{-- og meta --}}
@if(isset($og_meta) && !empty($og_meta))
    @foreach($og_meta as $og => $value)
        @if($value)
            <meta property="{{ $og }}" content="{{ $value }}" />
        @endif
    @endforeach
@endif

{{-- rss setup --}}
@if(config('settings.rss'))
    <link rel="alternate" type="application/rss+xml" href="{{ route('rss.index') }}" title="Flux RSS des actualités du {{ config('settings.app_name_' . config('app.locale')) }}" />
@endif

{{-- csrf token --}}
<meta content="{{ csrf_token() }}" name="csrf-token" />

{{-- minified versionned css --}}
<link href="{{ $css or url(elixir('css/app.front.css')) }}" rel="stylesheet" />