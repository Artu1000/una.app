// custom file input
$(document).on('change', '.btn-file :file', function () {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
});

// check parent checkbox or not, according if all the children checkboxes are checked or not
function evalChildrenCheckboxes(elt) {
    var permission_group = elt.attr('id').split('.')[0];
    var checked = true;
    $('.permission input[type=checkbox]').each(function (key, checkbox) {
        var id = checkbox.id.split('.');
        if (id[0] === permission_group && id[1]) {
            if (!checkbox.checked) {
                checked = false;
            }
        }
    });
    $('input#' + permission_group).prop('checked', checked);
}

$(function () {

    // manage language inputs visibility
    if(app.multilingual){
        var model_trans_language = app.locale;
        function showLocaleFields(){
            $('.model_trans_input').addClass('hidden');
            $('.model_trans_input.' + model_trans_language).removeClass('hidden');
        }
        showLocaleFields();
        $('.model_trans_manager li').click(function(e){
            e.preventDefault();
            $('.model_trans_manager li').removeClass('active');
            $(this).addClass('active');
            model_trans_language = $(this).children('a').attr('href');
            showLocaleFields();
        });
    }

    // custom file input
    $('.btn-file :file').on('fileselect', function (event, numFiles, label) {
        var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;
        if (input.length) {
            input.val(log);
        } else {
            if (log) alert(log);
        }
    });

    // we prepare the datepicker and datetimepicker
    switch(app.locale){
        case 'fr':
            var locale = app.locale;
            var format = 'DD/MM/YYYY HH:mm';
            break;
        case 'en':
            var format = 'DD/MM/YYYY hh:mm A';
            var locale = 'en-gb';
            break;
    }
    // we activate the datepicker
    if($('.datepicker').length){
        $('.datepicker').datetimepicker({
            locale: locale,
            format: 'DD/MM/YYYY'
        });
    }
    // we activate the datetimepicker
    if($('.datetimepicker').length){
        $('.datetimepicker').datetimepicker({
            locale: locale,
            format: format
        });
    }

    // permissions checkboxes
    // permission child checkboxes check on click on the parent
    $('.permission.parent input').click(function () {
        var permission_group = $(this).attr('id');
        var checked = $(this).is(':checked');
        $('.permission input[type=checkbox]').each(function (key, checkbox) {
            if (checkbox.id.split('.')[0] === permission_group) {
                checkbox.checked = checked;
            }
        });
    });
    // on check on child checkbox, manage parent checkbox check status
    $('.permission input[type=checkbox]').change(function () {
        evalChildrenCheckboxes($(this));
    });

    // we manage the activation in list from the swipe button
    $('.swipe-btn.activate').click(function () {
        // we get the swipe group
        var swipe_group = $(this).parent('.swipe-group');

        // we add a loading spinner
        swipe_group.append('<span class="swipe-action-icon">' + app.loading_spinner + '</span>');

        // we get the ajax request data
        var url = $(this).attr('data-url');
        var id = $(this).attr('data-id');
        var activation_order = !swipe_group.find('input.swipe').is(':checked');

        // we do the post request
        $.ajax({
            method: 'POST',
            url: url,
            data: {
                _token: app.csrf_token,
                id: id,
                activation_order: activation_order
            }
        }).done(function () {
            // we replace the loading spinner by a check icon
            swipe_group.find('.swipe-action-icon').remove();
            swipe_group.append('<span class="swipe-action-icon text-success"><i class="fa fa-thumbs-up"></i></span>');
        }).fail(function () {
            // we replace the loading spinner by a check icon
            swipe_group.find('.swipe-action-icon').remove();
            swipe_group.append('<span class="swipe-action-icon text-danger"><i class="fa fa-thumbs-down"></i></span>');

            // we set the checkbox at its original value
            window.setTimeout(function () {
                swipe_group.find('input.swipe').prop('checked', !activation_order);
            }, 500);
        }).always(function () {
            // we fade out the icon
            swipe_group.find('.swipe-action-icon').css({
                '-webkit-animation': 'fadeOut 10000ms',
                '-moz-animation': 'fadeOut 10000ms',
                '-ms-animation': 'fadeOut 10000ms',
                '-o-animation': 'fadeOut 10000ms',
                'animation': 'fadeOut 10000ms'
            }).promise().done(function () {
                // keep invisible
                $(this).css('opacity', 0);
            });
        });
    });

    // we activate the markdown editor
    if ($('.markdown').length) {
        var simplemde = new SimpleMDE({
            element: $(".markdown")[0],
            hideIcons: ['side-by-side', 'fullscreen'],
            spellChecker: false
        });
    }
});

