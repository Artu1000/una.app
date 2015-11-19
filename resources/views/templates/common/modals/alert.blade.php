<!-- Large modal -->
<div class="modal fade" id="alert" tabindex="-1" role="dialog" aria-labelledby="alertModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header {{ $alert['message']['class'] }}">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="myModalLabel">{!! $alert['message']['title'] !!}</h3>
            </div>
            <div class="modal-body">
                {!! $alert['message']['content'] !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- We manage the modal show with no page reload -->
@if($alert['before_reload'])
    <script type="text/javascript">app.modal_alert = true;</script>
@endif