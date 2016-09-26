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

// we set the dropzone autodiscover on false
Dropzone.autoDiscover = false;

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

    if ($('.yearpicker').length || $('.datepicker').length || $('.datetimepicker').length || $('.timepicker').length) {

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

        // we activate the timepicker
        var timepicker = $('.timepicker');
        if (timepicker.length) {
            timepicker.datetimepicker({
                locale: locale,
                format: 'HH:mm'
            });
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
                    data.responseJSON.message.forEach(function (error) {
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

    // collapse other menu elements when toggling a new one
    $('[data-toggle="collapse"]').click(function () {
        // we collapse all the other menu elements
        $('.collapse').not(this).collapse('hide');
    });

    // we manage the file dropzone behaviour
    var dropzone = $('form#dropzone');
    if (dropzone.length) {
        // we set the uploaded and rejected files arrays
        var uploaded_files = [];
        var rejected_files = [];
        var upload_process_status = false;
        // we get the template html and remove it from the page
        var previewNode = document.querySelector("#template");
        previewNode.id = "";
        var previewTemplate = previewNode.parentNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);
        // we instantiate the dropzone object
        var myDropzone = new Dropzone('form#dropzone', {
            paramName: dropzone.attr('data-param'),
            maxFilesize: dropzone.attr('data-max-megabytes-size'),
            thumbnailWidth: 40,
            thumbnailHeight: 40,
            parallelUploads: 20,
            previewTemplate: previewTemplate,
            autoQueue: false,
            acceptedFiles: dropzone.attr('data-accepted-extensions'),
            previewsContainer: "#previews",
            dictInvalidFileType: app.invalid_file_type,
            dictFileTooBig: app.file_too_big
        });
        // we update the total progress bar on load
        myDropzone.on("totaluploadprogress", function (progress) {
            // we complete the progress bar
            $("#total-progress .progress-bar").css('width', progress + "%");
        });
        myDropzone.on("sending", function (file) {
            // we show the total progress bar when upload starts
            $("#total-progress").show();
            // we disable the start button
            $("#actions .start").attr("disabled", "disabled");
            // we set the upload process status to true
            upload_process_status = true;
        });
        // we hide the total progress bar when nothing's uploading anymore
        myDropzone.on("queuecomplete", function (progress) {
            if (upload_process_status === true) {
                // we hide the total progress bar when upload is finished
                $("#total-progress").hide();
                // we reload the page with the uploaded and rejected files data
                var url = app.reload_route;
                if (uploaded_files.length) {
                    url += '?uploaded=' + JSON.stringify(uploaded_files);
                }
                if (rejected_files.length) {
                    if (!uploaded_files.length) {
                        url += '?';
                    } else {
                        url += '&';
                    }
                    url += 'rejected=' + JSON.stringify(rejected_files);
                }
                location.href = url;
            }
            // we reset the upload process status to false
            upload_process_status = false;
        });
        // we launch the files transfer
        $("#actions .start").click(function () {
            myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
        });
        // we remove all the selected files
        $("#actions .cancel").click(function () {
            myDropzone.removeAllFiles(true);
        });
        // when a file is added
        myDropzone.on("addedfile", function (progress) {
            // we show the previews zone
            $('#previews').show();
            // we enable the start button
            $("#actions .start").removeAttr("disabled");
        });
        // when the previews zone is empty
        myDropzone.on("reset", function (progress) {
            // we hide the previews zone
            $('#previews').hide();
            // we disable the start button
            $("#actions .start").attr("disabled", "disabled");
        });
        // when a file is uploaded with success
        myDropzone.on("success", function (file, response) {
            // we add the file to the uploaded ones
            uploaded_files.push({
                original_name: file.name,
                stored_name: response
            });
            // we remove the file from the previews zone
            myDropzone.removeFile(file);
        });
        // when an error occure during a file upload
        myDropzone.on("error", function (file, response) {
            if (upload_process_status === true) {
                // we add the file to the rejected ones
                rejected_files.push({
                    name: file.name,
                    message: response
                });
                // we remove the file from the previews zone
                myDropzone.removeFile(file);
            }
        });
        // we custom the dropzone design on dragover
        myDropzone.on("dragover", function () {
            dropzone.css('border-color', '#5BC0DE');
        });
        myDropzone.on("dragleave", function () {
            dropzone.css('border-color', '#337AB7');
        });
    }

    // we manage the input update on change
    var inputs = $('input.submit-on-change');
    if (inputs.length) {
        // we prepare the update value function
        function updateValue($this) {
            var form = $this.closest('form');
            // we show the loading spinner
            form.find('.input-change-icon').remove();
            form.append('<span class="input-change-icon">' + app.loading_spinner + '</span>');
            var value = null;
            // we launch the ajax request
            $.ajax({
                method: form.attr('method'),
                url: form.attr('action'),
                data: {
                    _token: form.find('input[name=_token]').val(),
                    _method: form.find('input[name=_method]').val(),
                    value: form.find('input.value').val(),
                }
            }).done(function (data) {
                // we replace the loading spinner by a success icon
                form.find('.input-change-icon').remove();
                form.append('<span class="input-change-icon text-success">' + app.success_icon + '</i></span>');
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
                // we set the value
                value = data.value;
            }).fail(function (data) {
                // we show the error messages
                if (data.responseJSON.message) {
                    data.responseJSON.message.forEach(function (error) {
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
                // we set the value
                value = data.responseJSON.value;
                // we replace the loading spinner by an error icon
                form.find('.input-change-icon').remove();
                form.append('<span class="input-change-icon text-danger">' + app.error_icon + '</span>');
            }).always(function () {
                // we fade out the icon
                form.find('.input-change-icon').css({
                    '-webkit-animation': 'fadeOut 10000ms',
                    '-moz-animation': 'fadeOut 10000ms',
                    '-ms-animation': 'fadeOut 10000ms',
                    '-o-animation': 'fadeOut 10000ms',
                    'animation': 'fadeOut 10000ms'
                }).promise().done(function () {
                    // keep invisible
                    $(this).css('opacity', 0);
                });
                // we show the value
                $this.val(value);
            });
        }

        //setup before functions
        var typing_timing = 500;
        var typing_timer;
        // we set the listeners on the inputs
        inputs.each(function () {
            // we store the object
            var $this = $(this);
            // on keyup, we lauch the countdown before saving
            $this.on('keyup', function () {
                clearTimeout(typing_timer);
                typing_timer = setTimeout(function () {
                    updateValue($this);
                }, typing_timing);
            });
            // on keydown, we clear the countdown
            $this.on('keydown', function () {
                clearTimeout(typing_timer);
            });
        });
    }

    // dashboard management
    var dashboard = $('#content.dashboard');
    if(dashboard.length) {

        // we manage the predefined periods
        $('#pre_defined_period').change(function () {
            var selected_period = app.pre_formatted_periods[$(this).val()];
            $('#start_date').val(selected_period['start_date']);
            $('#end_date').val(selected_period['end_date']);
        });

        var google_charts = {
            loaded: false,
            url: 'https://www.gstatic.com/charts/loader.js',
            data: null,
            callScript: function () {
                window.drawCharts = function () {
                    google_charts.drawCharts();
                };
                $.ajax({
                    url: google_charts.url,
                    dataType: 'script'
                }).done(function () {
                    google.charts.load('current', {packages: ['corechart', 'table'], language: app.locale});
                    google.charts.setOnLoadCallback(drawCharts);
                    google_charts.loaded = true;
                });
            },
            drawTopBrowsersChart: function (data) {
                var top_browsers_data_table = new google.visualization.DataTable();
                top_browsers_data_table.addColumn('string', 'Element');
                top_browsers_data_table.addColumn('number', 'Percentage');
                var formatted_top_browsers_data = [];
                $.each(data['top_browsers'], function (key, item) {
                    formatted_top_browsers_data.push([
                        item.browser,
                        item.sessions
                    ]);
                });
                top_browsers_data_table.addRows(formatted_top_browsers_data);
                // instantiate and draw the chart.
                var options = {
                    // pieHole: 0.4,
                };
                var top_browsers_chart = new google.visualization.PieChart(document.getElementById('top_browsers'));
                top_browsers_chart.draw(top_browsers_data_table, options);
            },
            drawTopReferrersChart: function (data) {
                var top_referrers_data_table = new google.visualization.DataTable();
                top_referrers_data_table.addColumn('string', 'Element');
                top_referrers_data_table.addColumn('number', 'Percentage');
                var formatted_top_referrers_data = [];
                $.each(data['top_referrers'], function (key, item) {
                    formatted_top_referrers_data.push([
                        item.url,
                        item.pageViews
                    ]);
                });
                top_referrers_data_table.addRows(formatted_top_referrers_data);
                // instantiate and draw the chart.
                var options = {
                    // pieHole: 0.4,
                };
                var top_referrers_chart = new google.visualization.PieChart(document.getElementById('top_referrers'));
                top_referrers_chart.draw(top_referrers_data_table, options);
            },
            drawMostVisitedPagesChart: function (data) {
                var most_visited_pages_data_table = new google.visualization.DataTable();
                most_visited_pages_data_table.addColumn('string', app.trans.pages);
                most_visited_pages_data_table.addColumn('string', app.trans.url);
                most_visited_pages_data_table.addColumn('number', app.trans.views);
                var formatted_most_visited_pages_data = [];
                $.each(data['most_visited_pages'], function (key, item) {
                    formatted_most_visited_pages_data.push([
                        item.pageTitle,
                        item.url,
                        item.pageViews
                    ]);
                });
                most_visited_pages_data_table.addRows(formatted_most_visited_pages_data);
                // instantiate and draw the chart.
                var options = {
                    showRowNumber: true,
                    width: '100%',
                    height: '100%'
                };
                var most_visited_pages_chart = new google.visualization.Table(document.getElementById('most_visited_pages'));
                most_visited_pages_chart.draw(most_visited_pages_data_table, options);
            },
            drawVisitorsChart: function (data) {
                var visitors_data_table = new google.visualization.DataTable();
                visitors_data_table.addColumn('string', 'Date');
                visitors_data_table.addColumn('number', app.trans.visitors);
                visitors_data_table.addColumn('number', app.trans.page_views);
                var formatted_visitors_data = [];
                $.each(data['visitors_and_page_views'], function (key, item) {
                    formatted_visitors_data.push([
                        item.date,
                        item.visitors,
                        item.page_views
                    ]);
                });
                visitors_data_table.addRows(formatted_visitors_data);
                // instantiate and draw the chart.
                var options = {
                    width: '100%',
                    height: '100%',
                };
                var visitors_chart = new google.visualization.AreaChart(document.getElementById('visitors'));
                visitors_chart.draw(visitors_data_table, options);
            },
            drawOtherStatsChart: function (data) {
                var other_stats_data_table = new google.visualization.DataTable();
                other_stats_data_table.addColumn('string', 'Date');
                other_stats_data_table.addColumn('number', app.trans.other_stats);
                other_stats_data_table.addColumn('number', app.trans.page_views);
                var formatted_other_stats_data = [];
                $.each(data['other_stats'], function (key, item) {
                    formatted_other_stats_data.push([
                        item.date,
                        item.form_downloads,
                        item.qr_code_scans
                    ]);
                });
                other_stats_data_table.addRows(formatted_other_stats_data);
                // instantiate and draw the chart.
                var options = {
                    // width: '100%',
                    // height: '100%',
                };
                var other_stats_chart = new google.visualization.ColumnChart(document.getElementById('other_stats'));
                other_stats_chart.draw(other_stats_data_table, options);
            },
            drawCharts: function () {
                if (!google_charts.loaded) {
                    google_charts.callScript();
                } else {
                    google_charts.drawVisitorsChart(google_charts.data);
                    google_charts.drawMostVisitedPagesChart(google_charts.data);
                    google_charts.drawTopBrowsersChart(google_charts.data);
                    google_charts.drawTopReferrersChart(google_charts.data);
                    google_charts.drawOtherStatsChart(google_charts.data);
                }
            }
        };

        $(window).resize(function () {
            google_charts.drawCharts();
        });

        var $form = $('#period_form');
        $('#period_form button[type=submit]').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            // we get the html contained into the button
            var html = $this.html();
            // we remove the fontawesome icon
            var begin = html.indexOf('<i');
            var end = html.indexOf('i>');
            var previous_icon = html.substring(begin, end + 2);
            var cleaned_html = html.replace(previous_icon, '');
            // we put the loading spinner
            $this.html(app.loading_spinner + ' ' + cleaned_html);
            $.ajax({
                method: $form.attr('method'),
                url: $form.attr('action'),
                data: {
                    start_date: $('#start_date').val(),
                    end_date: $('#end_date').val(),
                }
            }).done(function (data) {
                $('.chart').removeClass('hidden');
                // we draw the charts
                google_charts.data = data;
                google_charts.drawCharts();
                // we get the html contained into the button
                var html = $this.html();
                // we remove the fontawesome spinner icon
                var begin = html.indexOf('<i');
                var end = html.indexOf('i>');
                var to_remove = html.substring(begin, end + 2);
                var cleaned_html = html.replace(to_remove, '');
                // we put the loading spinner
                $this.html(previous_icon + ' ' + cleaned_html);
            }).fail(function (data) {
                // we show the error messages
                if (data.responseJSON.message) {
                    data.responseJSON.message.forEach(function (error) {
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
            });
        });
    }
});

