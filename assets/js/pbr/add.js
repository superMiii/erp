$(document).ready(function() {
    /* $('#i_document').inputmask("aa-9999-999999"); */
    number();
    /* pickdate(); */
    /* $('#ceklis').click(function(event) {
        if ($('#ceklis').is(':checked')) {
            $("#i_document").attr("readonly", false);
        } else {
            $("#i_document").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    }); */

    /* $("#i_document").keyup(function() {
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
                Swal.fire('Error :)');
            }
        });
    }); */

    $("#d_document").change(function() {
        number();
    });

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

        $("#tabledetail tbody tr").each(function() {
            $(this).find("td select").each(function() {
                if ($(this).val() == '' || $(this).val() == null) {
                    Swal.fire('Barang tidak boleh kosong!');
                    ada = true;
                }
            });
            // $(this).find("td .n_order").each(function() {
            //     if ($(this).val() == '' || $(this).val() == null || $(this).val() == 0) {
            //         Swal.fire('Quantity Tidak Boleh Kosong Atau 0!');
            //         ada = true;
            //     }
            // });
        });

        if (!ada) {
            e.preventDefault();
            var formData = new FormData(this);
            if (formData) {
                sweetaddv2($("#path").val(), formData);
                // var total_deliver = 0;
                // var total_order = 0;
                // for (var x = 1; x <= $('#jml').val(); x++) {
                //     total_deliver = total_deliver + parseInt($('#n_deliver' + x).val().replaceAll(',', ''));
                //     total_order = total_order + parseInt($('#n_order' + x).val().replaceAll(',', ''));
                // }

                // if (total_order != total_deliver) {
                //     sweet3button($("#path").val(), formData);
                //     //alert("Jumlah Kirim Tidak Terpenuhi, Buat SO Baru ???");    
                // } else {
                //     sweetaddv2($("#path").val(), formData);
                // }
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

function number() {
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
            Swal.fire('Error :)');
        }
    });
}

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