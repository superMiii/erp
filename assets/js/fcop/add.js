$(document).ready(function() {
    //$('#i_document').inputmask("aa-9999-999999");
    // $(".switch:checkbox").checkboxpicker();
    // number();
    pickdatemonthandyear();
    // get_data();
    $('#ceklis').click(function(event) {
        if ($('#ceklis').is(':checked')) {
            $("#i_document").attr("readonly", false);
        } else {
            $("#i_document").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
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
    });

    $("#d_document").change(function() {
        //number();
    });


    $("#i_supplier").select2({
        dropdownAutoWidth: true,
        width: '100%',
        containerCssClass: 'select-sm',
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_supplier",
            dataType: "json",
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
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
    }).change(function(event) {

        if ($('#d_document').val() != "") {
            // $.ajax({
            //     type: "post",
            //     data: {
            //         'i_supplier': $(this).val(),
            //         'd_document': $('#d_document_submit').val()
            //     },
            //     url: base_url + $("#path").val() + "/get_recent",
            //     dataType: "json",
            //     success: function(data) {
            //         if (data['data'] != null) {
            //             clear_tabel();
            //             var i = $("#jml").val();
            //             $.each(data['data'], function(k, v) { 
            //                 i++;
            //                 var no = $("#tabledetail tbody tr").length + 1;
            //                 $("#jml").val(i);
            //                 var newRow = $("<tr>");
            //                 var cols = "";
            //                     cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
            //                     cols += `<td><select data-nourut="${i}" required class="form-control select2-size-sm" name="i_product[]" id="i_product${i}"><option value=""></option></select></td>`;
            //                     cols += `<td>
            //                     <input type="hidden" name="i_product_motif[]" id="i_product_motif${i}" readonly class="form-control form-control-sm">
            //                     <input type="hidden" name="i_product_grade[]" id="i_product_grade${i}" readonly class="form-control form-control-sm">
            //                     <input type="text" id="e_product_motifname${i}" readonly class="form-control form-control-sm">
            //                     </td>`;
            //                     cols += `<td><input type="number" value="0" name="n_quantity[]" id="n_quantity${i}" class="form-control form-control-sm" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\'></td>`;
            //                     cols += `<td><input type="text" name="e_remarkitem[]" id="e_remarkitem${i}" class="form-control form-control-sm"></td>`;
            //                     cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>`;
            //                 newRow.append(cols);
            //                 $("#tabledetail").append(newRow);
            //                 $("#i_product" + i).select2({
            //                     placeholder: "Search",
            //                     dropdownAutoWidth: true,
            //                     width: '100%',
            //                     containerCssClass: 'select-xs',
            //                     allowClear: true,
            //                     ajax: {
            //                         url: base_url + $("#path").val() + "/get_product",
            //                         dataType: "json",
            //                         delay: 250,
            //                         data: function(params) {
            //                             var query = {
            //                                 q: params.term,
            //                             };
            //                             return query;
            //                         },
            //                         processResults: function(data) {
            //                             return {
            //                                 results: data,
            //                             };
            //                         },
            //                         cache: false,
            //                     },
            //                 }).change(function(event) {
            //                     var z = $(this).data("nourut");
            //                     var ada = false;
            //                     for (var x = 1; x <= $("#jml").val(); x++) {
            //                         if ($(this).val() != null) {
            //                             if ($(this).val() == $("#i_product" + x).val() /* && $("#i_level" + z).val() == $("#i_level" + x).val() */ && z != x) {
            //                                 swal("Sorry :(", "Data already exist ..", "error");
            //                                 ada = true;
            //                                 break;
            //                             }
            //                         }
            //                     }
            //                     if (ada) {
            //                         $(this).val("");
            //                         $(this).html("");
            //                     } else {
            //                         $.ajax({
            //                             type: "post",
            //                             data: {
            //                                 'i_product': $('#i_product' + z).val(),
            //                             },
            //                             url: base_url + $("#path").val() + '/get_product_detail',
            //                             dataType: "json",
            //                             success: function(data) {
            //                                 $("#i_product_motif" + z).val(data.detail[0]['i_product_motif']);
            //                                 $("#i_product_grade" + z).val(1);
            //                                 $("#e_product_motifname" + z).val(data.detail[0]['e_product_motifname']);
            //                                 $("#n_quantity" + z).val(0);
            //                             },
            //                             error: function() {
            //                                 swal('Error :)');
            //                             }
            //                         });
            //                     }
            //                 });
            //             });
            //         }
            //     },
            //     error: function() {
            //         swal('500 internal server error : (');
            //     }
            // });
        } else {
            swal('Tanggal Dokumen Harus Di Isi');
        }
        
    });


    
     var Detail = $(function() {
        var i = $("#jml").val();
        $("#addrow").on("click", function() {
            i++;
            var no = $("#tabledetail tr").length;
            $("#jml").val(i);
            var newRow = $("<tr>");
            var cols = "";
            cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
            cols += `<td><select data-nourut="${i}" required class="form-control select2-size-sm" name="i_product[]" id="i_product${i}"><option value=""></option></select></td>`;
            cols += `<td>
            <input type="hidden" name="e_product_name[]" id="e_product_name${i}" readonly class="form-control form-control-sm">
            <input type="hidden" name="i_product_motif[]" id="i_product_motif${i}" readonly class="form-control form-control-sm">
            <input type="hidden" name="i_product_grade[]" id="i_product_grade${i}" readonly class="form-control form-control-sm">
            <input type="text" id="e_product_motifname${i}" readonly class="form-control form-control-sm">
            </td>`;
            cols += `<td><input type="text" value="0" name="n_quantity[]" id="n_quantity${i}" class="form-control form-control-sm" onkeypress="return bilanganasli(event);" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\'></td>`;
            cols += `<td><input type="text" name="e_remarkitem[]" id="e_remarkitem${i}" class="form-control form-control-sm"></td>`;
            cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>`;
            newRow.append(cols);
            $("#tabledetail").append(newRow);
            $("#i_product" + i).select2({
                placeholder: "Search",
                dropdownAutoWidth: true,
                width: '100%',
                containerCssClass: 'select-xs',
                allowClear: true,
                ajax: {
                    url: base_url + $("#path").val() + "/get_product",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
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
            }).change(function(event) {
                var z = $(this).data("nourut");
                var ada = false;
                for (var x = 1; x <= $("#jml").val(); x++) {
                    if ($(this).val() != null) {
                        if ($(this).val() == $("#i_product" + x).val() /* && $("#i_level" + z).val() == $("#i_level" + x).val() */ && z != x) {
                            swal("Sorry :(", "Data already exist ..", "error");
                            ada = true;
                            break;
                        }
                    }
                }
                if (ada) {
                    $(this).val("");
                    $(this).html("");
                } else {
                    $.ajax({
                        type: "post",
                        data: {
                            'i_product': $('#i_product' + z).val(),
                        },
                        url: base_url + $("#path").val() + '/get_product_detail',
                        dataType: "json",
                        success: function(data) {
                            $("#i_product_motif" + z).val(data.detail[0]['i_product_motif']);
                            $("#i_product_grade" + z).val(1);
                            $("#e_product_motifname" + z).val(data.detail[0]['e_product_motifname']);
                            $("#e_product_name" + z).val(data.detail[0]['e_product_name']);
                            
                            $("#n_quantity" + z).val(0);
                        },
                        error: function() {
                            swal('Error :)');
                        }
                    });
                }
            });
        });



        /*----------  Hapus Baris Data Saudara  ----------*/

        $("#tabledetail").on("click", ".ibtnDel", function(event) {
            $(this).closest("tr").remove();

            $("#jml").val(i);
            var obj = $("#tabledetail tr:visible").find("spanx");
            $.each(obj, function(key, value) {
                id = value.id;
                $("#" + id).html(key + 1);
            });
        });
    });


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
                sweetadd($("#path").val(), formData);
            }
        } else {
            return false;
        }
    });
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
            swal('Error :)');
        }
    });
}

function clear_tabel() {
    $('#tabledetail tbody').empty();
    $('#tfoot_subtotal').val("");
    $('#tfoot_n_diskon').val("");
    $('#tfoot_v_diskon').val("");
    $('#tfoot_v_dpp').val("");
    $('#tfoot_v_ppn').val("");
    $('#tfoot_total').val("");
    $('#jml').val("0");
}

function validasi(i) {
    var n_order = $('#n_order'+i).val();
    var n_op = $('#n_op'+i).val();

    if (n_order > n_op) {
        swal('Max ' + n_op);
        $('#n_order'+i).val(n_op);
    }
}

function hitung() {
    var jml = $('#jml').val();

    //var v_price = 0.00;
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
        if ($('#n_order' + x).length && $('#i_product' + x).val() != "") {
            v_price = $('#v_price' + x).val().replaceAll(',', '');
            n_order = $('#n_order' + x).val().replaceAll(',', '');
            v_disc = $('#v_disc' + x).val().replaceAll(',', '');
            // $('#n_disc' + x).val(disc);
            // $('#n_disc2' + x).val(disc2);
            // $('#n_disc3' + x).val(disc3);

            var gross = v_price * n_order;
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
    var foot_vdisc = $('#tfoot_v_diskon').val().replaceAll(',', '');
    var dpp = sub_total - foot_vdisc;
    var ppn = 0;
    if ($('#ppn').val() == 't') ppn = dpp * 0.1;
    var total_final = dpp + ppn;
    $('#tfoot_v_dpp').val(number_format(dpp, 2, '.', ','));
    $('#tfoot_v_ppn').val(number_format(ppn, 2, '.', ','));
    $('#tfoot_total').val(number_format(total_final, 2, '.', ','));

}

function change_ndisc(i) {
    var n_order = $('#n_order'+i).val();
    var v_price = $('#v_price'+i).val().replaceAll(',', '');
    var n_disc = $('#n_disc'+i).val().replaceAll(',', '');
    var v_disc = 0;
    if (n_disc > 0.00) v_disc =  (n_order * v_price) * n_disc / 100;
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

function get_data() {
    $.ajax({
        type: "post",
        data: {
            'i_supplier': $('#i_supplier').val(),
            'i_so': $('#i_so').val(),
            'i_sr': $('#i_sr').val()
        },
        url: base_url + $("#path").val() + "/get_supplier_price",
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
                    var cols = "";
                    cols += `<td class="text-center" valign="center"><spanx id="snum${i}">${no}</spanx></td>`;
                    cols += `<td><input type="text" class="form-control text-left form-control-sm" id="e_product_name${i}" name="e_product_name[]" readonly value="${v['i_product_id'] + ' - ' + v['e_product_name'] + ' - ' + v['e_product_motifname'] }"></td>`;
                    cols += `<td><input type="text" class="form-control text-right form-control-sm" id="v_price${i}" name="v_price[]" value="${number_format(v['v_price'], 2, '.', ',')}" readonly>
                    <input type="hidden" class="form-control form-control-sm" id="i_product${i}" name="i_product[]" value="${v['i_product']}" readonly>
                    <input type="hidden" class="form-control form-control-sm" id="i_product_motif${i}" name="i_product_motif[]" value="${v['i_product_motif']}" readonly>
                    <input type="hidden" class="form-control form-control-sm" id="i_product_grade${i}" name="i_product_grade[]" value="${v['i_product_grade']}" readonly>
                    </td>`;
                    cols += `<td><input type="text" autocomplete="off" class="form-control text-right n_order form-control-sm" id="n_order${i}" value="${v['n_op']}" name="n_order[]" onkeypress="return bilanganasli(event);hitung();" onkeyup="validasi(${i});hitung();" onblur=\"if(this.value==''){this.value='0';hitung();}\" onfocus=\"if(this.value=='0'){this.value='';}\">
                    <input type="hidden" class="form-control form-control-sm" id="n_op${i}" name="n_op[]" value="${v['n_op']}" readonly>
                    </td>`;
                    cols += `<td><input type="text" class="formatrupiah form-control text-right form-control-sm" id="n_disc${i}" name="n_disc[]" value="0" onkeyup="formatrupiahkeyup(this);change_ndisc(${i});hitung();" onkeydown="formatrupiahkeydown(this);change_ndisc(${i});hitung();"  onblur="if(this.value==''){this.value='0';hitung();}" onfocus="if(this.value=='0'){this.value='';}"></td>`;
                    cols += `<td><input type="text" class="formatrupiah form-control text-right form-control-sm" id="v_disc${i}" name="v_disc[]" value="0" onkeyup="formatrupiahkeyup(this);change_vdisc(${i});hitung();" onkeydown="formatrupiahkeydown(this);change_vdisc(${i});hitung();"  onblur="if(this.value==''){this.value='0';hitung();}" onfocus="if(this.value=='0'){this.value='';}"></td>`;
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

