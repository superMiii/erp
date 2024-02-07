$(document).ready(function() {
    $(".switch:checkbox").checkboxpicker();
    var controller = $("#path").val();
    $("#i_company").select2({
        /* placeholder: "Select Company", */
        width: "100%",
        dropdownAutoWidth: true,
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_company",
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

    var controller = $("#path").val();
    var Detail = $(function() {
        var i = $("#jml").val();
        $("#addrow").on("click", function() {
            i++;
            var no = $("#tablecover tr").length;
            $("#jml").val(i);
            var newRow = $("<tr>");
            var cols = "";
            cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
            cols += `<td><select data-nourut="${i}" required class="form-control" name="i_departemen${i}" id="i_departemen${i}"><option value=""></option></select></td>`;
            cols += `<td><select data-urut="${i}" required class="form-control" multiple name="i_level${i}[]" id="i_level${i}"><option value=""></option></select></td>`;
            cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-2x text-danger ibtnDel"></i></td>`;
            newRow.append(cols);
            $("#tablecover").append(newRow);
            $("#i_departemen" + i)
                .select2({
                    placeholder: "Select",
                    dropdownAutoWidth: true,
                    width: '100%',
                    /* containerCssClass: 'select-xs', */
                    allowClear: true,
                    ajax: {
                        url: base_url + controller + "/get_department",
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
                .change(function(event) {
                    $("#i_level" + z).val("");
                    $("#i_level" + z).html("");
                    var z = $(this).data("nourut");
                    var ada = false;
                    for (var x = 1; x <= $("#jml").val(); x++) {
                        if ($(this).val() != null) {
                            if ($(this).val() == $("#i_departemen" + x).val() /* && $("#i_level" + z).val() == $("#i_level" + x).val() */ && z != x) {
                                swal("Sorry :(", "Data already exist ..", "error");
                                ada = true;
                                break;
                            }
                        }
                    }
                    if (ada) {
                        $(this).val("");
                        $(this).html("");
                        $("#i_level" + z).val("");
                        $("#i_level" + z).html("");
                    }
                });

            $("#i_level" + i)
                .select2({
                    placeholder: "Select",
                    dropdownAutoWidth: true,
                    width: '100%',
                    /* containerCssClass: 'select-xs', */
                    allowClear: true,
                    ajax: {
                        url: base_url + controller + "/get_level",
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
                /* .change(function(event) {
                    var z = $(this).data("urut");
                    var ada = false;
                    for (var x = 1; x <= $("#jml").val(); x++) {
                        if ($(this).val() != null) {
                            if ($(this).val() == $("#i_level" + x).val() && $("#i_departemen" + z).val() == $("#i_departemen" + x).val() && z != x) {
                                swal("Sorry :(", "Data already exist ..", "error");
                                ada = true;
                                break;
                            }
                        }
                    }
                    if (ada) {
                        $(this).val("");
                        $(this).html("");
                    }
                }) */
            ;
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

    for (let ii = 1; ii <= $('#jmlx').val(); ii++) {
        $("#i_company" + ii).select2({
            placeholder: "Select",
            dropdownAutoWidth: true,
            width: '100%',
            /* containerCssClass: 'select-xs', */
            allowClear: true,
            ajax: {
                url: base_url + controller + "/get_company",
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
            var zz = $(this).data("nourut");
            $('#i_area' + zz).val("");
            $('#i_area' + zz).html("");
            var ada = false;
            for (var xx = 1; xx <= $("#jmlx").val(); xx++) {
                if ($(this).val() != null) {
                    if ($(this).val() == $("#i_company" + xx).val() /* && $("#i_area" + z).val() == $("#i_area" + x).val() */ && zz != xx) {
                        swal("Sorry :(", "Data already exist ..", "error");
                        ada = true;
                        break;
                    }
                }
            }
            if (ada) {
                $(this).val("");
                $(this).html("");
                $('#i_area' + zz).val("");
                $('#i_area' + zz).html("");
            }
        });

        $("#i_area" + ii).select2({
            placeholder: "Select",
            dropdownAutoWidth: true,
            width: '100%',
            /* containerCssClass: 'select-xs', */
            allowClear: true,
            ajax: {
                url: base_url + controller + "/get_area",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        i_company: $('#i_company' + $(this).data("urut")).val(),
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
    }

    var Detailx = $(function() {
        var ii = $("#jmlx").val();
        $("#addriw").on("click", function() {
            ii++;
            var noo = $("#tablecoverarea tr").length;
            $("#jmlx").val(ii);
            var newRow = $("<tr>");
            var cols = "";
            cols += `<td class="text-center"><spanx id="snum${ii}">${noo}</spanx></td>`;
            cols += `<td><select data-nourut="${ii}" required class="form-control" name="i_company${ii}" id="i_company${ii}"><option value=""></option></select></td>`;
            cols += `<td><select data-urut="${ii}" required class="form-control" multiple name="i_area${ii}[]" id="i_area${ii}"><option value=""></option></select></td>`;
            cols += `<td class="text-center"><fieldset><div class="float-center"><input type="checkbox" onchange="cek(${ii});" id="f_all_area${ii}" class="switch" data-onstyle="success" data-on-label="Ya" data-off-label="Tidak" data-switch-always /><input type="hidden" id="f_checked${ii}" name="f_checked${ii}" value="f"/></div></fieldset></td>`;
            cols += `<td><select data-urutrv="${ii}" class="form-control" multiple name="i_rv_type${ii}[]" id="i_rv_type${ii}"><option value=""></option></select></td>`;
            cols += `<td><select data-urutpv="${ii}" class="form-control" multiple name="i_pv_type${ii}[]" id="i_pv_type${ii}"><option value=""></option></select></td>`;
            cols += `<td><select data-urutst="${ii}" class="form-control" multiple name="i_store${ii}[]" id="i_store${ii}"><option value=""></option></select></td>`;
            cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-2x text-danger ibtnDel"></i></td>`;
            newRow.append(cols);
            $("#tablecoverarea").append(newRow);
            $(".switch:checkbox").checkboxpicker();
            $("#i_company" + ii).select2({
                placeholder: "Select",
                dropdownAutoWidth: true,
                width: '100%',
                /* containerCssClass: 'select-xs', */
                allowClear: true,
                ajax: {
                    url: base_url + controller + "/get_company",
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
                var zz = $(this).data("nourut");
                $('#i_area' + zz).val("");
                $('#i_area' + zz).html("");
                $('#i_rv_type' + zz).html("");
                $('#i_pv_type' + zz).html("");
                $('#i_store' + zz).html("");
                var ada = false;
                for (var xx = 1; xx <= $("#jmlx").val(); xx++) {
                    if ($(this).val() != null) {
                        if ($(this).val() == $("#i_company" + xx).val() /* && $("#i_area" + z).val() == $("#i_area" + x).val() */ && zz != xx) {
                            swal("Sorry :(", "Data already exist ..", "error");
                            ada = true;
                            break;
                        }
                    }
                }
                if (ada) {
                    $(this).val("");
                    $(this).html("");
                    $('#i_area' + zz).val("");
                    $('#i_area' + zz).html("");
                    $('#i_rv_type' + zz).html("");
                    $('#i_pv_type' + zz).html("");
                    $('#i_store' + zz).html("");
                }
            });

            $("#i_area" + ii).select2({
                placeholder: "Select",
                dropdownAutoWidth: true,
                width: '100%',
                /* containerCssClass: 'select-sm', */
                allowClear: true,
                ajax: {
                    url: base_url + controller + "/get_area",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
                            i_company: $('#i_company' + $(this).data("urut")).val(),
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

            $("#i_rv_type" + ii).select2({
                placeholder: "Select",
                dropdownAutoWidth: true,
                width: '100%',
                /* containerCssClass: 'select-sm', */
                allowClear: true,
                ajax: {
                    url: base_url + controller + "/get_rv_type",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
                            i_company: $('#i_company' + $(this).data("urutrv")).val(),
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

            $("#i_pv_type" + ii).select2({
                placeholder: "Select",
                dropdownAutoWidth: true,
                width: '100%',
                /* containerCssClass: 'select-sm', */
                allowClear: true,
                ajax: {
                    url: base_url + controller + "/get_pv_type",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
                            i_company: $('#i_company' + $(this).data("urutpv")).val(),
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

            $("#i_store" + ii).select2({
                placeholder: "Select",
                dropdownAutoWidth: true,
                width: '100%',
                /* containerCssClass: 'select-sm', */
                allowClear: true,
                ajax: {
                    url: base_url + controller + "/get_store",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
                            i_company: $('#i_company' + $(this).data("urutst")).val(),
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
        });

        $("#tablecoverarea").on("click", ".ibtnDel", function(event) {
            $(this).closest("tr").remove();

            $("#jmlx").val(ii);
            var obj = $("#tablecoverarea tr:visible").find("spanx");
            $.each(obj, function(key, value) {
                id = value.id;
                $("#" + id).html(key + 1);
            });
        });
    });


    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $('form').on('submit', function(e) { //bind event on form submit.
        let tabel = $("#tablecover tbody tr").length;
        let tabelx = $("#tablecoverarea tbody tr").length;

        if (tabel < 1) {
            swal("Sorry :(", "Input item Departemen & Level minimum 1!", "error");
            return false;
        }

        if (tabelx < 1) {
            swal("Sorry :(", "Input item Perusahaan & Area minimum 1!", "error");
            return false;
        }

        e.preventDefault();
        var formData = new FormData(this);
        if (formData) {
            sweetadd(controller, formData);
        }
    });
});

function cek(i) {
    var Checked = $("#f_all_area" + i + ":checkbox:checked").length;
    if (Checked > 0) {
        $("#f_checked" + i).val("t");
        $("#i_area" + i).val("");
        $("#i_area" + i).html("");
        $("#i_area" + i).prop("required", false);
        $("#i_area" + i).prop("disabled", true);
    } else {
        $("#f_checked" + i).val("f");
        $("#i_area" + i).prop("required", true);
        $("#i_area" + i).prop("disabled", false);
    }
}