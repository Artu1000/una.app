{{-- minified & versionned front js --}}
<script src="{{ elixir('js/app.front.js') }}"></script>
{{-- adding js for the needs of the page --}}
@extends((isset($dependency)) ? 'dependencies.'. $dependency : 'dependencies.none')