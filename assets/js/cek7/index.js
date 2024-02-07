$(document).ready(function() {
    $(".select2").select2();
    pickdatetabel();
    var controller = $("#path").val() + "/serverside";
    var column = $("#serverside thead tr th").length;
    var parameter = {
        year: $("#year").val(),
        month: $("#month").val(),
    };
    var right = [1,4,7];
    datatableinfoparams(controller, column, parameter, right);
    var parameter2 = {
        year: $("#year").val(),
        month: $("#month").val(),
    };   


    $(".closing").click(function() {
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
            data: parameter2,
            cache: false,
            /* async: false, */
            url: base_url + $("#path").val() + "/closing",
            dataType: "json",
            success: function(data) {
                $('.app-content').unblock();
                if (data.sukses == true) {
                    Swal.fire({
                        type: "success",
                        title: "Berhasil :)",
                        text: "Berhasil closing periode " + data.periode + " ^_^",
                        /* animation: !1,
                        customClass: "animated flipInX", */
                        confirmButtonClass: "btn btn-success",
                    }).then(
                        function(result) {
                            window.location = base_url + $("#path").val();
                        }
                    );
                } else {
                    Swal.fire({
                        type: "error",
                        title: "Maaf :(",
                        text: "Gagal closing periode " + data.periode + " ^C^",
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                }
            }
        });
    });


    $(".miru").click(function() {
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
            data: parameter2,
            cache: false,
            /* async: false, */
            url: base_url + $("#path").val() + "/closing2",
            dataType: "json",
            success: function(data) {
                $('.app-content').unblock();
                if (data.sukses == true) {
                    Swal.fire({
                        type: "success",
                        title: "Berhasil :)",
                        text: "Berhasil Perbarui Saldo Awal " + data.periode + " ^_^",
                        /* animation: !1,
                        customClass: "animated flipInX", */
                        confirmButtonClass: "btn btn-success",
                    }).then(
                        function(result) {
                            window.location = base_url + $("#path").val();
                        }
                    );
                } else {
                    Swal.fire({
                        type: "error",
                        title: "Maaf :(",
                        text: "Gagal Perbarui Saldo Awal " + data.periode + " ^C^",
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                }
            }
        });
    });
    

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
});