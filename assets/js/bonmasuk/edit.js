$(document).ready(function() {
    $('#i_document').inputmask("aa-9999-999999");
    pickdate();

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
                        $("#i_product_grade" + z).val(3);
                        $("#e_product_motifname" + z).val(data.detail[0]['e_product_motifname']);
                        $("#n_stock" + z).val(0);
                        $("#v_unit_price" + z).val(0);
                        $("#n_acc" + z).val(0);
                        $("#n_saldo" + z).val(0);
                    },
                    error: function() {
                        swal('Error :)');
                    }
                });
            }
        });
    }

    $("#i_store").select2({
        width: "100%",
        dropdownAutoWidth: true,
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_store",
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
    }).change(function() {
        $.ajax({
            type: "post",
            data: {
                'i_store': $(this).val(),
            },
            url: base_url + $("#path").val() + '/get_area',
            dataType: "json",
            success: function(data) {
                $('#i_area').val(data.detail[0].i_area);
            },
            error: function() {
                swal('Error :)');
            }
        });
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
            <input type="hidden" name="n_stock[]" id="n_stock${i}" readonly class="form-control form-control-sm">
            <input type="hidden" name="v_unit_price[]" id="v_unit_price${i}" readonly class="form-control form-control-sm">
            <input type="hidden" name="n_saldo[]" id="n_saldo${i}" readonly class="form-control form-control-sm">
            <input type="text" id="e_product_motifname${i}" readonly class="form-control form-control-sm">
            </td>`;
            cols += `<td><input type="number" value="0" name="n_order[]" id="n_order${i}" class="form-control form-control-sm" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\'></td>`;
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
                            $("#i_product_grade" + z).val(3);
                            $("#e_product_motifname" + z).val(data.detail[0]['e_product_motifname']);
                            $("#n_stock" + z).val(0);
                            $("#v_unit_price" + z).val(0);
                            $("#n_acc" + z).val(0);
                            $("#n_saldo" + z).val(0);
                        },
                        error: function() {
                            swal('Error :)');
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
        number();
    });

    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $('form').on('submit', function(e) { //bind event on form submit.
        let tabel = $("#tablecover tbody tr").length;

        if (tabel < 1) {
            swal("Sorry :(", "Input item Departemen & Level minimum 1!", "error");
            return false;
        }

        e.preventDefault();
        var formData = new FormData(this);
        if (formData) {
            sweetedit($("#path").val(), formData);
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