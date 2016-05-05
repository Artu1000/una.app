{{-- minified & versionned js file --}}
<script type="text/javascript" src="{{ $js or elixir('js/app.front.js') }}" async defer></script>

{{-- google analytics code insertion --}}
@if($ga_code = config('settings.google_analytics'))
    <script async defer>{!! $ga_code !!}</script>
@endif

