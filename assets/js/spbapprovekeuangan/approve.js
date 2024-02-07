$(document).ready(function() {
    $(".switch:checkbox").checkboxpicker();
    hitung();
    var bl = $('#bl').val();
    var span = document.createElement("span");
    span.innerHTML = "Status Pelanggan <b>BLACKLIST</b>, Mohon Periksa Kembali Pelanggan Sebelum Menyetujui.";
    if ((bl === 'Blacklist')){
        swal({
            title: 'PERINGATAN TOKO BLACKLIST !!!',            
            content: span,
            icon: "info",
            showCancelButton: true,            
        });
    }
});

function app(folder, id, dfrom, dto, harea) {
    var keterangan = $('#e_note').val();
    var pla             = parseFloat(formatulang($('#pla').val()));
    var tfoot_total     = parseFloat(formatulang($('#tfoot_total').val()));
    if (keterangan == '' || keterangan == null) {
        Swal.fire('Keterangan harus diisi!');
    } else {
        if ((tfoot_total > pla) || (pla === '0')){
            Swal.fire({
                title: 'PERINGATAN PLAFON',
                html: "Jumlah <b>SPB</b> = Rp." + formatcemua(tfoot_total) + "  <br> <b>PLAFON</b> = Rp." + formatcemua(pla),
                type: "error",
                animation: !1,
                customClass: "animated flipInX",
                showCancelButton: !0,
                confirmButtonColor: "#7FFF00",
                cancelButtonColor: "#808080",
                confirmButtonText: label_sw_app,
                confirmButtonClass: "btn btn-primary",
                cancelButtonText: label_sw_cancel,
                cancelButtonClass: "btn btn-warning ml-1",
                buttonsStyling: !1,
            }).then((isConfirm) => {
                if (isConfirm) {
                    sweetapprovev44(folder, id, keterangan, dfrom, dto, harea);
                } else {
                    Swal.fire('Batal Menyetujui');
                }
            });
        }else{
            sweetapprovev44(folder, id, keterangan, dfrom, dto, harea);
        }
    }
}

function notapp(folder, id, dfrom, dto, harea) {
    var keterangan = $('#e_note').val();
    if (keterangan == '' || keterangan == null) {
        Swal.fire('Keterangan harus diisi!');
    } else {
        sweetnotapprovev44(folder, id, keterangan, dfrom, dto, harea);
    }
}

function hitung() {
    var jml = $("#jml").val();

    var v_price = 0.0;

    var sub_total = 0.0;
    for (var x = 1; x <= jml; x++) {
        if ($("#n_order" + x).length && $("#i_product" + x).val() != "") {
            v_price = $("#v_price" + x).val().replaceAll(",", "");
            n_order = $("#n_order" + x).val().replaceAll(",", "");

            var disc1 = $("#n_disc1" + x).val();
            var disc2 = $("#n_disc2" + x).val();
            var disc3 = $("#n_disc3" + x).val();
            var disc4 = $("#n_disc4" + x).val();

            var valid_disc1 = 0;
            var valid_disc2 = 0;
            var valid_disc3 = 0;
            var valid_disc4 = 0;

            if (disc1 > 0.0) valid_disc1 = disc1 / 100;
            if (disc2 > 0.0) valid_disc2 = disc2 / 100;
            if (disc3 > 0.0) valid_disc3 = disc3 / 100;
            if (disc4 > 0.0) valid_disc4 = disc4 / 100;
            $("#n_disc1" + x).val(disc1);
            $("#n_disc2" + x).val(disc2);
            $("#n_disc3" + x).val(disc3);
            $("#n_disc4" + x).val(disc4);

            var gross = v_price * n_order;
            var v_disc1 = gross * valid_disc1;
            var v_disc2 = (gross - v_disc1) * valid_disc2;
            var v_disc3 = (gross - v_disc1 - v_disc2) * valid_disc3;
            var v_disc4 = (gross - v_disc1 - v_disc2 - v_disc3) * valid_disc4;
            var total_baris = gross - v_disc1 - v_disc2 - v_disc3 - v_disc4;

            $("#v_disc1" + x).val(number_format(v_disc1, 2, ".", ","));
            $("#v_disc2" + x).val(number_format(v_disc2, 2, ".", ","));
            $("#v_disc3" + x).val(number_format(v_disc3, 2, ".", ","));
            $("#v_disc4" + x).val(number_format(v_disc4, 2, ".", ","));
            $("#total_baris" + x).val(number_format(total_baris, 2, ".", ","));

            sub_total += total_baris;
        }
    }

    $("#tfoot_subtotal").val(number_format(sub_total, 2, ".", ","));
    var foot_vdisc = $("#tfoot_v_diskon").val().replaceAll(",", "");
    var dpp = sub_total - foot_vdisc;
    var ppn = 0;
    if ($("#ppn").val() == "t") {
        ppn = dpp * (parseFloat($("#nppn").val()) / 100);
    }
    var total_final = dpp + ppn;
    $("#tfoot_v_dpp").val(number_format(dpp, 2, ".", ","));
    $("#tfoot_v_ppn").val(number_format(ppn, 2, ".", ","));
    $("#tfoot_total").val(number_format(total_final, 2, ".", ","));
}