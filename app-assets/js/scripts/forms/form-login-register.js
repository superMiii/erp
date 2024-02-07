$(document).ready(function(){
	'use strict';
	//Login Register Validation
	if($("form.form-horizontal").attr("novalidate")!=undefined){
		$("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
	}

	// Remember checkbox
	if($('.chk-remember').length){
		$('.chk-remember').iCheck({
			checkboxClass: 'icheckbox_square-blue',
			radioClass: 'iradio_square-blue',
		});
	}
	// For change default year in copyright
	var $year = new Date().getFullYear();
	$(".year").text($year);

	const passwordInput = document.getElementById("user-password");
	const showPasswordCheckbox = document.getElementById("showPassword");

	showPasswordCheckbox.addEventListener("change", function() {
		if (showPasswordCheckbox.checked) {
			passwordInput.type = "text";
		} else {
			passwordInput.type = "password";
		}
	});
});
