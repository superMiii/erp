$(document).ready(function() {
    /* for (let i = 1; i <= $('#jml').val(); i++) {
        $("#i_product" + i).select2({
            placeholder: g_pilihdata,
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
                        i_price_group: $("#i_price_group").val(),
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
                        swal(g_maaf, g_exist, "error");
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
                        i_price_group: $("#i_price_group").val(),
                        i_product_group: $("#i_product_group").val(),
                        i_product: $("#i_product" + z).val(),
                        i_store: $("#i_store").val(),
                        i_store_location: $("#i_store_location").val(),

                    },
                    url: base_url + $("#path").val() + "/get_product_price",
                    dataType: "json",
                    success: function(data) {
                        $("#v_price" + z).val(data["detail"][0]["v_price"]);
                        $("#n_stock" + z).val(data["detail"][0]["n_stock"]);
                        $("#i_product_motif" + z).val(data["detail"][0]["i_product_motif"]);
                        $("#i_product_grade" + z).val(data["detail"][0]["i_product_grade"]);
                        $("#i_product_status" + z).val(data["detail"][0]["i_product_status"]);
                        $("#e_product_name" + z).val(data["detail"][0]["e_product_name"]);
                        $("#e_product_motifname" + z).val(data["detail"][0]["e_product_motifname"]);
                    },
                });

            }
        });
    } */

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
                cols += `<td><select data-nourut="${i}" required class="form-control select2-size-sm" name="i_product[]" id="i_product${i}"><option value=""></option></select>
                <input type="hidden" value="" id="i_product_old${i}" name="i_product_old[]" readonly>
                </td>`;
                cols += `<td><input type="text" readonly class="form-control form-control-sm" id="e_product_motifname${i}" name="e_product_motifname[]">
                    <input type="hidden" class="form-control text-right form-control-sm" id="v_price${i}" name="v_price[]" readonly>
                    <input type="hidden" class="form-control form-control-sm" id="e_product_name${i}" name="e_product_name[]" readonly>
                    <input type="hidden" class="form-control form-control-sm" id="i_product_motif${i}" name="i_product_motif[]" readonly>
                    <input type="hidden" class="form-control form-control-sm" id="i_product_grade${i}" name="i_product_grade[]" readonly>
                    <input type="hidden" class="form-control form-control-sm" id="i_product_status${i}" name="i_product_status[]" readonly>
                </td>`;
                cols += `<td><input type="text" readonly class="form-control form-control-sm text-right" id="n_order${i}" name="n_order[]" value="0"></td>`;
                cols += `<td><input type="text" readonly class="form-control form-control-sm text-right" id="n_stock${i}" name="n_stock[]" value="0"></td>`;
                cols += `<td><input type="number" autocomplete="off" class="form-control text-center n_deliver form-control-sm" id="n_deliver${i}" value="0" name="n_deliver[]" onkeyup="cek(${i}); sama(${i}); " onblur=\"if(this.value==''){this.value='0';hitung();}\" onfocus=\"if(this.value=='0'){this.value='';}\"></td>`;
                cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>`;
                newRow.append(cols);
                $("#tabledetail").append(newRow);
                $("#i_product" + i)
                    .select2({
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
                                    i_price_group: $("#i_price_group").val(),
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
                    })
                    .change(function(event) {
                        var z = $(this).data("nourut");
                        var ada = false;
                        for (var x = 1; x <= $("#jml").val(); x++) {
                            if ($(this).val() != null) {
                                if ($(this).val() == $("#i_product" + x).val() && z != x) {
                                    swal(g_maaf, g_exist, "error");
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
                                    i_price_group: $("#i_price_group").val(),
                                    i_product_group: $("#i_product_group").val(),
                                    i_product: $("#i_product" + z).val(),
                                    i_store: $("#i_store").val(),
                                    i_store_location: $("#i_store_location").val(),
                                },
                                url: base_url + $("#path").val() + "/get_product_price",
                                dataType: "json",
                                success: function(data) {
                                    $("#v_price" + z).val(data["detail"][0]["v_price"]);
                                    $("#n_stock" + z).val(data["detail"][0]["n_stock"]);
                                    $("#i_product_motif" + z).val(data["detail"][0]["i_product_motif"]);
                                    $("#i_product_grade" + z).val(data["detail"][0]["i_product_grade"]);
                                    $("#i_product_status" + z).val(data["detail"][0]["i_product_status"]);
                                    $("#e_product_name" + z).val(data["detail"][0]["e_product_name"]);
                                    $("#e_product_motifname" + z).val(data["detail"][0]["e_product_motifname"]);
                                    $("#n_deliver" + z).focus();
                                },
                            });
                        }
                    });
            } else {
                swal(g_maaf, "Maksimal 20 Item :)", "error");
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

        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
        $('form').on('submit', function(e) { //bind event on form submit.
            let tabel = $("#tabledetail tbody tr").length;
            let ada = false;
            if (tabel < 1) {
                swal(g_maaf, g_detailmin, "error");
                return false;
            }

            $("#tabledetail tbody tr").each(function() {
                $(this).find("td select").each(function() {
                    if ($(this).val() == '' || $(this).val() == null) {
                        swal('Barang tidak boleh kosong!');
                        ada = true;
                    }
                });
            });

            if (!ada) {
                e.preventDefault();
                var formData = new FormData(this);
                if (formData) {
                    sweeteditv33($("#path").val(), $("#d_from").val(), $("#d_to").val(), $("#harea").val(), formData);
                }
            } else {
                return false;
            }
        });
    });
});

function cek(i) {
    if (parseInt($('#n_deliver' + i).val()) > parseInt($('#n_stock' + i).val())) {
        swal('Maaf Qty Kirim = ' + $('#n_deliver' + i).val() + ', tidak boleh lebih dari Stok = ' + $('#n_stock' + i).val());
        $('#n_deliver' + i).val($('#n_stock' + i).val());
    }
}

function sama(i) {
    $('#n_order' + i).val($('#n_deliver' + i).val());
}