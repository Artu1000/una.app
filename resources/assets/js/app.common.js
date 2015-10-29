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

    // capitalize input letters
    $('.capitalize').keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });

    // capitalize input first-letter
    $('.capitalize-first-letter').keyup(function() {
        this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);
    });

    // select all text on click on input
    $('input').click(function(){
        //this.setSelectionRange(0, this.value.length);
        this.select();
    });
});