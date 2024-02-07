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
        clear_tabel();
    });

    $("#i_bank").select2({
        dropdownAutoWidth: true,
        width: "100%",
        containerCssClass: "select-sm",
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_bank",
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

    $("#i_document").keyup(function() {
        cek_kode();
    });

    $("#d_document").change(function() {
        number();
    });

    var Detail = $(function() {
        var i = $("#jml").val();
        $("#addrow").on("click", function() {
            i++;
            var no = $("#tabledetail tbody tr").length + 1;
            $("#jml").val(i);
            var newRow = $("<tr>");
            var cols = "";
            cols += `<td class="text-center" valign="center"><spanx id="snum${i}">${no}</spanx></td>`;
            cols += `<td><select data-nourut="${i}" required class="form-control select2-size-sm" name="i_tunai[]" id="i_tunai${i}"><option value=""></option></select></td>`;
            cols += `<td><input type="text" readonly class="form-control form-control-sm" id="d_tunai${i}" name="d_tunai[]" readonly></td>`;
            cols += `<td><input type="text" readonly class="form-control form-control-sm" id="cus${i}" name="cus[]" readonly></td>`;
            cols += `<td><input type="text" autocomplete="off" onkeyup="hetang();" class="form-control form-control-sm text-right v_jumlah" value="0" id="v_jumlah_item${i}" name="v_jumlah_item[]" readonly></td>`;
            cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>`;
            newRow.append(cols);
            $("#tabledetail").append(newRow);
            $("#i_tunai" + i).select2({
                placeholder: g_pilihdata,
                dropdownAutoWidth: true,
                width: '100%',
                containerCssClass: 'select-xs',
                allowClear: true,
                ajax: {
                    url: base_url + $("#path").val() + "/get_tunai",
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
                        if ($(this).val() == $("#i_tunai" + x).val() && z != x) {
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
                            'i_tunai': $(this).val(),
                        },
                        url: base_url + $("#path").val() + "/get_detail_tunai",
                        dataType: "json",
                        success: function(data) {
                            $("#d_tunai" + z).val(data['detail'][0]['d_tunai']);
                            $("#cus" + z).val(data['detail'][0]['cus']);
                            $("#v_jumlah_item" + z).val(number_format(data['detail'][0]['v_jumlah']));
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
                        text: "Item not empty",
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
                sweetaddv33($("#path").val(), $("#d_from").val(), $("#d_to").val(), $("#harea").val(), formData);
            }
        } else {
            return false;
        }
    });
});

function cek_kode() {
    $.ajax({
        type: "post",
        data: {
            i_document: $('#i_document').val(),
            i_area: $('#i_area').val(),
        },
        url: base_url + $("#path").val() + "/cek_code",
        dataType: "json",
        success: function(data) {
            if (data == 1) {
                $("#ada").attr("hidden", false);
                $("#submit").attr("disabled", true);
            } else {
                $("#ada").attr("hidden", true);
                $("#submit").attr("disabled", false);
            }
        },
        /* error: function() {
            Swal.fire("Error :)");
        }, */
    });
}

function number() {
    $.ajax({
        type: "post",
        data: {
            'tanggal': $('#d_document').val(),
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
    let v_jumlah = 0;
    $("#tabledetail tbody tr td .v_jumlah").each(function() {
        v_jumlah += parseFloat(formatulang($(this).val()));
    });
    $('#v_jumlah').val(number_format(v_jumlah));
}

function clear_tabel() {
    $('#tabledetail tbody').empty();
    $('#jml').val("0");
}