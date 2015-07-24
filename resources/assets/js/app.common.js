$(function() {

    // Make link unclikable
    $('.unclickable').click(function(e){
        e.preventDefault();
    });

    // setup alert modal
    if(alert === true){
        $('#modalAlert').modal();
    }

    // setup ajax request
    $.ajaxSetup({
        headers: {'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')}
    });

    // reset message on confirm modal cancel
    $('#modalConfirm').on('hidden.bs.modal', function () {
        $('#message').empty();
    });

    // click on bootstrap input groups focus on the input targeted
    $('.input-group-addon').click(function(){
        if($(this).attr('for')){
            $('#'+$(this).attr('for')).focus();
        }
    });
});