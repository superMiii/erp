$(document).ready(function() {
    $(".select2").select2();
    pickdatetabel();
    var controller = $("#path").val() + "/serverside";
    var column = $("#serverside thead tr th").length;
    var parameter = {
        year: $("#year").val(),
        month: $("#month").val(),
        i_area: $("#i_area").val(),
    };
    var right = [3, 4, 5, 6, 7];
    // datatableinfoparams(controller, column, parameter, right);
    $("#i_area").select2({
        dropdownAutoWidth: true,
        width: "100%",
        /* containerCssClass: "select-sm", */
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
    });


	$("#export").click(function(e) {
        // var tableToExcel = (function () {

            var myBlob =  new Blob( [table.innerHTML] , {type:'application/vnd.ms-excel'});
            var url = window.URL.createObjectURL(myBlob);
            var a = document.createElement("a");
            document.body.appendChild(a);
            a.href = url;
            a.download = "export.xls";
            a.click();
          //adding some delay in removing the dynamically created link solved the problem in FireFox
            setTimeout(function() {window.URL.revokeObjectURL(url);},0);
    
    // });
    });

   
});

function comma(input){
    var num = input.toString();
    if(!isNaN(num)){
        if(num.indexOf('.') > -1){
            num = num.split('.');
            num[0] = num[0].toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,').split('').reverse().join('').replace(/^[\,]/,'');
            if(num[1].length > 2){
                while(num[1].length > 2){
                    num[1] = num[1].substring(0,num[1].length-1);
                }
            }
            input = num[0];
        }else{
            input = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,').split('').reverse().join('').replace(/^[\,]/,'')
        };
    }
    return input;
}

var totalJan = 0;
var totalFeb = 0;
var totalMar = 0;
var totalApr = 0;
var totalMei = 0;
var totalJun = 0;
var totalJul = 0;
var totalAgu = 0;
var totalSep = 0;
var totalOkt = 0;
var totalNov = 0;
var totalDes = 0;

$('.bln1').each(function(x){
    totalJan += parseFloat( $(this).data('value') );
})
$('#tfoot1').html('Rp. ' + comma(totalJan));


$('.bln2').each(function(x){
    totalFeb += parseFloat( $(this).data('value') );
})
$('#tfoot2').html('Rp. ' + comma(totalFeb));


$('.bln3').each(function(x){
    totalMar += parseFloat( $(this).data('value') );
})
$('#tfoot3').html('Rp. ' + comma(totalMar));


$('.bln4').each(function(x){
    totalApr += parseFloat( $(this).data('value') );
})
$('#tfoot4').html('Rp. ' + comma(totalApr));


$('.bln5').each(function(x){
    totalMei += parseFloat( $(this).data('value') );
})
$('#tfoot5').html('Rp. ' + comma(totalMei));


$('.bln6').each(function(x){
    totalJun += parseFloat( $(this).data('value') );
})
$('#tfoot6').html('Rp. ' + comma(totalJun));


$('.bln7').each(function(x){
    totalJul += parseFloat( $(this).data('value') );
})
$('#tfoot7').html('Rp. ' + comma(totalJul));


$('.bln8').each(function(x){
    totalAgu += parseFloat( $(this).data('value') );
})
$('#tfoot8').html('Rp. ' + comma(totalAgu));


$('.bln9').each(function(x){
    totalSep += parseFloat( $(this).data('value') );
})
$('#tfoot9').html('Rp. ' + comma(totalSep));


$('.bln10').each(function(x){
    totalOkt += parseFloat( $(this).data('value') );
})
$('#tfoot10').html('Rp. ' + comma(totalOkt));


$('.bln11').each(function(x){
    totalNov += parseFloat( $(this).data('value') );
})
$('#tfoot11').html('Rp. ' + comma(totalNov));


$('.bln12').each(function(x){
    totalDes += parseFloat( $(this).data('value') );
})
$('#tfoot12').html('Rp. ' + comma(totalDes));


function exportexcel() {
    var year = document.getElementById('year').value;
    var i_area = document.getElementById('i_area').value;

    if (year == '') {
        alert('Pilih Tanggal Terlebih Dahulu!!!');
        return false;
    } else {
        var abc =  base_url + $("#path").val() + "/export/" + year + "/" + i_area;
        $("#href").attr("href", abc);
        return true;
    }
}
