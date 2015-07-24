{{-- meta --}}
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{env('SITE_NAME')}} - {{$seoMeta['page_title']}}</title>
<meta name="description" content='{{$seoMeta['description']}}'>
<meta name="keywords" content="{{$seoMeta['keywords']}}">
<meta name="author" content='Arthur LORENT'>

{{-- csrf token --}}
<meta content="{{ csrf_token() }}" name="csrf-token" />

{{-- minified versionned front css --}}
<link href="{{ elixir('css/app.front.css') }}" rel="stylesheet">

{{-- js base url --}}
<script>var base_url = '<?php echo url(); ?>';</script>