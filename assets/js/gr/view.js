$(document).ready(function() {
    //$('#i_document').inputmask("aa-9999-999999");
    $(".switch:checkbox").checkboxpicker();
    // number();
    //pickdate();
    get_data();
    // $('#ceklis').click(function(event) {
    //     if ($('#ceklis').is(':checked')) {
    //         $("#i_document").attr("readonly", false);
    //     } else {
    //         $("#i_document").attr("readonly", true);
    //         $("#ada").attr("hidden", true);
    //         // number();
    //     }
    // });

    // $("#i_document").keyup(function() {
    //     cek_kode();
    // });

    // $("#d_document").change(function() {
    //     cek_kode();
    // });

    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $('form').on('submit', function(e) { //bind event on form submit.
        let tabel = $("#tabledetail tbody tr").length;
        let ada = false;
        if (tabel < 1) {
            swal(g_maaf, g_detailmin, "error");
            return false;
        }

        if (!ada) {
            e.preventDefault();
            var formData = new FormData(this);
            if (formData) {
                sweetedit($("#path").val(), formData);
            }
        } else {
            return false;
        }
    });
});


// function number() {
//     $.ajax({
//         type: "post",
//         data: {
//             'tanggal': $('#d_document').val(),
//         },
//         url: base_url + $("#path").val() + '/number ',
//         dataType: "json",
//         success: function(data) {
//             $('#i_document').val(data);
//         },
//         error: function() {
//             swal('Error :)');
//         }
//     });
// }
function cek_kode() {
    $.ajax({
            type: "post",
            data: {
                'i_document': $('#i_document').val(),
                'd_document': $('#d_document').val(),
                'i_document_old': $('#i_document_old').val(),
            },
            url: base_url + $("#path").val() + '/cek_edit',
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
} 
       

function clear_tabel() {
    $('#tabledetail tbody').empty();
    $('#tfoot_subtotal').val("");
    // $('#tfoot_n_diskon').val("");
    // $('#tfoot_v_diskon').val("");
    $('#tfoot_v_dpp').val("");
    $('#tfoot_v_ppn').val("");
    $('#tfoot_total').val("");
    $('#jml').val("0");
}

function validasi(i) {
    var n_delivery = $('#n_delivery'+i).val();
    var n_op = $('#n_op'+i).val();

    if (n_delivery > n_op) {
        swal('Max ' + n_op);
        $('#n_delivery'+i).val(n_op);
    }
}

function hitung() {
    var jml = $('#jml').val();

    var sub_total = 0.00;
    for (var x = 1; x <= jml; x++) {
        if ($('#n_delivery' + x).length && $('#i_product' + x).val() != "") {
            v_price = $('#v_price' + x).val().replaceAll(',', '');
            n_delivery = $('#n_delivery' + x).val().replaceAll(',', '');
            v_disc = $('#v_disc' + x).val().replaceAll(',', '');
            // $('#n_disc' + x).val(disc);
            // $('#n_disc2' + x).val(disc2);
            // $('#n_disc3' + x).val(disc3);

            var gross = v_price * n_delivery;
            // var v_disc = gross * valid_disc;
            // var v_disc2 = (gross - v_disc) * valid_disc2;
            // var v_disc3 = (gross - v_disc - v_disc2) * valid_disc3;
            var total_baris = gross - v_disc;

            // $('#v_disc' + x).val(number_format(v_disc, 2, '.', ','));
            // $('#v_disc2' + x).val(number_format(v_disc2, 2, '.', ','));
            // $('#v_disc3' + x).val(number_format(v_disc3, 2, '.', ','));
            $('#total_baris' + x).val(number_format(total_baris, 2, '.', ','));

            sub_total += total_baris;
        }
    }

    $('#tfoot_subtotal').val(number_format(sub_total, 2, '.', ','));
    //var foot_ndisc = $('#tfoot_n_diskon').val();
    // $('#tfoot_v_diskon').val($('#tfoot_v_diskon').val());
    var foot_vdisc = $('#tfoot_v_diskon').val().replaceAll(',', '');
    var dpp = sub_total - foot_vdisc;
    var ppn = 0;
    // alert($('#tfoot_v_diskon').val());
    // if ($('#ppn').val() == 't') ppn = dpp * 0.1;
    var total_final = dpp ;//+ ppn;
    $('#tfoot_v_dpp').val(number_format(dpp, 2, '.', ','));
    $('#tfoot_v_ppn').val(number_format(ppn, 2, '.', ','));
    $('#tfoot_total').val(number_format(total_final, 2, '.', ','));

}

function change_ndisc(i) {
    var n_delivery = $('#n_delivery'+i).val();
    var v_price = $('#v_price'+i).val().replaceAll(',', '');
    var n_disc = $('#n_disc'+i).val().replaceAll(',', '');
    var v_disc = 0;
    if (n_disc > 0.00) v_disc =  (n_delivery * v_price) * n_disc / 100;
    $('#v_disc'+i).val(number_format(v_disc, 2, '.', ','));
    // var sub_total = $('#tfoot_subtotal').val().replaceAll(',', '');
    // var foot_vdisc = $('#tfoot_n_diskon').val().replaceAll(',', '');
    // var v_disc = 0;
    // if (foot_vdisc > 0.00) v_disc = sub_total * foot_vdisc / 100;
    // $('#tfoot_v_diskon').val(number_format(v_disc, 2, '.', ','));
}

function change_vdisc(i) {
    $('#n_disc'+i).val(0);

}


function change_ndisc_foot() {
    var sub_total = $('#tfoot_subtotal').val().replaceAll(',', '');
    var foot_vdisc = $('#tfoot_n_diskon').val().replaceAll(',', '');
    var v_disc = 0;
    if (foot_vdisc > 0.00) v_disc = sub_total * foot_vdisc / 100;
    $('#tfoot_v_diskon').val(number_format(v_disc, 2, '.', ','));
}

function change_vdisc_foot() {
    $('#tfoot_n_diskon').val(0);
}

function get_data() {
    $.ajax({
        type: "post",
        data: {
            'id': $('#id').val(),
        },
        url: base_url + $("#path").val() + "/get_data_detail",
        dataType: "json",
        success: function(data) {
            if (data['data'] != null) {
                clear_tabel();
                var i = $("#jml").val();
                $.each(data['data'], function(k, v) {
                    i++;
                    var no = $("#tabledetail tbody tr").length + 1;
                    $("#jml").val(i);
                    var newRow = $("<tr>");
                    var x_deliver = parseFloat(v['n_deliver'])-parseFloat(v['x_bonus']);
                    var cols = "";
                    cols += `<td class="text-center" valign="center"><spanx id="snum${i}">${no}</spanx></td>`;
                    cols += `<td><input type="text" class="form-control text-left form-control-sm" id="e_product_name_mix${i}" name="e_product_name_mix[]" readonly value="${v['i_product_id'] + ' - ' + v['e_product_name'] + ' - ' + v['e_product_motifname'] }"></td>`;
                    cols += `<td><input type="text" class="form-control text-right form-control-sm" id="v_price${i}" name="v_price[]" value="${number_format(v['v_product_mill'], 2, '.', ',')}" readonly>
                    <input type="hidden" class="form-control form-control-sm" id="i_po_item${i}" name="i_po_item[]" value="${v['i_po_item']}" readonly>
                    <input type="hidden" class="form-control form-control-sm" id="i_product${i}" name="i_product[]" value="${v['i_product']}" readonly>
                    <input type="hidden" class="form-control form-control-sm" id="i_product_motif${i}" name="i_product_motif[]" value="${v['i_product_motif']}" readonly>
                    <input type="hidden" class="form-control form-control-sm" id="i_product_grade${i}" name="i_product_grade[]" value="${v['i_product_grade']}" readonly>
                    <input type="hidden" class="form-control form-control-sm" id="e_product_name${i}" name="e_product_name[]" value="${v['e_product_name']}" readonly>
                    </td>`;
                    cols += `<td><input type="text" class="form-control text-right form-control-sm" id="n_op${i}" name="n_op[]" value="${v['n_op']}" readonly></td>`;
                    cols += `<td>
                    <input type="hidden" class="form-control text-right form-control-sm" id="n_delivery_old${i}" name="n_delivery_old[]" value="${x_deliver}" readonly>
                    <input type="text" autocomplete="off" class="form-control text-right form-control-sm" readonly id="n_delivery${i}" value="${x_deliver}" name="n_delivery[]" onkeypress="return bilanganasli(event);hitung();" onkeyup="change_ndisc(${i});hitung();" onblur=\"if(this.value==''){this.value='0';hitung();}\" onfocus=\"if(this.value=='0'){this.value='';}\">
                    </td>`;
                    
                    cols += `<td><input type="text" autocomplete="off" class="form-control text-right form-control-sm" readonly id="bonus${i}" value="${v['x_bonus']}" name="bonus[]" onkeypress="return bilanganasli(event);" onkeyup="" onblur=\"if(this.value==''){this.value='0';hitung();}\" onfocus=\"if(this.value=='0'){this.value='';}\">
                    </td>`;
                    cols += `<td><input type="text" class="formatrupiah form-control text-right form-control-sm" readonly id="n_disc${i}" name="n_disc[]" value="${number_format(v['n_gr_discount'], 2, '.', ',')}" onkeyup="formatrupiahkeyup(this);change_ndisc(${i});hitung();" onkeydown="formatrupiahkeydown(this);change_ndisc(${i});hitung();"  onblur="if(this.value==''){this.value='0';hitung();}" onfocus="if(this.value=='0'){this.value='';}"></td>`;
                    cols += `<td><input type="text" class="formatrupiah form-control text-right form-control-sm" readonly id="v_disc${i}" name="v_disc[]" value="${number_format(v['v_gr_discount'], 2, '.', ',')}" onkeyup="formatrupiahkeyup(this);change_vdisc(${i});hitung();" onkeydown="formatrupiahkeydown(this);change_vdisc(${i});hitung();"  onblur="if(this.value==''){this.value='0';hitung();}" onfocus="if(this.value=='0'){this.value='';}"></td>`;
                    cols += `<td><input type="text" class="form-control text-right form-control-sm" id="e_remark${i}" name="e_remark[]" value="${v['e_remark']}" readonly ></td>`;
                    cols += `<td><input type="text" class="form-control text-right form-control-sm text-right" id="total_baris${i}" name="total_baris[]" readonly></td>`;
                    newRow.append(cols);
                    $("#tabledetail").append(newRow);
                });
                hitung();
            }
        },
        error: function() {
            swal('500 internal server error : (');
        }
    });
}

