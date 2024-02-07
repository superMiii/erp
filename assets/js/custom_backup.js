function set_company(id, name, ppn) {
    $.ajax({
        type: "POST",
        data: {
            id: id,
            name: name,
            ppn: ppn,
        },
        url: base_url + "auth/set_company",
        dataType: "html",
        success: function(data) {
            window.location = base_url+current_link;
        },
        error: function() {
            alert("Error :)");
        },
    });
}

function set_department(id, name) {
    $.ajax({
        type: "POST",
        data: {
            id: id,
            name: name,
        },
        url: base_url + "auth/set_department",
        dataType: "html",
        success: function(data) {
            window.location = base_url;
        },
        error: function() {
            alert("Error :)");
        },
    });
}

function set_level(id, name) {
    $.ajax({
        type: "POST",
        data: {
            id: id,
            name: name,
        },
        url: base_url + "auth/set_level",
        dataType: "html",
        success: function(data) {
            window.location = base_url;
        },
        error: function() {
            alert("Error :)");
        },
    });
}


function set_activemenu(idmenu_1, idmenu_2, idmenu_3, folder) {
    $.ajax({
        type: "POST",
        data: {
            idmenu_1: idmenu_1,
            idmenu_2: idmenu_2,
            idmenu_3: idmenu_3
        },
        url: base_url + "auth/set_activemenu",
        dataType: "html",
        success: function(data) {
            //alert(idmenu_1 + "|" + idmenu_2 + "|" + idmenu_3);
            window.location = base_url+folder;
        },
        error: function() {
            alert("Error :)");
        },
    });
}

/** Datatabel Tanpa Button Tambah */
function datatable(link, column) {
    var t = $("#serverside").DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: base_url + link,
            type: "post",
            error: function(data, err) {
                $(".serverside-error").html("");
                $("#serverside tbody").empty();
                $("#serverside").append(
                    '<tr><td class="text-center" colspan="' +
                    column +
                    '">No data available in table</td></tr>'
                );
                $("#serverside_processing").css("display", "none");
            },
        },
        jQueryUI: false,
        scrollX: true,
        ScrollCollapse: false,
        autoWidth: false,
        pageLength: 10,
        order: [
            [1, "asc"]
        ],
        columnDefs: [{
                targets: [0, column - 1],
                width: "5%",
                orderable: false,
            },
            {
                targets: [0],
                width: "3%",
                className: "text-right",
            },
        ],
        pagingType: "full_numbers",
        dom: "Bfrtip",
        className: "my-1",
        lengthMenu: [
            [10, 25, 50, -1],
            ["10 rows", "25 rows", "50 rows", "Show all"],
        ],
        buttons: ["pageLength"],
        language: {
            infoPostFix: "",
            search: "<span>Search:</span> _INPUT_",
            searchPlaceholder: "Type to filter...",
            lengthMenu: "<span>Show:</span> _MENU_",
            url: "",
            paginate: {
                first: "First",
                last: "Last",
                next: $("html").attr("dir") == "rtl" ? "&larr;" : "&rarr;",
                previous: $("html").attr("dir") == "rtl" ? "&rarr;" : "&larr;",
            },
        },
        bStateSave: true,
        fnStateSave: function(oSettings, oData) {
            localStorage.setItem("offersDataTables", JSON.stringify(oData));
        },
        fnStateLoad: function(oSettings) {
            return JSON.parse(localStorage.getItem("offersDataTables"));
        },
    });
    t.on("draw.dt", function() {
        var info = t.page.info();
        t.column(0, {
                search: "applied",
                order: "applied",
                page: "applied",
            })
            .nodes()
            .each(function(cell, i) {
                cell.innerHTML = i + 1 + info.start;
            });
    });
    $("div.dataTables_filter input", t.table().container()).focus();
}

/** Datatable Plus Button Tambah */
function datatableadd(link, column, linkadd, color) {
    var t = $("#serverside").DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: base_url + link,
            type: "post",
            error: function(data, err) {
                $(".serverside-error").html("");
                $("#serverside tbody").empty();
                $("#serverside").append(
                    '<tr><td class="text-center" colspan="' +
                    column +
                    '">No data available in table</td></tr>'
                );
                $("#serverside_processing").css("display", "none");
            },
        },
        jQueryUI: false,
        sScrollX: "100%",
        bScrollCollapse: false,
        //ScrollCollapse: false,
        autoWidth: false,
        pageLength: 10,
        order: [
            [1, "asc"]
        ],
        columnDefs: [{
                targets: [0, column - 1],
                width: "5%",
                orderable: false,
            },
            {
                targets: [0],
                width: "3%",
                className: "text-right",
            },
        ],
        pagingType: "full_numbers",
        dom: "Bfrtip",
        className: "my-1",
        lengthMenu: [
            [10, 25, 50, -1],
            ["10 rows", "25 rows", "50 rows", "Show all"],
        ],
        buttons: [
            "pageLength",
            {
                text: '<i class="ft-plus-circle"></i> Tambah',
                className: "ml-1",
                action: function(e, dt, node, config) {
                    window.location.href = base_url + linkadd;
                },
            },
        ],
        language: {
            infoPostFix: "",
            search: "<span>Search:</span> _INPUT_",
            searchPlaceholder: "Type to filter...",
            lengthMenu: "<span>Show:</span> _MENU_",
            url: "",
            paginate: {
                first: "First",
                last: "Last",
                next: $("html").attr("dir") == "rtl" ? "&larr;" : "&rarr;",
                previous: $("html").attr("dir") == "rtl" ? "&rarr;" : "&larr;",
            },
        },
        bStateSave: true,
        fnStateSave: function(oSettings, oData) {
            localStorage.setItem("offersDataTables", JSON.stringify(oData));
        },
        fnStateLoad: function(oSettings) {
            return JSON.parse(localStorage.getItem("offersDataTables"));
        },
    });
    t.on("draw.dt", function() {
        var info = t.page.info();
        t.column(0, {
                search: "applied",
                order: "applied",
                page: "applied",
            })
            .nodes()
            .each(function(cell, i) {
                cell.innerHTML = i + 1 + info.start;
            });
    });
    $("div.dataTables_filter input", t.table().container()).focus();

    //$('.dataTables_scrollHeadInner').attr('style', 'width: !important');
}

/** SweetAlert Add Data */
function sweetadd(link, formData) {
    swal({
        title: "Simpan Data Ini?",
        text: "Data akan disimpan!",
        icon: "info",
        showCancelButton: true,
        buttons: {
            confirm: {
                text: "Simpan",
                value: true,
                visible: true,
                className: "btn-success",
                closeModal: false,
            },
            cancel: {
                text: "Batal",
                value: null,
                visible: true,
                className: "btn-secondary",
                closeModal: false,
            },
        },
    }).then((isConfirm) => {
        if (isConfirm) {
            $.ajax({
                type: "POST",
                enctype: "multipart/form-data",
                data: formData,
                url: base_url + link + "/save",
                dataType: "json",
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function() {
                    $(".app-content").block({
                        message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>',
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
                success: function(data) {
                    if (data.sukses == true && data.ada == false) {
                        swal("Sukses!", "Data Berhasil Disimpan :)", "success").then(
                            function(result) {
                                window.location = base_url + link;
                            }
                        );
                    } else if (data.sukses == false && data.ada == true) {
                        swal("Maaf :(", "Data Tersebut Sudah Ada :(", "error");
                    } else {
                        swal("Maaf :(", "Data Gagal Disimpan :(", "error");
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    swal("Maaf", "Data Gagal Disimpan :(", "error");
                    $(".app-content").unblock();
                },
            });
        } else {
            swal("Batal", "Data batal disimpan :)", "error");
        }
    });
}

/** SweetAlert Edit Data */
function sweetedit(link, formData) {
    swal({
        title: "Update Data Ini?",
        text: "Data akan diupdate!",
        icon: "info",
        showCancelButton: true,
        buttons: {
            confirm: {
                text: "Update",
                value: true,
                visible: true,
                className: "btn-success",
                closeModal: false,
            },
            cancel: {
                text: "Batal",
                value: null,
                visible: true,
                className: "btn-secondary",
                closeModal: false,
            },
        },
    }).then((isConfirm) => {
        if (isConfirm) {
            $.ajax({
                type: "POST",
                enctype: "multipart/form-data",
                data: formData,
                url: base_url + link + "/update",
                dataType: "json",
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function() {
                    $(".app-content").block({
                        message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>',
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
                            "z-index": 100,
                        },
                    });
                },
                success: function(data) {
                    if (data.sukses == true && data.ada == false) {
                        swal("Sukses!", "Data Berhasil Diupdate :)", "success").then(
                            function(result) {
                                window.location = base_url + link;
                            }
                        );
                    } else if (data.sukses == false && data.ada == true) {
                        swal("Maaf :(", "Data Tersebut Sudah Ada :(", "error");
                    } else {
                        swal("Maaf :(", "Data Gagal Diupdate :(", "error");
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    swal("Maaf", "Data Gagal Diupdate :(", "error");
                    $(".app-content").unblock();
                },
            });
        } else {
            swal("Batal", "Data batal diupdate :)", "error");
        }
    });
}

/** Update Status */
function changestatus(link, id) {
    $.ajax({
        type: "POST",
        data: {
            id: id,
        },
        url: base_url + link + "/changestatus",
        dataType: "json",
        beforeSend: function() {
            $(".app-content").block({
                message: '<img src="' +
                    base_url +
                    'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>',
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
        success: function(data) {
            if (data.sukses == true) {
                swal("Success!", "Data update successfully :)", "success").then(
                    function(result) {
                        window.location = base_url + link;
                    }
                );
            } else {
                swal("Sorry :(", "Data failed to update :(", "error");
            }
            $(".app-content").unblock();
        },
        error: function() {
            swal("Sorry", "Data failed to update :(", "error");
            $(".app-content").unblock();
        },
    });
}


/** SweetAlert Delete Data */
function sweetdelete(link, id) {
    swal({
        title: "Hapus Data Ini?",
        text: "Data akan dihapus permanen!",
        icon: "error",
        showCancelButton: true,
        buttons: {
            confirm: {
                text: "Hapus",
                value: true,
                visible: true,
                className: "btn-success",
                closeModal: false,
            },
            cancel: {
                text: "Batal",
                value: null,
                visible: true,
                className: "btn-secondary",
                closeModal: false,
            },
        },
    }).then((isConfirm) => {
        if (isConfirm) {
            $.ajax({
                type: "POST",
                data: {
                    id: id,
                },
                url: base_url + link + "/delete",
                dataType: "json",
                beforeSend: function() {
                    $(".app-content").block({
                        message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>',
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
                success: function(data) {
                    if (data.sukses == true) {
                        swal("Success!", "Data Berhasil Dihapus :)", "success").then(
                            function(result) {
                                window.location = base_url + link;
                            }
                        );
                    } else {
                        swal("Maaf :(", "Data Gagal Dihapus :(", "error");
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    swal("Maaf", "Data Gagal Dihapus :(", "error");
                    $(".app-content").unblock();
                },
            });
        } else {
            swal("Batal", "Data Batal Dihapus :)", "error");
        }
    });
}