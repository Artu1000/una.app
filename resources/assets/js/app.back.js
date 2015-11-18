// custom file input
$(document).on('change', '.btn-file :file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
});

// check parent checkbox or not, according if all the children checkboxes are checked or not
function evalChildrenCheckboxes(elt){
    var permission_group = elt.attr('id').split('.')[0];
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
}

$(function() {

    // custom file input
    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
        var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;
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

    // permissions checkboxes
    // permission child checkboxes check on click on the parent
    $('.permission.parent input').click(function(){
        var permission_group = $(this).attr('id');
        var checked = $(this).is(':checked');
        $('.permission input[type=checkbox]').each(function(key, checkbox){
            if(checkbox.id.split('.')[0] === permission_group){
                checkbox.checked = checked;
            }
        });
    });
    // on check on child checkbox, manage parent checkbox check status
    $('.permission input[type=checkbox]').change(function(){
        evalChildrenCheckboxes($(this));
    });
});

