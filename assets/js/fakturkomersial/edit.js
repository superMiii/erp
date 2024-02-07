$(document).ready(function() {
    var controller = $("#path").val();
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $('form').on('submit', function(e) { //bind event on form submit.
        e.preventDefault();
        var formData = new FormData(this);
        if (formData) {
        	var start = $('#n_start').val().replaceAll(',', '');
			var end = $('#n_end').val().replaceAll(',', '');
			if (start >= end) {
				swal('Nomor Mulai Harus Lebih Kecil Dari Nomor Akhir');
			}else {
				sweetedit(controller, formData);
			}
        }
    });
});