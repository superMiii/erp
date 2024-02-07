$(document).ready(function() {
    $(".switch:checkbox").checkboxpicker();
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $("form").on("submit", function(e) {
        //bind event on form submit.
        e.preventDefault();
        var formData = new FormData(this);
        if (formData) {
            sweetaddv33($("#path").val(), $("#d_from").val(), $("#d_to").val(), $("#harea").val(), formData);
        }
    });
});