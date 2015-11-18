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

    // permission child checkboxes check on click on the parent
    $('.permission.parent').click(function(e){
        e.preventDefault();
        var parent_checkbox = $(this).find('input');
        var permission_group = parent_checkbox.attr('id');
        var checked = parent_checkbox.is(":checked");
        $('.permission input[type=checkbox]').each(function(key, checkbox){
            if(checkbox.id.split('.')[0] === permission_group){
                checkbox.checked = !checked;
            }
        });
    });

    // on check on child checkbox, manage parent checkbox check status
    $('.permission input[type=checkbox]').click(function(e){
        var permission_group = $(this).attr('id').split('.')[0];
        var checked = true;
        $('.permission input[type=checkbox]').each(function(key, checkbox){
            var id = checkbox.id.split('.');
            if(id[0] === permission_group && id[1]){
                if(!checkbox.checked){
                    checked = false;
                }
            }
        });
        $('input#' + permission_group).prop('checked', checked);
    });
});

