$(function() {
    // Make link unclikable
    $('.unclickable').click(function(e){
        e.preventDefault();
    });

    // manage alert modal
    if(app.modal_alert === true){
        // we show the alert modal
        $('#alert').modal({
            backdrop: 'static'
        })
    }

    // manage confirm modal
    var confirm = false;
    $('.confirm').on('click', function(e){
        e.preventDefault();

        var attribute = $(this).attr('data-confirm');
        var modal = $('#confirm');
        modal.find('.attribute').html(attribute);

        // we get the form
        var $form = $(this).closest('form');
        // we show the modal
        $('#confirm').modal({ backdrop: 'static', keyboard: false }).one('click', '#modal-confirm-button', function() {
            // we submit the form on confirm
            $form.trigger('submit');
        });
    });

    // click on bootstrap input groups focus on the input targeted
    $('.input-group-addon').click(function(){
        var target = $(this).attr('for');
        if(target){
            $('#' + target).focus().trigger('click');
        }
    });

    // capitalize input letters
    $('.capitalize').on('focus change', function() {
        this.value = this.value.toLocaleUpperCase();
    });

    // capitalize input first-letter
    $('.capitalize-first-letter').on('focus change', function() {
        this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);
    });

    // select all text on click on input
    $('input, textarea').on('focus', function(){
        this.select();
    });

    // open new window when clicking on the link
    $(".new_window").on("click", function (event) {
        event.preventDefault();
        // we get the closest a target
        var target = $(this).closest('a').attr('href');
        // we open the target in a new window
        window.open(target);
    });

    // replace button fontawesome icon by loading spinner on click
    // show spinner even if no font awesome icon is found
    function replaceFontAwesomeIconBySpinner($this) {
        // we get the html contained into the button
        var html = $this.html();
        // we remove the fontawesome icon
        var begin = html.indexOf('<i');
        var end = html.indexOf('i>');
        var to_remove = html.substring(begin, end + 2);
        var cleaned_html = html.replace(to_remove, '');
        // we put the loading spinner
        $this.html(app.loading_spinner + ' ' + cleaned_html);
    }
    $('.spin-on-click').one('click', function (e) {
        // we prevent any action
        e.preventDefault();
        // we store this
        var $this = $(this);
        // we replace the fontawesome icon by a spinner
        replaceFontAwesomeIconBySpinner($this);
        // we execute the prevented action
        if ($this.is("button[type=submit]")) {
            console.log('submit');
            $this.closest('form').submit();
        } else if ($this.is("button")) {
            console.log('button');
            window.location.href = $this.closest('a').attr('href');
        } else if ($this.is("a")) {
            window.location.href = $this.attr('href');
        }
    });
    $('.spin-on-click-without-action').click(function (e) {
        // we replace the fontawesome icon by a spinner
        replaceFontAwesomeIconBySpinner($(this));
    });

    // node element with this class submits the closest form
    $('.submit-form').click(function(e){
        e.preventDefault();
        $(this).closest('form').submit();
    });

    // we submit the form on select change detection
    var autosubmit = $('select.autosubmit');
    if (autosubmit.length) {
        autosubmit.change(function () {
            $(this).closest('form').submit();
        });
    }
});