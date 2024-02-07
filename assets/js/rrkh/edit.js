$(document).ready(function() {
    $('.skin-square input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
    });
    $('#day').val(set_day($('#d_document').val()));
    $("#i_area").select2({
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
        /* $("#i_salesman").val("");
        $("#i_salesman").html(""); */
        clear_tabel();
    });

    $("#i_salesman").select2({
        dropdownAutoWidth: true,
        width: "100%",
        containerCssClass: "select-sm",
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_salesman",
            dataType: "json",
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
                    i_customer: $("#i_customer").val(),
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
    });

    $("#d_document").change(function() {
        $('#day').val(set_day($(this).val()));
    });

    for (let i = 1; i <= $('#jml').val(); i++) {
        $("#i_kunjungan_type" + i).select2({
            placeholder: g_pilihdata,
            dropdownAutoWidth: true,
            width: '100%',
            containerCssClass: 'select-xs',
            allowClear: true,
            ajax: {
                url: base_url + $("#path").val() + "/get_kunjungan",
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
        });
        $("#i_customer" + i).select2({
            placeholder: g_pilihdata,
            dropdownAutoWidth: true,
            width: '100%',
            containerCssClass: 'select-xs',
            allowClear: true,
            ajax: {
                url: base_url + $("#path").val() + "/get_customer",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        i_area: $("#i_area").val(),
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
                    if ($(this).val() == $("#i_customer" + x).val() && z != x) {
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
                        'i_customer': $(this).val(),
                    },
                    url: base_url + $("#path").val() + "/get_detail_customer",
                    dataType: "json",
                    success: function(data) {
                        $("#i_city" + z).val(data['detail'][0]['i_city']);
                        $("#e_city_name" + z).val(data['detail'][0]['e_city_name']);
                    },
                });
            }
        });
    }

    var Detail = $(function() {
        var i = $("#jml").val();
        $("#addrow").on("click", function() {
            i++;
            var no = $("#tabledetail tbody tr").length + 1;
            $("#jml").val(i);
            var newRow = $("<tr>");
            var cols = "";
            cols += `<td class="text-center" valign="center"><spanx id="snum${i}">${no}</spanx></td>`;
            cols += `<td class="text-center"><div class="controls skin-square"><input type="checkbox" id="f_kunjungan_valid${i}" name="f_kunjungan_valid[]"></div></td>`;
            cols += `<td><select data-nourut="${i}" required class="form-control select2-size-sm" name="i_customer[]" id="i_customer${i}"><option value=""></option></select></td>`;
            cols += `<td><input type="text" readonly class="form-control form-control-sm" id="e_city_name${i}" readonly><input type="hidden" id="i_city${i}" name="i_city[]"></td>`;
            cols += `<td><select data-nourut="${i}" required class="form-control select2-size-sm" name="i_kunjungan_type[]" id="i_kunjungan_type${i}"><option value=""></option></select></td>`;
            cols += `<td class="text-center"><div class="controls skin-square"><input type="checkbox" id="f_kunjungan_realisasi${i}" name="f_kunjungan_realisasi[]"></div></td>`;
            cols += `<td><input type="text" class="form-control form-control-sm" id="e_remark${i}" name="e_remark[]"></td>`;
            cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>`;
            newRow.append(cols);
            $("#tabledetail").append(newRow);
            // Square Checkbox & Radio
            $('.skin-square input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
            });
            $("#i_kunjungan_type" + i).select2({
                placeholder: g_pilihdata,
                dropdownAutoWidth: true,
                width: '100%',
                containerCssClass: 'select-xs',
                allowClear: true,
                ajax: {
                    url: base_url + $("#path").val() + "/get_kunjungan",
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
            });
            $("#i_customer" + i).select2({
                placeholder: g_pilihdata,
                dropdownAutoWidth: true,
                width: '100%',
                containerCssClass: 'select-xs',
                allowClear: true,
                ajax: {
                    url: base_url + $("#path").val() + "/get_customer",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
                            i_area: $("#i_area").val(),
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
                        if ($(this).val() == $("#i_customer" + x).val() && z != x) {
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
                            'i_customer': $(this).val(),
                        },
                        url: base_url + $("#path").val() + "/get_detail_customer",
                        dataType: "json",
                        success: function(data) {
                            $("#i_city" + z).val(data['detail'][0]['i_city']);
                            $("#e_city_name" + z).val(data['detail'][0]['e_city_name']);
                        },
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

        $("#tabledetail tbody tr").each(function() {
            $(this).find("td select").each(function() {
                if ($(this).val() == '' || $(this).val() == null) {
                    Swal.fire({
                        type: "error",
                        title: g_maaf,
                        text: "Item not empty",
                        confirmButtonClass: "btn btn-danger",
                    });
                    ada = true;
                }
            });
        });

        if (!ada) {
            e.preventDefault();
            var formData = new FormData(this);
            if (formData) {
                sweeteditv33($("#path").val(), $("#d_from").val(), $("#d_to").val(), $("#harea").val(), formData);
            }
        } else {
            return false;
        }
    });
});

function clear_tabel() {
    $('#tabledetail tbody').empty();
    $('#jml').val("0");
}