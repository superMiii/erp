$(document).ready(function() {
    $('.skin-square input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
    });
    $('#day').val(set_day($('#d_document').val()));
});