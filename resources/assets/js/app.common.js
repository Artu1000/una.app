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

    // click on bootstrap input groups focus on the input targeted
    $('.input-group-addon').click(function(){
        var target = $(this).attr('for');
        if(target){
            console.log(target);
            $('#' + target).focus().trigger('click');
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

    // open new window when clicking on the link
    $(".new_window").on("click", function (event) {
        event.preventDefault();
        window.open(this.href);
    });

    // replace button fontawesome icon by loading spinner on click
    // show spinner even if no font awesome icon is found
    $('.spin-on-click').click(function(e){
        // we get the html contained into the button
        var html = $(this).html();
        // we remove the fontawesome icon
        var begin = html.indexOf('<i');
        var end = html.indexOf('i>');
        var to_remove = html.substring(begin, end + 2);
        var cleaned_html = html.replace(to_remove, '');
        // we put the loading spinner
        $(this).html(app.loading_spinner + ' ' + cleaned_html);
    });

    // node element with this class submits the closest form
    $('.submit-form').click(function(e){
        e.preventDefault();
        $(this).closest('form').submit();
    });
});