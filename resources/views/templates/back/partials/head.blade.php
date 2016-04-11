{{-- page title --}}
<title>{{ $seo_meta['page_title'] }} - {{ config('settings.app_name_' . config('app.locale')) }}</title>

{{-- favicon --}}
<link rel="icon" href="{{ url('/') }}/favicon.ico">

{{-- meta --}}
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content='{{ $seo_meta['meta_desc'] }}'>
<meta name="keywords" content="{{ $seo_meta['meta_keywords'] }}">
<meta name="author" content='Arthur LORENT'>

{{-- csrf token --}}
<meta content="{{ csrf_token() }}" name="csrf-token" />

{{-- minified versionned css --}}
<link href="{{ $css or url(elixir('css/app.back.css')) }}" rel="stylesheet">