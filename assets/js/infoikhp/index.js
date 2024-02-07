$(document).ready(function() {
    pickdatetabel();
    var controller = $("#path").val() + "/serverside";
    var column = $("#serverside thead tr th").length;
    var parameter = {
        dfrom: $("#dfrom").val(),
        dto: $("#dto").val(),
        i_area: $("#i_area").val(),
    };
    var right = [7, 8, 9, 10];
    // datatableinfoparams(controller, column, parameter, right);
    $("#i_area").select2({
        dropdownAutoWidth: true,
        width: "100%",
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
    $(".ext").click(function() {
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
});




function exportexcel() {
    var dfrom = document.getElementById('dfrom').value;
    var dto = document.getElementById('dto').value;
    var i_area = document.getElementById('i_area').value;

    if (dfrom == '') {
        alert('Pilih Tanggal Terlebih Dahulu!!!');
        return false;
    } else {
        var abc =  base_url + $("#path").val() + "/export/" + dfrom + "/" + dto + "/" + i_area ;
        $("#href").attr("href", abc);
        return true;
    }
}

function prt() {
    var dfrom = document.getElementById('dfrom').value;
    var dto = document.getElementById('dto').value;
    var i_area = document.getElementById('i_area').value;

    if (dfrom == '') {
        alert('Pilih Tanggal Terlebih Dahulu!!!');
        return false;
    } else {
        var abc =  base_url + $("#path").val() + "/print/" + dfrom + "/" + dto + "/" + i_area ;
        $("#href").attr("href", abc);
        return true;
    }
}