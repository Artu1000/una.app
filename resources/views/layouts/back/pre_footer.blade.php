<!-- Placed at the end of the document so the pages load faster -->
<script src='{{ url() }}/assets/vendor/jquery/dist/jquery.min.js'></script>
<script src='{{ url() }}/assets/vendor/bootstrap/dist/js/bootstrap.min.js'></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src='{{ url() }}/js/ie10-viewport-bug-workaround.js'></script>
<!-- Input Validity checker -->
{{--<script src='{{ url() }}/js/textchange.js'></script>--}}
{{--<script src='{{ url() }}/js/input-validity-checker.js'></script>--}}
<!-- Custom global JS -->
<script src='{{ url() }}/js/app.js'></script>
@extends((isset($dependency)) ? 'dependencies.'. $dependency : 'dependencies.empty')