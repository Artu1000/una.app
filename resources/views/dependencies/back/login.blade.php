<script type="text/javascript">
    function checkIfPasswordIsEmpty(){
        // if password input is filled, consider it as ok
        if ($('#inputPassword').val()) {
            $('#passwordFormGroup').addClass('ok');
        }
        else if(!$('#inputPassword').val()) {
            // if no password, do nothing
        }
        else {
            $('#passwordFormGroup').addClass('nok');
        }
    }

    function setResetPasswordEmailStatusAndDisableResetButtonIfBlockerRemains(){
        // copy email in the reset form
        $('#forgotten_pswd_email').val($('#inputEmail').val());
        // disable button by default
        $('#resetPasswordSubmit').addClass('disabled');
        // remove status form group class
        $('#emailResetFormGroup').removeClass('ok nok');
        // if emails are the same and email source is valid
        if( $('#forgotten_pswd_email').val() === $('#inputEmail').val() && $('#emailFormGroup').hasClass('ok') ){
            $('#emailResetFormGroup').addClass('ok');
            $('#resetPasswordSubmit').removeClass('disabled');
        }
    }

    $(function () {
        checkValidity('#inputEmail', 'email', '/verifier/validate', null,
                setResetPasswordEmailStatusAndDisableResetButtonIfBlockerRemains);
        checkValidity('#inputPassword', null, null, checkIfPasswordIsEmpty);
    });
</script>