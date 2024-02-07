$(document).ready(function() {    
    $(".switch:checkbox").checkboxpicker();
    // number();
});

$('#d_kembali').change(function() {
    urem();
});


$("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
$('form').on('submit', function(e) { 
    e.preventDefault();
    var formData = new FormData(this);
    if (formData) {
        var ang     = 0;
        var ing     = 0;
        var ong     = 0;
        var kum     = $('#e_area').val();
        var kem     = $('#e_kota').val();
        var kim     = $('#e_staff_name').val();
        var kr      = $("#i_store").val();
        var yz      = $("#i_dn_atasan").val();
        var en      = $("#i_dn_departement").val();
        var rh      = $("#i_dn_jabatan").val();
        // if (kr == '' || kr == null){
        //     Swal.fire('Silahkan Pilih ASAL KEBERANGKATAN !');
        // }else{
        //     if (kim == '' || kim == null) {
        //         Swal.fire('Silahkan Masukan NAMA KARYAWAN !');
        //     }else{
        //         if (ong  + parseInt($('#n_lama_dinas').val().replaceAll(',', '')) > 0){
        //             if (($('#d_kembali').val()) < ($('#d_berangkat').val())) {
        //                 Swal.fire('Tanggal Berangkat = ' + $('#d_berangkat').val() + ', Melebihi Tanggal Kembali = ' + $('#d_kembali').val() + ' (MOHON MASUKAN TANGGAL DENGAN BENAR) !!!' );
        //                 $('#d_kembali').val($('#d_berangkat').val());
        //             } else{
        //                 if (yz == '' || yz == null) {
        //                     Swal.fire('Silahkan PILIH NAMA ATASAN !');
        //                 }else{
        //                     if (en == '' || en == null) {
        //                         Swal.fire('Silahkan PILIH DEPARTEMENT !');
        //                     }else{
        //                         if (rh == '' || rh == null) {
        //                             Swal.fire('Silahkan PILIH JABATAN !');
        //                         }else{
        //                             if (kum == '' || kum == null) {
        //                                 Swal.fire('Silahkan Masukan Area Provinsi Tujuan !');
        //                             }else{
        //                                 if (kem == '' || kem == null) {
        //                                     Swal.fire('Silahkan Masukan Kota Tujuan !');
        //                                 }else{
        //                                     if (ang  + parseInt($('#v_anggaran_biaya').val().replaceAll(',', '')) > 0){
        //                                         if (ing  + parseInt($('#v_spb_target').val().replaceAll(',', '')) > 0){ 
                                                    sweeteditv332($("#path").val(), $("#d_from").val(), $("#d_to").val(), $("#hstore").val(), formData);
        //                                         }else{
        //                                             Swal.fire('Silahkan Masukan TARGET SPB !!!');
        //                                         }
        //                                     }else{
        //                                         Swal.fire('Silahkan Masukan ANGGARAN BIAYA !!!');
        //                                     }    
        //                                 }
        //                             }                                    
        //                         }  
        //                     }
        //                 }
        //             }
        //         }else{
        //             Swal.fire('Silahkan Masukan LAMA PERJALANAN DINAS !!!');
        //         }
        //     } 
        // }    
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


function itung() {
    let v_realisasi_biaya = parseFloat(formatulang($('#v_realisasi_biaya').val()));
    let v_spb_realisasi = parseFloat(formatulang($('#v_spb_realisasi').val()));
    let v_nota_tertagih = parseFloat(formatulang($('#v_nota_tertagih').val()));
    
    if (v_realisasi_biaya == '' || v_realisasi_biaya == null|| isNaN(v_realisasi_biaya)) {
        v_realisasi_biaya = 0;
    }
    if (v_spb_realisasi == '' || v_spb_realisasi == null|| isNaN(v_spb_realisasi)) {
        v_spb_realisasi = 0;
    }
    if (v_nota_tertagih == '' || v_nota_tertagih == null|| isNaN(v_nota_tertagih)) {
        v_nota_tertagih = 0;
    }
    
    if ((parseFloat(formatulang($('#v_spb_realisasi').val())) + parseFloat(formatulang($('#v_nota_tertagih').val()))) != 0) {
        let n_biaya_vs_spb = (v_realisasi_biaya / (v_spb_realisasi + v_nota_tertagih))*100;
        $('#n_biaya_vs_spb').val(format2Decimals(n_biaya_vs_spb));
    }else{
        $('#n_biaya_vs_spb').val(0);
    }

    
}