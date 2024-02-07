$(document).ready(function() {
    number();
    suzu();
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
        number();
        suzu();
    });

    $("#i_pv_type").select2({
        dropdownAutoWidth: true,
        width: "100%",
        containerCssClass: "select-sm",
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_pv_type",
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
        number();
        suzu();
        $("#i_coa").val("");
        $("#i_coa").html("");
        $.ajax({
            type: "post",
            data: {
                'i_pv_type': $(this).val(),
            },
            url: base_url + $("#path").val() + '/get_tex',
            dataType: "json",
            success: function(data) {
                $('#tex').val(data);
                if (data != 'BK') {
                    $('.clear').attr("hidden", true);
                    $('.colay').attr('colspan', 4);
                } else {
                    $('.clear').attr("hidden", false);
                    $('.colay').attr('colspan', 6);
                }

            },
            error: function() {
                Swal.fire({
                    type: "error",
                    title: g_maaf,
                    confirmButtonClass: "btn btn-danger",
                });
            }
        });
    });

    $("#i_coa").select2({
        dropdownAutoWidth: true,
        width: "100%",
        containerCssClass: "select-sm",
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_coa_type",
            dataType: "json",
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
                    i_pv_type: $('#i_pv_type').val(),
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
        get_saldo();
        number();
        suzu();
    });

    $("#d_document").change(function() {
        number();
        suzu();
    });

    var Detail = $(function() {
        var i = $("#jml").val();
        $("#addrow").on("click", function() {
            i++;
            let max = $('#d_document').val();
            var no = $("#tabledetail tbody tr").length + 1;
            if (no <= 30) {
            if ($("#tex").val() != 'BK') {
                var tex = 'hidden';
            } else {
                var tex = '';
            }
            $("#jml").val(i);
            var newRow = $("<tr>");
            var cols = "";
            cols += `<td width="1%" class="text-center" valign="center"><spanx id="snum${i}">${no}</spanx></td>`;
            cols += `<td width="9%"><select required class="form-control isi select2-size-sm" name="i_coa_item[]" id="i_coa_item${i}"><option value=""></option></select></td>`;
            cols += `<td width="9%"><input type="date" max="${max}" value="${max}" class="form-control isi form-control-sm" id="d_bukti${i}" name="d_bukti[]"></td>`;

            cols += `<td width="4%" class="clear" ${tex}><select data-nourut="${i}" class="form-control wajib select2-size-sm" name="i_pv_refference_type[]" id="i_pv_refference_type${i}"><option value=""></option></select></td>`;
            cols += `<td width="14%" class="clear" ${tex}><select data-nourut="${i}" class="form-control wajib select2-size-sm" name="i_pv_refference[]" id="i_pv_refference${i}"><option value=""></option></select></td>`;
            cols += `<input type="hidden" data-nourut="${i}" class="form-control wajib select2-size-sm" name="jum[]" id="jum${i}"  readonly>`;

            cols += `<td width="37%"><input type="text" class="form-control isi form-control-sm" id="e_remark_item${i}" name="e_remark_item[]"></td>`;
            cols += `<td width="14%"><input type="text" class="formatrupiah isi form-control form-control-sm text-right v_pv" value="0" id="v_pv_item${i}" name="v_pv_item[]" onkeyup="reformat(this);hetang();juma(${i});" autocomplete="off" onblur="if(this.value==''){this.value='0';hitung();}" onfocus="if(this.value=='0'){this.value='';}"></td>`;
            cols += `<td width="1%" class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>`;
            newRow.append(cols);
            $("#tabledetail").append(newRow);
            $("#i_coa_item" + i).select2({
                placeholder: g_pilihdata,
                dropdownAutoWidth: true,
                width: '100%',
                containerCssClass: 'select-xs',
                allowClear: true,
                ajax: {
                    url: base_url + $("#path").val() + "/get_coa",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
                            i_pv_type: $('#i_pv_type').val(),
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
            $("#i_pv_refference_type" + i).select2({
                placeholder: g_pilihdata,
                dropdownAutoWidth: true,
                width: '100%',
                containerCssClass: 'select-xs',
                allowClear: true,
                ajax: {
                    url: base_url + $("#path").val() + "/get_reference_type",
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
                $("#i_pv_refference" + z).val("");
                $("#i_pv_refference" + z).html("");
            });
            $("#i_pv_refference" + i).select2({
                placeholder: g_pilihdata,
                dropdownAutoWidth: true,
                width: '100%',
                containerCssClass: 'select-xs',
                allowClear: true,
                ajax: {
                    url: base_url + $("#path").val() + "/get_reference",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
                            i_pv_refference_type: $("#i_pv_refference_type" + i).val(),
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
                var h = $(this).data("nourut");
                $.ajax({
                    type: "post",
                    data: {
                        'id': $(this).val(),
                        'i_pv_refference_type': $('#i_pv_refference_type' + h).val(),
                    },
                    url: base_url + $("#path").val() + '/get_detail_referensi',
                    dataType: "json",
                    success: function(data) {
                        $("#v_pv_item" + h).val(formatcemua(data["detail"][0]["v_jumlah"]));
                        $("#jum" + h).val(formatcemua(data["detail"][0]["v_jumlah"]));
                        hetang();
                    },
                    error: function() {
                        Swal.fire({
                            type: "error",
                            title: g_maaf,
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                });
            });
            } else {
                /* swal(g_maaf, "Maksimal 20 Item :)", "error"); */
                Swal.fire({
                    type: "error",
                    title: g_maaf,
                    text: "Max 30 Item :)",
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
            $(this).find("td .isi").each(function() {
                if ($(this).val() == '' || $(this).val() == null || $(this).val() == 0) {
                    Swal.fire({
                        type: "error",
                        title: g_maaf,
                        text: "Item not empty or 0",
                        confirmButtonClass: "btn btn-danger",
                    });
                    ada = true;
                }
            });
        });

        if ($("#tex").val() == 'BK') {
            $("#tabledetail tbody tr").each(function() {
                $(this).find("td .wajib").each(function() {
                    if ($(this).val() == '' || $(this).val() == null || $(this).val() == 0) {
                        Swal.fire({
                            type: "error",
                            title: g_maaf,
                            text: "REFERENSI HARUS DIISI",
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
                sweetaddv5($("#path").val(), $("#d_from").val(), $("#d_to").val(), $("#htype").val(), $("#harea").val(), $("#hcoa").val(), formData);
            }
        } else {
            return false;
        }
    });
});

function number() {
    $.ajax({
        type: "post",
        data: {
            'tanggal': $('#d_document').val(),
            'i_area': $('#i_area').val(),
            'i_pv_type': $('#i_pv_type').val(),
            'i_coa': $('#i_coa').val(),
        },
        url: base_url + $("#path").val() + '/number ',
        dataType: "json",
        success: function(data) {
            $('#i_document').val(data);
        },
        error: function() {
            Swal.fire({
                type: "error",
                title: g_maaf,
                confirmButtonClass: "btn btn-danger",
            });
        }
    });
}

function hetang() {
    let v_pv = 0;
    $("#tabledetail tbody tr td .v_pv").each(function() {
        v_pv -= parseFloat(formatulang($(this).val()));
    });
    $('#v_pv').val(formatcemua(v_pv));
    $('#v_sisa_saldo').val(formatcemua(parseFloat(formatulang($('#v_saldo').val())) + v_pv));
}

function clear_tabel() {
    $('#tabledetail tbody').empty();
    $('#jml').val("0");
    hetang();
}

function get_saldo() {
    $.ajax({
        type: "post",
        data: {
            'tanggal': $('#d_document').val(),
            'i_coa': $("#i_coa").val(),
        },
        url: base_url + $("#path").val() + '/get_saldo',
        dataType: "json",
        success: function(data) {
            $('#v_saldo').val(formatcemua(data));
            hetang();
        },
        error: function() {
            Swal.fire({
                type: "error",
                title: g_maaf,
                confirmButtonClass: "btn btn-danger",
            });
        }
    });
}

function juma(i) {
    var data = $('#tex').val();
    if (data =='BK') {
    if (parseFloat(formatulang($('#v_pv_item' + i).val())) > parseFloat(formatulang($('#jum' + i).val()))) {
        Swal.fire({
            type: "error",
            title: g_maaf,
            text: "JUMLAH = '" + $('#jum' + i).val() + "' melebihi REFERENSI = '" + $('#v_pv_item' + i).val() + "'.",
            confirmButtonClass: "btn btn-danger",
        });
        $('#v_pv_item' + i).val($('#jum' + i).val());
        hetang();
    }
    }
}

function suzu() {    
    $.ajax({
        type: "post",
        data: {
            'tanggal': $('#d_document').val(),
            'i_area': $('#i_area').val(),
            'i_pv_type': $('#i_pv_type').val(),
            'i_coa': $('#i_coa').val(),
        },
        url: base_url + $("#path").val() + '/suzu ',
        dataType: "json",
        success: function(data) {
            $("#tgl").val(data["ori"][0]["tgl"]);
            $("#kodd").val(data["ori"][0]["kod"]);
            rem();
        },
    });
}


function rem() {
    if ($('#tgl').val() > $('#d_document').val()) {
        Swal.fire({
            type: "error",
            title: g_maaf,
            text: "Tanggal Dokumen = '" + $('#d_document').val() + "' , Melampaui Tanggal Voucher = '" + $('#tgl').val() + "' ( " + $('#kodd').val() + " ) " ,
            confirmButtonClass: "btn btn-danger",
        });
    }
}