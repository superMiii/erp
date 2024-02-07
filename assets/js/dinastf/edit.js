$(document).ready(function() {    
    $(".switch:checkbox").checkboxpicker();
    // number();
});


$('#d_kembali').change(function() {
    urem();
});

$('#v_transfer').change(function() {
    miu();
});
$('#v_tf1').change(function() {
    miu();
});
$('#v_tf2').change(function() {
    miu();
});
$('#v_tf3').change(function() {
    miu();
});
$('#v_tf4').change(function() {
    miu();
});

$("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
$('form').on('submit', function(e) { 
    e.preventDefault();
    var formData = new FormData(this);
    if (formData) {
        // var ang= 0;
        // var ing= 0;
        // var ong= 0;
        // var kum = $('#e_area').val();
        // var kem = $('#e_kota').val();
        // var kim = $('#e_staff_name').val();
        // if (kim == '' || kim == null) {
        //     Swal.fire('Silahkan Isi NAMA KARYAWAN !');
        // }else{
        //     if (kum == '' || kum == null) {
        //         Swal.fire('Silahkan Isi Area Provinsi Tujuan !');
        //     }else{
        //         if (kem == '' || kem == null) {
        //             Swal.fire('Silahkan Isi Kota Tujuan !');
        //         }else{
        //             if (ang  + parseInt($('#v_anggaran_biaya').val().replaceAll(',', '')) > 0){
        //                 if (ing  + parseInt($('#v_spb_target').val().replaceAll(',', '')) > 0){               
        //                     if (($('#d_kembali').val()) < ($('#d_berangkat').val())) {
        //                         Swal.fire('Tanggal Berangkat = ' + $('#d_berangkat').val() + ', Melebihi Tanggal Kembali = ' + $('#d_kembali').val() + ' (MOHON ISI TANGGAL DENGAN BENAR) !!!' );
        //                         $('#d_kembali').val($('#d_berangkat').val());
        //                     } else{
                                // if (ong  + parseInt($('#n_lama_dinas').val().replaceAll(',', '')) > 0){
                                    
    var v_anggaran_biaya    = parseFloat($('#v_anggaran_biaya').val().replaceAll(",", ""));
    var v_transfer          = parseFloat($('#v_transfer').val().replaceAll(",", ""));
    var v_tf1               = parseFloat($('#v_tf1').val().replaceAll(",", ""));
    var v_tf2               = parseFloat($('#v_tf2').val().replaceAll(",", ""));
    var v_tf3               = parseFloat($('#v_tf3').val().replaceAll(",", ""));
    var v_tf4               = parseFloat($('#v_tf4').val().replaceAll(",", ""));
    var vv                  = (v_transfer + v_tf1 + v_tf2 + v_tf3 + v_tf4);
    if ((vv) > (v_anggaran_biaya)) {
        Swal.fire('Jumlah Total Transfer = ' + number_format(vv, '.', ',') + ' Melebihi Anggaran Biaya = ' + $('#v_anggaran_biaya').val() + ' !!!' );
    } else{
        sweeteditv33($("#path").val(), $("#d_from").val(), $("#d_to").val(), $("#hstore").val(), formData);

    }
                                // }else{
                                //     Swal.fire('Silahkan isi LAMA PERJALANAN DINAS !!!');
                                // }
        //                     }
        //                 }else{
        //                     Swal.fire('Silahkan isi TARGET SPB !!!');
        //                 }
        //             }else{
        //                 Swal.fire('Silahkan isi ANGGARAN BIAYA !!!');
        //             }        
        //         } 
        //     }
        //  }  
    }
});

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
            Swal.fire({
                type: "error",
                title: g_maaf,
                text: "Error data",
                confirmButtonClass: "btn btn-danger",
            });
        }
    });
}

function raya() {
    if (parseInt($('#n_lama_dinas').val()) > '999') {
        Swal.fire('Lama Perjalanan Dinas melebihi ketentuan, mohon diisi dengan benar !');
        $('#n_lama_dinas').val('1');
    } 

    var ry = parseInt($('#n_lama_dinas').val());
    var lm = $('#d_berangkat').val();

    const date = new Date(lm);
    date.setDate(date.getDate() + ry)
    // const date_back = ("0" + date.getDate()).slice(-2) + "-" + ("0"+(date.getMonth()+1)).slice(-2) + "-" +date.getFullYear();
    const date_back = date.getFullYear()+ "-" + ("0"+(date.getMonth()+1)).slice(-2) + "-" +("0" + date.getDate()).slice(-2) ;
    // console.log(date_back);
    // console.log($('#d_kembali').val());
    $('#d_kembali').val(date_back);
    
}

function urem() {
    if (($('#d_kembali').val()) < ($('#d_berangkat').val())) {
        Swal.fire('Tanggal Berangkat = ' + $('#d_berangkat').val() + ', Melebihi Tanggal Kembali = ' + $('#d_kembali').val() + ' (MOHON ISI TANGGAL DENGAN BENAR)' );
        $('#d_kembali').val($('#d_berangkat').val());
    }    
}


function vespa() {
    if (parseInt($('#n_biaya_vs_spb').val()) > '100') {
        Swal.fire('Biaya vs SPB diisi dengan PERSENTASE !');
        $('#n_biaya_vs_spb').val('1');
    } 
}

function miu() {
    var v_anggaran_biaya    = parseFloat($('#v_anggaran_biaya').val().replaceAll(",", ""));
    var v_transfer          = parseFloat($('#v_transfer').val().replaceAll(",", ""));
    var v_tf1               = parseFloat($('#v_tf1').val().replaceAll(",", ""));
    var v_tf2               = parseFloat($('#v_tf2').val().replaceAll(",", ""));
    var v_tf3               = parseFloat($('#v_tf3').val().replaceAll(",", ""));
    var v_tf4               = parseFloat($('#v_tf4').val().replaceAll(",", ""));
    var vv                  = (v_transfer + v_tf1 + v_tf2 + v_tf3 + v_tf4);
    if ((vv) > (v_anggaran_biaya)) {
        Swal.fire('Jumlah Total Transfer = ' + number_format(vv, '.', ',') + ' Melebihi Anggaran Biaya = ' + $('#v_anggaran_biaya').val() + ' !!!' );
        // $('#v_transfer').val($('#v_anggaran_biaya').val());
    }    
}