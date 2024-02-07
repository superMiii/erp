$(document).ready(function() {
    var form = $(".steps-validation").show();
    var controller = $("#path").val();
    
    $(".steps-validation").steps({
        headerTag: "h6",
        bodyTag: "fieldset",
        transitionEffect: "fade",
        titleTemplate: '<span class="step">#index#</span> #title#',
        labels: {
            previous: '<i class="fa fa-arrow-left mr-1" /> Previous',
            next: 'Next <i class="fa fa-arrow-right ml-1" />',
            finish: 'Submit <i class="fa fa-paper-plane ml-1" />',
            /* finish: "Submit"  */
        },
        onStepChanging: function(e, t, i) {
            return (
                t > i ||
                (!(3 === i && Number($("#age-2").val()) < 18) &&
                    (t < i &&
                        (form.find(".body:eq(" + i + ") label.error").remove(),
                            form.find(".body:eq(" + i + ") .error").removeClass("error")),
                        (form.validate().settings.ignore = ":disabled,:hidden"),
                        form.valid()))
            );
        },
        onFinishing: function(e, t) {
            return (form.validate().settings.ignore = ":disabled"), form.valid();
        },
        onFinished: function(e, t) {
            langx();
            e.preventDefault();
            var formData = new FormData(this);
            if (formData) {
                sweetaddv2(controller, formData);
            }
        },
    }), $(".steps-validation").validate({
        ignore: "input[type=hidden]",
        errorClass: "danger",
        successClass: "success",
        highlight: function(e, t) {
            $(e).removeClass(t);
        },
        unhighlight: function(e, t) {
            $(e).removeClass(t);
        },
        errorPlacement: function(e, t) {
            e.insertAfter(t);
        },
        rules: {
            user_id: {
                equalTo: "#i_user_id",
            },
            repeat_password: {
                equalTo: "#password",
            },
            repeat_e_user_password: {
                equalTo: "#e_user_password",
            },
        },
    }), /** STEP 1 */ $(".skin-square input").iCheck({
        checkboxClass: "icheckbox_square-red",
        radioClass: "iradio_square-red",
    }), $("#iarea").select2({
        placeholder: $("#textcari_area").val(),
        containerCssClass: "select-sm",
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_area",
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
        $("#icity").val(null);
        $("#icity").html(null);
        $("#icover").val(null);
        $("#icover").html(null);
        number();
    }), 
    $( "#kode" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                i_document: $(this).val(),
            },
            url: base_url + $("#path").val() + "/cek_kode ",
            dataType: "json",
            success: function(data) {
                if (data===1){
                    Swal.fire('Kode Lang Sudah Ada !!!');
                 let len = $("#kode").val();
                //  console.log(); 
                $("#kode").val(len.substring(0,len.length - 1));
            }
            },
            error: function() {
                swal("Error :)");
            },
        });
      }),


    
    
    $("#icity").select2({
        placeholder: $("#textcari_city").val(),
        containerCssClass: "select-sm",
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_city",
            dataType: "json",
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
                    param1: $("#iarea").val(),
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
        $("#icover").val(null);
        $("#icover").html(null);
    }), $("#icover").select2({
        placeholder: $("#textcari_cover").val(),
        containerCssClass: "select-sm",
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_cover",
            dataType: "json",
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
                    param1: $("#iarea").val(),
                    param2: $("#icity").val(),
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
    }), $("#igroup").select2({
        placeholder: $("#textcari_group").val(),
        containerCssClass: "select-sm",
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_group",
            dataType: "json",
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
                    iareax: $('#iarea').val(),
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
    }), $("#itype").select2({
        placeholder: $("#textcari_type").val(),
        containerCssClass: "select-sm",
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_type",
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
    }), $("#ilevel").select2({
        placeholder: $("#textcari_level").val(),
        containerCssClass: "select-sm",
        width: "100%",
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
    }), $("#istatus").select2({
        placeholder: $("#textcari_status").val(),
        containerCssClass: "select-sm",
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_status",
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
    }), $("#iprice").select2({
        placeholder: $("#textcari_price").val(),
        containerCssClass: "select-sm",
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_price",
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
    }), $("#ipayment").select2({
        placeholder: $("#textcari_payment").val(),
        containerCssClass: "select-sm",
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_payment",
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
    }), $("#ipaygroup").select2({
        placeholder: $("#textcari_paygroup").val(),
        containerCssClass: "select-sm",
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_paygroup",
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
    }), $("#chk-samasp").on("ifChanged", function(event) {
        if (event.target.checked == true) {
            $("#e_shipment_address").val($("#alamat").val());
        } else {
            $("#e_shipment_address").val("");
        }
    }), $("#chk-sama").on("ifChanged", function(event) {
        if (event.target.checked == true) {
            $("#alamat_npwp").val($("#alamat").val());
        } else {
            $("#alamat_npwp").val("");
        }
    }), $("#chk-flafon").on("ifChanged", function(event) {
        if (event.target.checked == true) {
            $("#ipaygroup").attr("disabled", true);
        } else {
            $("#ipaygroup").attr("disabled", false);
        }
    });
    /** END STEP 1 */

    /** STEP 2 */
    $(".switch:checkbox").checkboxpicker();
    number();

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
                    iarea: $("#iarea").val(),
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
                    <input type="hidden" class="form-control text-right form-control-sm" id="v_disc${i}" name="v_disc[]" readonly>
                    <input type="hidden" class="form-control text-right form-control-sm" id="v_disc2${i}" name="v_disc2[]" readonly>
                    <input type="hidden" class="form-control text-right form-control-sm" id="v_disc3${i}" name="v_disc3[]" readonly>
                    </td>`;
                // cols += `<td><input type="text" autocomplete="off" class="form-control text-right n_order form-control-sm" id="n_order${i}" value="1" name="n_order[]" onkeypress="return bilanganasli(event);hitung();" onkeyup="hitung()" onblur=\"if(this.value==''){this.value='1';hitung();}\" onfocus=\"if(this.value=='1'){this.value='';}\"></td>`;
                cols += `<td><input type="number" id="n_order${i}" value="1" name="n_order[]" class="form-control text-center n_order form-control-sm" onkeyup="hitung()" onblur=\"if(this.value==''){this.value='1';hitung();}\" onfocus=\"if(this.value=='1'){this.value='';}\"></td>`;
                cols += `<td><input type="text" class="form-control text-right form-control-sm" id="n_disc${i}" name="n_disc[]" readonly></td>`;
                cols += `<td><input type="text" class="form-control text-right form-control-sm" id="n_disc2${i}" name="n_disc2[]" readonly></td>`;
                cols += `<td><input type="text" class="form-control text-right form-control-sm" id="total_baris${i}" name="total_baris[]" readonly></td>`;
                cols += `<td><input type="text" class="form-control form-control-sm" id="e_remark${i}" name="e_remark[]"></td>`;
                cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>`;
                cols += `<td><input type="hidden" class="form-control text-right form-control-sm" id="n_disc3${i}" name="n_disc3[]" readonly></td>`;
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
                                i_price_group: $("#iprice").val(),
                                i_product_group: $("#i_product_group").val(),
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
                                i_price_group: $("#iprice").val(),
                                i_product_group: $("#i_product_group").val(),
                                i_product: $("#i_product" + z).val(),
                            },
                            url: base_url + $("#path").val() + "/get_product_price",
                            dataType: "json",
                            success: function(data) {
                                $("#v_price" + z).val(
                                    formatcemua(data["detail"][0]["v_price"])
                                );
                                $("#i_product_motif" + z).val(
                                    data["detail"][0]["i_product_motif"]
                                );
                                $("#i_product_grade" + z).val(
                                    data["detail"][0]["i_product_grade"]
                                );
                                $("#i_product_status" + z).val(
                                    data["detail"][0]["i_product_status"]
                                );
                                $("#e_product_name" + z).val(
                                    data["detail"][0]["e_product_name"]
                                );
                                hitung();
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

            hitung();
        });
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

function hitung() {
    var jml = $("#jml").val();

    var v_price = 0.0;
    var disc = $("#diskon").val();
    var disc2 = $("#diskon2").val();
    var disc3 = $("#diskon3").val();

    var valid_disc = 0;
    var valid_disc2 = 0;
    var valid_disc3 = 0;

    if (disc > 0.0) valid_disc = disc / 100;
    if (disc2 > 0.0) valid_disc2 = disc2 / 100;
    if (disc3 > 0.0) valid_disc3 = disc3 / 100;

    var sub_total = 0.0;
    for (var x = 1; x <= jml; x++) {
        if ($("#n_order" + x).length && $("#i_product" + x).val() != "") {
            v_price = $("#v_price" + x)
                .val()
                .replaceAll(",", "");
            n_order = $("#n_order" + x)
                .val()
                .replaceAll(",", "");
            $("#n_disc" + x).val(disc);
            $("#n_disc2" + x).val(disc2);
            $("#n_disc3" + x).val(disc3);

            var gross = v_price * n_order;
            var v_disc = gross * valid_disc;
            var v_disc2 = (gross - v_disc) * valid_disc2;
            var v_disc3 = (gross - v_disc - v_disc2) * valid_disc3;
            var total_baris = gross - v_disc - v_disc2 - v_disc3;

            $("#v_disc" + x).val(number_format(v_disc, 2, ".", ","));
            $("#v_disc2" + x).val(number_format(v_disc2, 2, ".", ","));
            $("#v_disc3" + x).val(number_format(v_disc3, 2, ".", ","));
            $("#total_baris" + x).val(number_format(total_baris, 2, ".", ","));

            sub_total += total_baris;
        }
    }

    $("#tfoot_subtotal").val(number_format(sub_total, 2, ".", ","));
    //var foot_ndisc = $('#tfoot_n_diskon').val();
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

function number() {
    $.ajax({
        type: "post",
        data: {
            tanggal: $("#d_document").val(),
            i_area: $("#iarea").val(),
        },
        url: base_url + $("#path").val() + "/number ",
        dataType: "json",
        success: function(data) {
            $("#i_document").val(data);
        },
        error: function() {
            swal("Error :)");
        },
    });
}

function langx() {
    $.ajax({
        type: "post",
        data: {
            'i_document': $("#kode").val(),
        },
        url: base_url + $("#path").val() + '/cek_kode ',
        dataType: "json",
        success: function(data) {
            if (data===1){
                Swal.fire('Kode Lang Sudah Ada, Silahkan gunakan KODE LANG lain !');
             let len = $("#kode").val();
            //  console.log(); 
            // $("#kode").val(len.substring(0,len.length - 1));
            }
        },
        error: function() {
            Swal.fire('Error :)');
        }
    });
}