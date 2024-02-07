$(document).ready(function() {
    number();
    $('.skin-square input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
    });
    $("#i_promo_type").select2({
        dropdownAutoWidth: true,
        width: "100%",
        containerCssClass: "select-sm",
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_type",
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
        $.ajax({
            type: "post",
            data: {
                'i_promo_type': $(this).val(),
            },
            url: base_url + $("#path").val() + "/get_valid",
            dataType: "json",
            success: function(data) {
                $('#n_promo_discount1').val(0);
                $('#n_promo_discount2').val(0);
                if (data['valid'].length > 0) {
                    let read = data['valid'][0]['n_valid'];
                    if (read == 2) {
                        $('#n_promo_discount1').attr('readonly', false);
                        $('#n_promo_discount2').attr('readonly', false);
                    } else if (read == 1) {
                        $('#n_promo_discount1').attr('readonly', false);
                        $('#n_promo_discount2').attr('readonly', true);
                    } else {
                        $('#n_promo_discount1').attr('readonly', true);
                        $('#n_promo_discount2').attr('readonly', true);
                    }
                } else {
                    $('#n_promo_discount1').attr('readonly', true);
                    $('#n_promo_discount2').attr('readonly', true);
                }
                $('#f_read_price').val(data['valid'][0]['f_read_price']);
                clear_tabel_product();
            },
        });
    });

    $("#i_price_group").select2({
        dropdownAutoWidth: true,
        width: "100%",
        containerCssClass: "select-sm",
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_group",
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
        number();
    });

    $('#f_all_product').on('ifChanged', function(event) {
        if (event.target.checked === true) {
            $('.tableproduct').attr('hidden', true);
        } else {
            $('.tableproduct').attr('hidden', false);
        }
        clear_tabel_product();
        /* alert('checked = ' + event.target.checked);
        alert('value = ' + event.target.value); */
    });

    var Product = $(function() {
        var i = $("#jml_product").val();
        $("#addrowproduct").on("click", function() {
            if ($('#i_price_group').val() != '') {
                if ($('#f_read_price').val() == 'f') {
                    var read = '';
                } else {
                    var read = 'readonly';
                }
                i++;
                var no = $("#tableproduct tbody tr").length + 1;
                $("#jml_product").val(i);
                var newRow = $("<tr>");
                var cols = "";
                cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
                cols += `<td><select data-nourut="${i}" required class="form-control" name="i_product[]" id="i_product${i}"><option value=""></option></select></td>`;
                cols += `<td><input type="text" readonly class="form-control form-control-sm" id="e_product_motifname${i}" readonly><input type="hidden" id="i_product_motif${i}" name="i_product_motif[]"><input type="hidden" id="i_product_grade${i}" name="i_product_grade[]"></td>`;
                cols += `<td><input type="text" class="form-control form-control-sm text-right" ${read} onkeyup="onlyangka(this);" id="v_unit_price${i}" name="v_unit_price[]"></td>`;
                cols += `<td><input type="text" class="form-control form-control-sm text-right" onkeyup="onlyangka(this);" id="n_quantity_min${i}" name="n_quantity_min[]" value="1" onblur=\"if(this.value==''){this.value='1';}\" onfocus=\"if(this.value=='1'){this.value='';}\"></td>`;
                cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>`;
                newRow.append(cols);
                $("#tableproduct").append(newRow);
                $("#i_product" + i).select2({
                    placeholder: g_pilihdata,
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
                                i_price_group: $("#i_price_group").val(),
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
                    for (var x = 1; x <= $("#jml_product").val(); x++) {
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
                                'i_product': $(this).val(),
                                i_price_group: $("#i_price_group").val(),
                            },
                            url: base_url + $("#path").val() + "/get_detail_product",
                            dataType: "json",
                            success: function(data) {
                                $("#i_product_motif" + z).val(data['detail'][0]['i_product_motif']);
                                $("#i_product_grade" + z).val(data['detail'][0]['i_product_grade']);
                                $("#e_product_motifname" + z).val(data['detail'][0]['e_product_motifname']);
                                $("#v_unit_price" + z).val(formatcemua(data['detail'][0]['v_unit_price']));
                                $("#n_quantity_min" + z).focus();
                            },
                        });
                    }
                });
            } else {
                Swal.fire({
                    type: "error",
                    title: g_maaf,
                    text: "Pilih Kelompok Harga Terlebih Dahulu!",
                    confirmButtonClass: "btn btn-danger",
                });
            }
        });

        /*----------  Hapus Baris Data Saudara  ----------*/

        $("#tableproduct").on("click", ".ibtnDel", function(event) {
            $(this).closest("tr").remove();

            $("#jml_product").val(i);
            var obj = $("#tableproduct tr:visible").find("spanx");
            $.each(obj, function(key, value) {
                id = value.id;
                $("#" + id).html(key + 1);
            });
        });
    });

    $('#f_all_customer').on('ifChanged', function(event) {
        if (event.target.checked === true) {
            $('.tablecustomer').attr('hidden', true);
        } else {
            $('.tablecustomer').attr('hidden', false);
        }
        clear_tabel_customer();
        /* alert('checked = ' + event.target.checked);
        alert('value = ' + event.target.value); */
    });

    var Customer = $(function() {
        var i = $("#jml_customer").val();
        $("#addrowcustomer").on("click", function() {
            i++;
            var no = $("#tablecustomer tbody tr").length + 1;
            $("#jml_customer").val(i);
            var newRow = $("<tr>");
            var cols = "";
            cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
            cols += `<td><select data-nourut="${i}" required class="form-control" name="i_customer[]" id="i_customer${i}"><option value=""></option></select></td>`;
            cols += `<td><input type="text" readonly class="form-control form-control-sm" id="e_customer_address${i}" readonly></td>`;
            cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>`;
            newRow.append(cols);
            $("#tablecustomer").append(newRow);
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
                for (var x = 1; x <= $("#jml_customer").val(); x++) {
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
                            $("#e_customer_address" + z).val(data['detail'][0]['e_customer_address']);
                        },
                    });
                }
            });
        });

        /*----------  Hapus Baris Data Saudara  ----------*/

        $("#tablecustomer").on("click", ".ibtnDel", function(event) {
            $(this).closest("tr").remove();

            $("#jml_customer").val(i);
            var obj = $("#tablecustomer tr:visible").find("spanx");
            $.each(obj, function(key, value) {
                id = value.id;
                $("#" + id).html(key + 1);
            });
        });
    });

    $('#f_all_area').on('ifChanged', function(event) {
        if (event.target.checked === true) {
            $('.tablearea').attr('hidden', true);
        } else {
            $('.tablearea').attr('hidden', false);
        }
        clear_tabel_area();
        /* alert('checked = ' + event.target.checked);
        alert('value = ' + event.target.value); */
    });

    var Area = $(function() {
        var i = $("#jml_area").val();
        $("#addrowarea").on("click", function() {
            i++;
            var no = $("#tablearea tbody tr").length + 1;
            $("#jml_area").val(i);
            var newRow = $("<tr>");
            var cols = "";
            cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
            cols += `<td><select data-nourut="${i}" required class="form-control" name="i_area[]" id="i_area${i}"><option value=""></option></select></td>`;
            cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>`;
            newRow.append(cols);
            $("#tablearea").append(newRow);
            $("#i_area" + i).select2({
                placeholder: g_pilihdata,
                dropdownAutoWidth: true,
                width: '100%',
                containerCssClass: 'select-xs',
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
                var z = $(this).data("nourut");
                var ada = false;
                for (var x = 1; x <= $("#jml_area").val(); x++) {
                    if ($(this).val() != null) {
                        if ($(this).val() == $("#i_area" + x).val() && z != x) {
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

        $("#tablearea").on("click", ".ibtnDel", function(event) {
            $(this).closest("tr").remove();

            $("#jml_area").val(i);
            var obj = $("#tablearea tr:visible").find("spanx");
            $.each(obj, function(key, value) {
                id = value.id;
                $("#" + id).html(key + 1);
            });
        });
    });

    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $('form').on('submit', function(e) { //bind event on form submit.
        let ada = false;
        let tableproduct = $("#tableproduct tbody tr").length;
        const product = $('#f_all_product').iCheck('update')[0].checked;
        if (product === false) {
            if (tableproduct < 1) {
                Swal.fire({
                    type: "error",
                    title: g_maaf,
                    text: "Input Barang Minimal 1!",
                    confirmButtonClass: "btn btn-danger",
                });
                return false;
            }

            $("#tableproduct tbody tr").each(function() {
                $(this).find("td select").each(function() {
                    if ($(this).val() == '' || $(this).val() == null) {
                        Swal.fire({
                            type: "error",
                            title: g_maaf,
                            text: "Kode Barang tidak boleh kosong!",
                            confirmButtonClass: "btn btn-danger",
                        });
                        ada = true;
                    }
                });
            });
        }
        let tablecustomer = $("#tablecustomer tbody tr").length;
        const customer = $('#f_all_customer').iCheck('update')[0].checked;
        if (customer === false) {
            if (tablecustomer < 1) {
                Swal.fire({
                    type: "error",
                    title: g_maaf,
                    text: "Input Pelanggan Minimal 1!",
                    confirmButtonClass: "btn btn-danger",
                });
                return false;
            }

            $("#tablecustomer tbody tr").each(function() {
                $(this).find("td select").each(function() {
                    if ($(this).val() == '' || $(this).val() == null) {
                        Swal.fire({
                            type: "error",
                            title: g_maaf,
                            text: "Pelanggan tidak boleh kosong!",
                            confirmButtonClass: "btn btn-danger",
                        });
                        ada = true;
                    }
                });
            });
        }
        let tablearea = $("#tablearea tbody tr").length;
        const area = $('#f_all_area').iCheck('update')[0].checked;
        if (area === false) {
            if (tablearea < 1) {
                Swal.fire({
                    type: "error",
                    title: g_maaf,
                    text: "Input Area Minimal 1!",
                    confirmButtonClass: "btn btn-danger",
                });
                return false;
            }

            $("#tablearea tbody tr").each(function() {
                $(this).find("td select").each(function() {
                    if ($(this).val() == '' || $(this).val() == null) {
                        Swal.fire({
                            type: "error",
                            title: g_maaf,
                            text: "Area tidak boleh kosong!",
                            confirmButtonClass: "btn btn-danger",
                        });
                        ada = true;
                    }
                });
            });
        }

        if (!ada) {
            e.preventDefault();
            var formData = new FormData(this);
            if (formData) {
                // sweetaddv2($("#path").val(), formData);
                sweetaddv3($("#path").val(), $("#d_from").val(), $("#d_to").val(), formData);
            }
        } else {
            return false;
        }
    });
});

function clear_tabel_product() {
    $('#tableproduct tbody').empty();
    $('#jml_product').val(0);
}

function clear_tabel_customer() {
    $('#tablecustomer tbody').empty();
    $('#jml_customer').val(0);
}

function clear_tabel_area() {
    $('#tablearea tbody').empty();
    $('#jml_area').val(0);
}

function number() {
    $.ajax({
        type: "post",
        data: {
            tanggal: $("#d_document").val(),
        },
        url: base_url + $("#path").val() + "/number ",
        dataType: "json",
        success: function(data) {
            $("#i_document").val(data);
        },
        error: function() {
            Swal.fire({
                type: "error",
                title: g_maaf,
                text: "Ada kesalahan",
                confirmButtonClass: "btn btn-danger",
            });
        },
    });
}