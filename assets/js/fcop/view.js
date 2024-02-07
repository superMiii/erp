$(document).ready(function() {

    get_data();
   
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
                    cols += `<td><input type="text" id="${i}" readonly class="form-control form-control-sm" value="${v['i_product_id']} - ${v['e_product_name']}"></td>`;
                    cols += `<td>
                    <input type="hidden" name="e_product_name[]" id="e_product_name${i}" readonly class="form-control form-control-sm" value="${v['e_product_name']}">
                    <input type="hidden" name="i_product_motif[]" id="i_product_motif${i}" readonly class="form-control form-control-sm" value="${v['i_product_motif']}">
                    <input type="text" id="e_product_motifname${i}" readonly class="form-control form-control-sm" value="${v['e_product_motifname']}">
                    </td>`;
                    cols += `<td><input type="text" readonly value="${v['n_forecast']}" name="n_quantity[]" id="n_quantity${i}" class="form-control form-control-sm" onkeypress="return bilanganasli(event);" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\'></td>`;
                    cols += `<td><input type="text" readonly value="${v['diterima']}" name="diterima[]" id="diterima${i}" class="form-control form-control-sm" onkeypress="return bilanganasli(event);" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\'></td>`;
                    cols += `<td><input type="text" readonly name="e_remarkitem[]" id="e_remarkitem${i}" class="form-control form-control-sm" value="${v['e_remark']}"></td>`;
                    newRow.append(cols);
                    $("#tabledetail").append(newRow);
                });
            }
        },
        error: function() {
            swal('500 internal server error : (');
        }
    });
}