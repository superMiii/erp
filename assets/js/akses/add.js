$(document).ready(function() {
    $(".duallistbox").bootstrapDualListbox();
    var controller = $("#path").val();
    $(".select2").select2();
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $("form").on("submit", function(e) {
        //bind event on form submit.
        e.preventDefault();
        var formData = new FormData(this);
        if (formData) {
            sweetadd(controller, formData);
        }
    });
});