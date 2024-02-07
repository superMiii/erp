$(document).ready(function() {
    // $("#export").click(function(e) {
    //     let fileName = 'Export_Harga';
    //     /* var Contents = $('#serverside').html(); */
    //     // window.open('data:application/vnd.ms-excel, ' + 'filename=exportData.excel;' + '<table>' + encodeURIComponent($('#serverside').html()) + '</table>');
    //     window.open('data:application/vnd.ms-excel, ' + '<table>' + encodeURIComponent($('#serverside').html()) + '</table>');
    // e.preventDefault();
    // });
    // function fnExcelReport() {
    // $("#export").click(function() {
    //     var tab_text = "<table border='2px'><tr bgcolor='#87AFC6'>";
    //     var textRange;
    //     var j = 0;
    //     tab = document.getElementById('serverside'); // id of table

    //     for (j = 0; j < tab.rows.length; j++) {
    //         tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
    //         //tab_text=tab_text+"</tr>";
    //     }

    //     tab_text = tab_text + "</table>";
    //     tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
    //     tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
    //     tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

    //     var ua = window.navigator.userAgent;
    //     var msie = ua.indexOf("MSIE ");

    //     if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer
    //     {
    //         txtArea1.document.open("txt/html", "replace");
    //         txtArea1.document.write(tab_text);
    //         txtArea1.document.close();
    //         txtArea1.focus();
    //         sa = txtArea1.document.execCommand("SaveAs", true, "PriceList.xls");
    //     } else //other browser not tested on IE 11
    //         sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));

    //     return (sa);
    // });
    
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

function exportexcel() {
    // var year = document.getElementById('year').value;
    // var i_area = document.getElementById('i_area').value;

    // if (year == '') {
    //     alert('Pilih Tanggal Terlebih Dahulu!!!');
    //     return false;
    // } else {
        var abc =  base_url + $("#path").val() + "/export";
        $("#href").attr("href", abc);
        return true;
    // }
}