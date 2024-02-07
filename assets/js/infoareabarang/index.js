$(document).ready(function() {
    pickdatetabel();
});


function exportexcel() {
    var dfrom = document.getElementById('dfrom').value;
    var dto = document.getElementById('dto').value;

    if (dfrom == '') {
        alert('Pilih Tanggal Terlebih Dahulu!!!');
        return false;
    } else {
        var abc =  base_url + $("#path").val() + "/export/" + dfrom + "/" + dto;
        $("#href").attr("href", abc);
        return true;
    }
}

