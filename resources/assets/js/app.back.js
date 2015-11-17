// custom file input
$(document).on('change', '.btn-file :file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
});

$(function() {

    // custom file input
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
});

