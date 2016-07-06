{{-- google analytics code insertion --}}
@if($ga_code = config('settings.google_analytics'))
    {!! $ga_code !!}
@endif

{{-- minified & versionned js file --}}
<script type="text/javascript" src="{{ $js or elixir('js/app.front.js') }}" async defer></script>