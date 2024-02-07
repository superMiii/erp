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
                        cols += `<td><input type="text" class="formatrupiah form-control form-control-sm text-right" onkeyup="formatrupiahkeyup(this);" onkeydown="formatrupiahkeydown(this);" name="v_price${data['detail'][i]['i_price_group']}" value="${formatcemua(data['detail'][i]['v_price'])}" onblur=\"if(this.value==''){this.value='0';}\" onfocus=\"if(this.value=='0'){this.value='';}\"></td>`;
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
        // if (ada) {
        //     swal('Harga harus lebih besar dari 0 !');
        //     return false;
        // }
        e.preventDefault();
        var formData = new FormData(this);
        if (formData) {
            sweetadd(controller, formData);
        }
    });
});