$(document).ready(function() {
    $(".switch:checkbox").checkboxpicker();
});


function app(folder, id, dfrom, dto, harea) {
    var keterangan = $('#e_note').val();
    if (keterangan == '' || keterangan == null) {
        swal('Keterangan harus diisi!');
    } else {
        // sweetapprove(folder, id, keterangan);
        sweetapprovedkb(folder, id, keterangan, dfrom, dto, harea);
        // sweetapprovev3(folder, id, keterangan, dfrom, dto);
                    // sweetapprovev33(folder, id, keterangan, dfrom, dto, harea);
    }
}
