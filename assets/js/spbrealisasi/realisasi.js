$(document).ready(function() {
    //$('#i_document').inputmask("aa-9999-999999");
    $(".switch:checkbox").checkboxpicker();
    // pickdate();
    get_data();

    $("#i_store_loc").select2({
        dropdownAutoWidth: true,
        width: '100%',
        containerCssClass: 'select-sm',
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_store",
            dataType: "json",
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
                    f_so_stockdaerah: $('#f_so_stockdaerah').val(),
                    i_area: $('#i_area').val()
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
        $.ajax({
            type: "post",
            data: {
                'i_store_loc': $(this).val(),
            },
            url: base_url + $("#path").val() + "/get_store_utama",
            dataType: "json",
            success: function(data) {
                if (data['data'] != null) {
                    $('#i_store').val(data['data']);
                    clear_tabel();
                    get_data();
                }
            },
            error: function() {
                swal('500 internal server error : (');
            }
        });
    });

    $('#ceklis').click(function(event) {
        if ($('#ceklis').is(':checked')) {
            $("#i_document").attr("readonly", false);
        } else {
            $("#i_document").attr("readonly", true);
            $("#ada").attr("hidden", true);
            $("#i_document").val($("#i_document_old").val());
            /* number(); */
        }
    });


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
            $(this).find("td .n_order").each(function() {
                if ($(this).val() == '' || $(this).val() == null || $(this).val() == 0) {
                    swal('Quantity Tidak Boleh Kosong Atau 0!');
                    ada = true;
                }
            });
        });

        if (!ada) {
            e.preventDefault();
            var formData = new FormData(this);
            if (formData) {
                sweetrealisasi33($("#path").val(), $("#d_from").val(), $("#d_to").val(), $("#harea").val(), formData);
            }
        } else {
            return false;
        }
    });
});


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

function change_ndisc() {
    var sub_total = $('#tfoot_subtotal').val().replaceAll(',', '');
    var foot_vdisc = $('#tfoot_n_diskon').val().replaceAll(',', '');
    var v_disc = 0;
    if (foot_vdisc > 0.00) v_disc = sub_total * foot_vdisc / 100;
    $('#tfoot_v_diskon').val(number_format(v_disc, 2, '.', ','));
}

function change_vdisc() {
    $('#tfoot_n_diskon').val(0);
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
            swal('Error :)');
        }
    });
}

function get_data() {
    $.ajax({
        type: "post",
        data: {
            'i_store': $('#i_store').val(),
            'i_store_loc': $('#i_store_loc').val(),
            'i_so': $('#id').val(),
            'i_periode': $('#i_periode').val(),
        },
        url: base_url + $("#path").val() + "/get_data_realisasi",
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
                    cols += `<td><input type="text" class="form-control text-left form-control-sm" id="e_product_name${i}" name="e_product_name[]" readonly value="${v['i_product_id'] + ' - ' + v['e_product_name'] + ' - ' + v['e_product_motifname']}">
                    <input type="hidden" class="form-control form-control-sm" id="i_so_item${i}" name="i_so_item[]" value="${v['i_so_item']}" readonly>
                    <input type="hidden" class="form-control form-control-sm" id="i_product${i}" name="i_product[]" value="${v['i_product']}" readonly>
                    <input type="hidden" class="form-control form-control-sm" id="i_product_motif${i}" name="i_product_motif[]" value="${v['i_product_motif']}" readonly>
                    <input type="hidden" class="form-control form-control-sm" id="i_product_grade${i}" name="i_product_grade[]" value="${v['i_product_grade']}" readonly>
                    </td>`;
                    cols += `<td><input type="text" autocomplete="off" class="form-control text-right form-control-sm" id="n_order${i}" value="${v['n_order']}" name="n_order[]" readonly>
                    </td>`;
                    cols += `<td><input type="text" class="form-control text-right form-control-sm" id="n_stock${i}" name="n_stock[]" value="${v['n_stock']}" readonly></td>`;
                    cols += `<td><input type="text" class="form-control text-right form-control-sm" id="n_op${i}" name="n_op[]" value="${v['n_op']}" onkeypress="return bilanganasli(event);" onkeyup="validasi(${i});" onblur=\"if(this.value==''){this.value='0';}\" onfocus=\"if(this.value=='0'){this.value='';}\"></td>`;
                    newRow.append(cols);
                    $("#tabledetail").append(newRow);
                });
            }
        },
        error: function() {
            swal('500 internal server error : (');
        }
    });
}

function validasi(i) {
    var n_order = parseFloat($('#n_order' + i).val());
    var n_op = parseFloat($('#n_op' + i).val());

    if (n_op > n_order) {
        swal('Max ' + n_order);
        $('#n_op' + i).val(n_order);
    }
}