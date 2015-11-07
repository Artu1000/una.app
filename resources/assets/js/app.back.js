$(document).on('change', '.btn-file :file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
});

$(function() {

    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
        var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;

        console.log(numFiles);
        console.log(label);

        if( input.length ) {
            input.val(log);
        } else {
            if( log ) alert(log);
        }
    });

    // datetime picker
    $('.datepicker').datetimepicker({
        locale: 'fr',
        format: 'DD/MM/YYYY'
    });

    // replace button fontawesome icon by loading spinner on click
    $('.spin-on-click').click(function(e){

        // we get the html contained into the button
        var html = $(this).html();

        // we remove the fontawesome icon
        var begin = html.indexOf('<i');
        var end = html.indexOf('i>');
        var to_remove = html.substring(begin, end + 2);
        var cleaned_html = html.replace(to_remove, '');

        // we put the loading spinner
        $(this).html('<i class="fa fa-spinner fa-pulse"></i> ' + cleaned_html);
    });
});

