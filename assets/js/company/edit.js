$(document).ready(function() {

	$('.skin-square input').iCheck({
        checkboxClass: 'icheckbox_square-red',
        radioClass: 'iradio_square-red',
    });
    
    var controller = $("#path").val();
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $('form').on('submit', function(e) { //bind event on form submit.
        e.preventDefault();
        var formData = new FormData(this);
        if (formData) {
            sweetedit(controller, formData);
        }
    });

    $('#chk-sarua').on('ifChanged', function(event) {
        if (event.target.checked == true) {
            $('#alamat_npwp').val($('#alamat').val());
        } else {
            $('#alamat_npwp').val('');
        }
    });
});