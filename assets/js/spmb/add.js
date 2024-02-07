$(document).ready(function() {
    number();
    $("#i_area")
    .select2({
        dropdownAutoWidth: true,
        width: "100%",
        containerCssClass: "select-sm",
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_area",
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
        clear_tabel();
    });
});

$('#d_document').change(function() {
    number();
});

var Detail = $(function() {
    var i = $("#jml").val();
    $("#addrow").on("click", function() {
        i++;
        var no = $("#tablecover tbody tr").length;
        $("#jml").val(i);
        var newRow = $("<tr>");
        var cols = "";
        cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
        cols += `<td><select data-nourut="${i}" required class="form-control select2-size-sm" name="i_product[]" id="i_product${i}"><option value=""></option></select></td>`;
        cols += `<td>
            <input type="hidden" name="i_product_motif[]" id="i_product_motif${i}" readonly class="form-control form-control-sm">
            <input type="hidden" name="i_product_grade[]" id="i_product_grade${i}" readonly class="form-control form-control-sm">
            <input type="hidden" name="v_unit_price[]" id="v_unit_price${i}" readonly class="form-control form-control-sm">
            <input type="text" id="e_product_gradename${i}" readonly class="form-control form-control-sm">
            </td>`;
        cols += `<td><input type="number" value="0" name="n_order[]" id="n_order${i}" class="form-control n_order form-control-sm" onkeypress="return bilanganasli(event);raya();" onkeyup="raya()" onblur=\"if(this.value==''){this.value='1';raya();}\" onfocus=\"if(this.value=='1'){this.value='';}\"></td>`;
        cols += `<td><input type="number" value="0" readonly name="n_acc[]" id="n_acc${i}" class="form-control form-control-sm"></td>`;
        cols += `<td><input type="text" name="e_remarkitem[]" id="e_remarkitem${i}" class="form-control form-control-sm"></td>`;
        cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>`;
        newRow.append(cols);
        $("#tablecover").append(newRow);
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
                        i_area: $('#i_area').val(),
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
                        Swal.fire({
                            type: "error",
                            title: g_maaf,
                            text: g_exist,
                            confirmButtonClass: "btn btn-danger",
                        });
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
                        i_product: $('#i_product' + z).val(),
                        i_area: $('#i_area').val(),
                    },
                    url: base_url + $("#path").val() + '/get_product_detail',
                    dataType: "json",
                    success: function(data) {
                        $("#i_product_motif" + z).val(data.detail[0]['i_product_motif']);
                        $("#i_product_grade" + z).val(data.detail[0]['i_product_grade']);
                        $("#e_product_gradename" + z).val(data.detail[0]['e_product_gradename']);
                        $("#v_unit_price" + z).val(data.detail[0]['v_price']);
                        raya();
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
        });
    });



    /*----------  Hapus Baris Data Saudara  ----------*/

    $("#tablecover").on("click", ".ibtnDel", function(event) {
        $(this).closest("tr").remove();

        $("#jml").val(i);
        var obj = $("#tablecover tr:visible").find("spanx");
        $.each(obj, function(key, value) {
            id = value.id;
            $("#" + id).html(key + 1);
        });
        raya();
    });
});

$("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
$('form').on('submit', function(e) { //bind event on form submit.
    let tabel = $("#tablecover tbody tr").length;
    let ada = false;
    if (tabel < 1) {
        Swal.fire({
            type: "error",
            title: g_maaf,
            text: "Input minimum 1 item !",
            confirmButtonClass: "btn btn-danger",
        });
        return false;
    }

    $("#tablecover tbody tr").each(function() {
        $(this)
            .find("td select")
            .each(function() {
                if ($(this).val() == "" || $(this).val() == null) {
                    Swal.fire("Barang Tidak Boleh Kosong Atau 0");
                    ada = true;
                }
            });
            $(this)
                .find("td .n_order")
                .each(function() {
                    if (
                        $(this).val() == "" ||
                        $(this).val() == null ||
                        $(this).val() == 0
                    ) {
                        Swal.fire("JUMLAH Tidak Boleh Kosong Atau 0");
                        ada = true;
                    }
                });
            
    });

    if (!ada) {
        e.preventDefault();
        var formData = new FormData(this);
        if (formData) {
            // console.log($('#e_remark').val());
            var miu = $('#e_remark').val();
            // var keterangan      = $('#e_remark').val();
            // if (keterangan == '' || keterangan == null) {
            //     Swal.fire({
            //         type: "error",
            //         title: g_maaf,
            //         text: "KETERANGAN harus diisi dengan benar !",
            //         confirmButtonClass: "btn btn-danger",
            //     });
            // } else {
            if (miu == '' || miu == null) {
                Swal.fire('KETERANGAN harus diisi dengan benar !');
            }else{
                sweetaddv33($("#path").val(), $("#d_from").val(), $("#d_to").val(), $("#harea").val(), formData);
            }
            // }
        }
    } else {
        return false;
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

function clear_tabel() {
    $("#tablecover tbody").empty();
    $("#jml").val("0");
    raya();
}

function raya() {
    var jml = $('#jml').val();
    var v_price = 0.00;

    var sub_total = 0.00;
    var tot = 0;
    for (var x = 1; x <= jml; x++) {
        if ($('#n_order' + x).length && $('#i_product' + x).val() != "") {
            v_price = $('#v_unit_price' + x).val().replaceAll(',', '');
            n_order = $('#n_order' + x).val().replaceAll(',', '');

            sub_total += v_price * n_order;
            tot = Math.round(sub_total);
        }
    }
    $('#v_total').val(number_format(tot, 0, '.', ','));
    $('#terbilangnya').text(terbilang(tot));

}