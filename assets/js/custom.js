function set_company(id, name, ppn, n_ppn, f_plus_meterai, v_meterai, v_meterai_limit, e_color) {
    $.ajax({
        type: "POST",
        data: {
            id: id,
            name: name,
            ppn: ppn,
            n_ppn: n_ppn,
            f_plus_meterai: f_plus_meterai,
            v_meterai: v_meterai,
            v_meterai_limit: v_meterai_limit,
            e_color: e_color,
        },
        url: base_url + "auth/set_company",
        dataType: "html",
        success: function(data) {
            window.location = base_url + current_link;
            localStorage.clear();
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
            idmenu_3: idmenu_3,
            folder: folder,
        },
        url: base_url + "auth/set_activemenu",
        dataType: "html",
        success: function(data) {
            //alert(idmenu_1 + "|" + idmenu_2 + "|" + idmenu_3);
            window.location = base_url + folder;
            /* $('#serverside').DataTable().clear().draw(); */
            localStorage.clear();
        },
        error: function() {
            alert("Error :)");
        },
    });
}

function set_language(language) {
    $.ajax({
        type: "POST",
        data: {
            language: language
        },
        url: base_url + "auth/switch_language",
        dataType: "html",
        success: function(data) {
            window.location = base_url + current_link;
        },
        error: function() {
            alert("Error :)");
        },
    });
}

function set_collapse(menucolex) {
    $.ajax({
        type: "POST",
        data: {
            menucolex: menucolex
        },
        url: base_url + "auth/set_collapse",
        dataType: "html",
        success: function(data) {
            window.location = base_url + current_link;
        },
        error: function() {
            alert("Error :)");
        },
    });
}

if (lang == 'indonesia') {
    var labeladd = 'Tambah';
    var labelexport = 'Ekspor';
    var labelupload = 'Unggah';
    var labelsearch = 'Cari';
    var labeltype = 'Ketik untuk Mencari..';
    var labelfirst = 'Pertama';
    var labellast = 'Terakhir';
    var labelnodata = 'Tidak ada data di tabel ini';
    var labelshow = 'Lihat';
    var labelinfo = 'Menampilkan _START_ sampai _END_ dari _TOTAL_ entri';
    var labelfilter = "(disaring dari _MAX_ total entri)";

    var label_sw_title = "Simpan Data Ini ?";
    var label_sw_text = "Data akan di simpan!";
    var label_sw_confirm = "Simpan";
    var label_sw_ubah = "Ubah";
    var label_sw_realisasi = "Realisasi";
    var label_sw_cancel = "Batal";
    var label_sw_sukses = "Sukses";
    var label_sw_maaf = "Maaf";
    var label_sw_simpan = "Data Berhasil Di simpan";
    var label_sw_duplikat = "Data Tersebut Sudah Ada";
    var label_sw_gagal = "Data Gagal Di simpan";
    var label_sw_batal = "Data Batal Di simpan";
    var label_sw_titleubah = "Ubah Data Ini ?";
    var label_sw_textubah = "Data akan di ubah !";
    var label_sw_simpanubah = "Data Berhasil Di ubah";
    var label_sw_gagalubah = "Data Gagal Di ubah";
    var label_sw_batalubah = "Data Batal Di ubah";
    var label_sw_titlerealisasi = "Realisasi Data Ini ?";
    var label_sw_textrealisasi = "Data akan di Realisasi !";
    var label_sw_simpanrealisasi = "Data Berhasil Di Realisasi";
    var label_sw_gagalrealisasi = "Data Gagal Di Realisasi";
    var label_sw_batalrealisasi = "Data Batal Di Realisasi";

    var label_sw_terima = "Terima";
    var label_sw_titleterima = "Terima Data Ini ?";
    var label_sw_textterima = "Data akan di Terima !";
    var label_sw_gagalterima = "Data Gagal Di Terima";
    var label_sw_batalterima = "Data Batal Di Terima";

    var label_sw_del_title = "Hapus Data Ini?";
    var label_sw_del_text = "Data akan dihapus permanen!";
    var label_sw_del = "Hapus";

    var label_sw_del_success = "Data Berhasil Dihapus";
    var label_sw_del_failed = "Data Gagal Dihapus";
    var label_sw_del_cancel = "Data Batal Dihapus";

    var label_sw_app_title = "Setujui Data Ini?";
    var label_sw_app_text = "Data akan disetujui!";
    var label_sw_app = "Menyetujui";

    var label_sw_app_success = "Data Berhasil Disetujui";
    var label_sw_app_failed = "Data Gagal Disetujui";
    var label_sw_app_cancel = "Data Batal Disetujui";

    var label_sw_notapp_title = "Tolak Data Ini?";
    var label_sw_notapp_text = "Data akan ditolak!";
    var label_sw_notapp = "Tidak Menyetujui";

    var label_sw_notapp_success = "Data Berhasil Ditolak";
    var label_sw_notapp_failed = "Data Gagal Ditolak";
    var label_sw_notapp_cancel = "Data Batal Ditolak";

} else {
    var labeladd = 'Add';
    var labelexport = 'Export';
    var labelupload = 'Upload';
    var labelsearch = 'Search';
    var labeltype = 'Type to Search..';
    var labelfirst = 'First';
    var labellast = 'Last';
    var labelnodata = 'No data available in table';
    var labelshow = 'Show';
    var labelinfo = 'Showing _START_ to _END_ of _TOTAL_ entries';
    var labelfilter = "(filtered from _MAX_ total entries)";

    var label_sw_title = "Save This Data?";
    var label_sw_text = "Data will be saved!";
    var label_sw_confirm = "Save";
    var label_sw_ubah = "Update";
    var label_sw_cancel = "Cancel";
    var label_sw_sukses = "Success";
    var label_sw_maaf = "Sorry";
    var label_sw_simpan = "Data Saved Successfully";
    var label_sw_duplikat = "The data already exists";
    var label_sw_gagal = "Data saved failed";
    var label_sw_batal = "Data Cancel Saved";
    var label_sw_titleubah = "Update This Data?";
    var label_sw_textubah = "Data will be Update !";
    var label_sw_simpanubah = "Data Update Successfully";
    var label_sw_gagalubah = "Data Update Failed";
    var label_sw_batalubah = "Data Cancel Update";

    var label_sw_terima = "Receive";
    var label_sw_titleterima = "Receive This Data ?";
    var label_sw_textterima = "Data will be Receive !";
    var label_sw_gagalterima = "Data Receive Failed";
    var label_sw_batalterima = "Data Cancel Receive";

    var label_sw_del_title = "Delete This Data?";
    var label_sw_del_text = "The data will be permanently deleted!";
    var label_sw_del = "Delete";

    var label_sw_del_success = "Data Deleted Successfully";
    var label_sw_del_failed = "Data Delete Failed";
    var label_sw_del_cancel = "Data Cancel Deleted";

    var label_sw_app_title = "Setujui Data Ini?";
    var label_sw_app_text = "Data akan disetujui!";
    var label_sw_app = "Menyetujui";

    var label_sw_app_success = "Data Berhasil Disetujui";
    var label_sw_app_failed = "Data Gagal Disetujui";
    var label_sw_app_cancel = "Data Batal Disetujui";

    var label_sw_notapp_title = "Tolak Data Ini?";
    var label_sw_notapp_text = "Data akan ditolak!";
    var label_sw_notapp = "Tidak Menyetujui";

    var label_sw_notapp_success = "Data Berhasil Ditolak";
    var label_sw_notapp_failed = "Data Gagal Ditolak";
    var label_sw_notapp_cancel = "Data Batal Ditolak";
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
                    '">' + labelnodata + '</td></tr>'
                );
                $("#serverside_processing").css("display", "none");
            },
        },
        jQueryUI: false,
        /* scrollX: true,
        ScrollCollapse: false,
        autoWidth: false, */
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
            search: "<span>" + labelsearch + " :</span> _INPUT_",
            searchPlaceholder: labeltype,
            info: labelinfo,
            infoFiltered: labelfilter,
            lengthMenu: "<span>" + labelshow + " : </span> _MENU_",
            url: "",
            paginate: {
                first: labelfirst,
                last: labellast,
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
                    '">"' + labelnodata + '"</td></tr>'
                );
                $("#serverside_processing").css("display", "none");
            },
        },
        jQueryUI: false,
        // sScrollX: "100%",
        // bScrollCollapse: false,
        /* scrollX: true, */
        //ScrollCollapse: false,
        /* autoWidth: false, */
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
                text: '<i class="feather icon-plus-circle"></i> ' + labeladd,
                className: "btn-min-width ml-1",
                action: function(e, dt, node, config) {
                    window.location.href = base_url + linkadd;
                },
            },
        ],
        language: {
            infoPostFix: "",
            search: "<span>" + labelsearch + " :</span> _INPUT_",
            searchPlaceholder: labeltype,
            info: labelinfo,
            infoFiltered: labelfilter,
            lengthMenu: "<span>" + labelshow + " : </span> _MENU_",
            url: "",
            paginate: {
                first: labelfirst,
                last: labellast,
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

/** Datatable Parameter */
function datatableparams(link, column, params, col = 999) {
    if (col == 999) {
        col = [0];
    } else {
        col = col;
    }
    var t = $("#serverside").DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: base_url + link,
            data: params,
            type: "post",
            error: function(data, err) {
                $(".serverside-error").html("");
                $("#serverside tbody").empty();
                $("#serverside").append(
                    '<tr><td class="text-center" colspan="' +
                    column +
                    '">"' + labelnodata + '"</td></tr>'
                );
                $("#serverside_processing").css("display", "none");
            },
        },
        jQueryUI: false,
        // sScrollX: "100%",
        // bScrollCollapse: false,
        // scrollX: true,
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
            {
                targets: col,
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
        ],
        language: {
            infoPostFix: "",
            search: "<span>" + labelsearch + " :</span> _INPUT_",
            searchPlaceholder: labeltype,
            info: labelinfo,
            infoFiltered: labelfilter,
            lengthMenu: "<span>" + labelshow + " : </span> _MENU_",
            url: "",
            paginate: {
                first: labelfirst,
                last: labellast,
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

function datatablelink(link, column, params, col = 999, d_from_, d_to_) {
    if (col == 999) {
        col = [0];
    } else {
        col = col;
    }
    var t = $("#serverside").DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: base_url + link + '/' + d_from_ + '/' + d_to_,
            data: params,
            type: "post",
            error: function(data, err) {
                $(".serverside-error").html("");
                $("#serverside tbody").empty();
                $("#serverside").append(
                    '<tr><td class="text-center" colspan="' +
                    column +
                    '">"' + labelnodata + '"</td></tr>'
                );
                $("#serverside_processing").css("display", "none");
            },
        },
        jQueryUI: false,
        // sScrollX: "100%",
        // bScrollCollapse: false,
        // scrollX: true,
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
            {
                targets: col,
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
        ],
        language: {
            infoPostFix: "",
            search: "<span>" + labelsearch + " :</span> _INPUT_",
            searchPlaceholder: labeltype,
            info: labelinfo,
            infoFiltered: labelfilter,
            lengthMenu: "<span>" + labelshow + " : </span> _MENU_",
            url: "",
            paginate: {
                first: labelfirst,
                last: labellast,
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


function datatable3(link, column, params, col = 999, d_from_, d_to_,harea) {
    if (col == 999) {
        col = [0];
    } else {
        col = col;
    }
    var t = $("#serverside").DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: base_url + link + '/' + d_from_ + '/' + d_to_ + '/' + harea,
            data: params,
            type: "post",
            error: function(data, err) {
                $(".serverside-error").html("");
                $("#serverside tbody").empty();
                $("#serverside").append(
                    '<tr><td class="text-center" colspan="' +
                    column +
                    '">"' + labelnodata + '"</td></tr>'
                );
                $("#serverside_processing").css("display", "none");
            },
        },
        jQueryUI: false,
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
            {
                targets: col,
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
        ],
        language: {
            infoPostFix: "",
            search: "<span>" + labelsearch + " :</span> _INPUT_",
            searchPlaceholder: labeltype,
            info: labelinfo,
            infoFiltered: labelfilter,
            lengthMenu: "<span>" + labelshow + " : </span> _MENU_",
            url: "",
            paginate: {
                first: labelfirst,
                last: labellast,
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

function datatable3sup(link, column, params, col = 999, d_from_, d_to_,hsup) {
    if (col == 999) {
        col = [0];
    } else {
        col = col;
    }
    var t = $("#serverside").DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: base_url + link + '/' + d_from_ + '/' + d_to_ + '/' + hsup,
            data: params,
            type: "post",
            error: function(data, err) {
                $(".serverside-error").html("");
                $("#serverside tbody").empty();
                $("#serverside").append(
                    '<tr><td class="text-center" colspan="' +
                    column +
                    '">"' + labelnodata + '"</td></tr>'
                );
                $("#serverside_processing").css("display", "none");
            },
        },
        jQueryUI: false,
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
            {
                targets: col,
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
        ],
        language: {
            infoPostFix: "",
            search: "<span>" + labelsearch + " :</span> _INPUT_",
            searchPlaceholder: labeltype,
            info: labelinfo,
            infoFiltered: labelfilter,
            lengthMenu: "<span>" + labelshow + " : </span> _MENU_",
            url: "",
            paginate: {
                first: labelfirst,
                last: labellast,
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

function datatable5(link, column, params, col = 999, d_from_, d_to_, htype, harea, hcoa) {
    if (col == 999) {
        col = [0];
    } else {
        col = col;
    }
    var t = $("#serverside").DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: base_url + link + '/' + d_from_ + '/' + d_to_ + '/' + htype + '/' + harea + '/' + hcoa,
            data: params,
            type: "post",
            error: function(data, err) {
                $(".serverside-error").html("");
                $("#serverside tbody").empty();
                $("#serverside").append(
                    '<tr><td class="text-center" colspan="' +
                    column +
                    '">"' + labelnodata + '"</td></tr>'
                );
                $("#serverside_processing").css("display", "none");
            },
        },
        jQueryUI: false,
        // sScrollX: "100%",
        // bScrollCollapse: false,
        // scrollX: true,
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
            {
                targets: col,
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
        ],
        language: {
            infoPostFix: "",
            search: "<span>" + labelsearch + " :</span> _INPUT_",
            searchPlaceholder: labeltype,
            info: labelinfo,
            infoFiltered: labelfilter,
            lengthMenu: "<span>" + labelshow + " : </span> _MENU_",
            url: "",
            paginate: {
                first: labelfirst,
                last: labellast,
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

/** Datatable Plus Button Tambah */
function datatableaddparams(link, column, linkadd, params, col = 999) {
    if (col == 999) {
        col = [0];
    } else {
        col = col;
    }
    var t = $("#serverside").DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: base_url + link,
            data: params,
            type: "post",
            error: function(data, err) {
                $(".serverside-error").html("");
                $("#serverside tbody").empty();
                $("#serverside").append(
                    '<tr><td class="text-center" colspan="' +
                    column +
                    '">"' + labelnodata + '"</td></tr>'
                );
                $("#serverside_processing").css("display", "none");
            },
        },
        jQueryUI: false,
        /* sScrollX: "100%", */
        /* scrollX: true, */
        /* ScrollCollapse: false,
        autoWidth: false,
        fixedColumns: false, */
        pageLength: 10,
        order: [
            [1, "desc"]
        ],
        columnDefs: [{
                targets: [0, column - 1],
                width: "3%",
                orderable: false,
            },
            {
                targets: [0],
                width: "3%",
                className: "text-right",
            },
            {
                targets: col,
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
                text: '<i class="feather icon-plus-circle"></i> ' + labeladd,
                className: "btn-min-width ml-1",
                action: function(e, dt, node, config) {
                    window.location.href = base_url + linkadd;
                },
            },
        ],
        language: {
            infoPostFix: "",
            search: "<span>" + labelsearch + " :</span> _INPUT_",
            searchPlaceholder: labeltype,
            info: labelinfo,
            infoFiltered: labelfilter,
            lengthMenu: "<span>" + labelshow + " : </span> _MENU_",
            url: "",
            paginate: {
                first: labelfirst,
                last: labellast,
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

function datatableaddlink(link, column, linkadd, params, col = 999,d_from_,d_to_) {
    if (col == 999) {
        col = [0];
    } else {
        col = col;
    }
    var t = $("#serverside").DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: base_url + link,
            data: params,
            type: "post",
            error: function(data, err) {
                $(".serverside-error").html("");
                $("#serverside tbody").empty();
                $("#serverside").append(
                    '<tr><td class="text-center" colspan="' +
                    column +
                    '">"' + labelnodata + '"</td></tr>'
                );
                $("#serverside_processing").css("display", "none");
            },
        },
        jQueryUI: false,
        /* sScrollX: "100%", */
        /* scrollX: true, */
        /* ScrollCollapse: false,
        autoWidth: false,
        fixedColumns: false, */
        pageLength: 10,
        order: [
            [1, "desc"]
        ],
        columnDefs: [{
                targets: [0, column - 1],
                width: "3%",
                orderable: false,
            },
            {
                targets: [0],
                width: "3%",
                className: "text-right",
            },
            {
                targets: col,
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
                text: '<i class="feather icon-plus-circle"></i> ' + labeladd,
                className: "btn-min-width ml-1",
                action: function(e, dt, node, config) {
                    window.location.href = base_url + linkadd + '/' + d_from_ + '/' + d_to_;
                },
            },
        ],
        language: {
            infoPostFix: "",
            search: "<span>" + labelsearch + " :</span> _INPUT_",
            searchPlaceholder: labeltype,
            info: labelinfo,
            infoFiltered: labelfilter,
            lengthMenu: "<span>" + labelshow + " : </span> _MENU_",
            url: "",
            paginate: {
                first: labelfirst,
                last: labellast,
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


function datatableadd3(link, column, linkadd, params, col = 999,d_from_,d_to_,harea) {
    if (col == 999) {
        col = [0];
    } else {
        col = col;
    }
    var t = $("#serverside").DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: base_url + link,
            data: params,
            type: "post",
            error: function(data, err) {
                $(".serverside-error").html("");
                $("#serverside tbody").empty();
                $("#serverside").append(
                    '<tr><td class="text-center" colspan="' +
                    column +
                    '">"' + labelnodata + '"</td></tr>'
                );
                $("#serverside_processing").css("display", "none");
            },
        },
        jQueryUI: false,
        /* sScrollX: "100%", */
        /* scrollX: true, */
        /* ScrollCollapse: false,
        autoWidth: false,
        fixedColumns: false, */
        pageLength: 10,
        order: [
            [1, "desc"]
        ],
        columnDefs: [{
                targets: [0, column - 1],
                width: "3%",
                orderable: false,
            },
            {
                targets: [0],
                width: "3%",
                className: "text-right",
            },
            {
                targets: col,
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
                text: '<i class="feather icon-plus-circle"></i> ' + labeladd,
                className: "btn-min-width ml-1",
                action: function(e, dt, node, config) {
                    window.location.href = base_url + linkadd + '/' + d_from_ + '/' + d_to_ + '/' + harea;
                },
            },
        ],
        language: {
            infoPostFix: "",
            search: "<span>" + labelsearch + " :</span> _INPUT_",
            searchPlaceholder: labeltype,
            info: labelinfo,
            infoFiltered: labelfilter,
            lengthMenu: "<span>" + labelshow + " : </span> _MENU_",
            url: "",
            paginate: {
                first: labelfirst,
                last: labellast,
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

function datatableadd3sup(link, column, linkadd, params, col = 999,d_from_,d_to_,hsup) {
    if (col == 999) {
        col = [0];
    } else {
        col = col;
    }
    var t = $("#serverside").DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: base_url + link,
            data: params,
            type: "post",
            error: function(data, err) {
                $(".serverside-error").html("");
                $("#serverside tbody").empty();
                $("#serverside").append(
                    '<tr><td class="text-center" colspan="' +
                    column +
                    '">"' + labelnodata + '"</td></tr>'
                );
                $("#serverside_processing").css("display", "none");
            },
        },
        jQueryUI: false,
        /* sScrollX: "100%", */
        /* scrollX: true, */
        /* ScrollCollapse: false,
        autoWidth: false,
        fixedColumns: false, */
        pageLength: 10,
        order: [
            [1, "desc"]
        ],
        columnDefs: [{
                targets: [0, column - 1],
                width: "3%",
                orderable: false,
            },
            {
                targets: [0],
                width: "3%",
                className: "text-right",
            },
            {
                targets: col,
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
                text: '<i class="feather icon-plus-circle"></i> ' + labeladd,
                className: "btn-min-width ml-1",
                action: function(e, dt, node, config) {
                    window.location.href = base_url + linkadd + '/' + d_from_ + '/' + d_to_ + '/' + hsup;
                },
            },
        ],
        language: {
            infoPostFix: "",
            search: "<span>" + labelsearch + " :</span> _INPUT_",
            searchPlaceholder: labeltype,
            info: labelinfo,
            infoFiltered: labelfilter,
            lengthMenu: "<span>" + labelshow + " : </span> _MENU_",
            url: "",
            paginate: {
                first: labelfirst,
                last: labellast,
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

function datatableadd5(link, column, linkadd, params, col = 999,d_from_,d_to_,htype,harea,hcoa) {
    if (col == 999) {
        col = [0];
    } else {
        col = col;
    }
    var t = $("#serverside").DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: base_url + link,
            data: params,
            type: "post",
            error: function(data, err) {
                $(".serverside-error").html("");
                $("#serverside tbody").empty();
                $("#serverside").append(
                    '<tr><td class="text-center" colspan="' +
                    column +
                    '">"' + labelnodata + '"</td></tr>'
                );
                $("#serverside_processing").css("display", "none");
            },
        },
        jQueryUI: false,
        /* sScrollX: "100%", */
        /* scrollX: true, */
        /* ScrollCollapse: false,
        autoWidth: false,
        fixedColumns: false, */
        pageLength: 10,
        order: [
            [1, "desc"]
        ],
        columnDefs: [{
                targets: [0, column - 1],
                width: "3%",
                orderable: false,
            },
            {
                targets: [0],
                width: "3%",
                className: "text-right",
            },
            {
                targets: col,
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
                text: '<i class="feather icon-plus-circle"></i> ' + labeladd,
                className: "btn-min-width ml-1",
                action: function(e, dt, node, config) {
                    window.location.href = base_url + linkadd + '/' + d_from_ + '/' + d_to_ + '/' + htype + '/' + harea + '/' + hcoa;
                },
            },
        ],
        language: {
            infoPostFix: "",
            search: "<span>" + labelsearch + " :</span> _INPUT_",
            searchPlaceholder: labeltype,
            info: labelinfo,
            infoFiltered: labelfilter,
            lengthMenu: "<span>" + labelshow + " : </span> _MENU_",
            url: "",
            paginate: {
                first: labelfirst,
                last: labellast,
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

/** Datatable Plus Button Export */
function datatableexportparams(link, column, linkexport, params, col = 999) {
    if (col == 999) {
        col = [0];
    } else {
        col = col;
    }
    var t = $("#serverside").DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: base_url + link,
            data: params,
            type: "post",
            error: function(data, err) {
                $(".serverside-error").html("");
                $("#serverside tbody").empty();
                $("#serverside").append(
                    '<tr><td class="text-center" colspan="' +
                    column +
                    '">"' + labelnodata + '"</td></tr>'
                );
                $("#serverside_processing").css("display", "none");
            },
        },
        jQueryUI: false,
        pageLength: 10,
        order: [
            [1, "desc"]
        ],
        columnDefs: [{
                targets: [0, column - 1],
                width: "3%",
                orderable: false,
            },
            {
                targets: [0],
                width: "3%",
                className: "text-right",
            },
            {
                targets: col,
                className: "text-right",
            },
        ],
        pagingType: "full_numbers",
        dom: "Bfrtip",
        className: "my-1",
        lengthMenu: [
            [-1, 10, 25, 50],
            ["Show all", "10 rows", "25 rows", "50 rows"],
        ],
        buttons: [
            "pageLength",
            {
                text: '<i class="fa fa-file-excel-o"></i> ' + labelexport,
                className: "btn-min-width ml-1",
                action: function(e, dt, node, config) {
                    window.location.href = base_url + linkexport;
                },
            },
        ],
        language: {
            infoPostFix: "",
            search: "<span>" + labelsearch + " :</span> _INPUT_",
            searchPlaceholder: labeltype,
            info: labelinfo,
            infoFiltered: labelfilter,
            lengthMenu: "<span>" + labelshow + " : </span> _MENU_",
            url: "",
            paginate: {
                first: labelfirst,
                last: labellast,
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

/** Datatable Informasi Plus Button Export */
function datatableinfoparams(link, column, params, col = 999) {
    if (col == 999) {
        col = [0];
    } else {
        col = col;
    }
    var t = $("#serverside").DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: base_url + link,
            data: params,
            type: "post",
            error: function(data, err) {
                $(".serverside-error").html("");
                $("#serverside tbody").empty();
                $("#serverside").append(
                    '<tr><td class="text-center" colspan="' +
                    column +
                    '">"' + labelnodata + '"</td></tr>'
                );
                $("#serverside_processing").css("display", "none");
            },
        },
        jQueryUI: false,
        /* pageLength: 10, */
        /* order: [
            [0, "asc"]
        ], */
        columnDefs: [{
                targets: [0, column - 1],
                width: "3%",
                orderable: false,
            },
            {
                targets: [0],
                width: "3%",
                className: "text-right",
            },
            {
                targets: col,
                className: "text-right",
            },
        ],
        pagingType: "full_numbers",
        dom: "Bfrtip",
        className: "my-1",
        aLengthMenu: [
            [10, 25, 50, -1],
            ["10 rows", "25 rows", "50 rows", "Show all"],
        ],
        iDisplayLength: -1,
        buttons: [
            "pageLength",
            {
                text: '<i class="fa fa-file-excel-o"></i> ' + labelexport,
                className: "btn-min-width ml-1 export",
                /* action: function(e, dt, node, config) {
                    window.location.href = base_url + linkexport;
                }, */
            },
        ],
        language: {
            infoPostFix: "",
            search: "<span>" + labelsearch + " :</span> _INPUT_",
            searchPlaceholder: labeltype,
            info: labelinfo,
            infoFiltered: labelfilter,
            lengthMenu: "<span>" + labelshow + " : </span> _MENU_",
            url: "",
            paginate: {
                first: labelfirst,
                last: labellast,
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


function datatableinfo2(link, column, params, col = 999, d_from_, d_to_) {
    if (col == 999) {
        col = [0];
    } else {
        col = col;
    }
    var t = $("#serverside").DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: base_url + link + '/' + d_from_ + '/' + d_to_,
            data: params,
            type: "post",
            error: function(data, err) {
                $(".serverside-error").html("");
                $("#serverside tbody").empty();
                $("#serverside").append(
                    '<tr><td class="text-center" colspan="' +
                    column +
                    '">"' + labelnodata + '"</td></tr>'
                );
                $("#serverside_processing").css("display", "none");
            },
        },
        jQueryUI: false,
        /* pageLength: 10, */
        /* order: [
            [0, "asc"]
        ], */
        columnDefs: [{
                targets: [0, column - 1],
                width: "3%",
                orderable: false,
            },
            {
                targets: [0],
                width: "3%",
                className: "text-right",
            },
            {
                targets: col,
                className: "text-right",
            },
        ],
        pagingType: "full_numbers",
        dom: "Bfrtip",
        className: "my-1",
        aLengthMenu: [
            [10, 25, 50, -1],
            ["10 rows", "25 rows", "50 rows", "Show all"],
        ],
        iDisplayLength: -1,
        buttons: [
            "pageLength",
            {
                text: '<i class="fa fa-file-excel-o"></i> ' + labelexport,
                className: "btn-min-width ml-1 export",
                /* action: function(e, dt, node, config) {
                    window.location.href = base_url + linkexport;
                }, */
            },
        ],
        language: {
            infoPostFix: "",
            search: "<span>" + labelsearch + " :</span> _INPUT_",
            searchPlaceholder: labeltype,
            info: labelinfo,
            infoFiltered: labelfilter,
            lengthMenu: "<span>" + labelshow + " : </span> _MENU_",
            url: "",
            paginate: {
                first: labelfirst,
                last: labellast,
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


function datatableinfo3(link, column, params, col = 999, d_from_, d_to_, hstore) {
    if (col == 999) {
        col = [0];
    } else {
        col = col;
    }
    var t = $("#serverside").DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: base_url + link + '/' + d_from_ + '/' + d_to_ + '/' + hstore,
            data: params,
            type: "post",
            error: function(data, err) {
                $(".serverside-error").html("");
                $("#serverside tbody").empty();
                $("#serverside").append(
                    '<tr><td class="text-center" colspan="' +
                    column +
                    '">"' + labelnodata + '"</td></tr>'
                );
                $("#serverside_processing").css("display", "none");
            },
        },
        jQueryUI: false,
        /* pageLength: 10, */
        /* order: [
            [0, "asc"]
        ], */
        columnDefs: [{
                targets: [0, column - 1],
                width: "3%",
                orderable: false,
            },
            {
                targets: [0],
                width: "3%",
                className: "text-right",
            },
            {
                targets: col,
                className: "text-right",
            },
        ],
        pagingType: "full_numbers",
        dom: "Bfrtip",
        className: "my-1",
        aLengthMenu: [
            [10, 25, 50, -1],
            ["10 rows", "25 rows", "50 rows", "Show all"],
        ],
        iDisplayLength: -1,
        buttons: [
            "pageLength",
            {
                text: '<i class="fa fa-file-excel-o"></i> ' + labelexport,
                className: "btn-min-width ml-1 export",
                /* action: function(e, dt, node, config) {
                    window.location.href = base_url + linkexport;
                }, */
            },
        ],
        language: {
            infoPostFix: "",
            search: "<span>" + labelsearch + " :</span> _INPUT_",
            searchPlaceholder: labeltype,
            info: labelinfo,
            infoFiltered: labelfilter,
            lengthMenu: "<span>" + labelshow + " : </span> _MENU_",
            url: "",
            paginate: {
                first: labelfirst,
                last: labellast,
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

/** Datatable Informasi Plus Button Export */
function datatableinfo(link, column, col = 999) {
    if (col == 999) {
        col = [0];
    } else {
        col = col;
    }
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
                    '">"' + labelnodata + '"</td></tr>'
                );
                $("#serverside_processing").css("display", "none");
            },
        },
        jQueryUI: false,
        /* pageLength: 10, */
        order: [
            [1, "desc"]
        ],
        columnDefs: [{
                targets: [0, column - 1],
                width: "3%",
                orderable: false,
            },
            {
                targets: [0],
                width: "3%",
                className: "text-right",
            },
            {
                targets: col,
                className: "text-right",
            },
        ],
        pagingType: "full_numbers",
        dom: "Bfrtip",
        className: "my-1",
        aLengthMenu: [
            [10, 25, 50, -1],
            ["10 rows", "25 rows", "50 rows", "Show all"],
        ],
        iDisplayLength: -1,
        buttons: [
            "pageLength",
            {
                text: '<i class="fa fa-file-excel-o"></i> ' + labelexport,
                className: "btn-min-width ml-1 export",
                /* action: function(e, dt, node, config) {
                    window.location.href = base_url + linkexport;
                }, */
            },
        ],
        language: {
            infoPostFix: "",
            search: "<span>" + labelsearch + " :</span> _INPUT_",
            searchPlaceholder: labeltype,
            info: labelinfo,
            infoFiltered: labelfilter,
            lengthMenu: "<span>" + labelshow + " : </span> _MENU_",
            url: "",
            paginate: {
                first: labelfirst,
                last: labellast,
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
        title: label_sw_title,
        text: label_sw_text,
        icon: "info",
        showCancelButton: true,
        buttons: {
            confirm: {
                text: label_sw_confirm,
                value: true,
                visible: true,
                className: "btn-success",
                closeModal: false,
            },
            cancel: {
                text: label_sw_cancel,
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
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true && data.ada == false) {
                        swal(label_sw_sukses, label_sw_simpan, "success").then(
                            function(result) {
                                window.location = base_url + link;
                            }
                        );
                    } else if (data.sukses == false && data.ada == true) {
                        swal(label_sw_maaf, label_sw_duplikat, "error");
                    } else {
                        swal(label_sw_maaf, label_sw_gagal, "error");
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    swal(label_sw_maaf, label_sw_gagal, "error");
                    $(".app-content").unblock();
                },
            });
        } else {
            swal(label_sw_cancel, label_sw_batal, "error");
        }
    });
}

/** SweetAlert V2 Add Data */
function sweetaddv2(link, formData) {
    Swal.fire({
        title: label_sw_title,
        text: label_sw_text,
        type: "info",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_confirm,
        confirmButtonClass: "btn btn-primary",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-danger ml-1",
        buttonsStyling: !1,
    }).then(function(t) {
        if (t.value) {
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
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true && data.ada == false) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_simpan,
                            /* animation: !1,
                            customClass: "animated flipInX", */
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                window.location = base_url + link;
                            }
                        );
                    } else if (data.sukses == false && data.ada == true) {
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_duplikat,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    } else {
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_gagal,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    Swal.fire({
                        type: "error",
                        title: label_sw_maaf,
                        text: label_sw_gagal,
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                    $(".app-content").unblock();
                },
            });
        }
    });
}

/** SweetAlert V3 Add Data */
function sweetaddv3(link, dfrom, dto, formData) {
    Swal.fire({
        title: label_sw_title,
        text: label_sw_text,
        type: "info",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_confirm,
        confirmButtonClass: "btn btn-primary",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-danger ml-1",
        buttonsStyling: !1,
    }).then(function(t) {
        if (t.value) {
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
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true && data.ada == false) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_simpan,
                            /* animation: !1,
                            customClass: "animated flipInX", */
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                // window.location = base_url + link;
                                window.location = base_url + link + '/index/' + dfrom + '/' + dto;
                            }
                        );
                    } else if (data.sukses == false && data.ada == true) {
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_duplikat,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    } else {
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_gagal,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    Swal.fire({
                        type: "error",
                        title: label_sw_maaf,
                        text: label_sw_gagal,
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                    $(".app-content").unblock();
                },
            });
        }
    });
}


function sweetaddv33(link, dfrom, dto,harea, formData) {
    Swal.fire({
        title: label_sw_title,
        text: label_sw_text,
        type: "info",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_confirm,
        confirmButtonClass: "btn btn-success",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-secondary ml-1",
        buttonsStyling: !1,
    }).then(function(t) {
        if (t.value) {
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
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true && data.ada == false) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_simpan,
                            /* animation: !1,
                            customClass: "animated flipInX", */
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                // window.location = base_url + link;
                                window.location = base_url + link + '/index/' + dfrom + '/' + dto + '/' + harea;
                            }
                        );
                    } else if (data.sukses == false && data.ada == true) {
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_duplikat,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    } else {
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_gagal,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    Swal.fire({
                        type: "error",
                        title: label_sw_maaf,
                        text: label_sw_gagal,
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                    $(".app-content").unblock();
                },
            });
        }
    });
}

function sweetaddv33sup(link, dfrom, dto, hsup, formData) {
    Swal.fire({
        title: label_sw_title,
        text: label_sw_text,
        type: "info",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_confirm,
        confirmButtonClass: "btn btn-success",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-secondary ml-1",
        buttonsStyling: !1,
    }).then(function(t) {
        if (t.value) {
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
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true && data.ada == false) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_simpan,
                            /* animation: !1,
                            customClass: "animated flipInX", */
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                // window.location = base_url + link;
                                window.location = base_url + link + '/index/' + dfrom + '/' + dto + '/' + hsup;
                            }
                        );
                    } else if (data.sukses == false && data.ada == true) {
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_duplikat,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    } else {
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_gagal,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    Swal.fire({
                        type: "error",
                        title: label_sw_maaf,
                        text: label_sw_gagal,
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                    $(".app-content").unblock();
                },
            });
        }
    });
}


function sweetaddv5(link, dfrom, dto, htype, harea, hcoa, formData) {
    Swal.fire({
        title: label_sw_title,
        text: label_sw_text,
        type: "info",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_confirm,
        confirmButtonClass: "btn btn-primary",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-danger ml-1",
        buttonsStyling: !1,
    }).then(function(t) {
        if (t.value) {
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
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true && data.ada == false) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_simpan,
                            /* animation: !1,
                            customClass: "animated flipInX", */
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                // window.location = base_url + link;
                                window.location = base_url + link + '/index/' + dfrom + '/' + dto + '/' + htype + '/' + harea + '/' + hcoa;
                            }
                        );
                    } else if (data.sukses == false && data.ada == true) {
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_duplikat,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    } else {
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_gagal,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    Swal.fire({
                        type: "error",
                        title: label_sw_maaf,
                        text: label_sw_gagal,
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                    $(".app-content").unblock();
                },
            });
        }
    });
}

function sweetaddv4(link, dfrom, dto, harea, formData) {
    Swal.fire({
        title: label_sw_title,
        text: label_sw_text,
        type: "info",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_confirm,
        confirmButtonClass: "btn btn-success",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-secondary ml-1",
        buttonsStyling: !1,
    }).then(function(t) {
        if (t.value) {
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
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true && data.ada == false) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text:'DATA DISIMPAN : '+data.dokumen,
                            // text: label_sw_simpan,
                            /* animation: !1,
                            customClass: "animated flipInX", */
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                // window.location = base_url + link;
                                window.location = base_url + link + '/index/' + dfrom + '/' + dto + '/' + harea ;
                            }
                        );
                    } else if (data.sukses == false && data.ada == true) {
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_duplikat,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    } else {
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_gagal,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    Swal.fire({
                        type: "error",
                        title: label_sw_maaf,
                        text: label_sw_gagal,
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                    $(".app-content").unblock();
                },
            });
        }
    });
}

/** SweetAlert Edit Data */
function sweetedit(link, formData) {
    swal({
        title: label_sw_titleubah,
        text: label_sw_textubah,
        icon: "info",
        showCancelButton: true,
        buttons: {
            confirm: {
                text: label_sw_ubah,
                value: true,
                visible: true,
                className: "btn-success",
                closeModal: false,
            },
            cancel: {
                text: label_sw_cancel,
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
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                            "z-index": 100,
                        },
                    });
                },
                success: function(data) {
                    if (data.sukses == true && data.ada == false) {
                        swal(label_sw_sukses, label_sw_simpanubah, "success").then(
                            function(result) {
                                window.location = base_url + link;
                            }
                        );
                    } else if (data.sukses == false && data.ada == true) {
                        swal(label_sw_maaf, label_sw_duplikat, "error");
                    } else {
                        swal(label_sw_maaf, label_sw_gagalubah, "error");
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    swal(label_sw_maaf, label_sw_gagalubah, "error");
                    $(".app-content").unblock();
                },
            });
        } else {
            swal(label_sw_cancel, label_sw_batalubah, "error");
        }
    });
}
function sweetedit1(link, formData) {
    swal({
        title: label_sw_titleubah,
        text: label_sw_textubah,
        icon: "info",
        showCancelButton: true,
        buttons: {
            confirm: {
                text: label_sw_ubah,
                value: true,
                visible: true,
                className: "btn-success",
                closeModal: false,
            },
            cancel: {
                text: label_sw_cancel,
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
                url: base_url + link + "/update1",
                dataType: "json",
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function() {
                    $(".app-content").block({
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                            "z-index": 100,
                        },
                    });
                },
                success: function(data) {
                    if (data.sukses == true && data.ada == false) {
                        swal(label_sw_sukses, label_sw_simpanubah, "success").then(
                            function(result) {
                                window.location = base_url + link;
                            }
                        );
                    } else if (data.sukses == false && data.ada == true) {
                        swal(label_sw_maaf, label_sw_duplikat, "error");
                    } else {
                        swal(label_sw_maaf, label_sw_gagalubah, "error");
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    swal(label_sw_maaf, label_sw_gagalubah, "error");
                    $(".app-content").unblock();
                },
            });
        } else {
            swal(label_sw_cancel, label_sw_batalubah, "error");
        }
    });
}

/** SweetAlert V2 Edit Data */
function sweeteditv2(link, formData) {
    Swal.fire({
        title: label_sw_titleubah,
        text: label_sw_textubah,
        type: "info",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_ubah,
        confirmButtonClass: "btn btn-primary",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-danger ml-1",
        buttonsStyling: !1,
    }).then(function(t) {
        if (t.value) {
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
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true && data.ada == false) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_ubah,
                            /* animation: !1,
                            customClass: "animated flipInX", */
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                window.location = base_url + link;
                            }
                        );
                    } else if (data.sukses == false && data.ada == true) {
                        /* Swal.fire(label_sw_maaf, label_sw_duplikat, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_duplikat,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    } else {
                        /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_gagalubah,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                    Swal.fire({
                        type: "error",
                        title: label_sw_maaf,
                        text: label_sw_gagalubah,
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                    $(".app-content").unblock();
                },
            });
        }
    });
}

function sweeteditv3(link, dfrom, dto, formData) {
    Swal.fire({
        title: label_sw_titleubah,
        text: label_sw_textubah,
        type: "info",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_ubah,
        confirmButtonClass: "btn btn-primary",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-danger ml-1",
        buttonsStyling: !1,
    }).then(function(t) {
        if (t.value) {
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
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true && data.ada == false) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_ubah,
                            /* animation: !1,
                            customClass: "animated flipInX", */
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                // window.location = base_url + link;
                                window.location = base_url + link + '/index/' + dfrom + '/' + dto;
                            }
                        );
                    } else if (data.sukses == false && data.ada == true) {
                        /* Swal.fire(label_sw_maaf, label_sw_duplikat, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_duplikat,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    } else {
                        /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_gagalubah,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                    Swal.fire({
                        type: "error",
                        title: label_sw_maaf,
                        text: label_sw_gagalubah,
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                    $(".app-content").unblock();
                },
            });
        }
    });
}


function sweeteditv33(link, dfrom, dto,harea, formData) {
    Swal.fire({
        title: label_sw_titleubah,
        text: label_sw_textubah,
        type: "info",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_ubah,
        confirmButtonClass: "btn btn-warning",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-secondary ml-1",
        buttonsStyling: !1,
    }).then(function(t) {
        if (t.value) {
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
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true && data.ada == false) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_ubah,
                            /* animation: !1,
                            customClass: "animated flipInX", */
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                // window.location = base_url + link;
                                window.location = base_url + link + '/index/' + dfrom + '/' + dto + '/' + harea;
                            }
                        );
                    } else if (data.sukses == false && data.ada == true) {
                        /* Swal.fire(label_sw_maaf, label_sw_duplikat, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_duplikat,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    } else {
                        /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_gagalubah,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                    Swal.fire({
                        type: "error",
                        title: label_sw_maaf,
                        text: label_sw_gagalubah,
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                    $(".app-content").unblock();
                },
            });
        }
    });
}

function sweeteditv332(link, dfrom, dto,harea, formData) {
    Swal.fire({
        title: label_sw_titleubah,
        text: label_sw_textubah,
        type: "info",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_ubah,
        confirmButtonClass: "btn btn-warning",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-secondary ml-1",
        buttonsStyling: !1,
    }).then(function(t) {
        if (t.value) {
            $.ajax({
                type: "POST",
                enctype: "multipart/form-data",
                data: formData,
                url: base_url + link + "/update2",
                dataType: "json",
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function() {
                    $(".app-content").block({
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true && data.ada == false) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_ubah,
                            /* animation: !1,
                            customClass: "animated flipInX", */
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                // window.location = base_url + link;
                                window.location = base_url + link + '/index/' + dfrom + '/' + dto + '/' + harea;
                            }
                        );
                    } else if (data.sukses == false && data.ada == true) {
                        /* Swal.fire(label_sw_maaf, label_sw_duplikat, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_duplikat,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    } else {
                        /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_gagalubah,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                    Swal.fire({
                        type: "error",
                        title: label_sw_maaf,
                        text: label_sw_gagalubah,
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                    $(".app-content").unblock();
                },
            });
        }
    });
}

function sweeteditv33sup(link, dfrom, dto,hsup, formData) {
    Swal.fire({
        title: label_sw_titleubah,
        text: label_sw_textubah,
        type: "info",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_ubah,
        confirmButtonClass: "btn btn-warning",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-secondary ml-1",
        buttonsStyling: !1,
    }).then(function(t) {
        if (t.value) {
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
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true && data.ada == false) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_ubah,
                            /* animation: !1,
                            customClass: "animated flipInX", */
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                // window.location = base_url + link;
                                window.location = base_url + link + '/index/' + dfrom + '/' + dto + '/' + hsup;
                            }
                        );
                    } else if (data.sukses == false && data.ada == true) {
                        /* Swal.fire(label_sw_maaf, label_sw_duplikat, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_duplikat,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    } else {
                        /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_gagalubah,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                    Swal.fire({
                        type: "error",
                        title: label_sw_maaf,
                        text: label_sw_gagalubah,
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                    $(".app-content").unblock();
                },
            });
        }
    });
}

function sweeteditv5(link, dfrom, dto, htype, harea, hcoa, formData) {
    Swal.fire({
        title: label_sw_titleubah,
        text: label_sw_textubah,
        type: "info",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_ubah,
        confirmButtonClass: "btn btn-primary",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-danger ml-1",
        buttonsStyling: !1,
    }).then(function(t) {
        if (t.value) {
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
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true && data.ada == false) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_ubah,
                            /* animation: !1,
                            customClass: "animated flipInX", */
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                // window.location = base_url + link;
                                window.location = base_url + link + '/index/' + dfrom + '/' + dto + '/' + htype + '/' + harea + '/' + hcoa;
                            }
                        );
                    } else if (data.sukses == false && data.ada == true) {
                        /* Swal.fire(label_sw_maaf, label_sw_duplikat, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_duplikat,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    } else {
                        /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_gagalubah,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                    Swal.fire({
                        type: "error",
                        title: label_sw_maaf,
                        text: label_sw_gagalubah,
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                    $(".app-content").unblock();
                },
            });
        }
    });
}


function sweetedittgl(link, formData) {
    Swal.fire({
        title: label_sw_titleubah,
        text: label_sw_textubah,
        type: "info",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_ubah,
        confirmButtonClass: "btn btn-warning",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-secondary ml-1",
        buttonsStyling: !1,
    }).then(function(t) {
        if (t.value) {
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
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true && data.ada == false) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_ubah,
                            /* animation: !1,
                            customClass: "animated flipInX", */
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                // window.location = base_url + link;
                                // window.location = base_url + link + '/index/' + dfrom + '/' + dto + '/' + harea;
                                window.location = base_url + link;
                            }
                        );
                    } else if (data.sukses == false && data.ada == true) {
                        /* Swal.fire(label_sw_maaf, label_sw_duplikat, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_duplikat,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    } else {
                        /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_gagalubah,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                    Swal.fire({
                        type: "error",
                        title: label_sw_maaf,
                        text: label_sw_gagalubah,
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                    $(".app-content").unblock();
                },
            });
        }
    });
}

/** SweetAlert Realisasi Data */
function sweetrealisasi(link, formData) {
    swal({
        title: label_sw_titlerealisasi,
        text: label_sw_textrealisasi,
        icon: "info",
        showCancelButton: true,
        buttons: {
            confirm: {
                text: label_sw_realisasi,
                value: true,
                visible: true,
                className: "btn-success",
                closeModal: false,
            },
            cancel: {
                text: label_sw_cancel,
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
                url: base_url + link + "/realisasi",
                dataType: "json",
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function() {
                    $(".app-content").block({
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                            "z-index": 100,
                        },
                    });
                },
                success: function(data) {
                    if (data.sukses == true && data.ada == false) {
                        swal(label_sw_sukses, label_sw_simpanrealisasi, "success").then(
                            function(result) {
                                window.location = base_url + link;
                            }
                        );
                    } else if (data.sukses == false && data.ada == true) {
                        swal(label_sw_maaf, label_sw_duplikat, "error");
                    } else {
                        swal(label_sw_maaf, label_sw_gagalrealisasi, "error");
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    swal(label_sw_maaf, label_sw_gagalrealisasi, "error");
                    $(".app-content").unblock();
                },
            });
        } else {
            swal(label_sw_cancel, label_sw_batalrealisasi, "error");
        }
    });
}


function sweetrealisasi33(link, dfrom, dto,harea, formData) {
    swal({
        title: label_sw_titlerealisasi,
        text: label_sw_textrealisasi,
        icon: "info",
        showCancelButton: true,
        buttons: {
            confirm: {
                text: label_sw_realisasi,
                value: true,
                visible: true,
                className: "btn-success",
                closeModal: false,
            },
            cancel: {
                text: label_sw_cancel,
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
                url: base_url + link + "/realisasi",
                dataType: "json",
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function() {
                    $(".app-content").block({
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                            "z-index": 100,
                        },
                    });
                },
                success: function(data) {
                    if (data.sukses == true && data.ada == false) {
                        swal(label_sw_sukses, label_sw_simpanrealisasi, "success").then(
                            function(result) {
                                // window.location = base_url + link;
                                window.location = base_url + link + '/index/' + dfrom + '/' + dto + '/' + harea;
                            }
                        );
                    } else if (data.sukses == false && data.ada == true) {
                        swal(label_sw_maaf, label_sw_duplikat, "error");
                    } else {
                        swal(label_sw_maaf, label_sw_gagalrealisasi, "error");
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    swal(label_sw_maaf, label_sw_gagalrealisasi, "error");
                    $(".app-content").unblock();
                },
            });
        } else {
            swal(label_sw_cancel, label_sw_batalrealisasi, "error");
        }
    });
}

/** SweetAlert V2 Terima Data */
function sweetterimav2(link, formData) {
    Swal.fire({
        title: label_sw_titleterima,
        text: label_sw_textterima,
        type: "info",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_terima,
        confirmButtonClass: "btn btn-primary",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-danger ml-1",
        buttonsStyling: !1,
    }).then(function(t) {
        if (t.value) {
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
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true && data.ada == false) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_terima,
                            /* animation: !1,
                            customClass: "animated flipInX", */
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                window.location = base_url + link;
                            }
                        );
                    } else if (data.sukses == false && data.ada == true) {
                        /* Swal.fire(label_sw_maaf, label_sw_duplikat, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_duplikat,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    } else {
                        /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_gagalterima,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                    Swal.fire({
                        type: "error",
                        title: label_sw_maaf,
                        text: label_sw_gagalterima,
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                    $(".app-content").unblock();
                },
            });
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
                swal(label_sw_sukses, label_sw_simpanubah, "success").then(
                    function(result) {
                        // window.location = base_url + link;
                        window.location = base_url + link + '/index/' + $("#d_from").val() + '/' + $("#d_to").val() + '/' + $("#i_area").val();
                    }
                );
            } else {
                swal(label_sw_maaf, label_sw_gagalubah, "error");
            }
            $(".app-content").unblock();
        },
        error: function() {
            swal(label_sw_maaf, label_sw_gagalubah, "error");
            $(".app-content").unblock();
        },
    });
}

function changestatus2(link, id) {
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
                swal(label_sw_sukses, label_sw_simpanubah, "success").then(
                    function(result) {
                        // window.location = base_url + link;
                        window.location = base_url + link + '/index/' + $("#d_from").val() + '/' + $("#d_to").val() + '/' + $("#i_area").val();
                    }
                );
            } else {
                swal(label_sw_maaf, label_sw_gagalubah, "error");
            }
            $(".app-content").unblock();
        },
        error: function() {
            swal(label_sw_maaf, label_sw_gagalubah, "error");
            $(".app-content").unblock();
        },
    });
}


/** Update Status */
function changepareto(link, id) {
    $.ajax({
        type: "POST",
        data: {
            id: id,
        },
        url: base_url + link + "/changepareto",
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
                swal(label_sw_sukses, label_sw_simpanubah, "success").then(
                    function(result) {
                        window.location = base_url + link;
                    }
                );
            } else {
                swal(label_sw_maaf, label_sw_gagalubah, "error");
            }
            $(".app-content").unblock();
        },
        error: function() {
            swal(label_sw_maaf, label_sw_gagalubah, "error");
            $(".app-content").unblock();
        },
    });
}


/** SweetAlert Delete Data */
function sweetdelete(link, id) {
    swal({
        title: label_sw_del_title,
        text: label_sw_del_text,
        icon: "error",
        showCancelButton: true,
        buttons: {
            confirm: {
                text: label_sw_del,
                value: true,
                visible: true,
                className: "btn-success",
                closeModal: false,
            },
            cancel: {
                text: label_sw_cancel,
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
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true) {
                        swal(label_sw_sukses, label_sw_del_success, "success").then(
                            function(result) {
                                window.location = base_url + link;
                            }
                        );
                    } else {
                        swal(label_sw_maaf, label_sw_del_failed, "error");
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    swal(label_sw_maaf, label_sw_del_failed, "error");
                    $(".app-content").unblock();
                },
            });
        } else {
            swal(label_sw_cancel, label_sw_del_cancel, "error");
        }
    });
}

/** Sweet Delete */
function sweetdeletev2(link, id) {
    Swal.fire({
        title: label_sw_del_title,
        text: label_sw_del_text,
        type: "warning",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_del,
        confirmButtonClass: "btn btn-primary",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-warning ml-1",
        buttonsStyling: !1,
    }).then(function(t) {
        if (t.value) {
            $.ajax({
                type: "POST",
                data: {
                    id: id,
                },
                url: base_url + link + "/delete",
                dataType: "json",
                beforeSend: function() {
                    $(".app-content").block({
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_del_success,
                            /* animation: !1,
                            customClass: "animated flipInX", */
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                window.location = base_url + link;
                            }
                        );
                    } else {
                        /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_del_failed,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                    Swal.fire({
                        type: "error",
                        title: label_sw_maaf,
                        text: label_sw_del_failed,
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                    $(".app-content").unblock();
                },
            });
        }
    });
}
/** Sweet Delete */
function sweetdeletev2link(link, id, d_from_,d_to_) {
    Swal.fire({
        title: label_sw_del_title,
        text: label_sw_del_text,
        type: "warning",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_del,
        confirmButtonClass: "btn btn-primary",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-warning ml-1",
        buttonsStyling: !1,
    }).then(function(t) {
        if (t.value) {
            $.ajax({
                type: "POST",
                data: {
                    id: id,
                },
                url: base_url + link + "/delete",
                dataType: "json",
                beforeSend: function() {
                    $(".app-content").block({
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_del_success,
                            /* animation: !1,
                            customClass: "animated flipInX", */
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                // window.location = base_url + link;
                                window.location = base_url + link + '/index/' + d_from_ + '/' + d_to_;
                            }
                        );
                    } else {
                        /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_del_failed,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                    Swal.fire({
                        type: "error",
                        title: label_sw_maaf,
                        text: label_sw_del_failed,
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                    $(".app-content").unblock();
                },
            });
        }
    });
}

function sweetdeletev5link(link, id, d_from_,d_to_,type,area,coa) {
    Swal.fire({
        title: label_sw_del_title,
        text: label_sw_del_text,
        type: "warning",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_del,
        confirmButtonClass: "btn btn-primary",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-warning ml-1",
        buttonsStyling: !1,
    }).then(function(t) {
        if (t.value) {
            $.ajax({
                type: "POST",
                data: {
                    id: id,
                },
                url: base_url + link + "/delete",
                dataType: "json",
                beforeSend: function() {
                    $(".app-content").block({
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_del_success,
                            /* animation: !1,
                            customClass: "animated flipInX", */
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                // window.location = base_url + link;
                                window.location = base_url + link + '/index/' + d_from_ + '/' + d_to_ + '/' + type + '/' + area + '/' + coa;
                            }
                        );
                    } else {
                        /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_del_failed,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                    Swal.fire({
                        type: "error",
                        title: label_sw_maaf,
                        text: label_sw_del_failed,
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                    $(".app-content").unblock();
                },
            });
        }
    });
}

function sweetdeletev5raya(link, id, d_from_,d_to_,type,area,coa) {
    Swal.fire({
        
        title: 'VOUCHER akan dibatalkan ?',
        text: 'Silahkan isi alasan pembatalan VOUCHER !',
        type: "warning",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_del,
        confirmButtonClass: "btn btn-primary",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-warning ml-1",
        buttonsStyling: !1,
        
        input: 'text',
        inputAttributes: {
            autocapitalize: 'off'
        },
        showLoaderOnConfirm: true,

        preConfirm: (reason) => {
            // Validasi alasan jika diperlukan
            if (!reason) {
                Swal.showValidationMessage('Alasan harus diisi');
            }else{    
                // Kirim data ke controller PHP menggunakan $.ajax
                $.ajax({
                    type: "POST",
                    data: { id: id, alasan: reason },
                    url: base_url + link + "/delete2",
                    dataType: "json",
                    beforeSend: function() {
                        $(".app-content").block({
                            /* message: '<img src="' +
                                base_url +
                                'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                    success: function(data) {
                        if (data.sukses == true) {
                            Swal.fire({
                                type: "success",
                                title: label_sw_sukses,
                                text: label_sw_del_success,
                                /* animation: !1,
                                customClass: "animated flipInX", */
                                confirmButtonClass: "btn btn-success",
                            }).then(
                                function(result) {
                                    window.location = base_url + link + '/index/' + d_from_ + '/' + d_to_ + '/' + type + '/' + area + '/' + coa;
                                }
                            );
                        } else {
                            /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                            Swal.fire({
                                type: "error",
                                title: label_sw_maaf,
                                text: label_sw_del_failed,
                                animation: !1,
                                customClass: "animated flipInX",
                                confirmButtonClass: "btn btn-danger",
                            });
                        }
                        $(".app-content").unblock();
                    },
                    error: function() {
                        /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_del_failed,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                        $(".app-content").unblock();
                    },
                });
            }
        },
        allowOutsideClick: () => !Swal.isLoading()
    });
}

/** Sweet Delete */
function sweetdeletev3(link, id) {
    Swal.fire({
        title: label_sw_del_title,
        text: label_sw_del_text,
        type: "warning",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_del,
        confirmButtonClass: "btn btn-primary",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-warning ml-1",
        buttonsStyling: !1,
    }).then(function(t) {
        if (t.value) {
            $.ajax({
                type: "POST",
                data: {
                    id: id,
                },
                url: base_url + link + "/delete",
                dataType: "json",
                beforeSend: function() {
                    $(".app-content").block({
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_del_success,
                            /* animation: !1,
                            customClass: "animated flipInX", */
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                window.location = base_url + link + '/index/' + $("#d_from").val() + '/' + $("#d_to").val();
                            }
                        );
                    } else {
                        /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_del_failed,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                    Swal.fire({
                        type: "error",
                        title: label_sw_maaf,
                        text: label_sw_del_failed,
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                    $(".app-content").unblock();
                },
            });
        }
    });
}

function sweetdeletev33raya(link, id) {
    Swal.fire({
        
        title: 'NOTA akan dibatalkan ? (termasuk SPB & SJ)',
        text: 'Silahkan isi alasan pembatalan NOTA !',
        type: "warning",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_del,
        confirmButtonClass: "btn btn-primary",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-warning ml-1",
        buttonsStyling: !1,
        
        input: 'text',
        inputAttributes: {
            autocapitalize: 'off'
        },
        showLoaderOnConfirm: true,

        preConfirm: (reason) => {
            // Validasi alasan jika diperlukan
            if (!reason) {
                Swal.showValidationMessage('Alasan harus diisi');
            }else{    
                // Kirim data ke controller PHP menggunakan $.ajax
                $.ajax({
                    type: "POST",
                    data: { id: id, alasan: reason },
                    url: base_url + link + "/delete2",
                    dataType: "json",
                    beforeSend: function() {
                        $(".app-content").block({
                            /* message: '<img src="' +
                                base_url +
                                'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                    success: function(data) {
                        if (data.sukses == true) {
                            Swal.fire({
                                type: "success",
                                title: label_sw_sukses,
                                text: label_sw_del_success,
                                /* animation: !1,
                                customClass: "animated flipInX", */
                                confirmButtonClass: "btn btn-success",
                            }).then(
                                function(result) {
                                    window.location = base_url + link + '/index/' + $("#d_from").val() + '/' + $("#d_to").val() + '/' + $("#i_area").val();
                                }
                            );
                        } else {
                            /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                            Swal.fire({
                                type: "error",
                                title: label_sw_maaf,
                                text: label_sw_del_failed,
                                animation: !1,
                                customClass: "animated flipInX",
                                confirmButtonClass: "btn btn-danger",
                            });
                        }
                        $(".app-content").unblock();
                    },
                    error: function() {
                        /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_del_failed,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                        $(".app-content").unblock();
                    },
                });
            }
        },
        allowOutsideClick: () => !Swal.isLoading()
    });
}

function sweetdeletev33raya2(link, id) {
    Swal.fire({
        
        title: 'SJ akan dibatalkan ?',
        text: 'Silahkan isi alasan pembatalan SJ !',
        type: "warning",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_del,
        confirmButtonClass: "btn btn-primary",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-warning ml-1",
        buttonsStyling: !1,
        
        input: 'text',
        inputAttributes: {
            autocapitalize: 'off'
        },
        showLoaderOnConfirm: true,
        
        preConfirm: (reason) => {
            // Validasi alasan jika diperlukan
            if (!reason) {
                Swal.showValidationMessage('Alasan harus diisi');
            }else{    
                // Kirim data ke controller PHP menggunakan $.ajax
                $.ajax({
                    type: "POST",
                    data: { id: id, alasan: reason },
                    url: base_url + link + "/delete2",
                    dataType: "json",
                    beforeSend: function() {
                        $(".app-content").block({
                            /* message: '<img src="' +
                                base_url +
                                'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                    success: function(data) {
                        if (data.sukses == true) {
                            Swal.fire({
                                type: "success",
                                title: label_sw_sukses,
                                text: label_sw_del_success,
                                /* animation: !1,
                                customClass: "animated flipInX", */
                                confirmButtonClass: "btn btn-success",
                            }).then(
                                function(result) {
                                    window.location = base_url + link + '/index/' + $("#d_from").val() + '/' + $("#d_to").val() + '/' + $("#i_area").val();
                                }
                            );
                        } else {
                            /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                            Swal.fire({
                                type: "error",
                                title: label_sw_maaf,
                                text: label_sw_del_failed,
                                animation: !1,
                                customClass: "animated flipInX",
                                confirmButtonClass: "btn btn-danger",
                            });
                        }
                        $(".app-content").unblock();
                    },
                    error: function() {
                        /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_del_failed,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                        $(".app-content").unblock();
                    },
                });
            }
        },
        allowOutsideClick: () => !Swal.isLoading()
    });
}

function sweetdeletev33raya3(link, id) {
    Swal.fire({
        
        title: 'DOKUMEN akan dibatalkan ?',
        text: 'Silahkan isi alasan pembatalan DOKUMEN !',
        type: "warning",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_del,
        confirmButtonClass: "btn btn-primary",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-warning ml-1",
        buttonsStyling: !1,
        
        input: 'text',
        inputAttributes: {
            autocapitalize: 'off'
        },
        showLoaderOnConfirm: true,
        
        preConfirm: (reason) => {
            // Validasi alasan jika diperlukan
            if (!reason) {
                Swal.showValidationMessage('Alasan harus diisi');
            }else{    
                // Kirim data ke controller PHP menggunakan $.ajax
                $.ajax({
                    type: "POST",
                    data: { id: id, alasan: reason },
                    url: base_url + link + "/delete2",
                    dataType: "json",
                    beforeSend: function() {
                        $(".app-content").block({
                            /* message: '<img src="' +
                                base_url +
                                'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                    success: function(data) {
                        if (data.sukses == true) {
                            Swal.fire({
                                type: "success",
                                title: label_sw_sukses,
                                text: label_sw_del_success,
                                /* animation: !1,
                                customClass: "animated flipInX", */
                                confirmButtonClass: "btn btn-success",
                            }).then(
                                function(result) {
                                    window.location = base_url + link + '/index/' + $("#d_from").val() + '/' + $("#d_to").val() + '/' + $("#i_area").val();
                                }
                            );
                        } else {
                            /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                            Swal.fire({
                                type: "error",
                                title: label_sw_maaf,
                                text: label_sw_del_failed,
                                animation: !1,
                                customClass: "animated flipInX",
                                confirmButtonClass: "btn btn-danger",
                            });
                        }
                        $(".app-content").unblock();
                    },
                    error: function() {
                        /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_del_failed,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                        $(".app-content").unblock();
                    },
                });
            }
        },
        allowOutsideClick: () => !Swal.isLoading()
    });
}

function sweetdeletev33(link, id) {
    Swal.fire({
        title: label_sw_del_title,
        text: label_sw_del_text,
        type: "warning",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_del,
        confirmButtonClass: "btn btn-primary",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-warning ml-1",
        buttonsStyling: !1,
    }).then(function(t) {
        if (t.value) {
            $.ajax({
                type: "POST",
                data: {
                    id: id,
                },
                url: base_url + link + "/delete",
                dataType: "json",
                beforeSend: function() {
                    $(".app-content").block({
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_del_success,
                            /* animation: !1,
                            customClass: "animated flipInX", */
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                window.location = base_url + link + '/index/' + $("#d_from").val() + '/' + $("#d_to").val() + '/' + $("#i_area").val();
                            }
                        );
                    } else {
                        /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_del_failed,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                    Swal.fire({
                        type: "error",
                        title: label_sw_maaf,
                        text: label_sw_del_failed,
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                    $(".app-content").unblock();
                },
            });
        }
    });
}

function sweetdeletev33a(link, id) {
    Swal.fire({
        title: label_sw_del_title,
        text: label_sw_del_text,
        type: "warning",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_del,
        confirmButtonClass: "btn btn-primary",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-warning ml-1",
        buttonsStyling: !1,
    }).then(function(t) {
        if (t.value) {
            $.ajax({
                type: "POST",
                data: {
                    id: id,
                },
                url: base_url + link + "/delete",
                dataType: "json",
                beforeSend: function() {
                    $(".app-content").block({
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_del_success,
                            /* animation: !1,
                            customClass: "animated flipInX", */
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                window.location = base_url + link + '/index/' + $("#d_from").val() + '/' + $("#d_to").val() + '/' + $("#i_store").val();
                            }
                        );
                    } else {
                        /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_del_failed,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                    Swal.fire({
                        type: "error",
                        title: label_sw_maaf,
                        text: label_sw_del_failed,
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                    $(".app-content").unblock();
                },
            });
        }
    });
}
function sweetdeletev333raya(link, id) {
    Swal.fire({
        
        title: 'NOTA PEMBELIAN akan dibatalkan ?',
        text: 'Silahkan isi alasan pembatalan NOTA !',
        type: "warning",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_del,
        confirmButtonClass: "btn btn-primary",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-warning ml-1",
        buttonsStyling: !1,
        
        input: 'text',
        inputAttributes: {
            autocapitalize: 'off'
        },
        showLoaderOnConfirm: true,

        preConfirm: (reason) => {
            // Validasi alasan jika diperlukan
            if (!reason) {
                Swal.showValidationMessage('Alasan harus diisi');
            }else{    
                // Kirim data ke controller PHP menggunakan $.ajax
                $.ajax({
                    type: "POST",
                    data: { id: id, alasan: reason },
                    url: base_url + link + "/delete2",
                    dataType: "json",
                    beforeSend: function() {
                        $(".app-content").block({
                            /* message: '<img src="' +
                                base_url +
                                'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                    success: function(data) {
                        if (data.sukses == true) {
                            Swal.fire({
                                type: "success",
                                title: label_sw_sukses,
                                text: label_sw_del_success,
                                /* animation: !1,
                                customClass: "animated flipInX", */
                                confirmButtonClass: "btn btn-success",
                            }).then(
                                function(result) {
                                    window.location = base_url + link + '/index/' + $("#d_from").val() + '/' + $("#d_to").val() + '/' + $("#i_supplier").val();
                                }
                            );
                        } else {
                            /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                            Swal.fire({
                                type: "error",
                                title: label_sw_maaf,
                                text: label_sw_del_failed,
                                animation: !1,
                                customClass: "animated flipInX",
                                confirmButtonClass: "btn btn-danger",
                            });
                        }
                        $(".app-content").unblock();
                    },
                    error: function() {
                        /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_del_failed,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                        $(".app-content").unblock();
                    },
                });
            }
        },
        allowOutsideClick: () => !Swal.isLoading()
    });
}

function sweetdeletev333(link, id) {
    Swal.fire({
        title: label_sw_del_title,
        text: label_sw_del_text,
        type: "warning",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_del,
        confirmButtonClass: "btn btn-primary",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-warning ml-1",
        buttonsStyling: !1,
    }).then(function(t) {
        if (t.value) {
            $.ajax({
                type: "POST",
                data: {
                    id: id,
                },
                url: base_url + link + "/delete",
                dataType: "json",
                beforeSend: function() {
                    $(".app-content").block({
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_del_success,
                            /* animation: !1,
                            customClass: "animated flipInX", */
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                window.location = base_url + link + '/index/' + $("#d_from").val() + '/' + $("#d_to").val() + '/' + $("#i_supplier").val();
                            }
                        );
                    } else {
                        /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_del_failed,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                    Swal.fire({
                        type: "error",
                        title: label_sw_maaf,
                        text: label_sw_del_failed,
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                    $(".app-content").unblock();
                },
            });
        }
    });
}

/** Sweet Approve */
function sweetapprovev2(link, id, note = null) {
    Swal.fire({
        title: label_sw_app_title,
        text: label_sw_app_text,
        type: "info",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_app,
        confirmButtonClass: "btn btn-primary",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-warning ml-1",
        buttonsStyling: !1,
    }).then(function(t) {
        if (t.value) {
            $.ajax({
                type: "POST",
                data: {
                    id: id,
                    note: note,
                },
                url: base_url + link + "/approve",
                dataType: "json",
                beforeSend: function() {
                    $(".app-content").block({
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_app_success,
                            /* animation: !1,
                            customClass: "animated flipInX", */
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                window.location = base_url + link;
                            }
                        );
                    } else {
                        /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_app_failed,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                    Swal.fire({
                        type: "error",
                        title: label_sw_maaf,
                        text: label_sw_app_failed,
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                    $(".app-content").unblock();
                },
            });
        }
    });
}

/** SweetAlert Approve Data */
function sweetapprove(link, id, note) {
    swal({
        title: label_sw_app_title,
        text: label_sw_app_text,
        icon: "info",
        showCancelButton: true,
        buttons: {
            confirm: {
                text: label_sw_app,
                value: true,
                visible: true,
                className: "btn-success",
                closeModal: false,
            },
            cancel: {
                text: label_sw_cancel,
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
                    note: note
                },
                url: base_url + link + "/approve",
                dataType: "json",
                beforeSend: function() {
                    $(".app-content").block({
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true) {
                        swal(label_sw_sukses, label_sw_app_success, "success").then(
                            function(result) {
                                window.location = base_url + link;
                            }
                        );
                    } else {
                        swal(label_sw_maaf, label_sw_app_failed, "error");
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    swal(label_sw_maaf, label_sw_app_failed, "error");
                    $(".app-content").unblock();
                },
            });
        } else {
            swal(label_sw_cancel, label_sw_app_cancel, "error");
        }
    });
}
function sweetapprovedkb(link, id, note, d_from_,d_to_,harea) {
    swal({
        title: label_sw_app_title,
        text: label_sw_app_text,
        icon: "info",
        showCancelButton: true,
        buttons: {
            confirm: {
                text: label_sw_app,
                value: true,
                visible: true,
                className: "btn-success",
                closeModal: false,
            },
            cancel: {
                text: label_sw_cancel,
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
                    note: note
                },
                url: base_url + link + "/approve",
                dataType: "json",
                beforeSend: function() {
                    $(".app-content").block({
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true) {
                        swal(label_sw_sukses, label_sw_app_success, "success").then(
                            function(result) {
                                // window.location = base_url + link;
                                window.location = base_url + link + '/index/' + d_from_ + '/' + d_to_ + '/' + harea;
                            }
                        );
                    } else {
                        swal(label_sw_maaf, label_sw_app_failed, "error");
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    swal(label_sw_maaf, label_sw_app_failed, "error");
                    $(".app-content").unblock();
                },
            });
        } else {
            swal(label_sw_cancel, label_sw_app_cancel, "error");
        }
    });
}

function sweetapprovev3(link, id, note, d_from_,d_to_) {
    swal({
        title: label_sw_app_title,
        text: label_sw_app_text,
        icon: "info",
        showCancelButton: true,
        buttons: {
            confirm: {
                text: label_sw_app,
                value: true,
                visible: true,
                className: "btn-success",
                closeModal: false,
            },
            cancel: {
                text: label_sw_cancel,
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
                    note: note
                },
                url: base_url + link + "/approve",
                dataType: "json",
                beforeSend: function() {
                    $(".app-content").block({
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true) {
                        swal(label_sw_sukses, label_sw_app_success, "success").then(
                            function(result) {
                                window.location = base_url + link + '/index/' + d_from_ + '/' + d_to_;
                            }
                        );
                    } else {
                        swal(label_sw_maaf, label_sw_app_failed, "error");
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    swal(label_sw_maaf, label_sw_app_failed, "error");
                    $(".app-content").unblock();
                },
            });
        } else {
            swal(label_sw_cancel, label_sw_app_cancel, "error");
        }
    });
}

function sweetapprovev33(link, id, note, d_from_,d_to_,harea) {
    swal({
        title: label_sw_app_title,
        text: label_sw_app_text,
        icon: "info",
        showCancelButton: true,
        buttons: {
            confirm: {
                text: label_sw_app,
                value: true,
                visible: true,
                className: "btn-success",
                closeModal: false,
            },
            cancel: {
                text: label_sw_cancel,
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
                    note: note
                },
                url: base_url + link + "/approve",
                dataType: "json",
                beforeSend: function() {
                    $(".app-content").block({
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true) {
                        swal(label_sw_sukses, label_sw_app_success, "success").then(
                            function(result) {
                                window.location = base_url + link + '/index/' + d_from_ + '/' + d_to_ + '/' + harea;
                            }
                        );
                    } else {
                        swal(label_sw_maaf, label_sw_app_failed, "error");
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    swal(label_sw_maaf, label_sw_app_failed, "error");
                    $(".app-content").unblock();
                },
            });
        } else {
            swal(label_sw_cancel, label_sw_app_cancel, "error");
        }
    });
}

function sweetapprovev44(link, id, note, d_from_,d_to_,harea) {
    Swal.fire({
        title: label_sw_app_title,
        text: label_sw_app_text,
        type: "info",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_app,
        confirmButtonClass: "btn btn-info",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-secondary ml-1",
        buttonsStyling: !1,
    }).then(function(t) {
        if (t.value) {
            $.ajax({
                type: "POST",
                data: {
                    id: id,
                    note: note,
                },
                url: base_url + link + "/approve",
                dataType: "json",
                beforeSend: function() {
                    $(".app-content").block({
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_app_success,
                            /* animation: !1,
                            customClass: "animated flipInX", */
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                window.location = base_url + link + '/index/' + d_from_ + '/' + d_to_ + '/' + harea;
                            }
                        );
                    } else {
                        /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_app_failed,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                    Swal.fire({
                        type: "error",
                        title: label_sw_maaf,
                        text: label_sw_app_failed,
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                    $(".app-content").unblock();
                },
            });
        }
    });
}

function sweetapprovev444(link, id, d_from_,d_to_,harea) {
    Swal.fire({
        title: label_sw_app_title,
        text: label_sw_app_text,
        type: "info",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: label_sw_app,
        confirmButtonClass: "btn btn-info",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-secondary ml-1",
        buttonsStyling: !1,
    }).then(function(t) {
        if (t.value) {
            $.ajax({
                type: "POST",
                data: {
                    id: id,
                },
                url: base_url + link + "/approve",
                dataType: "json",
                beforeSend: function() {
                    $(".app-content").block({
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_app_success,
                            /* animation: !1,
                            customClass: "animated flipInX", */
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                window.location = base_url + link + '/index/' + d_from_ + '/' + d_to_ + '/' + harea;
                            }
                        );
                    } else {
                        /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_app_failed,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                    Swal.fire({
                        type: "error",
                        title: label_sw_maaf,
                        text: label_sw_app_failed,
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                    $(".app-content").unblock();
                },
            });
        }
    });
}

/** SweetAlert Not Approve Data */
function sweetnotapprove(link, id, note) {
    swal({
        title: label_sw_notapp_title,
        text: label_sw_notapp_text,
        icon: "error",
        showCancelButton: true,
        buttons: {
            confirm: {
                text: label_sw_notapp,
                value: true,
                visible: true,
                className: "btn-success",
                closeModal: false,
            },
            cancel: {
                text: label_sw_cancel,
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
                    note: note
                },
                url: base_url + link + "/notapprove",
                dataType: "json",
                beforeSend: function() {
                    $(".app-content").block({
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true) {
                        swal(label_sw_sukses, label_sw_notapp_success, "success").then(
                            function(result) {
                                window.location = base_url + link;
                            }
                        );
                    } else {
                        swal(label_sw_maaf, label_sw_notapp_failed, "error");
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    swal(label_sw_maaf, label_sw_notapp_failed, "error");
                    $(".app-content").unblock();
                },
            });
        } else {
            swal(label_sw_cancel, label_sw_notapp_cancel, "error");
        }
    });
}

function sweetnotapprovev3(link, id, note, d_from_,d_to_) {
    swal({
        title: label_sw_notapp_title,
        text: label_sw_notapp_text,
        icon: "error",
        showCancelButton: true,
        buttons: {
            confirm: {
                text: label_sw_notapp,
                value: true,
                visible: true,
                className: "btn-success",
                closeModal: false,
            },
            cancel: {
                text: label_sw_cancel,
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
                    note: note
                },
                url: base_url + link + "/notapprove",
                dataType: "json",
                beforeSend: function() {
                    $(".app-content").block({
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true) {
                        swal(label_sw_sukses, label_sw_notapp_success, "success").then(
                            function(result) {
                                window.location = base_url + link + '/index/' + d_from_ + '/' + d_to_;
                            }
                        );
                    } else {
                        swal(label_sw_maaf, label_sw_notapp_failed, "error");
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    swal(label_sw_maaf, label_sw_notapp_failed, "error");
                    $(".app-content").unblock();
                },
            });
        } else {
            swal(label_sw_cancel, label_sw_notapp_cancel, "error");
        }
    });
}

function sweetnotapprovev33(link, id, note, d_from_,d_to_,harea) {
    swal({
        title: label_sw_notapp_title,
        text: label_sw_notapp_text,
        icon: "error",
        showCancelButton: true,
        buttons: {
            confirm: {
                text: label_sw_notapp,
                value: true,
                visible: true,
                className: "btn-success",
                closeModal: false,
            },
            cancel: {
                text: label_sw_cancel,
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
                    note: note
                },
                url: base_url + link + "/notapprove",
                dataType: "json",
                beforeSend: function() {
                    $(".app-content").block({
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true) {
                        swal(label_sw_sukses, label_sw_notapp_success, "success").then(
                            function(result) {
                                window.location = base_url + link + '/index/' + d_from_ + '/' + d_to_ + '/' + harea;
                            }
                        );
                    } else {
                        swal(label_sw_maaf, label_sw_notapp_failed, "error");
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    swal(label_sw_maaf, label_sw_notapp_failed, "error");
                    $(".app-content").unblock();
                },
            });
        } else {
            swal(label_sw_cancel, label_sw_notapp_cancel, "error");
        }
    });
}

function sweetnotapprovev44(link, id, note, d_from_,d_to_,harea) {
    Swal.fire({
        title: label_sw_notapp_title,
        text: label_sw_notapp_text,
        type: "error",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#FF00FF",
        cancelButtonColor: "#FF00FF",
        confirmButtonText: label_sw_notapp,
        confirmButtonClass: "btn btn-danger",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-secondary ml-1",
        buttonsStyling: !1,
    }).then(function(t) {
        if (t.value) {
            $.ajax({
                type: "POST",
                data: {
                    id: id,
                    note: note,
                },
                url: base_url + link + "/notapprove",
                dataType: "json",
                beforeSend: function() {
                    $(".app-content").block({
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_notapp_success,
                            /* animation: !1,
                            customClass: "animated flipInX", */
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                window.location = base_url + link + '/index/' + d_from_ + '/' + d_to_ + '/' + harea;
                            }
                        );
                    } else {
                        /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_notapp_failed,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                    Swal.fire({
                        type: "error",
                        title: label_sw_maaf,
                        text: label_sw_notapp_failed,
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                    $(".app-content").unblock();
                },
            });
        }
    });
}

function sweetnotapprovev444(link, id, d_from_,d_to_,harea) {
    Swal.fire({
        title: label_sw_notapp_title,
        text: label_sw_notapp_text,
        type: "error",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        confirmButtonColor: "#FF00FF",
        cancelButtonColor: "#FF00FF",
        confirmButtonText: label_sw_notapp,
        confirmButtonClass: "btn btn-danger",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-secondary ml-1",
        buttonsStyling: !1,
    }).then(function(t) {
        if (t.value) {
            $.ajax({
                type: "POST",
                data: {
                    id: id,
                },
                url: base_url + link + "/notapprove",
                dataType: "json",
                beforeSend: function() {
                    $(".app-content").block({
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_notapp_success,
                            /* animation: !1,
                            customClass: "animated flipInX", */
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                window.location = base_url + link + '/index/' + d_from_ + '/' + d_to_ + '/' + harea;
                            }
                        );
                    } else {
                        /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                        Swal.fire({
                            type: "error",
                            title: label_sw_maaf,
                            text: label_sw_notapp_failed,
                            animation: !1,
                            customClass: "animated flipInX",
                            confirmButtonClass: "btn btn-danger",
                        });
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    /* Swal.fire(label_sw_maaf, label_sw_gagalubah, "error"); */
                    Swal.fire({
                        type: "error",
                        title: label_sw_maaf,
                        text: label_sw_notapp_failed,
                        animation: !1,
                        customClass: "animated flipInX",
                        confirmButtonClass: "btn btn-danger",
                    });
                    $(".app-content").unblock();
                },
            });
        }
    });
}

// $('.formatrupiah').on('keyup click change paste input', function (event) {
//     $(this).val(function (index, value) {
//         if (value != "") {
//             //return '$' + value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
//             var decimalCount;
//             value.match(/\./g) === null ? decimalCount = 0 : decimalCount = value.match(/\./g);

//             if (decimalCount.length > 1) {
//                 value = value.slice(0, -1);
//             }

//             var components = value.toString().split(".");
//             if (components.length === 1)
//                 components[0] = value;
//             components[0] = components[0].replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ',');
//             if (components.length === 2) {
//                 components[1] = components[1].replace(/\D/g, '').replace(/^\d{3}$/, '');
//             }

//             if (components.join('.') != '')
//                 return '' + components.join('.');
//             else
//                 return '0';
//         }
//     });
// });

$(".formatrupiah").on("keydown", function(e) {
    var keycode = (event.which) ? event.which : event.keyCode;
    if (e.shiftKey == true || e.ctrlKey == true) return false;
    if ([8, 110, 39, 37, 46].indexOf(keycode) >= 0 || // allow tab, dot, left and right arrows, delete keys
        (keycode == 190 && this.value.indexOf('.') === -1) || // allow dot if not exists in the value
        (keycode == 110 && this.value.indexOf('.') === -1) || // allow dot if not exists in the value
        (keycode >= 48 && keycode <= 57) || // allow numbers
        (keycode >= 96 && keycode <= 105)) { // allow numpad numbers
        // check for the decimals after dot and prevent any digits
        var parts = this.value.split('.');
        if (parts.length > 1 && // has decimals
            parts[1].length >= 2 && // should limit this
            (
                (keycode >= 48 && keycode <= 57) || (keycode >= 96 && keycode <= 105)
            ) // requested key is a digit
        ) {
            return false;
        } else {
            if (keycode == 110) {
                this.value += ".";
                return false;
            }
            return true;
        }
    } else {
        return false;
    }
}).on("keyup", function() {
    var parts = this.value.split('.');
    parts[0] = parts[0].replace(/,/g, '').replace(/^0+/g, '');
    if (parts[0] == "") parts[0] = "0";
    var calculated = parts[0].replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    if (parts.length >= 2) calculated += "." + parts[1].substring(0, 2);
    this.value = calculated;
    if (this.value == "NaN" || this.value == "") this.value = 0;
});

function formatrupiahkeyup(e) {
    var parts = e.value.split('.');
    parts[0] = parts[0].replace(/,/g, '').replace(/^0+/g, '');
    if (parts[0] == "") parts[0] = "0";
    var calculated = parts[0].replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    if (parts.length >= 2) calculated += "." + parts[1].substring(0, 2);
    e.value = calculated;
    if (e.value == "NaN" || e.value == "") e.value = 0;
}

function formatrupiahkeydown(e) {
    var keycode = (event.which) ? event.which : event.keyCode;
    if (e.shiftKey == true || e.ctrlKey == true) return false;
    if ([8, 110, 39, 37, 46].indexOf(keycode) >= 0 || // allow tab, dot, left and right arrows, delete keys
        (keycode == 190 && e.value.indexOf('.') === -1) || // allow dot if not exists in the value
        (keycode == 110 && e.value.indexOf('.') === -1) || // allow dot if not exists in the value
        (keycode >= 48 && keycode <= 57) || // allow numbers
        (keycode >= 96 && keycode <= 105)) { // allow numpad numbers
        // check for the decimals after dot and prevent any digits
        var parts = e.value.split('.');
        if (parts.length > 1 && // has decimals
            parts[1].length >= 2 && // should limit e
            (
                (keycode >= 48 && keycode <= 57) || (keycode >= 96 && keycode <= 105)
            ) // requested key is a digit
        ) {
            return false;
        } else {
            if (keycode == 110) {
                e.value += ".";
                return false;
            }
            return true;
        }
    } else {
        return false;
    }
}

function onlyangka(x) {
    x.value = x.value.replace(/[^\d.-]/g, '');
}

function bilanganasli(x) {
    var ASCIICode = (x.which) ? x.which : x.keyCode
    if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
        return false;
    return true
}

function reformat(input) {
    /* var num = input.value.replace(/\,/g, ""); */
    var num = input.value.replace(/[^\d.-]/g, '');
    if (!isNaN(num)) {
        if (num.indexOf(".") > -1) {
            num = num.split(".");
            num[0] = num[0]
                .toString()
                .split("")
                .reverse()
                .join("")
                .replace(/(?=\d*\.?)(\d{3})/g, "$1,")
                .split("")
                .reverse()
                .join("")
                .replace(/^[\,]/, "");
            if (num[1].length > 2) {
                alert("maksimum 2 desimal !!!");
                num[1] = num[1].substring(0, num[1].length - 1);
            }
            input.value = num[0] + "." + num[1];
        } else {
            input.value = num
                .toString()
                .split("")
                .reverse()
                .join("")
                .replace(/(?=\d*\.?)(\d{3})/g, "$1,")
                .split("")
                .reverse()
                .join("")
                .replace(/^[\,]/, "");
        }
    } else {
        alert("input harus numerik !!!");
        input.value = input.value.substring(0, input.value.length - 1);
    }
}

function formatulang(a) {
    var s = a.replace(/\,/g, "");
    return s;
}

function formatcemua(input) {
    var num = input.toString();
    if (!isNaN(num)) {
        if (num.indexOf(".") > -1) {
            num = num.split(".");
            num[0] = num[0]
                .toString()
                .split("")
                .reverse()
                .join("")
                .replace(/(?=\d*\.?)(\d{3})/g, "$1,")
                .split("")
                .reverse()
                .join("")
                .replace(/^[\,]/, "");
            if (num[1].length > 2) {
                while (num[1].length > 2) {
                    num[1] = num[1].substring(0, num[1].length - 1);
                }
            }
            input = num[0];
        } else {
            input = num
                .toString()
                .split("")
                .reverse()
                .join("")
                .replace(/(?=\d*\.?)(\d{3})/g, "$1,")
                .split("")
                .reverse()
                .join("")
                .replace(/^[\,]/, "");
        }
    }
    return input;
}

function format2Decimals(str) {
    return parseFloat(str).toFixed(2);
  }

  
function formatDate(d) {
    var dd = d.getDate();
    if (dd < 10) dd = "0" + dd;
    var mm = d.getMonth() + 1;
    if (mm < 10) mm = "0" + mm;
    var yy = d.getFullYear();
    return dd + "-" + mm + "-" + yy;
}

function splitdate(d) {
    if (d != '') {
        var dd = d.split("-")[0];
        var mm = d.split("-")[1];
        var yy = d.split("-")[2];
        return yy + mm + dd;
    } else {
        return null;
    }
}

function DateAdd(ItemType, DateToWorkOn, ValueToBeAdded) {
    switch (ItemType) {
        case "d": //add days
            DateToWorkOn.setDate(DateToWorkOn.getDate() + ValueToBeAdded);
            break;
        case "m": //add months
            DateToWorkOn.setMonth(DateToWorkOn.getMonth() + ValueToBeAdded);
            break;
        case "y": //add years
            DateToWorkOn.setYear(DateToWorkOn.getFullYear() + ValueToBeAdded);
            break;
        case "h": //add days
            DateToWorkOn.setHours(DateToWorkOn.getHours() + ValueToBeAdded);
            break;
        case "n": //add minutes
            DateToWorkOn.setMinutes(DateToWorkOn.getMinutes() + ValueToBeAdded);
            break;
        case "s": //add seconds
            DateToWorkOn.setSeconds(DateToWorkOn.getSeconds() + ValueToBeAdded);
            break;
    }
    return DateToWorkOn;
}

function number_format(number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

String.prototype.replaceAll = function(stringToFind, stringToReplace) {
    if (stringToFind === stringToReplace) return this;
    var temp = this;
    var index = temp.indexOf(stringToFind);
    while (index != -1) {
        temp = temp.replace(stringToFind, stringToReplace);
        index = temp.indexOf(stringToFind);
    }
    return temp;
};

function formatulang(a) {
    var s = a.replace(/\,/g, "");
    return s;
}

function pickdate() {
    var date = new Date();
    $('.date').pickadate({
        labelMonthNext: g_BulanSelanjutnya,
        labelMonthPrev: g_BulanSebelumnya,
        labelMonthSelect: g_PilihBulan,
        labelYearSelect: g_PilihTahun,
        selectMonths: true,
        selectYears: true,
        format: 'dd-mm-yyyy',
        formatSubmit: 'yyyy-mm-dd',
        monthsFull: [g_Januari, g_Februari, g_Maret, g_April, g_Mei, g_Juni, g_July, g_Agustus, g_September, g_Oktober, g_Nopember, g_Desember],
        monthsShort: [g_Jan, g_Feb, g_Mar, g_Apr, g_Mei, g_Jun, g_Jul, g_Ags, g_Sep, g_Okt, g_Nop, g_Des],
        weekdaysShort: [g_Ming, g_Sen, g_Sel, g_Rab, g_Kam, g_Jum, g_Sab],
        today: g_Hariini,
        clear: g_Bersihkan,
        close: g_Tutup,
        // min : ,
    });
    var picker = $('.date').pickadate('picker');
    picker.set('select', date);
    picker.set('min', new Date(date.getFullYear(), date.getMonth(), 1));
}

function pickdatetabel() {
    var date = new Date();
    $('.date').pickadate({
        labelMonthNext: g_BulanSelanjutnya,
        labelMonthPrev: g_BulanSebelumnya,
        labelMonthSelect: g_PilihBulan,
        labelYearSelect: g_PilihTahun,
        selectMonths: true,
        selectYears: true,
        format: 'dd-mm-yyyy',
        formatSubmit: 'yyyy-mm-dd',
        monthsFull: [g_Januari, g_Februari, g_Maret, g_April, g_Mei, g_Juni, g_July, g_Agustus, g_September, g_Oktober, g_Nopember, g_Desember],
        monthsShort: [g_Jan, g_Feb, g_Mar, g_Apr, g_Mei, g_Jun, g_Jul, g_Ags, g_Sep, g_Okt, g_Nop, g_Des],
        weekdaysShort: [g_Ming, g_Sen, g_Sel, g_Rab, g_Kam, g_Jum, g_Sab],
        today: g_Hariini,
        clear: g_Bersihkan,
        close: g_Tutup,
        max: date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate(),
    });
    /* var picker = $('.date').pickadate('picker');
    picker.set('select', date);
    picker.set('max', new Date(date.getFullYear(), date.getMonth(), 1)); */
}

function pickdatemonth() {
    var date = new Date();
    $('.date').pickadate({
        labelMonthNext: g_BulanSelanjutnya,
        labelMonthPrev: g_BulanSebelumnya,
        labelMonthSelect: g_PilihBulan,
        labelYearSelect: g_PilihTahun,
        selectMonths: true,
        selectYears: true,
        format: 'dd mmmm yyyy',
        formatSubmit: 'yyyy-mm-dd',
        monthsFull: [g_Januari, g_Februari, g_Maret, g_April, g_Mei, g_Juni, g_July, g_Agustus, g_September, g_Oktober, g_Nopember, g_Desember],
        monthsShort: [g_Jan, g_Feb, g_Mar, g_Apr, g_Mei, g_Jun, g_Jul, g_Ags, g_Sep, g_Okt, g_Nop, g_Des],
        weekdaysShort: [g_Ming, g_Sen, g_Sel, g_Rab, g_Kam, g_Jum, g_Sab],
        today: g_Hariini,
        clear: g_Bersihkan,
        close: g_Tutup,
        // min : ,
    });
    var picker = $('.date').pickadate('picker');
    picker.set('select', date);
    picker.set('min', new Date(date.getFullYear(), date.getMonth(), 1));
}

function pickdateedit() {
    var date = new Date();
    $('.date').pickadate({
        labelMonthNext: g_BulanSelanjutnya,
        labelMonthPrev: g_BulanSebelumnya,
        labelMonthSelect: g_PilihBulan,
        labelYearSelect: g_PilihTahun,
        selectMonths: true,
        selectYears: true,
        format: 'dd-mm-yyyy',
        formatSubmit: 'yyyy-mm-dd',
        monthsFull: [g_Januari, g_Februari, g_Maret, g_April, g_Mei, g_Juni, g_July, g_Agustus, g_September, g_Oktober, g_Nopember, g_Desember],
        monthsShort: [g_Jan, g_Feb, g_Mar, g_Apr, g_Mei, g_Jun, g_Jul, g_Ags, g_Sep, g_Okt, g_Nop, g_Des],
        weekdaysShort: [g_Ming, g_Sen, g_Sel, g_Rab, g_Kam, g_Jum, g_Sab],
        today: g_Hariini,
        clear: g_Bersihkan,
        close: g_Tutup,
        // min : ,
    });
}

function pickdatemonthandyear() {
    var date = new Date();
    $('.date').pickadate({
        labelMonthNext: g_BulanSelanjutnya,
        labelMonthPrev: g_BulanSebelumnya,
        labelMonthSelect: g_PilihBulan,
        labelYearSelect: g_PilihTahun,
        selectMonths: true,
        selectYears: true,
        format: 'mmmm-yyyy',
        formatSubmit: 'yyyy-mm-dd',
        monthsFull: [g_Januari, g_Februari, g_Maret, g_April, g_Mei, g_Juni, g_July, g_Agustus, g_September, g_Oktober, g_Nopember, g_Desember],
        monthsShort: [g_Jan, g_Feb, g_Mar, g_Apr, g_Mei, g_Jun, g_Jul, g_Ags, g_Sep, g_Okt, g_Nop, g_Des],
        weekdaysShort: [g_Ming, g_Sen, g_Sel, g_Rab, g_Kam, g_Jum, g_Sab],
        today: g_Hariini,
        clear: g_Bersihkan,
        close: g_Tutup,
    });
    var picker = $('.date').pickadate('picker');
    picker.set('select', date);
    picker.set('min', new Date(date.getFullYear(), date.getMonth(), date.getDate));
}

function pickdateperiode() {
    var date = new Date();
    $('.date').pickadate({
        labelMonthNext: g_BulanSelanjutnya,
        labelMonthPrev: g_BulanSebelumnya,
        labelMonthSelect: g_PilihBulan,
        labelYearSelect: g_PilihTahun,
        selectMonths: true,
        selectYears: true,
        format: 'dd-mm-yyyy',
        formatSubmit: 'yyyy-mm-dd',
        monthsFull: [g_Januari, g_Februari, g_Maret, g_April, g_Mei, g_Juni, g_July, g_Agustus, g_September, g_Oktober, g_Nopember, g_Desember],
        monthsShort: [g_Jan, g_Feb, g_Mar, g_Apr, g_Mei, g_Jun, g_Jul, g_Ags, g_Sep, g_Okt, g_Nop, g_Des],
        weekdaysShort: [g_Ming, g_Sen, g_Sel, g_Rab, g_Kam, g_Jum, g_Sab],
        today: g_Hariini,
        clear: g_Bersihkan,
        close: g_Tutup,
        max: date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate(),
        min: date.getFullYear() + '-' + (date.getMonth() + 1) + '-01',
    });
    /* var picker = $('.date').pickadate('picker');
    picker.set('select', date);
    picker.set('max', new Date(date.getFullYear(), date.getMonth(), 1)); */
}

function openLink(link, id) {
    window.open('' + base_url + link + '/print/' + id, '1429893142534', 'width=' + screen.availWidth + ',height=' + screen.availHeight + ',toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=0,left=0,top=0');
    return false;
}

function openLink2(link, dfrom, dto, i_area) {
    window.open('' + base_url + link + '/print/?d_from=' + dfrom + '&d_to=' + dto + '&i_area=' + i_area, '1429893142534', 'width=' + screen.availWidth + ',height=' + screen.availHeight + ',toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=0,left=0,top=0');
    return false;
}


/** SweetAlert V2 3 Button */
function sweet3button(link, formData) {
    Swal.fire({
        title: label_sw_title,
        text: label_sw_text,
        type: "info",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        showDenyButton: !0,
        denyButtonText: "Hanya Simpan",
        denyButtonColor: "#3085d6",
        denyButtonClass: "btn btn-primary ml-1",
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Buat SPB Baru",
        confirmButtonClass: "btn btn-primary",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-danger ml-1",
        buttonsStyling: !1,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                enctype: "multipart/form-data",
                data: formData,
                url: base_url + link + "/save_turunan",
                dataType: "json",
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function() {
                    $(".app-content").block({
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true && data.ada == false) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_simpan,
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                window.location = base_url + link;
                            }
                        );
                    } else if (data.sukses == false && data.ada == true) {
                        Swal.fire(label_sw_maaf, label_sw_duplikat, "error");
                    } else {
                        Swal.fire(label_sw_maaf, label_sw_gagal, "error");
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    Swal.fire(label_sw_maaf, label_sw_gagal, "error");
                    $(".app-content").unblock();
                },
            });
        } else if (result.isDenied) {
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
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true && data.ada == false) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_simpan,
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                window.location = base_url + link;
                            }
                        );
                    } else if (data.sukses == false && data.ada == true) {
                        Swal.fire(label_sw_maaf, label_sw_duplikat, "error");
                    } else {
                        Swal.fire(label_sw_maaf, label_sw_gagal, "error");
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    Swal.fire(label_sw_maaf, label_sw_gagal, "error");
                    $(".app-content").unblock();
                },
            });
        }
    })
}
/** SweetAlert V2 3 Button */
function sweet33turunan(link, dfrom, dto,harea, formData) {
    Swal.fire({
        title: label_sw_title,
        text: label_sw_text,
        type: "info",
        animation: !1,
        customClass: "animated flipInX",
        showCancelButton: !0,
        showDenyButton: !0,
        denyButtonText: "Hanya Simpan",
        denyButtonColor: "#3085d6",
        denyButtonClass: "btn btn-primary ml-1",
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Buat SPB Baru",
        confirmButtonClass: "btn btn-primary",
        cancelButtonText: label_sw_cancel,
        cancelButtonClass: "btn btn-danger ml-1",
        buttonsStyling: !1,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                enctype: "multipart/form-data",
                data: formData,
                url: base_url + link + "/save_turunan",
                dataType: "json",
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function() {
                    $(".app-content").block({
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true && data.ada == false) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_simpan,
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                // window.location = base_url + link;
                                window.location = base_url + link + '/index/' + dfrom + '/' + dto + '/' + harea;
                            }
                        );
                    } else if (data.sukses == false && data.ada == true) {
                        Swal.fire(label_sw_maaf, label_sw_duplikat, "error");
                    } else {
                        Swal.fire(label_sw_maaf, label_sw_gagal, "error");
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    Swal.fire(label_sw_maaf, label_sw_gagal, "error");
                    $(".app-content").unblock();
                },
            });
        } else if (result.isDenied) {
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
                        /* message: '<img src="' +
                            base_url +
                            'assets/images/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">L o a d i n g</h1>', */
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
                success: function(data) {
                    if (data.sukses == true && data.ada == false) {
                        Swal.fire({
                            type: "success",
                            title: label_sw_sukses,
                            text: label_sw_simpan,
                            confirmButtonClass: "btn btn-success",
                        }).then(
                            function(result) {
                                window.location = base_url + link;
                            }
                        );
                    } else if (data.sukses == false && data.ada == true) {
                        Swal.fire(label_sw_maaf, label_sw_duplikat, "error");
                    } else {
                        Swal.fire(label_sw_maaf, label_sw_gagal, "error");
                    }
                    $(".app-content").unblock();
                },
                error: function() {
                    Swal.fire(label_sw_maaf, label_sw_gagal, "error");
                    $(".app-content").unblock();
                },
            });
        }
    })
}

function set_day(date) {
    const d = new Date(date);
    const weekday = new Array(7);
    weekday[0] = "Minggu";
    weekday[1] = "Senin";
    weekday[2] = "Selasa";
    weekday[3] = "Rabu";
    weekday[4] = "Kamis";
    weekday[5] = "Jum'at";
    weekday[6] = "Sabtu";
    let day = weekday[d.getDay()];
    return day;
}

function terbilang(bilangan) {

    bilangan = String(bilangan);
    var angka = new Array('0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
    var kata = new Array('', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan');
    var tingkat = new Array('', 'Ribu', 'Juta', 'Milyar', 'Triliun');

    var panjang_bilangan = bilangan.length;

    /* pengujian panjang bilangan */
    if (panjang_bilangan > 15) {
        kaLimat = "Diluar Batas";
        return kaLimat;
    }

    /* mengambil angka-angka yang ada dalam bilangan, dimasukkan ke dalam array */
    for (i = 1; i <= panjang_bilangan; i++) {
        angka[i] = bilangan.substr(-(i), 1);
    }

    i = 1;
    j = 0;
    kaLimat = "";


    /* mulai proses iterasi terhadap array angka */
    while (i <= panjang_bilangan) {

        subkaLimat = "";
        kata1 = "";
        kata2 = "";
        kata3 = "";

        /* untuk Ratusan */
        if (angka[i + 2] != "0") {
            if (angka[i + 2] == "1") {
                kata1 = "Seratus";
            } else {
                kata1 = kata[angka[i + 2]] + " Ratus";
            }
        }

        /* untuk Puluhan atau Belasan */
        if (angka[i + 1] != "0") {
            if (angka[i + 1] == "1") {
                if (angka[i] == "0") {
                    kata2 = "Sepuluh";
                } else if (angka[i] == "1") {
                    kata2 = "Sebelas";
                } else {
                    kata2 = kata[angka[i]] + " Belas";
                }
            } else {
                kata2 = kata[angka[i + 1]] + " Puluh";
            }
        }

        /* untuk Satuan */
        if (angka[i] != "0") {
            if (angka[i + 1] != "1") {
                kata3 = kata[angka[i]];
            }
        }

        /* pengujian angka apakah tidak nol semua, lalu ditambahkan tingkat */
        if ((angka[i] != "0") || (angka[i + 1] != "0") || (angka[i + 2] != "0")) {
            subkaLimat = kata1 + " " + kata2 + " " + kata3 + " " + tingkat[j] + " ";
        }

        /* gabungkan variabe sub kaLimat (untuk Satu blok 3 angka) ke variabel kaLimat */
        kaLimat = subkaLimat + kaLimat;
        i = i + 3;
        j = j + 1;

    }

    /* mengganti Satu Ribu jadi Seribu jika diperlukan */
    if ((angka[5] == "0") && (angka[6] == "0")) {
        kaLimat = kaLimat.replace("Satu Ribu", "Seribu");
    }

    return kaLimat + "Rupiah";
}