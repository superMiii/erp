$(document).ready(function() {
    
    pickdatetabel();
    var controller = $("#path").val() + "/serverside";
    var column = 6;
    var d_from_ = $("#d_from").val();
    var d_to_ = $("#d_to").val();
    var parameter = {
        dfrom: $("#dfrom").val(),
        dto: $("#dto").val(),
    };
    
    var right = [];
    datatableinfo2(controller, column, parameter, right, d_from_, d_to_ );
    $(".export").click(function() {
        $.ajax({
            beforeSend: function() {
                $(".app-content").block({
                    message: '<div class="spinner-grow text-primary"></div><div class="spinner-grow text-success"></div><div class="spinner-grow text-teal"></div><div class="spinner-grow text-info"></div><div class="spinner-grow text-warning"></div><div class="spinner-grow text-orange"></div><div class="spinner-grow text-danger"></div><div class="spinner-grow text-secondary"></div><div class="spinner-grow text-dark"></div><div class="spinner-grow text-muted"></div><br><h1 class="text-muted d-block">P l e a s e &nbsp;&nbsp; W a i t</h1>',
                    centerX: false,
                    centerY: false,
                    overlayCSS: {
                        backgroundColor: "#fff",
                        opacity: 0.8,
                        cursor: "wait",
                    },
                    css: {
                        border: 0,
                        padding: 0,
                        backgroundColor: "none",
                    },
                });
            },
            complete: function() {
                $('.app-content').unblock();
            },
            type: "post",
            data: parameter,
            cache: false,
            /* async: false, */
            url: base_url + $("#path").val() + "/export",
            dataType: "json",
            success: function(data) {
                $('.app-content').unblock();
                var $a = $("<a>");
                $a.attr("href", data.file);
                $("body").append($a);
                $a.attr("download", data.nama_file);
                $a[0].click();
                $a.remove();
            }
        });
    });
    
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $('.form-validation').on('submit', function(e) { //bind event on form submit.
        e.preventDefault();
        var formData = new FormData(this);
        if (formData) {
            sweetaddv3($("#path").val(), $("#d_from").val(), $("#d_to").val(), formData);
        }
    });



});

function exporttabel() {
    var tab_text = "<table border='2px'><tr bgcolor='#87AFC6'>";
        var textRange;
        var j = 0;
        tab = document.getElementById('serverside2'); // id of table

        for (j = 0; j < tab.rows.length; j++) {
            tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
            //tab_text=tab_text+"</tr>";
        }

        tab_text = tab_text + "</table>";
        tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
        tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
        tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");

        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer
        {
            txtArea1.document.open("txt/html", "replace");
            txtArea1.document.write(tab_text);
            txtArea1.document.close();
            txtArea1.focus();
            sa = txtArea1.document.execCommand("SaveAs", true, "Detail_SJP.xls");
        } else //other browser not tested on IE 11
            sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));

        return (sa);
}


function refreshview(link) {
    // window.location = link;
    window.location = link + '/index/' + $("#d_from").val() + '/' + $("#d_to").val() ;
}