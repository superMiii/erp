$(document).ready(function() {
    for (let i = 1; i <= $('#jml').val(); i++) {
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
                    if ($(this).val() == $("#i_product" + x).val() && z != x) {
                        Swal.fire(g_maaf, g_exist, "error");
                        ada = true;
                        break;
                    }
                }
            }
            if (ada) {
                $(this).val("");
                $(this).html("");
                $("#i_product_motif" + z).val();
                $("#i_product_grade" + z).val();
                $("#e_product_name" + z).val();
                $("#e_product_motifname" + z).val();
            } else {
                if ($(this).val() != '' || $(this).val() != null) {
                    $.ajax({
                        type: "post",
                        data: {
                            'i_product': $("#i_product" + z).val(),

                        },
                        url: base_url + $("#path").val() + "/get_product_detail",
                        dataType: "json",
                        success: function(data) {
                            $("#i_product_motif" + z).val(data['detail'][0]['i_product_motif']);
                            $("#i_product_grade" + z).val(data['detail'][0]['i_product_grade']);
                            $("#e_product_name" + z).val(data['detail'][0]['e_product_name']);
                            $("#e_product_motifname" + z).val(data['detail'][0]['e_product_motifname']);
                            $("#n_bbm" + z).focus();

                        },
                    });
                }

            }
        });
    }

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
                    Swal.fire('Barang tidak boleh kosong!');
                    ada = true;
                }
            });
            // $(this).find("td .n_bbm").each(function() {
            //     if ($(this).val() == '' || $(this).val() == null || $(this).val() == 0) {
            //         Swal.fire('Quantity Tidak Boleh Kosong Atau 0!');
            //         ada = true;
            //     }
            // });
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

function cek(i) {
    if (parseInt($('#n_bbm' + i).val()) > parseInt($('#n_ttb' + i).val())) {
        Swal.fire('Maaf Qty BBM = ' + $('#n_bbm' + i).val() + ', tidak boleh lebih dari Qty TTB = ' + $('#n_ttb' + i).val());
        $('#n_bbm' + i).val($('#n_ttb' + i).val());
    }
}