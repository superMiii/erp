$(document).ready(function(){
	const passwordInput = document.getElementById("passwordkr");
	const passwordInput2 = document.getElementById("passwordyz");
	const showPasswordCheckbox = document.getElementById("showPassword");

	showPasswordCheckbox.addEventListener("change", function() {
		if (showPasswordCheckbox.checked) {
			passwordInput.type = "text";
			passwordInput2.type = "text";
		} else {
			passwordInput.type = "password";
			passwordInput2.type = "password";
		}
	});
});
