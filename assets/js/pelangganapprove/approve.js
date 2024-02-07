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
            finish: 'Approve <i class="fa fa-paper-plane ml-1" />',
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
            e.preventDefault();
            let id = $('#id').val() + '|' + $('#i_so').val();
            let keterangan = null;
            sweetapprovev2(controller, id, keterangan);
            /* var formData = new FormData(this);
            if (formData) {
                sweetaddv2(controller, formData);
                sweetapprovev2(folder, id, keterangan);
            } */
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
    }), $("#icity").select2({
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
    hitung();
    /** END STEP 2 */
});

function hitung() {
    // var jml = $("#jml").val();

    // var v_price = 0.0;
    // var disc = $("#diskon").val();
    // var disc2 = $("#diskon2").val();
    // var disc3 = $("#diskon3").val();

    // var valid_disc = 0;
    // var valid_disc2 = 0;
    // var valid_disc3 = 0;

    // if (disc > 0.0) valid_disc = disc / 100;
    // if (disc2 > 0.0) valid_disc2 = disc2 / 100;
    // if (disc3 > 0.0) valid_disc3 = disc3 / 100;

    // var sub_total = 0.0;
    // for (var x = 1; x <= jml; x++) {
    //     if ($("#n_order" + x).length && $("#i_product" + x).val() != "") {
    //         v_price = $("#v_price" + x)
    //             .val()
    //             .replaceAll(",", "");
    //         n_order = $("#n_order" + x)
    //             .val()
    //             .replaceAll(",", "");
    //         $("#n_disc" + x).val(disc);
    //         $("#n_disc2" + x).val(disc2);
    //         $("#n_disc3" + x).val(disc3);

    //         var gross = v_price * n_order;
    //         var v_disc = gross * valid_disc;
    //         var v_disc2 = (gross - v_disc) * valid_disc2;
    //         var v_disc3 = (gross - v_disc - v_disc2) * valid_disc3;
    //         var total_baris = gross - v_disc - v_disc2 - v_disc3;

    //         $("#v_disc" + x).val(number_format(v_disc, 2, ".", ","));
    //         $("#v_disc2" + x).val(number_format(v_disc2, 2, ".", ","));
    //         $("#v_disc3" + x).val(number_format(v_disc3, 2, ".", ","));
    //         $("#total_baris" + x).val(number_format(total_baris, 2, ".", ","));

    //         sub_total += total_baris;
    //     }
    // }

    // $("#tfoot_subtotal").val(number_format(sub_total, 2, ".", ","));
    // //var foot_ndisc = $('#tfoot_n_diskon').val();
    // var foot_vdisc = $("#tfoot_v_diskon").val().replaceAll(",", "");
    // var dpp = sub_total - foot_vdisc;
    // var ppn = 0;
    // if ($("#ppn").val() == "t") {
    //     ppn = dpp * (parseFloat($("#nppn").val()) / 100);
    // }
    // var total_final = dpp + ppn;
    // $("#tfoot_v_dpp").val(number_format(dpp, 2, ".", ","));
    // $("#tfoot_v_ppn").val(number_format(ppn, 2, ".", ","));
    // $("#tfoot_total").val(number_format(total_final, 2, ".", ","));
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