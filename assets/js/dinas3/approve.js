$(document).ready(function() {
    $(".switch:checkbox").checkboxpicker();
    // hitung();
    // var bl = $('#bl').val();
    // var span = document.createElement("span");
    // span.innerHTML = "Status Pelanggan <b>BLACKLIST</b>, Mohon Periksa Kembali Pelanggan Sebelum Menyetujui.";
    // if ((bl === 'Blacklist')){
    //     swal({
    //         title: 'PERINGATAN TOKO BLACKLIST !!!',            
    //         content: span,
    //         icon: "info",
    //         showCancelButton: true,            
    //     });
    // }
});

function app(folder, id, dfrom, dto, hstore) {
    var keterangan = $('#e_note').val();
    // var pla             = parseFloat(formatulang($('#pla').val()));
    // var tfoot_total     = parseFloat(formatulang($('#tfoot_total').val()));
    if (keterangan == '' || keterangan == null) {
        Swal.fire('Keterangan harus diisi!');
    } else {
    //     if ((tfoot_total > pla) || (pla === '0')){
    //         Swal.fire({
    //             title: 'PERINGATAN PLAFON',
    //             html: "Jumlah <b>SPB</b> = Rp." + formatcemua(tfoot_total) + "  <br> <b>PLAFON</b> = Rp." + formatcemua(pla),
    //             type: "error",
    //             animation: !1,
    //             customClass: "animated flipInX",
    //             showCancelButton: !0,
    //             confirmButtonColor: "#7FFF00",
    //             cancelButtonColor: "#808080",
    //             confirmButtonText: label_sw_app,
    //             confirmButtonClass: "btn btn-primary",
    //             cancelButtonText: label_sw_cancel,
    //             cancelButtonClass: "btn btn-warning ml-1",
    //             buttonsStyling: !1,
    //         }).then((isConfirm) => {
    //             if (isConfirm) {
    //                 sweetapprovev44(folder, id, keterangan, dfrom, dto, hstore);
    //             } else {
    //                 Swal.fire('Batal Menyetujui');
    //             }
    //         });
    //     }else{
            sweetapprovev44(folder, id, keterangan, dfrom, dto, hstore);
    //     }
    }
}

function notapp(folder, id, dfrom, dto, hstore) {
    var keterangan = $('#e_note').val();
    if (keterangan == '' || keterangan == null) {
        Swal.fire('Keterangan harus diisi!');
    } else {
        sweetnotapprovev44(folder, id, keterangan, dfrom, dto, hstore);
    }
}

