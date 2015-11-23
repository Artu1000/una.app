<!-- Large modal -->
<div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="confirmModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-danger">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle"></i> {{ trans('messages.modal.confirm.title') }}</h3>
            </div>
            <div class="modal-body content">
                <ul class="list-unstyled">
                    {{ trans('messages.modal.confirm.question') }}
                    <li><span class="text-danger"><i class="fa fa-hand-o-right"></i></span> {{ $confirm['action'] }} <b><span class="attribute"></span></b></li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> {{ trans('actions.cancel') }}</button>
                <button type="button" id="modal-confirm-button" class="btn btn-primary spin-on-click"><i class="fa fa-check-circle"></i> {{ trans('actions.confirm') }}</button>
            </div>
        </div>
    </div>
</div>