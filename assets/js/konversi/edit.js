$(document).ready(function() {

    for (let i = 1; i <= $('#jml').val(); i++) {
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
                    if ($(this).val() == $("#i_product" + x).val() && z != x) {
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
                        'i_product': $('#i_product' + z).val(),
                    },
                    url: base_url + $("#path").val() + '/get_product_detail',
                    dataType: "json",
                    success: function(data) {
                        $("#i_product_motif" + z).val(data.detail[0]['i_product_motif']);
                        $("#i_product_grade" + z).val(data.detail[0]['i_product_grade']);
                        $("#e_product_name" + z).val(data.detail[0]['e_product_name']);
                        $("#e_product_motifname" + z).val(data.detail[0]['e_product_motifname']);
                        $("#e_product_gradename" + z).val(data.detail[0]['e_product_gradename']);
                        $("#n_stk").val(data.detail[0]['n_stk']);
                        $("#convertion" + z).focus();
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
    }
});


var Detail = $(function() {
    var i = $("#jml").val();
    $("#addrow").on("click", function() {
        i++;
        var no = $("#tablecover tr").length;
        $("#jml").val(i);
        var newRow = $("<tr>");
        var cols = "";
        cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
        cols += `<td><select data-nourut="${i}" required class="form-control select2-size-sm" name="i_product[]" id="i_product${i}"><option value=""></option></select></td>`;
        cols += `<td>
            <input type="hidden" name="i_product_motif[]" id="i_product_motif${i}" readonly class="form-control form-control-sm">
            <input type="hidden" name="i_product_grade[]" id="i_product_grade${i}" readonly class="form-control form-control-sm">
            <input type="hidden" name="e_product_name[]" id="e_product_name${i}" readonly class="form-control form-control-sm">
            <input type="text" id="e_product_motifname${i}" readonly class="form-control form-control-sm">
            </td>`;
        cols += `<td><input type="text" readonly name="e_product_gradename[]" id="e_product_gradename${i}" class="form-control form-control-sm"></td>`;
        cols += `<td><input type="number" value="0" name="convertion[]" id="convertion${i}" class="form-control convertion form-control-sm" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\'></td>`;
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
                url: base_url + $("#path").val() + "/get_product2",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        i_product_asal: $('#i_product_asal').val(),
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
                    if ($(this).val() == $("#i_product" + x).val() && z != x) {
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
                        'i_product': $('#i_product' + z).val(),
                    },
                    url: base_url + $("#path").val() + '/get_product_detail',
                    dataType: "json",
                    success: function(data) {
                        $("#i_product_motif" + z).val(data.detail[0]['i_product_motif']);
                        $("#i_product_grade" + z).val(data.detail[0]['i_product_grade']);
                        $("#e_product_name" + z).val(data.detail[0]['e_product_name']);
                        $("#e_product_motifname" + z).val(data.detail[0]['e_product_motifname']);
                        $("#e_product_gradename" + z).val(data.detail[0]['e_product_gradename']);
                        $("#convertion" + z).focus();
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



    /*----------  Hapus Baris Data  ----------*/

    $("#tablecover").on("click", ".ibtnDel", function(event) {
        $(this).closest("tr").remove();

        $("#jml").val(i);
        var obj = $("#tablecover tr:visible").find("spanx");
        $.each(obj, function(key, value) {
            id = value.id;
            $("#" + id).html(key + 1);
        });
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
                .find("td .convertion")
                .each(function() {
                    if (
                        $(this).val() == "" ||
                        $(this).val() == null ||
                        $(this).val() == 0
                    ) {
                        Swal.fire("JUMLAH KONVERSI Tidak Boleh Kosong Atau 0");
                        ada = true;
                    }
                });
            
    });
    $(this)
    .find(".n_convertion")
    .each(function() {
        if (
            $(this).val() == "" ||
            $(this).val() == null ||
            $(this).val() == 0
        ) {
            Swal.fire("JUMLAH ASAL Tidak Boleh Kosong Atau 0");
            ada = true;
        }
    });    

    if (!ada) {
        e.preventDefault();
        var formData = new FormData(this);
        if (formData) {
            sweeteditv3($("#path").val(), $("#d_from").val(), $("#d_to").val(), formData);
        }       
    } else {
        return false;
    }
});


function raya() {
    var qty=parseFloat(formatulang($('#n_convertion').val()));
    var kon=parseFloat(formatulang($('#kon').val()));
    var stok=parseFloat(formatulang($('#n_stk').val()));
    var el= (kon+stok);
    if (qty > el) {
        Swal.fire('Maaf Jumlah Konversi = ' + $('#n_convertion').val() + ' Melebihi Stock = ' + $('#n_stk').val() + ' + ' + $('#kon').val() + ' = ' + el);
        $('#n_convertion').val(el);
    }
}