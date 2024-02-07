$(document).ready(function() {
$("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $('form').on('submit', function(e) { //bind event on form submit.
        e.preventDefault();
        var controller = 'main';
        var formData = new FormData(this);
        if (formData) {
            sweetedit1(controller, formData);
        }
    });
});