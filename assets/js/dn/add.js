$(document).ready(function() {
    number();
    hetang();
    $(".switch:checkbox").checkboxpicker();

    $("#d_document").change(function() {
        number();
    });

    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $("form").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        if (formData) {
            sweetaddv33sup($("#path").val(), $("#d_from").val(), $("#d_to").val(), $("#hsup").val(), formData);
        }
    });
});

function hetang() {
    let subtotal = 0;
    for (let i = 1; i <= $('#jml').val(); i++) {
        if (typeof $('#i_product' + i).val() !== 'undefined') {
            let v_unit_price = parseFloat(formatulang($('#v_unit_price' + i).val()));
            let n_order = parseFloat($('#n_order' + i).val());
            if (v_unit_price == '' || v_unit_price == null) {
                v_unit_price = 0;
            }
            if (n_order == '' || n_order == null) {
                n_order = 0;
            }
            if (isNaN(n_order)) {
                n_order = 0;
            }
            let v_jumlah = v_unit_price * n_order;
            $('#total_item' + i).val(formatcemua(v_jumlah));
            subtotal += v_jumlah;
        }
    }
    $('#tfoot_total').val(formatcemua(subtotal));
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