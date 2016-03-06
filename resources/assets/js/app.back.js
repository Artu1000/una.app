// custom file input
$(document).on('change', '.btn-file :file', function () {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
});

// check parent checkbox or not, according if all the children checkboxes are checked or not
function evalChildrenCheckboxes(elt) {
    var permission_group = elt.attr('id').split(app.permissions_separator)[0];
    var checked = true;
    $('.permission input[type=checkbox]').each(function (key, checkbox) {
        var id = checkbox.id.split(app.permissions_separator);
        if (id[0] === permission_group && id[1]) {
            if (!checkbox.checked) {
                checked = false;
            }
        }
    });
    $('input#' + permission_group).prop('checked', checked);
}

$(function () {

    // if we detect inputs language selectors
    if (app.multilingual && $('.inputs_language_selectors').length) {

        var selected_language = app.locale;

        function showLocaleFields() {

            // for each translated input
            $('.translated_input').each(function () {

                // we add the hidden class
                $(this).addClass('hidden');

                // we get the id of the input
                var input_id = $(this).attr('id');

                // for each element which references the input id
                $('[for=' + input_id + ']').each(function () {

                    // we exclude the last part of the id (locale)
                    var splitted = input_id.split('_');
                    var new_name = splitted.filter(function (segment, key) {
                        return key < (splitted.length - 1);
                    }).join('_');

                    // we change the for to reference the id according to the selected language
                    $(this).attr('for', new_name + '_' + selected_language);
                });
            });

            // we remove the hidden class on the inputs we want to show
            $('.translated_input.' + selected_language).removeClass('hidden');
        }

        // we execute the method on load
        showLocaleFields(app.locale);

        // on clicks on an input language selector
        var inputs_language_selector = $('.inputs_language_selectors li');
        inputs_language_selector.click(function (e) {

            // we remove the active class on all the selectors
            inputs_language_selector.removeClass('active');

            // we prevent the click action
            e.preventDefault();

            // we add the active class on the clicked language selector
            $(this).addClass('active');

            // we update our selected language variable
            selected_language = $(this).children('a').attr('href');

            // we show the correct fields according to the selected language
            showLocaleFields();
        });
    }

    // if we detect a custom file input
    if ($('.btn-file :file').length) {

        $(document).on('change', '.btn-file :file', function () {
            var input = $(this),
                numFiles = input.get(0).files ? input.get(0).files.length : 1,
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [numFiles, label]);
        });

        $('.btn-file :file').on('fileselect', function (event, numFiles, label) {
            var input = $(this).parents('.input-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' files selected' : label;
            if (input.length) {
                input.val(log);
            } else {
                if (log) alert(log);
            }
        });
    }

    if ($('.yearpicker').length || $('.datepicker').length || $('.datetimepicker').length) {

        // datetime / date / year picker
        var locale;
        var format;
        switch (app.locale) {
            case 'fr':
                locale = app.locale;
                format = 'DD/MM/YYYY HH:mm';
                break;
            case 'en':
                format = 'DD/MM/YYYY hh:mm A';
                locale = 'en-gb';
                break;
        }

        // we activate the datepicker
        var yearpicker = $('.yearpicker');
        if (yearpicker.length) {
            yearpicker.datetimepicker({
                locale: locale,
                format: 'YYYY'
            });
        }

        // we activate the datepicker
        var datepicker = $('.datepicker');
        if (datepicker.length) {
            datepicker.datetimepicker({
                locale: locale,
                format: 'DD/MM/YYYY'
            });
        }

        // we activate the datetimepicker
        var datetimepicker = $('.datetimepicker');
        if (datetimepicker.length) {
            datetimepicker.datetimepicker({
                locale: locale,
                format: format
            });
        }
    }

    // if we detect permissions checkboxes
    if ($('.permission.parent').length) {

        // check parent checkbox or not, according if all the children checkboxes are checked or not
        function evalChildrenCheckboxes(elt) {
            var permission_group = elt.attr('id').split(app.permissions_separator)[0];
            var checked = true;
            $('.permission input[type=checkbox]').each(function (key, checkbox) {
                var id = checkbox.id.split(app.permissions_separator);
                if (id[0] === permission_group && id[1]) {
                    if (!checkbox.checked) {
                        checked = false;
                    }
                }
            });
            $('input#' + permission_group).prop('checked', checked);
        }

        // permission child checkboxes check on click on the parent
        $('.permission.parent input').click(function () {
            var permission_group = $(this).attr('id');
            var checked = $(this).is(':checked');
            $('.permission input[type=checkbox]').each(function (key, checkbox) {
                if (checkbox.id.split(app.permissions_separator)[0] === permission_group) {
                    checkbox.checked = checked;
                }
            });
        });

        // on check on child checkbox, manage parent checkbox check status
        $('.permission input[type=checkbox]').change(function () {
            evalChildrenCheckboxes($(this));
        });
    }

    // we manage the activation in list from the swipe button
    $('.swipe-btn.activate').click(function () {

        // we get the swipe group
        var swipe_group = $(this).parent('.swipe-group');

        // we add a loading spinner
        swipe_group.append('<span class="swipe-action-icon">' + app.loading_spinner + '</span>');

        // we get the ajax request data
        var url = $(this).attr('data-url');
        var id = $(this).attr('data-id');

        // we get the form
        var form = $(this).closest('form');

        // we execute the ajax activation on the form submit
        form.one('submit', function (e) {

            // we prevent the default browser behavior
            e.preventDefault();

            // we get the form object
            var $this = $(this);

            // we do the post request
            $.ajax({
                method: $this.attr('method'),
                url: $this.attr('action'),
                data: {
                    _token: $this.find('input[name=_token]').val(),
                    active: !$this.find('input[name=active]').is(':checked')
                }
            }).done(function (data) {
                // we replace the loading spinner by a success icon
                swipe_group.find('.swipe-action-icon').remove();
                swipe_group.append('<span class="swipe-action-icon text-success">' + app.success_icon + '</i></span>');

                // we show the success messages
                if (data.message) {
                    data.message.forEach(function (success) {
                        $.notify({
                            // options
                            title: app.success_icon,
                            message: success
                        }, {
                            // settings
                            type: 'success',
                            delay: 6000,
                            allow_dismiss: false,
                            showProgressbar: true,
                            animate: {
                                enter: 'animated bounceInDown',
                                exit: 'animated bounceOutUp'
                            }
                        });
                    });
                }
            }).fail(function (data) {
                // we show the error messages
                if (data.responseJSON.message) {
                    data.responseJSON.forEach(function (error) {
                        $.notify({
                            // options
                            title: app.error_icon,
                            message: error
                        }, {
                            // settings
                            type: 'danger',
                            delay: 6000,
                            allow_dismiss: false,
                            showProgressbar: true,
                            animate: {
                                enter: 'animated bounceInDown',
                                exit: 'animated bounceOutUp'
                            }
                        });
                    });
                }

                // we replace the loading spinner by an error icon
                swipe_group.find('.swipe-action-icon').remove();
                swipe_group.append('<span class="swipe-action-icon text-danger">' + app.error_icon + '</span>');

                // we set the checkbox at its original value
                window.setTimeout(function () {
                    swipe_group.find('input.swipe').prop('checked', data.responseJSON.active);
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

        // we submit the form
        form.submit();
    });

    // if we detect a markdown text zone
    var markdown = $('.markdown');
    if (markdown.length) {
        // we activate the markdown editor
        new SimpleMDE({
            element: markdown[0],
            hideIcons: ['side-by-side', 'fullscreen'],
            spellChecker: false
        });
    }

    // we submit the form on select change detection
    var autosubmit = $('select.autosubmit');
    if (autosubmit.length) {
        autosubmit.change(function () {
            $(this).closest('form').submit();
        });
    }
});

