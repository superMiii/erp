$(document).ready(function() {
    get_promo();
    $(".switch:checkbox").checkboxpicker();
    $("#i_customer").select2({
        dropdownAutoWidth: true,
        width: "100%",
        containerCssClass: "select-sm",
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_customer",
            dataType: "json",
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
                    i_area: $("#i_area").val(),
                    i_promo: $('#i_promo').val(),
                    f_all_customer: $('#f_all_customer').val(),
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
                i_customer: $(this).val(),
            },
            url: base_url + $("#path").val() + "/get_customer_detail",
            dataType: "json",
            success: function(data) {
                clear_tabel();
                if (data["header"] != null) {
                    let diskon1 = data["header"][0]["n_customer_discount1"];
                    let diskon2 = data["header"][0]["n_customer_discount2"];
                    let diskon3 = data["header"][0]["n_customer_discount3"];
                    let diskon4 = data["header"][0]["n_customer_discount4"];
                    $("#disc1").val(diskon1);
                    $("#disc2").val(diskon2);
                    $("#disc3").val(diskon3);
                    $("#disc4").val(diskon4);
                    // if (parseInt(diskon1) > 0 && parseInt(diskon2) > 0) {
                    //     $("#disc1").val(diskon1);
                    //     $("#disc2").val(diskon2);
                    //     $("#disc3").val(diskon3);
                    //     $("#disc4").val(diskon4);
                    // } else if (parseInt(diskon1) > 0 && parseInt(diskon2) <= 0) {
                    //     $("#disc1").val(diskon1);
                    //     $("#disc2").val(diskon3);
                    //     $("#disc3").val(diskon4);
                    //     $("#disc4").val(0);
                    // } else if (parseInt(diskon1) <= 0 && parseInt(diskon2) <= 0) {
                    //     $("#disc1").val(diskon3);
                    //     $("#disc2").val(diskon4);
                    //     $("#disc3").val(0);
                    //     $("#disc4").val(0);
                    // } else {
                    //     $("#disc1").val(0);
                    //     $("#disc2").val(0);
                    //     $("#disc3").val(0);
                    //     $("#disc4").val(0);
                    // }
                    $("#alamat").val(data["header"][0]["e_customer_address"]);
                    $("#i_price_group").val(data["header"][0]["i_price_group"]);
                    $("#epricegroup").val(data["header"][0]["e_price_groupname"]);
                    $("#n_so_toplength").val(data["header"][0]["n_customer_top"]);
                    $("#e_customer_pkpnpwp").val(data["header"][0]["e_customer_npwpcode"]);
                    $("#eppn").val(data["header"][0]["eppn"]);
                    $("#i_salesman").val("");
                    $("#i_salesman").html("");
                } else {
                    $("#i_salesman").val("");
                    $("#i_salesman").html("");
                    swal("Non-existent data : (");
                }
            },
            error: function() {
                swal("500 internal server error : (");
            },
        });
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

    $("#i_product_group").select2({
        dropdownAutoWidth: true,
        width: "100%",
        containerCssClass: "select-sm",
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_product_group",
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

    $("#d_document").change(function() {
        number();
    });

    for (let i = 1; i <= $('#jml').val(); i++) {
        $("#i_product" + i).select2({
            placeholder: g_pilihdata,
            dropdownAutoWidth: true,
            width: "100%",
            containerCssClass: "select-xs",
            allowClear: true,
            ajax: {
                url: base_url + $("#path").val() + "/get_product",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        i_price_group: $("#i_price_group").val(),
                        i_product_group: $("#i_product_group").val(),
                        i_promo: $("#i_promo").val(),
                        f_all_product: $("#f_all_product").val(),
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
                $("#v_price" + z).val("");
                $("#total_baris" + z).val("");
            } else {
                $.ajax({
                    type: "post",
                    data: {
                        i_price_group: $("#i_price_group").val(),
                        i_product_group: $("#i_product_group").val(),
                        i_product: $("#i_product" + z).val(),
                        i_promo: $("#i_promo").val(),
                        f_all_product: $("#f_all_product").val(),
                    },
                    url: base_url + $("#path").val() + "/get_product_price",
                    dataType: "json",
                    success: function(data) {
                        $("#v_price" + z).val(number_format(data["detail"][0]["v_price"], 2, ".", ","));
                        $("#n_order" + z).val(data["detail"][0]["n_quantity_min"]);
                        $("#n_min" + z).val(data["detail"][0]["n_quantity_min"]);
                        $("#i_product_motif" + z).val(data["detail"][0]["i_product_motif"]);
                        $("#i_product_grade" + z).val(data["detail"][0]["i_product_grade"]);
                        $("#i_product_status" + z).val(data["detail"][0]["i_product_status"]);
                        $("#e_product_name" + z).val(data["detail"][0]["e_product_name"]);
                        hitung();
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
            if (no <= 20) {
                $("#jml").val(i);
                var newRow = $("<tr>");
                var cols = "";
                cols += `<td class="text-center" valign="center"><spanx id="snum${i}">${no}</spanx></td>`;
                cols += `<td><select data-nourut="${i}" required class="form-control select2-size-sm" name="i_product[]" id="i_product${i}"><option value=""></option></select></td>`;
                cols += `<td><input type="text" class="form-control text-right form-control-sm" id="v_price${i}" name="v_price[]" readonly>
                    <input type="hidden" class="form-control form-control-sm" id="e_product_name${i}" name="e_product_name[]" readonly>
                    <input type="hidden" class="form-control form-control-sm" id="i_product_motif${i}" name="i_product_motif[]" readonly>
                    <input type="hidden" class="form-control form-control-sm" id="i_product_grade${i}" name="i_product_grade[]" readonly>
                    <input type="hidden" class="form-control form-control-sm" id="i_product_status${i}" name="i_product_status[]" readonly>
                    <input type="hidden" class="form-control text-right form-control-sm" id="v_disc1${i}" name="v_disc1[]" readonly>
                    <input type="hidden" class="form-control text-right form-control-sm" id="v_disc2${i}" name="v_disc2[]" readonly>
                    <input type="hidden" class="form-control text-right form-control-sm" id="v_disc3${i}" name="v_disc3[]" readonly>
                    <input type="hidden" class="form-control text-right form-control-sm" id="v_disc4${i}" name="v_disc4[]" readonly>
                    <input type="hidden" class="form-control text-right form-control-sm" id="n_min${i}" value="1" name="n_min[]" readonly>
                    </td>`;
                // cols += `<td><input type="text" autocomplete="off" class="form-control text-right n_order form-control-sm" id="n_order${i}" value="1" name="n_order[]" onkeypress="return bilanganasli(event);cekminimal(${i});hitung();" onkeyup="cekminimal(${i}); hitung()" onblur=\"if(this.value==''){this.value='1';cekminimal(${i});hitung();}\" onfocus=\"if(this.value=='1'){this.value='';}\"></td>`;
                cols += `<td><input type="number" id="n_order${i}" value="1" name="n_order[]" class="form-control text-center n_order form-control-sm" onkeyup="hitung()" onblur=\"if(this.value==''){this.value='1';hitung();}\" onfocus=\"if(this.value=='1'){this.value='';}\"></td>`;
                cols += `<td><input type="text" class="form-control text-right form-control-sm" id="n_disc1${i}" name="n_disc1[]" readonly></td>`;
                cols += `<td><input type="text" class="form-control text-right form-control-sm" id="n_disc2${i}" name="n_disc2[]" readonly></td>`;
                cols += `<td><input type="text" class="form-control text-right form-control-sm" id="n_disc3${i}" name="n_disc3[]" readonly></td>`;
                cols += `<td><input type="text" class="form-control text-right form-control-sm" id="n_disc4${i}" name="n_disc4[]" readonly></td>`;
                cols += `<td><input type="text" class="form-control text-right form-control-sm" id="total_baris${i}" name="total_baris[]" readonly></td>`;
                cols += `<td><input type="text" class="form-control form-control-sm" id="e_remark${i}" name="e_remark[]"></td>`;
                cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>`;
                newRow.append(cols);
                $("#tabledetail").append(newRow);
                $("#i_product" + i).select2({
                    placeholder: g_pilihdata,
                    dropdownAutoWidth: true,
                    width: "100%",
                    containerCssClass: "select-xs",
                    allowClear: true,
                    ajax: {
                        url: base_url + $("#path").val() + "/get_product",
                        dataType: "json",
                        delay: 250,
                        data: function(params) {
                            var query = {
                                q: params.term,
                                i_price_group: $("#i_price_group").val(),
                                i_product_group: $("#i_product_group").val(),
                                i_promo: $("#i_promo").val(),
                                f_all_product: $("#f_all_product").val(),
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
                        $("#v_price" + z).val("");
                        $("#total_baris" + z).val("");
                    } else {
                        $.ajax({
                            type: "post",
                            data: {
                                i_price_group: $("#i_price_group").val(),
                                i_product_group: $("#i_product_group").val(),
                                i_product: $("#i_product" + z).val(),
                                i_promo: $("#i_promo").val(),
                                f_all_product: $("#f_all_product").val(),
                            },
                            url: base_url + $("#path").val() + "/get_product_price",
                            dataType: "json",
                            success: function(data) {
                                $("#v_price" + z).val(number_format(data["detail"][0]["v_price"], 2, ".", ","));
                                $("#n_order" + z).val(data["detail"][0]["n_quantity_min"]);
                                $("#n_min" + z).val(data["detail"][0]["n_quantity_min"]);
                                $("#i_product_motif" + z).val(data["detail"][0]["i_product_motif"]);
                                $("#i_product_grade" + z).val(data["detail"][0]["i_product_grade"]);
                                $("#i_product_status" + z).val(data["detail"][0]["i_product_status"]);
                                $("#e_product_name" + z).val(data["detail"][0]["e_product_name"]);
                                hitung();
                            },
                        });
                    }
                });
            } else {
                /* swal(g_maaf, "Maksimal 20 Item :)", "error"); */
                Swal.fire({
                    type: "error",
                    title: g_maaf,
                    text: "Max 20 Item :)",
                    confirmButtonClass: "btn btn-danger",
                });
            }
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

            hitung();
        });
    });

    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $("form").on("submit", function(e) {
        //bind event on form submit.
        let tabel = $("#tabledetail tbody tr").length;
        let ada = false;
        if (tabel < 1) {
            swal(g_maaf, g_detailmin, "error");
            return false;
        }

        $("#tabledetail tbody tr").each(function() {
            $(this)
                .find("td select")
                .each(function() {
                    if ($(this).val() == "" || $(this).val() == null) {
                        Swal.fire("Barang tidak boleh kosong!");
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
                        Swal.fire("Quantity Tidak Boleh Kosong Atau 0!");
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
    $("#tabledetail tbody").empty();
    $("#tfoot_subtotal").val("");
    $("#tfoot_n_diskon").val("");
    $("#tfoot_v_diskon").val("");
    $("#tfoot_v_dpp").val("");
    $("#tfoot_v_ppn").val("");
    $("#tfoot_total").val("");
    $("#jml").val("0");
}

function cekminimal(i) {
    var n_min = parseFloat($("#n_min" + i).val());
    var n_order = parseFloat($("#n_order" + i).val());
    if (n_order < n_min) {
        Swal.fire("Quantity Order = '" + n_order + "', Tidak Boleh Kurang dari Minimum Order ='" + n_min + "'!");
        $("#n_order" + i).val($("#n_min" + i).val());
        hitung();
    }
}

function hitung() {
    var jml = $("#jml").val();

    var v_price = 0.0;
    var disc1 = $("#disc1").val();
    var disc2 = $("#disc2").val();
    var disc3 = $("#disc3").val();
    var disc4 = $("#disc4").val();

    var valid_disc1 = 0;
    var valid_disc2 = 0;
    var valid_disc3 = 0;
    var valid_disc4 = 0;

    if (disc1 > 0.0) valid_disc1 = disc1 / 100;
    if (disc2 > 0.0) valid_disc2 = disc2 / 100;
    if (disc3 > 0.0) valid_disc3 = disc3 / 100;
    if (disc4 > 0.0) valid_disc4 = disc4 / 100;

    var sub_total = 0.0;
    for (var x = 1; x <= jml; x++) {
        if ($("#n_order" + x).length && $("#i_product" + x).val() != "") {
            v_price = $("#v_price" + x).val().replaceAll(",", "");
            n_order = $("#n_order" + x).val().replaceAll(",", "");
            $("#n_disc1" + x).val(disc1);
            $("#n_disc2" + x).val(disc2);
            $("#n_disc3" + x).val(disc3);
            $("#n_disc4" + x).val(disc4);

            var gross = v_price * n_order;
            var v_disc1 = gross * valid_disc1;
            var v_disc2 = (gross - v_disc1) * valid_disc2;
            var v_disc3 = (gross - v_disc1 - v_disc2) * valid_disc3;
            var v_disc4 = (gross - v_disc1 - v_disc2 - v_disc3) * valid_disc4;
            var total_baris = gross - v_disc1 - v_disc2 - v_disc3 - v_disc4;

            $("#v_disc1" + x).val(number_format(v_disc1, 2, ".", ","));
            $("#v_disc2" + x).val(number_format(v_disc2, 2, ".", ","));
            $("#v_disc3" + x).val(number_format(v_disc3, 2, ".", ","));
            $("#v_disc4" + x).val(number_format(v_disc4, 2, ".", ","));
            $("#total_baris" + x).val(number_format(total_baris, 2, ".", ","));

            sub_total += total_baris;
        }
    }

    $("#tfoot_subtotal").val(number_format(sub_total, 2, ".", ","));
    var foot_vdisc = $("#tfoot_v_diskon").val().replaceAll(",", "");
    var dpp = sub_total - foot_vdisc;
    var ppn = 0;
    if ($("#ppn").val() == "t") {
        ppn = dpp * (parseFloat($("#nppn").val()) / 100);
    }
    var total_final = dpp + ppn;
    $("#tfoot_v_dpp").val(number_format(dpp, 2, ".", ","));
    $("#tfoot_v_ppn").val(number_format(ppn, 2, ".", ","));
    $("#tfoot_total").val(number_format(total_final, 2, ".", ","));
}

function change_ndisc() {
    var sub_total = $("#tfoot_subtotal").val().replaceAll(",", "");
    var foot_vdisc = $("#tfoot_n_diskon").val().replaceAll(",", "");
    var v_disc = 0;
    if (foot_vdisc > 0.0) v_disc = (sub_total * foot_vdisc) / 100;
    $("#tfoot_v_diskon").val(number_format(v_disc, 2, ".", ","));
}

function change_vdisc() {
    $("#tfoot_n_diskon").val(0);
}

function get_promo() {
    $.ajax({
        type: "post",
        data: {
            i_customer: $('#i_customer').val(),
            i_promo   : $('#i_promo').val(),
        },
        url: base_url + $("#path").val() + "/get_customer_detail",
        dataType: "json",
        success: function(data) {
            if (data["header"] != null) {
                let diskon1 = data["header"][0]["n_customer_discount1"];
                let diskon2 = data["header"][0]["n_customer_discount2"];
                let diskon3 = data["header"][0]["n_customer_discount3"];
                let diskon4 = data["header"][0]["n_customer_discount4"];
                if (parseInt(diskon1) > 0 && parseInt(diskon2) > 0) {
                    $("#disc1").val(diskon1);
                    $("#disc2").val(diskon2);
                    $("#disc3").val(diskon3);
                    $("#disc4").val(diskon4);
                } else if (parseInt(diskon1) > 0 && parseInt(diskon2) <= 0) {
                    $("#disc1").val(diskon1);
                    $("#disc2").val(diskon3);
                    $("#disc3").val(diskon4);
                    $("#disc4").val(0);
                } else if (parseInt(diskon1) <= 0 && parseInt(diskon2) <= 0) {
                    $("#disc1").val(diskon3);
                    $("#disc2").val(diskon4);
                    $("#disc3").val(0);
                    $("#disc4").val(0);
                } else {
                    $("#disc1").val(0);
                    $("#disc2").val(0);
                    $("#disc3").val(0);
                    $("#disc4").val(0);
                }
                $("#alamat").val(data["header"][0]["e_customer_address"]);
                $("#i_price_group").val(data["header"][0]["i_price_group"]);
                $("#epricegroup").val(data["header"][0]["e_price_groupname"]);
                $("#n_so_toplength").val(data["header"][0]["n_customer_top"]);
                $("#e_customer_pkpnpwp").val(data["header"][0]["e_customer_npwpcode"]);
                $("#eppn").val(data["header"][0]["eppn"]);
                hitung();
            }
        },
        error: function() {
            swal("500 internal server error : (");
        },
    });
}