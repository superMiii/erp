$(document).ready(function() {
    hetang();
    $(".select2").select2();
    // $("#i_area").select2({
    //     dropdownAutoWidth: true,
    //     width: '100%',
    //     containerCssClass: 'select-sm',
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
    //     clear_tabel();
    // });

    for (let i = 1; i <= $('#jml').val(); i++) {
        $("#i_nota" + i).select2({
            placeholder: g_pilihdata,
            dropdownAutoWidth: true,
            width: '100%',
            containerCssClass: 'select-xs',
            allowClear: true,
            ajax: {
                url: base_url + $("#path").val() + "/get_nota",
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
            var z = $(this).data("nourut");
            var ada = false;
            for (var x = 1; x <= $("#jml").val(); x++) {
                if ($(this).val() != null) {
                    if ($(this).val() == $("#i_nota" + x).val() && z != x) {
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
                        'i_nota': $(this).val(),
                    },
                    url: base_url + $("#path").val() + "/get_detail_nota",
                    dataType: "json",
                    success: function(data) {
                        $("#d_nota" + z).val(data['detail'][0]['d_nota']);
                        $("#date_nota" + z).val(data['detail'][0]['date_nota']);
                        $("#due_date" + z).val(data['detail'][0]['due_date']);
                        $("#e_customer_name" + z).val(data['detail'][0]['e_customer']);
                        $("#v_jumlah" + z).val(number_format(data['detail'][0]['v_nota_netto']));
                        $("#v_sisa" + z).val(number_format(data['detail'][0]['v_sisa']));
                        hetang();
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
            $("#jml").val(i);
            var newRow = $("<tr>");
            var cols = "";
            cols += `<td class="text-center" valign="center"><spanx id="snum${i}">${no}</spanx></td>`;
            cols += `<td><select data-nourut="${i}" required class="form-control select2-size-sm" name="i_nota[]" id="i_nota${i}"><option value=""></option></select></td>`;
            cols += `<td><span id="date_nota${i}"></span><input type="hidden" id="d_nota${i}" name="d_nota[]"></td>`;
            cols += `<td><span id="due_date${i}"></span></td>`;
            cols += `<td><span id="e_customer_name${i}"></span></td>`;
            cols += `<td class="text-right"><span id="bayar${i}"></span><input type="hidden" id="v_bayar${i}" name="v_bayar[]"></td>`;
            cols += `<td class="text-right"><span id="sisa${i}"></span><input type="hidden" class="v_sisa" id="v_sisa${i}" name="v_sisa[]"></td>`;
            cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>`;
            newRow.append(cols);
            $("#tabledetail").append(newRow);
            $("#i_nota" + i).select2({
                placeholder: g_pilihdata,
                dropdownAutoWidth: true,
                width: '100%',
                containerCssClass: 'select-xs',
                allowClear: true,
                ajax: {
                    url: base_url + $("#path").val() + "/get_nota",
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
                var z = $(this).data("nourut");
                var ada = false;
                for (var x = 1; x <= $("#jml").val(); x++) {
                    if ($(this).val() != null) {
                        if ($(this).val() == $("#i_nota" + x).val() && z != x) {
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
                            'id': $(this).val(),
                        },
                        url: base_url + $("#path").val() + "/get_detail_nota",
                        dataType: "json",
                        success: function(data) {
                            $("#d_nota" + z).val(data['detail'][0]['d_nota']);
                            $("#date_nota" + z).text(data['detail'][0]['date_nota']);
                            $("#due_date" + z).text(data['detail'][0]['due_date']);
                            $("#e_customer_name" + z).text(data['detail'][0]['e_customer']);
                            $("#v_bayar" + z).val(data['detail'][0]['v_nota_netto']);
                            $("#bayar" + z).text(number_format(data['detail'][0]['v_nota_netto']));
                            $("#v_sisa" + z).val(data['detail'][0]['v_sisa']);
                            $("#sisa" + z).text(number_format(data['detail'][0]['v_sisa']));
                            hetang();
                        },
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
            $(this).find("td select").each(function() {
                if ($(this).val() == '' || $(this).val() == null) {
                    Swal.fire({
                        type: "error",
                        title: g_maaf,
                        text: "Barang Tidak Boleh Kosong!",
                        confirmButtonClass: "btn btn-danger",
                    });
                    ada = true;
                }
            });
        });

        if (!ada) {
            e.preventDefault();
            var formData = new FormData(this);
            if (formData) {
                sweeteditv33($("#path").val(), $("#d_from").val(), $("#d_to").val(), $("#harea").val(), formData);            }
        } else {
            return false;
        }
    });
});


function clear_tabel() {
    $('#tabledetail tbody').empty();
    $('#jml').val("0");
}

function hetang() {
    let v_sisa = 0;
    $("#tabledetail tbody tr td .v_sisa").each(function() {
        let nilai=parseFloat(formatulang($(this).val()));
        if (isNaN(nilai)) {
            nilai=0;
        }
        v_sisa += nilai;
    });
    $('#jumlah').text(number_format(v_sisa));
    $('#v_jumlah').val(v_sisa);
}