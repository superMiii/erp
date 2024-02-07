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
        $("#i_salesman").val("");
        $("#i_salesman").html("");
        clear_tabel();
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

    $("#i_dt").select2({
        dropdownAutoWidth: true,
        width: "100%",
        containerCssClass: "select-sm",
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_dt",
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
    });

    $("#i_document").keyup(function() {
        cek_kode();
    });

    $("#d_document").change(function() {
        number();
        clear_tabel();
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
            cols += `<td><select data-nourut="${i}" required class="form-control select2-size-sm" name="i_nota[]" id="i_nota${i}"><option value=""></option></select></td>`;
            cols += `<td><input type="text" readonly class="form-control form-control-sm" id="dnota${i}" readonly><input type="hidden" readonly class="form-control form-control-sm" id="d_nota${i}" name="d_nota[]" readonly></td>`;
            cols += `<td><input readonly class="form-control form-control-sm text-right" type="text" id="vnota${i}" name="vnota[]" value="0"></td>`;
            cols += `<td><input autocomplete="off" class="form-control form-control-sm text-right" type="text" id="vjumlah${i}" name="vjumlah[]" value="0" onkeydown="reformat(this);hetang();" onkeyup="onlyangka(this); reformat(this); hetang();" onpaste="return false;" onblur=\"if(this.value==''){this.value='0';hetang();}\" onfocus=\"if(this.value=='0'){this.value='';}\"></td>`;
            cols += `<td><input readonly class="form-control form-control-sm text-right" type="text" id="vsesa${i}" name="vsesa[]" value="0"><input type="hidden" id="vsisa${i}" name="vsisa${i}" value="0"></td>`;
            cols += `<td><input readonly class="form-control form-control-sm text-right" type="text" id="vlebih${i}" name="vlebih[]" value="0"></td>`;
            cols += `<td><select class="form-control form-control-sm" id="eremark${i}" name="eremark[]"><option value=""></option><option value="-"> - </option><option value="Retur">Retur</option><option value="Biaya Promo">Biaya Promo</option><option value="Kurang Bayar">Kurang Bayar</option><option value="Biaya Ekspedisi">Biaya Ekspedisi</option><option value="Biaya Administrasi">Biaya Administrasi</option></select></td>`;
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
                            $("#dnota" + z).val(data['detail'][0]['dnota']);
                            $("#vsesa" + z).val(formatcemua(data['detail'][0]['v_sisa']));
                            $("#vsisa" + z).val(formatcemua(data['detail'][0]['v_sisa']));
                            $("#vnota" + z).val(formatcemua(data['detail'][0]['v_sisa']));
                            tmp = formatulang($("#vjumlah").val());
                            jml = $("#jml").val();

                            if (tmp > 0) {
                                tmp = parseFloat(tmp);
                                sisa = 0;
                                jumasal = tmp;
                                jumall = jumasal;
                                bay = 0;

                                for (x = 1; x <= jml; x++) {
                                    if (typeof $("#vjumlah" + x).val() !== 'undefined') {
                                        if ($("#vjumlah" + x).val() == '') {
                                            jum = parseFloat(formatulang($("#vsisa" + x).val()));
                                        } else {
                                            jum = parseFloat(formatulang($("#vjumlah" + x).val()));
                                        }
                                        jumall = jumall - jum;
                                        if (jumall > 0) {
                                            $("#vlebih").val(formatcemua(jumall));
                                            if (x == z) {
                                                $("#vjumlah" + z).val(formatcemua(data['detail'][0]['v_sisa']));
                                                by = parseFloat(formatulang($("#vjumlah" + z).val()));
                                                bay = jumasal - by;
                                                sis = parseFloat(formatulang($("#vsisa" + z).val()));
                                                $("#vlebih" + z).val(formatcemua(bay));
                                            }
                                            sisa = sisa + jum;
                                        } else {
                                            $("#vlebih").val('0');
                                            $("#vlebih" + z).val('0');
                                            $("#vjumlah" + z).val(formatcemua(jumasal - sisa));
                                            $("#vlebih" + z).val('0');
                                        }
                                    }
                                }
                            }
                            hetang();
                        },
                    });
                }
            });
            $("#eremark" + i).select2({
                placeholder: 'Select Description',
                dropdownAutoWidth: true,
                containerCssClass: 'select-xs',
                width: '100%',
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
    var vjmlbyr = parseFloat(formatulang($("#vjumlah").val()));
    var vlebihitem = vjmlbyr;
    for (a = 1; a <= $('#jml').val(); a++) {
        if (typeof $("#vjumlah" + a).val() !== 'undefined') {
            vnota = parseFloat(formatulang($("#vsisa" + a).val()));
            vjmlitem = parseFloat(formatulang($("#vjumlah" + a).val()));
            /* if (vjmlitem == 0) {
                bbotol();
            } */
            vsisaitem = vnota - vjmlitem;
            if (vsisaitem < 0) {
                Swal.fire({
                    type: "error",
                    title: g_maaf,
                    text: "Jumlah bayar tidak bisa lebih besar dari nilai nota !!!!!",
                    confirmButtonClass: "btn btn-danger",
                });
                $("#vjumlah" + a).val(0);
                vjmlitem = parseFloat(formatulang($("#vjumlah" + a).val()));
                vsisaitem = parseFloat(formatulang($("#vsisa" + a).val()));
            }
            vlebihitem = vlebihitem - vjmlitem;
            if (vlebihitem < 0) {
                vlebihitem = vlebihitem + vjmlitem;
                vsisaitem = vnota - vlebihitem;
                Swal.fire({
                    type: "error",
                    title: g_maaf,
                    text: "Jumlah item tidak bisa lebih besar dari nilai bayar !!!!!",
                    confirmButtonClass: "btn btn-danger",
                });
                $("#vjumlah" + a).val(formatcemua(vlebihitem));
                vjmlitem = parseFloat(formatulang($("#vjumlah" + a).val()));
                vlebihitem = 0;
            }
            $("#vsesa" + a).val(formatcemua(vsisaitem));
            $("#vlebih" + a).val(formatcemua(vlebihitem));
        }
    }
    $("#vlebih").val(formatcemua(vlebihitem));
}

function clear_tabel() {
    $('#tabledetail tbody').empty();
    $('#jml').val("0");
}