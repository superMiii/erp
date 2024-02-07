$(document).ready(function() {
    texr();
    saldo();
    for (let i = 1; i <= $('#jml').val(); i++) {
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
                        i_rv_type: $('#i_rv_type').val(),
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
        $("#i_rv_refference_type" + i).select2({
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
            $("#i_rv_refference" + z).val("");
            $("#i_rv_refference" + z).html("");
        });
        $("#i_rv_refference" + i).select2({
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
                        i_rv_refference_type: $("#i_rv_refference_type" + i).val(),
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
                        'i_rv_refference_type': $('#i_rv_refference_type' + h).val(),
                    },
                    url: base_url + $("#path").val() + '/get_detail_referensi',
                    dataType: "json",
                    success: function(data) {
                        $("#v_rv_item" + h).val(formatcemua(data["detail"][0]["v_jumlah"]));
                        $("#araa" + h).val(formatcemua(data["detail"][0]["araa"]));
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
            // }
        });






    }

    // $("#i_area").select2({
    //     dropdownAutoWidth: true,
    //     width: "100%",
    //     containerCssClass: "select-sm",
    //     allowClear: true,
    //     ajax: {
    //         url: base_url + $("#path").val() + "/get_area",
    //         dataType: "json",
    //         delay: 250,
    //         data: function(params) {
    //             var query = {
    //                 q: params.term,
    //             };
    //             return query;
    //         },
    //         processResults: function(data) {
    //             return {
    //                 results: data,
    //             };
    //         },
    //         cache: false,
    //     },
    
    // }).change(function(event) {
    //     number();
    // });

    // $("#i_rv_type").select2({
    //     dropdownAutoWidth: true,
    //     width: "100%",
    //     containerCssClass: "select-sm",
    //     allowClear: true,
    //     ajax: {
    //         url: base_url + $("#path").val() + "/get_rv_type",
    //         dataType: "json",
    //         delay: 250,
    //         data: function(params) {
    //             var query = {
    //                 q: params.term,
    //             };
    //             return query;
    //         },
    //         processResults: function(data) {
    //             return {
    //                 results: data,
    //             };
    //         },
    //         cache: false,
    //     },
    // }).change(function(event) {
    //     $("#i_coa").val("");
    //     $("#i_coa").html("");
    //     $.ajax({
    //         type: "post",
    //         data: {
    //             'i_rv_type': $(this).val(),
    //         },
    //         url: base_url + $("#path").val() + '/get_tex',
    //         dataType: "json",
    //         success: function(data) {
    //             $('#tex').val(data);
    //             if (data!='BM') {
    //                 $('.clear').attr("hidden",true); 
    //                 $('.colay').attr('colspan',4);                   
    //             }else{
    //                 $('.clear').attr("hidden",false);  
    //                 $('.colay').attr('colspan',6);  
    //             }

    //         },
    //         error: function() {
    //             Swal.fire({
    //                 type: "error",
    //                 title: g_maaf,
    //                 confirmButtonClass: "btn btn-danger",
    //             });
    //         }
    //     });
    // });

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
                    i_rv_type: $('#i_rv_type').val(),
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
    });

    $("#d_document").change(function() {
        suzu();
        // clear_tabel();
    });

    var Detail = $(function() {
        var i = $("#jml").val();
        $("#addrow").on("click", function() {
            i++;
            let max = $('#d_document').val();
            var no = $("#tabledetail tbody tr").length + 1;
            if (no <= 30) {
            if ($("#tex").val()!='BM') {
                var tex = 'hidden';
            }else{
                var tex = '';
            }
            $("#jml").val(i);
            var newRow = $("<tr>");
            var cols = "";
            cols += `<td width="1%" class="text-center" valign="center"><spanx id="snum${i}">${no}</spanx></td>`;
            cols += `<td width="9%"><select required class="form-control isi select2-size-sm" name="i_coa_item[]" id="i_coa_item${i}"><option value=""></option></select></td>`;
            cols += `<td width="9%"><input type="date" max="${max}" value="${max}" class="form-control isi form-control-sm" id="d_bukti${i}" name="d_bukti[]"></td>`;
            
            cols += `<td width="4%" class="clear" required ${tex}><select data-nourut="${i}" class="form-control wajib select2-size-sm" name="i_rv_refference_type[]" id="i_rv_refference_type${i}"><option value=""></option></select></td>`;
            cols += `<td width="14%" class="clear" required ${tex}><select data-nourut="${i}" class="form-control wajib select2-size-sm" name="i_rv_refference[]" id="i_rv_refference${i}"><option value=""></option></select></td>`;
            cols += `<input type="hidden" data-nourut="${i}" class="form-control wajib select2-size-sm" name="araa[]" id="araa${i}"  readonly>`;
            cols += `<input type="hidden" data-nourut="${i}" class="form-control wajib select2-size-sm" name="jum[]" id="jum${i}"  readonly>`;
            
            cols += `<td width="37%"><input type="text" class="form-control isi form-control-sm" id="e_remark_item${i}" name="e_remark_item[]"></td>`;
            cols += `<td width="14%"><input type="text" class="formatrupiah isi form-control form-control-sm text-right v_rv" value="0" id="v_rv_item${i}" name="v_rv_item[]" onkeyup="reformat(this);hetang(); juma(${i});" autocomplete="off" onblur="if(this.value==''){this.value='0';hitung();}" onfocus="if(this.value=='0'){this.value='';}"></td>`;
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
                            i_rv_type: $('#i_rv_type').val(),
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
            $("#i_rv_refference_type" + i).select2({
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
                $("#i_rv_refference" + z).val("");
                $("#i_rv_refference" + z).html("");
            });
            $("#i_rv_refference" + i).select2({
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
                            i_rv_refference_type: $("#i_rv_refference_type" + i).val(),
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
                // var ada = false;
                // for (var x = 1; x <= $("#jml").val(); x++) {
                //     if ($(this).val() != null) {
                //         if ($(this).val() == $("#i_rv_refference" + x).val() && h != x) {
                //             Swal.fire({
                //                 type: "error",
                //                 title: g_maaf,
                //                 text: g_exist,
                //                 confirmButtonClass: "btn btn-danger",
                //             });
                //             ada = true;
                //             break;
                //         }
                //     }
                // }
                // if (ada) {
                //     $(this).val("");
                //     $(this).html("");
                // } else {
                    $.ajax({
                        type: "post",
                        data: {
                            'id': $(this).val(),
                            'i_rv_refference_type': $('#i_rv_refference_type' + h).val(),
                        },
                        url: base_url + $("#path").val() + '/get_detail_referensi',
                        dataType: "json",
                        success: function(data) {
                            $("#v_rv_item" + h).val(formatcemua(data["detail"][0]["v_jumlah"]));
                            $("#araa" + h).val(formatcemua(data["detail"][0]["araa"]));
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
                // }
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
        
        if ($("#tex").val()=='BM') {
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
                // sweeteditv2($("#path").val(), formData);
                sweeteditv5($("#path").val(), $("#d_from").val(), $("#d_to").val(), $("#htype").val(), $("#harea").val(), $("#hcoa").val(), formData);
            }
        } else {
            return false;
        }
    });
});

function hetang() {
    let v_rv = 0;
    $("#tabledetail tbody tr td .v_rv").each(function() {
        v_rv += parseFloat(formatulang($(this).val()));
    });
    $('#v_rv').val(formatcemua(v_rv));
    $('#v_sisa_saldo').val(formatcemua(parseFloat(formatulang($('#v_saldo').val())) + v_rv));
}

function saldo() {
    let v_rv = 0;
    $("#tabledetail tbody tr td .v_rv").each(function() {
        v_rv += parseFloat(formatulang($(this).val()));
    });
    $.ajax({
        type: "post",
        data: {
            'tanggal': $('#d_document').val(),
            'i_coa': $("#i_coa").val(),
        },
        url: base_url + $("#path").val() + '/get_saldo',
        dataType: "json",
        success: function(data) {
            $('#v_saldo').val(formatcemua(parseFloat(data) - v_rv));
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
function texr(){
    var data = $('#tex').val();
    if (data!='BM') {
        $('.clear').attr("hidden",true); 
        $('.colay').attr('colspan',4);                   
    }else{
        $('.clear').attr("hidden",false);  
        $('.colay').attr('colspan',6);  
    }
}


// function clear_tabel() {
//     $('#tabledetail tbody').empty();
//     $('#jml').val("0");
//     hetang();
// }

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

function number() {
    $.ajax({
        type: "post",
        data: {
            'tanggal': $('#d_document').val(),
            'i_area': $('#i_area').val(),
            'i_rv_type': $('#i_rv_type').val(),
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


function juma(i) {
    var data = $('#tex').val();
    if (data =='BM') {
    if (parseFloat(formatulang($('#v_rv_item' + i).val())) > parseFloat(formatulang($('#jum' + i).val()))) {
        Swal.fire({
            type: "error",
            title: g_maaf,
            text: "JUMLAH = '" + $('#jum' + i).val() + "' melebihi REFERENSI = '" + $('#v_rv_item' + i).val() + "'.",
            confirmButtonClass: "btn btn-danger",
        });
        $('#v_rv_item' + i).val($('#jum' + i).val());
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
            'i_rv_type': $('#i_rv_type').val(),
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