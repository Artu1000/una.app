$(function() {

    // Make link unclikable
    $('.unclickable').click(function(e){
        e.preventDefault();
    });

    // setup alert modal
    if(app.modal_alert === true){
        $('#alert').modal({
            backdrop: 'static'
        });
    }

    // setup ajax request
    //$.ajaxSetup({
    //    headers: {'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')}
    //});

    // click on bootstrap input groups focus on the input targeted
    $('.input-group-addon').click(function(){
        if($(this).attr('for')){
            $('#'+$(this).attr('for')).focus();
        }
    });
});