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
                        'i_product': $('#i_product' + z).val(),
                    },
                    url: base_url + $("#path").val() + '/get_product_detail',
                    dataType: "json",
                    success: function(data) {
                        $("#i_product_motif" + z).val(data.detail[0]['i_product_motif']);
                        $("#i_product_grade" + z).val(data.detail[0]['i_product_grade']);
                        $("#e_product_motifname" + z).val(data.detail[0]['e_product_motifname']);
                        $("#v_unit_price" + z).val(data.detail[0]['v_price']);
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

    $("#i_area").select2({
        width: "100%",
        dropdownAutoWidth: true,
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
    })
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
            <input type="hidden" name="v_unit_price[]" id="v_unit_price${i}" readonly class="form-control form-control-sm">
            <input type="text" id="e_product_motifname${i}" readonly class="form-control form-control-sm">
            </td>`;
        cols += `<td><input type="number" value="0" name="n_order[]" id="n_order${i}" class="form-control form-control-sm" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\'></td>`;
        cols += `<td><input type="number" value="0" readonly name="n_acc[]" id="n_acc${i}" class="form-control form-control-sm" onkeyup="hetang(${i});"></td>`;
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
                        'i_product': $('#i_product' + z).val(),
                    },
                    url: base_url + $("#path").val() + '/get_product_detail',
                    dataType: "json",
                    success: function(data) {
                        $("#i_product_motif" + z).val(data.detail[0]['i_product_motif']);
                        $("#i_product_grade" + z).val(data.detail[0]['i_product_grade']);
                        $("#e_product_motifname" + z).val(data.detail[0]['e_product_motifname']);
                        $("#v_unit_price" + z).val(data.detail[0]['v_price']);
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
    });
});

$("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
$('form').on('submit', function(e) { //bind event on form submit.
    let tabel = $("#tablecover tbody tr").length;

    if (tabel < 1) {
        Swal.fire({
            type: "error",
            title: g_maaf,
            text: "Input minimum 1 item !",
            confirmButtonClass: "btn btn-danger",
        });
        return false;
    }

    e.preventDefault();
    var formData = new FormData(this);
    if (formData) {
        var keterangan = $('#e_note').val();
    if (keterangan == '' || keterangan == null) {
        Swal.fire({
            type: "error",
            title: g_maaf,
            text: "Keterangan harus diisi!",
            confirmButtonClass: "btn btn-danger",
        });
    } else {
        sweeteditv33($("#path").val(), $("#d_from").val(), $("#d_to").val(), $("#harea").val(), formData);
    }
    }
});

function app(folder, id) {
    var keterangan = $('#e_note').val();
    if (keterangan == '' || keterangan == null) {
        Swal.fire({
            type: "error",
            title: g_maaf,
            text: "Keterangan harus diisi!",
            confirmButtonClass: "btn btn-danger",
        });
    } else {
        sweetapprovev2(folder, id, keterangan);
    }
}

function hetang(i) {
    if (parseFloat($('#n_acc' + i).val()) > parseFloat($('#n_order' + i).val())) {
        Swal.fire({
            type: "error",
            title: g_maaf,
            text: "Jml Acc != > Jml Order",
            confirmButtonClass: "btn btn-danger",
        });
        $('#n_acc' + i).val($('#n_order' + i).val());
    }
}