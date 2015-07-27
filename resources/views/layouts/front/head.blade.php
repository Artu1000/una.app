{{-- page title --}}
<title>{{env('SITE_NAME')}} - {{$seoMeta['page_title']}}</title>

{{-- favicon --}}
<link rel="icon" href="{{ url('/') }}/favicon.ico">

{{-- meta --}}
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content='{{$seoMeta['meta_desc']}}'>
<meta name="keywords" content="{{$seoMeta['meta_keywords']}}">
<meta name="author" content='Arthur LORENT'>

{{-- csrf token --}}
<meta content="{{ csrf_token() }}" name="csrf-token" />

{{-- minified versionned front css --}}
<link href="{{ elixir('css/app.front.css') }}" rel="stylesheet">

{{-- js base url --}}
<script type="text/javascript">
    var base_url = '<?php echo url(); ?>';
    var site_name = '<?php echo env('SITE_NAME'); ?>';
</script>