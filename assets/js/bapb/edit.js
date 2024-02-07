$(document).ready(function() {
    hetang();
    $(".select2").select2();
    $("#i_sl_kirim").select2({
        dropdownAutoWidth: true,
        width: '100%',
        containerCssClass: 'select-sm',
    });

    $("#i_area").select2({
        dropdownAutoWidth: true,
        width: '100%',
        containerCssClass: 'select-sm',
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
        $('#i_customer').val("");
        $('#i_customer').html("");
    });

    $("#i_customer").select2({
        dropdownAutoWidth: true,
        width: '100%',
        containerCssClass: 'select-sm',
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_customer",
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
        clear_tabel();
    });

    for (let i = 1; i <= $('#jml').val(); i++) {
        $("#i_do" + i).select2({
            placeholder: g_pilihdata,
            dropdownAutoWidth: true,
            width: '100%',
            containerCssClass: 'select-xs',
            allowClear: true,
            ajax: {
                url: base_url + $("#path").val() + "/get_do",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        i_area: $("#i_area").val(),
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
        }).change(function(event) {
            var z = $(this).data("nourut");
            var ada = false;
            for (var x = 1; x <= $("#jml").val(); x++) {
                if ($(this).val() != null) {
                    if ($(this).val() == $("#i_do" + x).val() && z != x) {
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
                        'i_do': $(this).val(),
                    },
                    url: base_url + $("#path").val() + "/get_detail_do",
                    dataType: "json",
                    success: function(data) {
                        /* $("#e_customer_name" + z).val(data['detail'][0]['e_customer_name']); */
                        $("#d_do" + z).val(data['detail'][0]['d_do']);
                        $("#date_do" + z).val(data['detail'][0]['date_do']);
                        $("#v_jumlah" + z).val(number_format(data['detail'][0]['v_jumlah']));
                        hetang();
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
            cols += `<td><select data-nourut="${i}" required class="form-control select2-size-sm" name="i_do[]" id="i_do${i}"><option value=""></option></select></td>`;
            cols += `<td><input type="hidden" id="d_do${i}" name="d_do[]"><input type="text" readonly class="form-control form-control-sm" id="date_do${i}" readonly></td>`;
            /* cols += `<td><input type="text" readonly class="form-control form-control-sm" id="e_customer_name${i}" readonly></td>`; */
            cols += `<td><input type="text" readonly class="form-control form-control-sm text-right v_jumlah" value="0" id="v_jumlah${i}" name="v_jumlah[]" readonly></td>`;
            cols += `<td><input type="text" class="form-control form-control-sm" id="e_remark${i}" name="e_remark[]"></td>`;
            cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>`;
            newRow.append(cols);
            $("#tabledetail").append(newRow);
            $("#i_do" + i).select2({
                placeholder: g_pilihdata,
                dropdownAutoWidth: true,
                width: '100%',
                containerCssClass: 'select-xs',
                allowClear: true,
                ajax: {
                    url: base_url + $("#path").val() + "/get_do",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
                            i_area: $("#i_area").val(),
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
            }).change(function(event) {
                var z = $(this).data("nourut");
                var ada = false;
                for (var x = 1; x <= $("#jml").val(); x++) {
                    if ($(this).val() != null) {
                        if ($(this).val() == $("#i_do" + x).val() && z != x) {
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
                            'i_do': $(this).val(),
                        },
                        url: base_url + $("#path").val() + "/get_detail_do",
                        dataType: "json",
                        success: function(data) {
                            /* $("#e_customer_name" + z).val(data['detail'][0]['e_customer_name']); */
                            $("#d_do" + z).val(data['detail'][0]['d_do']);
                            $("#date_do" + z).val(data['detail'][0]['date_do']);
                            $("#v_jumlah" + z).val(number_format(data['detail'][0]['v_jumlah']));
                            hetang();
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
            hetang();
        });
    });

    for (let ii = 1; ii <= $("#jmlx").val(); ii++) {
        $("#i_sl_ekspedisi" + ii).select2({
            placeholder: g_pilihdata,
            dropdownAutoWidth: true,
            width: '100%',
            containerCssClass: 'select-xs',
            allowClear: true,
            ajax: {
                url: base_url + $("#path").val() + "/get_ekspedisi",
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
            for (var x = 1; x <= $("#jmlx").val(); x++) {
                if ($(this).val() != null) {
                    if ($(this).val() == $("#i_sl_ekspedisi" + x).val() && z != x) {
                        swal(g_maaf, g_exist, "error");
                        ada = true;
                        break;
                    }
                }
            }
            if (ada) {
                $(this).val("");
                $(this).html("");

            }
        });
    }

    var Detailx = $(function() {
        var ii = $("#jmlx").val();
        $("#addrowx").on("click", function() {
            ii++;
            var no = $("#tabledetailx tbody tr").length + 1;
            $("#jmlx").val(ii);
            var newRow = $("<tr>");
            var cols = "";
            cols += `<td class="text-center" valign="center"><spanxx id="snum${ii}">${no}</spanxx></td>`;
            cols += `<td><select data-nourut="${ii}" required class="form-control select2-size-sm" name="i_sl_ekspedisi[]" id="i_sl_ekspedisi${ii}"><option value=""></option></select></td>`;
            cols += `<td><input type="text" class="form-control form-control-sm" id="e_remark_ekspedisi${ii}" name="e_remark_ekspedisi[]"></td>`;
            cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>`;
            newRow.append(cols);
            $("#tabledetailx").append(newRow);
            $("#i_sl_ekspedisi" + ii).select2({
                placeholder: g_pilihdata,
                dropdownAutoWidth: true,
                width: '100%',
                containerCssClass: 'select-xs',
                allowClear: true,
                ajax: {
                    url: base_url + $("#path").val() + "/get_ekspedisi",
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
                for (var x = 1; x <= $("#jmlx").val(); x++) {
                    if ($(this).val() != null) {
                        if ($(this).val() == $("#i_sl_ekspedisi" + x).val() && z != x) {
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
                }
            });
        });

        /*----------  Hapus Baris Data Saudara  ----------*/

        $("#tabledetailx").on("click", ".ibtnDel", function(event) {
            $(this).closest("tr").remove();

            $("#jmlx").val(i);
            var obj = $("#tabledetailx tr:visible").find("spanxx");
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
    $('#tabledetailx tbody').empty();
    $('#jmlx').val("0");
}

function hetang() {
    let v_cgr = 0;
    $("#tabledetail tbody tr td .v_jumlah").each(function() {
        v_cgr += parseFloat(formatulang($(this).val()));
    });
    $('#v_cgr').val(number_format(v_cgr));
}