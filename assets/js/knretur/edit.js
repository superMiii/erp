$(document).ready(function() {
    hetang();
    $(".switch:checkbox").checkboxpicker();

    $("#i_pajak").select2({
        placeholder: g_pilihdata,
        dropdownAutoWidth: true,
        width: '100%',
        containerCssClass: 'select-xs',
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_pajak",
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
    }).change(function() {
        $.ajax({
            type: "post",
            data: {
                i_seri_pajak: $(this).val(),
                i_customer: $('#i_customer').val(),
            },
            url: base_url + $("#path").val() + "/detail_pajak ",
            dataType: "json",
            success: function(data) {
                $("#d_pajak").val(data);
            },
            error: function() {
                swal("Error :)");
            },
        });
    });

    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $("form").on("submit", function(e) {
        //bind event on form submit.
        e.preventDefault();
        var formData = new FormData(this);
        if (formData) {
            sweeteditv33($("#path").val(), $("#d_from").val(), $("#d_to").val(), $("#harea").val(), formData);
        }
    });
});


function hetang() {
    let subtotal = 0;
    let dis = 0;
    for (let i = 1; i <= $('#jml').val(); i++) {
        if (typeof $('#v_unit_price' + i).val() !== 'undefined') {
            let v_unit_price = parseFloat(formatulang($('#v_unit_price' + i).val()));
            let n_quantity = parseFloat($('#n_quantity' + i).val());
            if (v_unit_price == '' || v_unit_price == null) {
                v_unit_price = 0;
            }
            if (n_quantity == '' || n_quantity == null) {
                n_quantity = 0;
            }
            let v_jumlah = v_unit_price * n_quantity;
            let v_discount1 = v_jumlah * (parseFloat(formatulang($("#n_ttb_discount1" + i).val())) / 100);
            $("#v_ttb_discount1" + i).val(Math.round(v_discount1));
            let v_discount2 = (v_jumlah - v_discount1) * (parseFloat(formatulang($("#n_ttb_discount2" + i).val())) / 100);
            $("#v_ttb_discount2" + i).val(Math.round(v_discount2));
            let v_discount3 = (v_jumlah - v_discount1 - v_discount2) * (parseFloat(formatulang($("#n_ttb_discount3" + i).val())) / 100);
            $("#v_ttb_discount3" + i).val(Math.round(v_discount3));
            let v_total_discount = v_discount1 + v_discount2 + v_discount3;
            let v_total = v_jumlah;
            $('#total_item' + i).val(formatcemua(v_total));
            subtotal += v_total;
            dis += v_total_discount;
        }
    }
    $('#distotal').val(formatcemua(dis));
    $('#v_ttb_gross').val(formatcemua(subtotal));
    let v_ttb_discounttotal = parseFloat(formatulang($('#v_ttb_discounttotal').val()));
    let v_ttb_dpp = subtotal - v_ttb_discounttotal - dis;
    $('#v_ttb_dpp').val(formatcemua(v_ttb_dpp));
    let v_ttb_ppn = v_ttb_dpp * parseFloat($('#n_ppn').val()) / 100;
    $('#v_ttb_ppn').val(formatcemua(v_ttb_ppn));

    if ($('#f_ttb_plusppn').val() !== 'f'){
        $('#v_ttb_netto').val(formatcemua(v_ttb_dpp + v_ttb_ppn));
    }else{
        $('#v_ttb_netto').val(formatcemua(v_ttb_dpp));
    }
    // $('#v_ttb_netto').val(formatcemua(v_ttb_dpp + v_ttb_ppn));
}

// function hetangdiskon() {
//     let n_discount_total = parseFloat(formatulang($('#n_ttb_discounttotal').val()));
//     let v_ttb_gross = parseFloat(formatulang($('#v_ttb_gross').val()));
//     if (n_discount_total == '' || n_discount_total == null) {
//         n_discount_total = 0;
//     }
//     if (v_ttb_gross == '' || v_ttb_gross == null) {
//         v_ttb_gross = 0;
//     }
//     let v_ttb_discounttotal = v_ttb_gross * (n_discount_total / 100);
//     $('#v_ttb_discounttotal').val(formatcemua(v_ttb_discounttotal));
//     hetang();
// }

// function hetangdiskonrp() {
//     let v_ttb_discounttotal = parseFloat(formatulang($('#v_ttb_discounttotal').val()));
//     let v_ttb_gross = parseFloat(formatulang($('#v_ttb_gross').val()));
//     if (v_ttb_discounttotal == '' || v_ttb_discounttotal == null) {
//         v_ttb_discounttotal = 0;
//     }
//     if (v_ttb_gross == '' || v_ttb_gross == null) {
//         v_ttb_gross = 0;
//     }
//     let n_ttb_discounttotal = (v_ttb_discounttotal / v_ttb_gross) * 100;
//     $('#n_ttb_discounttotal').val(n_ttb_discounttotal);
//     hetang();
// }