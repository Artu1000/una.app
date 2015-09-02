{{-- page title --}}
<title>{{$seoMeta['page_title']}} - {{env('SITE_NAME')}}</title>

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

{{-- minified versionned css --}}
<link href="{{ isset($css) ? $css : elixir('css/app.front.css') }}" rel="stylesheet">

{{-- js data --}}
<script type="text/javascript">
    var base_url = <?php echo json_encode(url('/')); ?>;
    var site_name = <?php echo json_encode(env('SITE_NAME')); ?>;
    var page_data = <?php echo !empty($jsPageData) ? json_encode($jsPageData) : '""'; ?>;
</script>