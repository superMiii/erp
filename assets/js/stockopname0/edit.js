$(document).ready(function() {
    // for (let i = 1; i <= $('#jml').val(); i++) {
    //     $("#i_product" + i).select2({
    //         placeholder: "Search",
    //         dropdownAutoWidth: true,
    //         width: '100%',
    //         containerCssClass: 'select-xs',
    //         allowClear: true,
    //         ajax: {
    //             url: base_url + $("#path").val() + "/get_product",
    //             dataType: "json",
    //             delay: 250,
    //             data: function(params) {
    //                 var query = {
    //                     q: params.term,
    //                     i_store_loc: $('#i_store_loc').val(),
    //                     i_store: $('#i_store').val(),
    //                 };
    //                 return query;
    //             },
    //             processResults: function(data) {
    //                 return {
    //                     results: data,
    //                 };
    //             },
    //             cache: false,
    //         },
    //     }).change(function(event) {
    //         var z = $(this).data("nourut");
    //         var ada = false;
    //         for (var x = 1; x <= $("#jml").val(); x++) {
    //             if ($(this).val() != null) {
    //                 if ($(this).val() == $("#i_product" + x).val() && z != x) {
    //                     Swal.fire({
    //                         type: "error",
    //                         title: g_maaf,
    //                         text: g_exist,
    //                         confirmButtonClass: "btn btn-danger",
    //                     });
    //                     ada = true;
    //                     break;
    //                 }
    //             }
    //         }
    //         if (ada) {
    //             $(this).val("");
    //             $(this).html("");
    //         } else {
    //             $.ajax({
    //                 type: "post",
    //                 data: {
    //                     i_product: $('#i_product' + z).val(),
    //                     i_store_loc: $('#i_store_loc').val(),
    //                     i_store: $('#i_store').val(),
    //                 },
    //                 url: base_url + $("#path").val() + '/get_product_detail',
    //                 dataType: "json",
    //                 success: function(data) {
    //                     $("#i_product_motif" + z).val(data.detail[0]['i_product_motif']);
    //                     $("#i_product_grade" + z).val(data.detail[0]['i_product_grade']);
    //                     $("#e_product_gradename" + z).val(data.detail[0]['e_product_gradename']);
    //                     $("#e_product_name" + z).val(data.detail[0]['e_product_name']);
    //                     $("#e_product_motifname" + z).val(data.detail[0]['e_product_motifname']);
    //                 },
    //                 error: function() {
    //                     Swal.fire({
    //                         type: "error",
    //                         title: g_maaf,
    //                         text: "Error data",
    //                         confirmButtonClass: "btn btn-danger",
    //                     });
    //                 }
    //             });
    //         }
    //     });
    // }
    $("#i_store_loc").select2({
        width: "100%",
        dropdownAutoWidth: true,
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_store",
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
    }).change(function() {
        $.ajax({
            type: "post",
            data: {
                'i_store_loc': $(this).val(),
                'd_so': $('#d_document').val(),
            },
            url: base_url + $("#path").val() + '/get_area',
            dataType: "json",
            success: function(data) {
                if (data['detail'] != null && data['detail_product'] != null) {
                    clear_tabel();
                    $('#i_area').val(data.detail[0].i_area);
                    $('#i_store').val(data.detail[0].i_store);
                    $('#jml').val(data['detail_product'].length);
                    for (let i = 0; i < data['detail_product'].length; i++) {
                        var no = $("#tablecover tr").length;
                        var newRow = $("<tr>");
                        var cols = "";
                        cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
                        cols += `<td><select data-nourut="${i}" required class="form-control select2-size-sm" name="i_product[]" id="i_product${i}"><option value="${data['detail_product'][i]['i_product']}">${data['detail_product'][i]['i_product_id']}</option></select></td>`;
                        cols += `<td><input type="text" id="e_product_gradename${i}" value="${data['detail_product'][i]['e_product_gradename']}" readonly class="form-control form-control-sm"></td>`;
                        cols += `<td><input type="text" id="e_product_name${i}" name="e_product_name[]" value="${data['detail_product'][i]['e_product_name']}" readonly class="form-control form-control-sm"></td>`;
                        cols += `<td>
                                <input type="hidden" id="i_product_motif${i}" name="i_product_motif[]" value="${data['detail_product'][i]['i_product_motif']}">
                                <input type="hidden" id="i_product_grade${i}" name="i_product_grade[]" value="${data['detail_product'][i]['i_product_grade']}">
                                <input type="text" id="e_product_motifname${i}" value="${data['detail_product'][i]['e_product_motifname']}" readonly class="form-control form-control-sm">
                                </td>`;
                        cols += `<td><input type="number" value="0" name="n_stockopname[]" class="form-control form-control-sm" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\'></td>`;
                        // cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>`;
                        newRow.append(cols);
                        $("#tablecover").append(newRow);
                        $("#i_product" + i).select2({
                            placeholder: "Search",
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
                                        i_store_loc: $('#i_store_loc').val(),
                                        i_store: $('#i_store').val(),
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
                            } else {
                                $.ajax({
                                    type: "post",
                                    data: {
                                        i_product: $('#i_product' + z).val(),
                                        i_store_loc: $('#i_store_loc').val(),
                                        i_store: $('#i_store').val(),
                                    },
                                    url: base_url + $("#path").val() + '/get_product_detail',
                                    dataType: "json",
                                    success: function(data) {
                                        $("#i_product_motif" + z).val(data.detail[0]['i_product_motif']);
                                        $("#i_product_grade" + z).val(data.detail[0]['i_product_grade']);
                                        $("#e_product_gradename" + z).val(data.detail[0]['e_product_gradename']);
                                        $("#e_product_name" + z).val(data.detail[0]['e_product_name']);
                                        $("#e_product_motifname" + z).val(data.detail[0]['e_product_motifname']);
                                    },
                                    error: function() {
                                        Swal.fire({
                                            type: "error",
                                            title: g_maaf,
                                            text: "Error data",
                                            confirmButtonClass: "btn btn-danger",
                                        });
                                    }
                                });
                            }
                        });
                    }
                }
            },
            error: function() {
                Swal.fire({
                    type: "error",
                    title: g_maaf,
                    text: "Error data",
                    confirmButtonClass: "btn btn-danger",
                });
            }
        });
    });

    var Detail = $(function() {
        var i = $("#jml").val();
        $("#addrow").on("click", function() {
            i++;
            var no = $("#tablecover tr").length;
            $("#jml").val(i);
            var newRow = $("<tr>");
            var cols = "";
            cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
            cols += `<td><select data-nourut="${i}" required class="form-control select2-size-sm" name="i_product[]" id="i_product${i}"></select></td>`;
            cols += `<td><input type="text" id="e_product_gradename${i}" value="" readonly class="form-control form-control-sm"></td>`;
            cols += `<td><input type="text" id="e_product_name${i}" name="e_product_name[]" value="" readonly class="form-control form-control-sm"></td>`;
            cols += `<td>
                        <input type="hidden" id="i_product_motif${i}" name="i_product_motif[]" value="">
                        <input type="hidden" id="i_product_grade${i}" name="i_product_grade[]" value="">
                        <input type="text" id="e_product_motifname${i}" value="" readonly class="form-control form-control-sm">
                    </td>`;
            cols += `<td><input type="number" value="0" name="n_stockopname[]" class="form-control form-control-sm" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\'></td>`;
            // cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>`;
            newRow.append(cols);
            $("#tablecover").append(newRow);
            $("#i_product" + i).select2({
                placeholder: "Search",
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
                            i_store_loc: $('#i_store_loc').val(),
                            i_store: $('#i_store').val(),
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
                } else {
                    $.ajax({
                        type: "post",
                        data: {
                            i_product: $('#i_product' + z).val(),
                            i_store_loc: $('#i_store_loc').val(),
                            i_store: $('#i_store').val(),
                        },
                        url: base_url + $("#path").val() + '/get_product_detail',
                        dataType: "json",
                        success: function(data) {
                            $("#i_product_motif" + z).val(data.detail[0]['i_product_motif']);
                            $("#i_product_grade" + z).val(data.detail[0]['i_product_grade']);
                            $("#e_product_gradename" + z).val(data.detail[0]['e_product_gradename']);
                            $("#e_product_name" + z).val(data.detail[0]['e_product_name']);
                            $("#e_product_motifname" + z).val(data.detail[0]['e_product_motifname']);
                        },
                        error: function() {
                            Swal.fire({
                                type: "error",
                                title: g_maaf,
                                text: "Error data",
                                confirmButtonClass: "btn btn-danger",
                            });
                        }
                    });
                }
            });
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

    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $('form').on('submit', function(e) { //bind event on form submit.
        let tabel = $("#tablecover tbody tr").length;
        let ada = false;
        if (tabel < 1) {
            Swal.fire({
                type: "error",
                title: g_maaf,
                text: "Input minimum 1 item !",
                confirmButtonClass: "btn btn-danger",
            });
            return false;
        }

        $("#tablecover tbody tr").each(function() {
            $(this).find("td select").each(function() {
                if ($(this).val() == "" || $(this).val() == null) {
                    Swal.fire("Barang tidak boleh kosong!");
                    ada = true;
                }
            });
        });

        if (!ada) {
            e.preventDefault();
            var formData = new FormData(this);
            if (formData) {
                // sweeteditv2($("#path").val(), formData);
                sweeteditv3($("#path").val(), $("#d_from").val(), $("#d_to").val(), formData);  
            }
        } else {
            return false;
        }
    });
});

function clear_tabel() {
    $("#tablecover tbody").empty();
}