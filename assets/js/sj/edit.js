$(document).ready(function() {
    /* $('#i_document').inputmask("aa-9999-999999"); */
    /* pickdateedit(); */
    /* $('#ceklis').click(function(event) {
        if ($('#ceklis').is(':checked')) {
            $("#i_document").attr("readonly", false);
        } else {
            $("#i_document").attr("readonly", true);
            $("#ada").attr("hidden", true);
            $("#i_document").val($("#i_document_old").val());
        }
    });

    $("#i_document").keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'i_document': $(this).val(),
            },
            url: base_url + $("#path").val() + '/cek_code',
            dataType: "json",
            success: function(data) {
                if (data == 1) {
                    $("#ada").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                } else {
                    $("#ada").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function() {
                swal('Error :)');
            }
        });
    }); */

    /* $("#d_document").change(function() {
        number();
    }); */

    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $('form').on('submit', function(e) { //bind event on form submit.
        let tabel = $("#tabledetail tbody tr").length;
        let ada = false;
        if (tabel < 1) {
            Swal.fire({
                type: "error",
                title: g_maaf,
                text: g_detailmin,
                confirmButtonClass: "btn btn-danger",
            });
            return false;
        }


        var jm =0;
        $("#tabledetail tbody tr").each(function() {
            $(this).find("td select").each(function() {
                if ($(this).val() == '' || $(this).val() == null) {
                    Swal.fire('Barang tidak boleh kosong!');
                    ada = true;
                }
            });
            
            $(this).find("td .n_order").each(function() {
                var kr = $(this).val();
                if ($(this).val() == '' || $(this).val() == null ) {
                    kr=0;

                    // Swal.fire('Quantity Tidak Boleh Kosong Atau 0!');
                    // ada = true;
                }
                jm+=parseFloat(kr);
            });
        });

        if (jm > 0) {
            if (!ada) {
                e.preventDefault();
                var formData = new FormData(this);
                if (formData) {
                    // sweetedit($("#path").val(), formData);
                    sweeteditv33($("#path").val(), $("#d_from").val(), $("#d_to").val(), $("#harea").val(), formData);
                }
            } else {
                return false;
            }
        }else{
            Swal.fire('Total Kirim SJ tidak boleh 0');
            return false;
        }
    });
});

function hitung() {
    var jml = $('#jml').val();

    var v_price = 0.00;
    var disc = $('#disc').val();
    var disc2 = $('#disc2').val();
    var disc3 = $('#disc3').val();

    var valid_disc = 0;
    var valid_disc2 = 0;
    var valid_disc3 = 0;

    if (disc > 0.00) valid_disc = disc / 100;
    if (disc2 > 0.00) valid_disc2 = disc2 / 100;
    if (disc3 > 0.00) valid_disc3 = disc3 / 100;

    var sub_total = 0.00;
    for (var x = 1; x <= jml; x++) {
        if ($('#n_order' + x).length && $('#i_product' + x).val() != "") {
            v_price = $('#v_price' + x).val().replaceAll(',', '');
            n_order = $('#n_order' + x).val().replaceAll(',', '');
            $('#n_disc' + x).val(disc);
            $('#n_disc2' + x).val(disc2);
            $('#n_disc3' + x).val(disc3);

            var gross = v_price * n_order;
            var v_disc = gross * valid_disc;
            var v_disc2 = (gross - v_disc) * valid_disc2;
            var v_disc3 = (gross - v_disc - v_disc2) * valid_disc3;
            var total_baris = gross - v_disc - v_disc2 - v_disc3;

            $('#v_disc' + x).val(number_format(v_disc, 2, '.', ','));
            $('#v_disc2' + x).val(number_format(v_disc2, 2, '.', ','));
            $('#v_disc3' + x).val(number_format(v_disc3, 2, '.', ','));
            $('#total_baris' + x).val(number_format(total_baris, 2, '.', ','));

            sub_total += total_baris;
        }
    }

    $('#tfoot_subtotal').val(number_format(sub_total, 2, '.', ','));
    //var foot_ndisc = $('#tfoot_n_diskon').val();
    var foot_vdisc = $('#tfoot_v_diskon').val().replaceAll(',', '');
    var dpp = sub_total - foot_vdisc;
    var ppn = 0;
    if ($('#ppn').val() == 't') ppn = dpp * 0.1;
    var total_final = dpp + ppn;
    $('#tfoot_v_dpp').val(number_format(dpp, 2, '.', ','));
    $('#tfoot_v_ppn').val(number_format(ppn, 2, '.', ','));
    $('#tfoot_total').val(number_format(total_final, 2, '.', ','));

}

/* function number() {
    $.ajax({
        type: "post",
        data: {
            'tanggal': $('#d_document').val(),
        },
        url: base_url + $("#path").val() + '/number ',
        dataType: "json",
        success: function(data) {
            $('#i_document').val(data);
        },
        error: function() {
            swal('Error :)');
        }
    });
} */

function cek(i) {
    if (parseInt($('#n_deliver' + i).val()) > parseInt($('#n_stock' + i).val())) {
        // Swal.fire('Any fool can use a computer')
        Swal.fire('Maaf Qty Kirim = ' + $('#n_deliver' + i).val() + ', tidak boleh lebih dari Stok = ' + $('#n_stock' + i).val());
        $('#n_deliver' + i).val($('#n_stock' + i).val());
    } else if (parseInt($('#n_deliver' + i).val()) > parseInt($('#n_order' + i).val())) {
        // Swal.fire('Any fool can use a computer')
        Swal.fire('Maaf Qty Kirim = ' + $('#n_deliver' + i).val() + ', tidak boleh lebih dari order = ' + $('#n_order' + i).val());
        $('#n_deliver' + i).val($('#n_order' + i).val());
    }
}