$(document).ready(function() {
    $(".switch:checkbox").checkboxpicker();
    number();
    $("#i_area").select2({
        dropdownAutoWidth: true,
        width: "100%",
        containerCssClass: "select-sm",
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_area",
            dataType: "json",
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
                };
                return query;
            },
            processResults: function(data) {
                return {
                    results: data,
                };
            },
            cache: false,
        },
    }).change(function(event) {
        $("#i_customer").val("");
        $("#i_customer").html("");
        $("#i_salesman").val("");
        $("#i_salesman").html("");
        $(".clear").val("");
    });

    $("#i_customer").select2({
        dropdownAutoWidth: true,
        width: "100%",
        containerCssClass: "select-sm",
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_customer",
            dataType: "json",
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
                    i_area: $("#i_area").val(),
                };
                return query;
            },
            processResults: function(data) {
                return {
                    results: data,
                };
            },
            cache: false,
        },
    }).change(function(event) {
        $.ajax({
            type: "post",
            data: {
                i_customer: $(this).val(),
            },
            url: base_url + $("#path").val() + "/get_customer_detail",
            dataType: "json",
            success: function(data) {
                if (data["header"] != null) {
                    $("#address").val(data["header"][0]["e_customer_address"]);
                    /* $("#i_price_group").val(data["header"][0]["i_price_group"]);
                    $("#e_price_group").val(data["header"][0]["e_price_groupname"]);
                    $("#pkp").val(data["header"][0]["pkp"]);
                    $("#f_ttb_pkp").val(data["header"][0]["f_customer_pkp"]);
                    $("#e_customer_pkpnpwp").val(data["header"][0]["e_customer_npwpcode"]);
                    $("#eppn").val(data["header"][0]["eppn"]); */
                    $("#i_salesman").val("");
                    $("#i_salesman").html("");
                } else {
                    $(".clear").val("");
                    $("#i_salesman").val("");
                    $("#i_salesman").html("");
                    Swal.fire({
                        type: "error",
                        title: g_maaf,
                        text: "Non-existent data : (",
                        confirmButtonClass: "btn btn-danger",
                    });
                }
            },
            error: function() {
                swal("500 internal server error : (");
                Swal.fire({
                    type: "error",
                    title: g_maaf,
                    text: "500 internal server error : (",
                    confirmButtonClass: "btn btn-danger",
                });
            },
        });
    });

    $("#i_salesman").select2({
        dropdownAutoWidth: true,
        width: "100%",
        containerCssClass: "select-sm",
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_salesman",
            dataType: "json",
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
                    i_customer: $("#i_customer").val(),
                };
                return query;
            },
            processResults: function(data) {
                return {
                    results: data,
                };
            },
            cache: false,
        },
    });

    $("#d_document").change(function() {
        number();
    });

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

function hetang() {
    let kotor = parseFloat(formatulang($('#v_gross').val()));
    let diskon = parseFloat(formatulang($('#v_discount').val()));
    if (isNaN(kotor)) {
        kotor = 0;
    }
    if (isNaN(diskon)) {
        diskon = 0;
    }
    if (diskon >= kotor) {
        Swal.fire({
            type: "error",
            title: g_maaf,
            text: "Nilai Diskon = '" + $('#v_discount').val() + "' tidak boleh lebih atau sama dengan Nilai Kotor = '" + $('#v_gross').val() + "' !",
            confirmButtonClass: "btn btn-danger",
        });
        $('#v_discount').val(0);
    } else {
        $('#v_netto').val(formatcemua(kotor - diskon));
        $('#v_sisa').val(formatcemua(kotor - diskon));
    }
}

function number() {
    $.ajax({
        type: "post",
        data: {
            tanggal: $("#d_document").val(),
        },
        url: base_url + $("#path").val() + "/number ",
        dataType: "json",
        success: function(data) {
            $("#i_document").val(data);
        },
        error: function() {
            swal("Error :)");
        },
    });
}