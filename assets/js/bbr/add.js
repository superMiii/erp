$(document).ready(function() {
    number();
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
        var no = $("#tabledetail tbody tr").length+1;
        $("#jml").val(i);
        var newRow = $("<tr>");
        var cols = "";
        cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
        cols += `<td><select data-nourut="${i}" required class="form-control select2-size-sm" name="i_product[]" id="i_product${i}"><option value=""></option></select></td>`;
        cols += `<td><input type="text" name="v_unit_price[]" id="v_unit_price${i}" readonly class="form-control form-control-sm text-right"></td>`;
        cols += `<td><input type="text" name="st[]" id="st${i}" readonly class="form-control form-control-sm text-right"></td>`;
        cols += `<td><input type="number" autocomplete="off" class="form-control text-center n_order form-control-sm" value="0" name="n_order[]" id="n_order${i}" onkeyup="itung(${i}); miru(${i});" onblur=\'if(this.value==""){this.value="0";miru(${i})}\' onfocus=\'if(this.value=="0"){this.value="";}\'></td>`;
        cols += `<td><input type="text" name="v_tot[]" id="v_tot${i}" readonly class="form-control form-control-sm total text-right"></td>`;
        cols += `<td>
            <input type="hidden" name="i_product_motif[]" id="i_product_motif${i}" readonly class="form-control form-control-sm">
            <input type="hidden" name="i_product_grade[]" id="i_product_grade${i}" readonly class="form-control form-control-sm">            
            <input type="hidden" name="e_product_name[]" id="e_product_name${i}" readonly class="form-control form-control-sm">           
            <input type="hidden" id="e_product_motifname${i}" readonly class="form-control form-control-sm">       
            <input type="text" name="ket[]" id="ket${i}" class="form-control form-control-sm">
            </td>`;
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
                        i_supplier:$("#i_supplier").val(),
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
            var jm =0;
            if (ada) {
                $(this).val("");
                $(this).html("");
            } else {
                $.ajax({
                    type: "post",
                    data: {
                        'i_product': $('#i_product' + z).val(),
                        'i_supplier': $('#i_supplier').val(),
                    },
                    url: base_url + $("#path").val() + '/get_product_detail',
                    dataType: "json",
                    success: function(data) {
                        $("#i_product_motif" + z).val(data.detail[0]['i_product_motif']);
                        $("#i_product_grade" + z).val(data.detail[0]['i_product_grade']);
                        $("#e_product_motifname" + z).val(data.detail[0]['e_product_motifname']);
                        $("#e_product_name" + z).val(data.detail[0]['e_product_name']);
                        $("#v_unit_price" + z).val(number_format(data.detail[0]['v_price'],2)); 
                        $("#st" + z).val(data.detail[0]['st']);
                        $("#n_order" + z).val("0");
                        $("#n_order" + z).html("0");
                        $("#v_tot" + z).val("0");
                        $("#v_tot" + z).html("0");
                        itung();
                        hetang();
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
            Swal.fire({
                type: "error",
                title: g_maaf,
                text: g_detailmin,
                confirmButtonClass: "btn btn-danger",
            });
            return false;
        }

        var jm =0;
        $("#tabledetail tbody tr").each(function() {
            $(this).find("td select").each(function() {
                if ($(this).val() == '' || $(this).val() == null) {
                    Swal.fire('Barang tidak boleh kosong atau 0');
                    ada = true;
                }
            });
            
            $(this).find("td .n_order").each(function() {
                var kr = $(this).val();
                if ($(this).val() == '' || $(this).val() == null ) {
                    kr=0;
                }
                jm+=parseFloat(kr);
            });
        });

        if (jm > 0) {
            if (!ada) {
                e.preventDefault();
                var formData = new FormData(this);
                if (formData) {
                    sweetaddv33sup($("#path").val(), $("#d_from").val(), $("#d_to").val(), $("#hsup").val(), formData);
                }
            } else {
                return false;
            }

        }else{
            Swal.fire('Total Kirim RETUR tidak boleh KOSONG');
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

function itung(e) {
    var qty= parseFloat(formatulang($("#n_order"+e).val()));
    if (qty == '' || qty == null|| isNaN(qty)) {
        qty = 0;
    }
    var harga= parseFloat(formatulang($("#v_unit_price"+e).val()));
    $("#v_tot"+e).val(number_format(qty*harga,2));
    hetang();
}
function hetang() {
    let v_total = 0;
    $("#tabledetail tbody tr td .total").each(function() {
        v_total += parseFloat(formatulang($(this).val()));
    });
    $('#tfoot_total').val(number_format(v_total,2));
}
function miru(i) {
    if (parseFloat($('#n_order' + i).val()) > parseFloat($('#st' + i).val())) {
        Swal.fire({
            type: "error",
            title: g_maaf,
            text: "Qty Retur = '" + $('#n_order' + i).val() + "' tidak boleh lebih dari Stock = '" + $('#st' + i).val() + "' !",
            confirmButtonClass: "btn btn-danger",
        });
        $('#n_order' + i).val($('#st' + i).val());
    }
}


function clear_tabel() {
    $("#tabledetail tbody").empty();
    $("#jml").val("0");
}