$(document).ready(function() {
    // $('#i_document').inputmask("aa-9999-999999");
    // $(".switch:checkbox").checkboxpicker();
    //number();
    pickdatemonthandyear();
    get_data();

    $("#i_supplier").select2({
        dropdownAutoWidth: true,
        width: '100%',
        containerCssClass: 'select-sm',
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_supplier",
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

        if ($('#d_document').val() != "") {
            // $.ajax({
            //     type: "post",
            //     data: {
            //         'i_supplier': $(this).val(),
            //         'd_document': $('#d_document_submit').val()
            //     },
            //     url: base_url + $("#path").val() + "/get_recent",
            //     dataType: "json",
            //     success: function(data) {
            //         if (data['data'] != null) {
            //             clear_tabel();
            //             var i = $("#jml").val();
            //             $.each(data['data'], function(k, v) { 
            //                 i++;
            //                 var no = $("#tabledetail tbody tr").length + 1;
            //                 $("#jml").val(i);
            //                 var newRow = $("<tr>");
            //                 var cols = "";
            //                     cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
            //                     cols += `<td><select data-nourut="${i}" required class="form-control select2-size-sm" name="i_product[]" id="i_product${i}"><option value=""></option></select></td>`;
            //                     cols += `<td>
            //                     <input type="hidden" name="i_product_motif[]" id="i_product_motif${i}" readonly class="form-control form-control-sm">
            //                     <input type="hidden" name="i_product_grade[]" id="i_product_grade${i}" readonly class="form-control form-control-sm">
            //                     <input type="text" id="e_product_motifname${i}" readonly class="form-control form-control-sm">
            //                     </td>`;
            //                     cols += `<td><input type="number" value="0" name="n_quantity[]" id="n_quantity${i}" class="form-control form-control-sm" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\'></td>`;
            //                     cols += `<td><input type="text" name="e_remarkitem[]" id="e_remarkitem${i}" class="form-control form-control-sm"></td>`;
            //                     cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>`;
            //                 newRow.append(cols);
            //                 $("#tabledetail").append(newRow);
            //                 $("#i_product" + i).select2({
            //                     placeholder: "Search",
            //                     dropdownAutoWidth: true,
            //                     width: '100%',
            //                     containerCssClass: 'select-xs',
            //                     allowClear: true,
            //                     ajax: {
            //                         url: base_url + $("#path").val() + "/get_product",
            //                         dataType: "json",
            //                         delay: 250,
            //                         data: function(params) {
            //                             var query = {
            //                                 q: params.term,
            //                             };
            //                             return query;
            //                         },
            //                         processResults: function(data) {
            //                             return {
            //                                 results: data,
            //                             };
            //                         },
            //                         cache: false,
            //                     },
            //                 }).change(function(event) {
            //                     var z = $(this).data("nourut");
            //                     var ada = false;
            //                     for (var x = 1; x <= $("#jml").val(); x++) {
            //                         if ($(this).val() != null) {
            //                             if ($(this).val() == $("#i_product" + x).val() /* && $("#i_level" + z).val() == $("#i_level" + x).val() */ && z != x) {
            //                                 swal("Sorry :(", "Data already exist ..", "error");
            //                                 ada = true;
            //                                 break;
            //                             }
            //                         }
            //                     }
            //                     if (ada) {
            //                         $(this).val("");
            //                         $(this).html("");
            //                     } else {
            //                         $.ajax({
            //                             type: "post",
            //                             data: {
            //                                 'i_product': $('#i_product' + z).val(),
            //                             },
            //                             url: base_url + $("#path").val() + '/get_product_detail',
            //                             dataType: "json",
            //                             success: function(data) {
            //                                 $("#i_product_motif" + z).val(data.detail[0]['i_product_motif']);
            //                                 $("#i_product_grade" + z).val(1);
            //                                 $("#e_product_motifname" + z).val(data.detail[0]['e_product_motifname']);
            //                                 $("#n_quantity" + z).val(0);
            //                             },
            //                             error: function() {
            //                                 swal('Error :)');
            //                             }
            //                         });
            //                     }
            //                 });
            //             });
            //         }
            //     },
            //     error: function() {
            //         swal('500 internal server error : (');
            //     }
            // });
        } else {
            swal('Tanggal Dokumen Harus Di Isi');
        }
        
    });


    
     var Detail = $(function() {
       
        $("#addrow").on("click", function() {
            var i = $("#jml").val();
            i++;
            var no = $("#tabledetail tr").length;
            $("#jml").val(i);
            var newRow = $("<tr>");
            var cols = "";
            cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
            cols += `<td><select data-nourut="${i}" required class="form-control select2-size-sm" name="i_product[]" id="i_product${i}"><option value=""></option></select></td>`;
            cols += `<td>
            <input type="hidden" name="e_product_name[]" id="e_product_name${i}" readonly class="form-control form-control-sm">
            <input type="hidden" name="i_product_motif[]" id="i_product_motif${i}" readonly class="form-control form-control-sm">
            <input type="hidden" name="i_product_grade[]" id="i_product_grade${i}" readonly class="form-control form-control-sm">
            <input type="text" id="e_product_motifname${i}" readonly class="form-control form-control-sm">
            </td>`;
            cols += `<td><input type="text" value="0" name="n_quantity[]" id="n_quantity${i}" class="form-control form-control-sm" onkeypress="return bilanganasli(event);" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\'></td>`;
            cols += `<td><input type="text" name="e_remarkitem[]" id="e_remarkitem${i}" class="form-control form-control-sm"></td>`;
            cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>`;
            newRow.append(cols);
            $("#tabledetail").append(newRow);
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
                        if ($(this).val() == $("#i_product" + x).val() /* && $("#i_level" + z).val() == $("#i_level" + x).val() */ && z != x) {
                            swal("Sorry :(", "Data already exist ..", "error");
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
                            'i_product': $('#i_product' + z).val(),
                        },
                        url: base_url + $("#path").val() + '/get_product_detail',
                        dataType: "json",
                        success: function(data) {
                            $("#i_product_motif" + z).val(data.detail[0]['i_product_motif']);
                            $("#i_product_grade" + z).val(1);
                            $("#e_product_motifname" + z).val(data.detail[0]['e_product_motifname']);
                            $("#e_product_name" + z).val(data.detail[0]['e_product_name']);
                            
                            $("#n_quantity" + z).val(0);
                        },
                        error: function() {
                            swal('Error :)');
                        }
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
        });
    });
    

    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $('form').on('submit', function(e) { //bind event on form submit.
        let tabel = $("#tabledetail tbody tr").length;
        let ada = false;
        if (tabel < 1) {
            swal(g_maaf, g_detailmin, "error");
            return false;
        }

        if (!ada) {
            e.preventDefault();
            var formData = new FormData(this);
            if (formData) {
                sweetedit($("#path").val(), formData);
            }
        } else {
            return false;
        }
    });
});

function clear_tabel() {
    $('#tabledetail tbody').empty();
    $('#tfoot_subtotal').val("");
    $('#tfoot_n_diskon').val("");
    $('#tfoot_v_diskon').val("");
    $('#tfoot_v_dpp').val("");
    $('#tfoot_v_ppn').val("");
    $('#tfoot_total').val("");
    $('#jml').val("0");
}

function get_data() {
    $.ajax({
        type: "post",
        data: {
            'id': $('#id').val()
        },
        url: base_url + $("#path").val() + "/get_data_detail",
        dataType: "json",
        success: function(data) {
            if (data['data'] != null) {
                clear_tabel();
                var i = $("#jml").val();
                $.each(data['data'], function(k, v) {
                    i++;
                    var no = $("#tabledetail tbody tr").length + 1;
                    $("#jml").val(i);
                    var newRow = $("<tr>");
                    var cols = "";
                    cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
                    cols += `<td><select data-nourut="${i}" required class="form-control select2-size-sm" name="i_product[]" id="i_product${i}"><option value="${v['i_product']}">${v['i_product_id']} - ${v['e_product_name']}</option></select></td>`;
                    cols += `<td>
                    <input type="hidden" name="e_product_name[]" id="e_product_name${i}" readonly class="form-control form-control-sm" value="${v['e_product_name']}">
                    <input type="hidden" name="i_product_motif[]" id="i_product_motif${i}" readonly class="form-control form-control-sm" value="${v['i_product_motif']}">
                    <input type="text" id="e_product_motifname${i}" readonly class="form-control form-control-sm" value="${v['e_product_motifname']}">
                    </td>`;
                    cols += `<td><input type="text" value="${v['n_forecast']}" name="n_quantity[]" id="n_quantity${i}" class="form-control form-control-sm" onkeypress="return bilanganasli(event);" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\'></td>`;
                    cols += `<td><input type="text" name="e_remarkitem[]" id="e_remarkitem${i}" class="form-control form-control-sm" value="${v['e_remark']}"></td>`;
                    cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>`;
                    newRow.append(cols);
                    $("#tabledetail").append(newRow);
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
                                if ($(this).val() == $("#i_product" + x).val() /* && $("#i_level" + z).val() == $("#i_level" + x).val() */ && z != x) {
                                    swal("Sorry :(", "Data already exist ..", "error");
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
                                    'i_product': $('#i_product' + z).val(),
                                },
                                url: base_url + $("#path").val() + '/get_product_detail',
                                dataType: "json",
                                success: function(data) {
                                    $("#i_product_motif" + z).val(data.detail[0]['i_product_motif']);
                                    $("#i_product_grade" + z).val(1);
                                    $("#e_product_motifname" + z).val(data.detail[0]['e_product_motifname']);
                                    $("#e_product_name" + z).val(data.detail[0]['e_product_name']);
                                    
                                    $("#n_quantity" + z).val(0);
                                },
                                error: function() {
                                    swal('Error :)');
                                }
                            });
                        }
                    });
                });
            }
        },
        error: function() {
            swal('500 internal server error : (');
        }
    });
}

