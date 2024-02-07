$(document).ready(function() {
    var controller = $("#path").val();
    $(".select2").select2();
    $("#i_product").select2({
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_product",
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

    $('#i_product, #i_product_grade').change(function(event) {
        $("#tabledetail tr:gt(0)").remove();
        $("#jml").val(0);
        $.ajax({
            type: "post",
            data: {
                'i_product': $('#i_product').val(),
                'i_product_grade': $('#i_product_grade').val(),
            },
            url: base_url + controller + "/get_price_group",
            dataType: "json",
            success: function(data) {
                if (data['detail'] != null) {
                    var no = 0;
                    for (let i = 0; i < data['detail'].length; i++) {
                        no++;
                        var newRow = $("<tr>");
                        var cols = "";
                        cols += `<td class="text-center mb-2"><spanx id="snum${i}">${no}</spanx></td>`;
                        cols += `<td>${data['detail'][i]['i_price_groupid']}
                        <input type="hidden" name="i_price_group_${data['detail'][i]['i_price_group']}" value="${data['detail'][i]['i_price_group']}">
                        </td>`;
                        cols += `<td>${data['detail'][i]['e_price_groupname']}</td>`;
                        cols += `<td><input type="text" class="formatrupiah form-control form-control-sm text-right hargax${i}" onkeyup="formatrupiahkeyup(this);" onkeydown="formatrupiahkeydown(this);" name="v_price${data['detail'][i]['i_price_group']}" value="0" readonly onblur=\"if(this.value==''){this.value='0';}\" onfocus=\"if(this.value=='0'){this.value='';}\"></td>`;
                        newRow.append(cols);
                        $("#tabledetail").append(newRow);
                    }
                } else {
                    swal('Non-existent data : (');
                }
            },
            error: function() {
                swal('500 internal server error : (');
            }
        });
    });


    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $('form').on('submit', function(e) { //bind event on form submit.
        let ada = false;
        $("#tabledetail tbody tr td .formatrupiah").each(function() {
            if ($(this).val() <= 0) {
                ada = true;
            }
        });
        if (ada) {
            swal('Harga harus lebih besar dari 0 !');
            return false;
        }
        e.preventDefault();
        var formData = new FormData(this);
        if (formData) {
            sweetadd(controller, formData);
        }
    });
});


 function roundUp(num, precision) {
    precision = Math.pow(10, precision)
    return Math.round(num * precision) / precision
  }
function harga00() {
    var x = parseFloat(formatulang($('#v_price').val()));
    var y = parseFloat(formatulang($('#v_margin').val()));

    var harga1 = (x / (1 - (y / 100)));
    var harga2 = (x / (1 - ((y + 5) / 100)));
    var harga3 = (x / (1 - ((y + 5 + 3) / 100)));
    var harga4 = (x / (1 - ((y + 5 + 3 + 9 + 5) / 100)));

    $(".hargax0").val(roundUp(harga1, -2));
    $(".hargax1").val(roundUp(harga2, -2));
    $(".hargax2").val(roundUp(harga3, -2));
    $(".hargax3").val(roundUp(harga4, -2));

    // $(".hargax0").val(Math.round(harga1));
    // $(".hargax1").val(Math.round(harga2));
    // $(".hargax2").val(Math.round(harga3));
    // $(".hargax3").val(Math.round(harga4));
}

function rumusharga() {
    var x = $("#v_price").val();
    var y = $("#v_margin").val();;

    var harga1 = (x / (1 - (y / 100)));
    var harga2 = (x / (1 - ((y + 5) / 100)));
    var harga3 = (x / (1 - ((y + 5 + 3) / 100)));
    var harga4 = (x / (1 - ((y + 5 + 3 + 9 + 5) / 100)));
    
    for (let i = 1; i <= $("#jml").val(); i++) {
        const element = array[i];
        
    }
    $("#v_harga1").val(harga1);
    // $("#v_harga2" + x).val(number_format(harga2, 2, ".", ","));
    // $("#v_harga3" + x).val(number_format(harga3, 2, ".", ","));
    // $("#v_harga4" + x).val(number_format(harga4, 2, ".", ","));


    // var jml = $("#jml").val();
    // var v_price = 0.0;
    // var disc = $("#disc").val();
    // var disc2 = $("#disc2").val();
    // var disc3 = $("#disc3").val();
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