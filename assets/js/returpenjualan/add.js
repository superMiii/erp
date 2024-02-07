$(document).ready(function() {
    number();
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
        $("#i_customer").val("");
        $("#i_customer").html("");
        $("#i_salesman").val("");
        $("#i_salesman").html("");
        $(".clear").val("");
        clear_tabel();
    });

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
                    $("#address").val(data["header"][0]["e_customer_address"]);
                    $("#i_price_group").val(data["header"][0]["i_price_group"]);
                    $("#e_price_group").val(data["header"][0]["e_price_groupname"]);
                    $("#pkp").val(data["header"][0]["pkp"]);
                    $("#f_ttb_pkp").val(data["header"][0]["f_customer_pkp"]);
                    $("#e_customer_pkpnpwp").val(data["header"][0]["e_customer_npwpcode"]);
                    $("#eppn").val(data["header"][0]["eppn"]);
                    $("#i_salesman").val("");
                    $("#i_salesman").html("");
                } else {
                    $(".clear").val("");
                    $("#i_salesman").val("");
                    $("#i_salesman").html("");
                    Swal.fire({
                        type: "error",
                        title: g_maaf,
                        text: "Non-existent data : (",
                        confirmButtonClass: "btn btn-danger",
                    });
                }
            },
            error: function() {                
                Swal.fire({
                    type: "error",
                    title: g_maaf,
                    text: "500 internal server error : (",
                    confirmButtonClass: "btn btn-danger",
                });
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

    $("#i_alasan_retur").select2({
        dropdownAutoWidth: true,
        width: "100%",
        containerCssClass: "select-sm",
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_alasan_retur",
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

    $("#d_document").change(function() {
        number();
    });

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
                cols += `<td><select data-nourut="${i}" required class="form-control select2-size-sm" name="i_nota_item[]" id="i_nota_item${i}"><option value=""></option></select></td>`;
                cols += `<td><input type="text" class="form-control text-right form-control-sm" id="i_nota_id${i}" name="i_nota_id[]" readonly>`;
                cols += `<td><input type="text" class="form-control text-right form-control-sm" id="n_ttb_discount1${i}" name="n_ttb_discount1[]" readonly><input type="hidden" id="v_ttb_discount1${i}" name="v_ttb_discount1[]" readonly></td>`;
                cols += `<td><input type="text" class="form-control text-right form-control-sm" id="n_ttb_discount2${i}" name="n_ttb_discount2[]" readonly><input type="hidden" id="v_ttb_discount2${i}" name="v_ttb_discount2[]" readonly></td>`;
                cols += `<td><input type="text" class="form-control text-right form-control-sm" id="n_ttb_discount3${i}" name="n_ttb_discount3[]" readonly><input type="hidden" id="v_ttb_discount3${i}" name="v_ttb_discount3[]" readonly></td>`;
                // cols += `<td><input type="text" autocomplete="off" class="form-control text-right n_quantity form-control-sm" id="n_quantity${i}" value="0" name="n_quantity[]" onkeypress="return bilanganasli(event);hetang();" onkeyup="hetang(); cek(${i}); hetangdiskonrp(); hetangdiskon();" onblur=\"if(this.value==''){this.value='0';hetang();}\" onfocus=\"if(this.value=='0'){this.value='';}\"></td>`;
                cols += `<td><input type="number" autocomplete="off" class="form-control text-center n_quantity form-control-sm" id="n_quantity${i}" value="0" name="n_quantity[]" onkeyup="hetang(); cek(${i}); hetangdiskonrp(); hetangdiskon();" onblur=\"if(this.value==''){this.value='0';hetang();}\" onfocus=\"if(this.value=='0'){this.value='';}\"></td>`;
                cols += `<td><input type="text" class="form-control text-right form-control-sm" id="v_unit_price${i}" name="v_unit_price[]" readonly>
                    <input type="hidden" class="form-control form-control-sm" id="i_nota${i}" name="i_nota[]" readonly>
                    <input type="hidden" class="form-control form-control-sm" id="d_nota${i}" name="d_nota[]" readonly>
                    <input type="hidden" class="form-control form-control-sm" id="n_nota${i}" name="n_nota[]" readonly>
                    <input type="hidden" class="form-control form-control-sm" id="i_product1${i}" name="i_product1[]" readonly>
                    <input type="hidden" class="form-control form-control-sm" id="i_product1_grade${i}" name="i_product1_grade[]" readonly>
                    <input type="hidden" class="form-control form-control-sm" id="i_product1_motif${i}" name="i_product1_motif[]" readonly>
                    </td>`;
                cols += `<td><input type="text" class="form-control text-right form-control-sm" id="total_item${i}" name="total_item[]" readonly></td>`;
                cols += `<td><input type="text" class="form-control form-control-sm" name="e_ttb_remark[]"></td>`;
                cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>`;
                newRow.append(cols);
                $("#tabledetail").append(newRow);
                $("#i_nota_item" + i).select2({
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
                            if ($(this).val() == $("#i_nota_item" + x).val() && z != x) {
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
                                i_nota_item: $("#i_nota_item" + z).val(),
                                i_customer: $("#i_customer").val(),
                            },
                            url: base_url + $("#path").val() + "/get_product_price",
                            dataType: "json",
                            success: function(data) {
                                $("#i_nota" + z).val(data["detail"][0]["i_nota"]);
                                $("#d_nota" + z).val(data["detail"][0]["d_nota"]);
                                $("#n_nota" + z).val(data["detail"][0]["n_nota"]);
                                $("#v_unit_price" + z).val(formatcemua(data["detail"][0]["v_unit_price"]));
                                $("#v_unit_price" + z).val(formatcemua(data["detail"][0]["v_unit_price"]));
                                $("#i_product1" + z).val(data["detail"][0]["i_product"]);
                                $("#i_product1_grade" + z).val(data["detail"][0]["i_product_grade"]);
                                $("#i_product1_motif" + z).val(data["detail"][0]["i_product_motif"]);
                                $("#i_nota_id" + z).val(data["detail"][0]["i_nota_id"]);
                                $("#n_ttb_discount1" + z).val(data["detail"][0]["n_nota_discount1"]);
                                $("#n_ttb_discount2" + z).val(data["detail"][0]["n_nota_discount2"]);
                                $("#n_ttb_discount3" + z).val(data["detail"][0]["n_nota_discount3"]);
                                hetang();
                            },
                        });
                    }
                });
            } else {
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

            hetang();
        });
    });

    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $("form").on("submit", function(e) {
        //bind event on form submit.
        let tabel = $("#tabledetail tbody tr").length;
        let ada = false;
        if (tabel < 1) {
            Swal.fire(g_maaf, g_detailmin, "error");
            return false;
        }

        $("#tabledetail tbody tr").each(function() {
            $(this).find("td select").each(function() {
                if ($(this).val() == "" || $(this).val() == null) {
                    Swal.fire("Barang tidak boleh kosong!");
                    ada = true;
                }
            });
            $(this).find("td .n_quantity").each(function() {
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
                        if ($('#f_ttb_plusppn').val() == 't'){
                            if(parseFloat($('#n_ppn').val()) < 1){
                                Swal.fire('SILAHKAN ISI PPN !!!');
                                return false;
                            }else{
                                sweetaddv33($("#path").val(), $("#d_from").val(), $("#d_to").val(), $("#harea").val(), formData);
                            }
                        }else{
                            sweetaddv33($("#path").val(), $("#d_from").val(), $("#d_to").val(), $("#harea").val(), formData);
                        }
                        
                    }
                } else {
                    return false;
                }
            

        
    });
});

function clear_tabel() {
    $("#tabledetail tbody").empty();
    $(".tfoot").val(0);
    $("#jml").val("0");
}

function cek(i) {
    if (parseFloat($('#n_quantity' + i).val()) > parseFloat($('#n_nota' + i).val())) {
        Swal.fire({
            type: "error",
            title: g_maaf,
            text: "Qty Retur = '" + $('#n_quantity' + i).val() + "' tidak boleh lebih dari Total Qty Nota Pelanggan = '" + $('#n_nota' + i).val() + "' !",
            confirmButtonClass: "btn btn-danger",
        });
        $('#n_quantity' + i).val($('#n_nota' + i).val());
        hetang();
    }
}

function hetang() {
    let subtotal = 0;
    let dis = 0;
    for (let i = 1; i <= $('#jml').val(); i++) {
        if (typeof $('#i_nota_item' + i).val() !== 'undefined') {
            let v_unit_price = parseFloat(formatulang($('#v_unit_price' + i).val()));
            let n_quantity = parseFloat($('#n_quantity' + i).val());
            if (v_unit_price == '' || v_unit_price == null) {
                v_unit_price = 0;
            }
            if (n_quantity == '' || n_quantity == null) {
                n_quantity = 0;
            }
            if (isNaN(n_quantity)) {
                n_quantity = 0;
            }
            let v_jumlah = v_unit_price * n_quantity;
            let v_discount1 = v_jumlah * (parseFloat(formatulang($("#n_ttb_discount1" + i).val())) / 100);
            $("#v_ttb_discount1" + i).val(Math.round(v_discount1));
            let v_discount2 = (v_jumlah - v_discount1) * (parseFloat(formatulang($("#n_ttb_discount2" + i).val())) / 100);
            $("#v_ttb_discount2" + i).val(Math.round(v_discount2));
            let v_discount3 = (v_jumlah - v_discount1 - v_discount2) * (parseFloat(formatulang($("#n_ttb_discount3" + i).val())) / 100);
            $("#v_ttb_discount3" + i).val(Math.round(v_discount3));
            let v_total_discount = v_discount1 + v_discount2 + v_discount3;
            let v_total = v_jumlah;
            $('#total_item' + i).val(formatcemua(v_total));
            subtotal += v_total;
            dis += v_total_discount;
        }
    }
    $('#distotal').val(formatcemua(dis));
    $('#v_ttb_gross').val(formatcemua(subtotal));
    let v_ttb_discounttotal = parseFloat(formatulang($('#v_ttb_discounttotal').val()));
    let v_ttb_dpp = subtotal - v_ttb_discounttotal - dis;
    $('#v_ttb_dpp').val(formatcemua(v_ttb_dpp));
    let v_ttb_ppn = v_ttb_dpp * parseFloat($('#n_ppn').val()) / 100;
    $('#v_ttb_ppn').val(formatcemua(v_ttb_ppn));
    $('#v_ttb_netto').val(formatcemua(v_ttb_dpp + v_ttb_ppn));
}

function hetangdiskon() {
    let n_discount_total = parseFloat(formatulang($('#n_ttb_discounttotal').val()));
    let v_ttb_gross = parseFloat(formatulang($('#v_ttb_gross').val()));
    if (n_discount_total == '' || n_discount_total == null || isNaN(n_discount_total)) {
        n_discount_total = 0;
    }
    // if (isNaN(n_discount_total)) {
    //     n_discount_total = 0;
    // }
    // alert(n_discount_total);
    if (v_ttb_gross == '' || v_ttb_gross == null|| isNaN(v_ttb_gross)) {
        v_ttb_gross = 0;
    }
    // alert(v_ttb_gross);
    let v_ttb_discounttotal = v_ttb_gross * (n_discount_total / 100);
    $('#v_ttb_discounttotal').val(formatcemua(v_ttb_discounttotal));
    hetang();
}

function hetangdiskonrp() {
    let v_ttb_discounttotal = parseFloat(formatulang($('#v_ttb_discounttotal').val()));
    let v_ttb_gross = parseFloat(formatulang($('#v_ttb_gross').val()));
    if (v_ttb_discounttotal == '' || v_ttb_discounttotal == null || isNaN(v_ttb_discounttotal)) {
        v_ttb_discounttotal = 0;
    }
    if (v_ttb_gross == '' || v_ttb_gross == null|| isNaN(v_ttb_gross)) {
        v_ttb_gross = 0;
    }
    let n_ttb_discounttotal = (v_ttb_discounttotal / v_ttb_gross) * 100;
    if (isNaN(n_ttb_discounttotal)) {
        n_ttb_discounttotal = 0;
    }
    $('#n_ttb_discounttotal').val(n_ttb_discounttotal);
    hetang();
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
            
        },
    });
}