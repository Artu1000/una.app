{{-- meta --}}
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{env('SITE_NAME')}} - {{$seo['page_title']}}</title>
<meta name="description" content='{{$seo['description']}}'>
<meta name="keywords" content="{{$seo['keywords']}}">
<meta name="author" content='Arthur LORENT'>

{{-- CRSF token--}}
<meta content="{{ csrf_token() }}" name="csrf-token" />

{{-- base common CSS --}}
<link href="{{ url() }}/css/app.css" rel="stylesheet">

{{-- backend custom CSS --}}
<link href="{{ url() }}/css/back/custom.css" rel="stylesheet">

{{-- js base url --}}
<script>var base_url = '<?php echo url(); ?>';</script>