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
            swal(g_maaf, g_detailmin, "error");
            return false;
        }

        $("#tabledetail tbody tr").each(function() {
            $(this).find("td select").each(function() {
                if ($(this).val() == '' || $(this).val() == null) {
                    swal('Barang tidak boleh kosong!');
                    ada = true;
                }
            });
            /*$(this).find("td .n_order").each(function() {
                if ($(this).val() == '' || $(this).val() == null || $(this).val() == 0) {
                    swal('Quantity Tidak Boleh Kosong Atau 0!');
                    ada = true;
                }
            });*/
        });

        if (!ada) {
            e.preventDefault();
            var formData = new FormData(this);
            if (formData) {
                sweeteditv33($("#path").val(), $("#d_from").val(), $("#d_to").val(), $("#harea").val(), formData);
            }
        } else {
            return false;
        }
    });
});

function hitung() {
    var jml = $('#jml').val();
    var v_price = 0.00;
    // var disc = $('#disc').val();
    // var disc2 = $('#disc2').val();
    // var disc3 = $('#disc3').val();

    // var valid_disc = 0;
    // var valid_disc2 = 0;
    // var valid_disc3 = 0;

    // if (disc > 0.00) valid_disc = disc / 100;
    // if (disc2 > 0.00) valid_disc2 = disc2 / 100;
    // if (disc3 > 0.00) valid_disc3 = disc3 / 100;

    var sub_total = 0.00;
    for (var x = 1; x <= jml; x++) {
        if ($('#n_deliver' + x).length && $('#i_product' + x).val() != "") {
            v_price = $('#v_unit_price' + x).val().replaceAll(',', '');
            n_deliver = $('#n_deliver' + x).val().replaceAll(',', '');

            // $('#total_baris' + x).val(number_format(total_baris, 2, '.', ','));

            sub_total += v_price * n_deliver;
        }
    }

    // $('#tfoot_subtotal').val(number_format(sub_total, 2, '.', ','));
    // //var foot_ndisc = $('#tfoot_n_diskon').val();
    // var foot_vdisc = $('#tfoot_v_diskon').val().replaceAll(',', '');
    // var dpp = sub_total - foot_vdisc;
    // var ppn = 0;
    // if ($('#ppn').val() == 't') ppn = dpp * 0.1;
    // var total_final = dpp + ppn;
    // $('#tfoot_v_dpp').val(number_format(dpp, 2, '.', ','));
    // $('#tfoot_v_ppn').val(number_format(ppn, 2, '.', ','));
    $('#v_total').val(number_format(sub_total, 2, '.', ','));
    $('#terbilangnya').text(terbilang(sub_total));

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
    if (parseInt($('#n_receive' + i).val()) > parseInt($('#n_deliver' + i).val())) {
        Swal.fire('Maaf Qty Terima = ' + $('#n_receive' + i).val() + ', tidak boleh lebih dari Kirim = ' + $('#n_deliver' + i).val());
        $('#n_receive' + i).val($('#n_deliver' + i).val());
    } 
}