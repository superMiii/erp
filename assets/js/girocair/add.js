$(document).ready(function() {
    $('#ceklis').click(function(event) {
        if ($('#ceklis').is(':checked')) {
            $("#d_giro_tolak").attr("readonly", false);
        } else {
            $("#d_giro_tolak").attr("readonly", true);
        }
    });
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $("form").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        if (formData) {
            sweetaddv33($("#path").val(), $("#d_from").val(), $("#d_to").val(), $("#harea").val(), formData);
        }
    });
});