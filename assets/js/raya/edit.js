$(document).ready(function() {
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $("form").on("submit", function(e) {
        //bind event on form submit.
        e.preventDefault();
        var formData = new FormData(this);
        if (formData) {
            sweeteditv33sup($("#path").val(), $("#d_from").val(), $("#d_to").val(), $("#hsup").val(), formData);
        }
    });
});