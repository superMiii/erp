$(document).ready(function() {
    var controller = $("#path").val();

    $('.skin-square input').iCheck({
        checkboxClass: 'icheckbox_square-red',
        radioClass: 'iradio_square-red',
    });
    
    $(".select2").select2();
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $('form').on('submit', function(e) { //bind event on form submit.
        e.preventDefault();
        var formData = new FormData(this);
        if (formData) {
            sweetadd(controller, formData);
        }
    });
});