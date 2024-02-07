$(document).ready(function() {
    var controller = $("#path").val();
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $('form').on('submit', function(e) { //bind event on form submit.
        e.preventDefault();
        var formData = new FormData(this);
        if (formData) {
        	var start = $('#n_start').val().replaceAll(',', '');
			var end = $('#n_end').val().replaceAll(',', '');
			if (start.length != 8) {
                swal('Jumlah Karakter Mulai Harus 8 Digit');
            }else if (end.length != 8) {
                swal('Jumlah Karakter Akhir Harus 8 Digit');
            }else if (start.length != end.length) {
				swal('Jumlah Karakter Harus Sama');
			}else {
				sweetedit(controller, formData);
			}
        }
    });
});