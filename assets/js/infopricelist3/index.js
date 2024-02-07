$(document).ready(function() {
    pickdatetabel();
});


function exportexcel() {
        var abc =  base_url + $("#path").val() + "/export/";
        $("#href").attr("href", abc);
        return true;
}

