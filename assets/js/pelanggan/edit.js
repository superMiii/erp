$(document).ready(function() {
    $(".switch:checkbox").checkboxpicker();
    var controller = $("#path").val();

    // $('.percentage-inputmask').inputmask("99.99%");

    $('.skin-square input').iCheck({
        checkboxClass: 'icheckbox_square-red',
        radioClass: 'iradio_square-red',
    });

    $("#iarea").select2({
        placeholder:  $("#textcari_area").val(),
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_area",
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
    }) .change(function (event) {
        $('#icity').val(null);
        $('#icity').html(null);
        $('#icover').val(null);
        $('#icover').html(null);
    });


    $("#icity").select2({
        placeholder:  $("#textcari_city").val(),
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_city",
            dataType: "json",
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
                    param1 : $("#iarea").val(),
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
    }) .change(function (event) {
        $('#icover').val(null);
        $('#icover').html(null);
    });

    $("#icover").select2({
        placeholder:  $("#textcari_cover").val(),
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_cover",
            dataType: "json",
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
                    param1 : $("#iarea").val(),
                    param2 : $("#icity").val(),
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

    $("#igroup").select2({
        placeholder:  $("#textcari_group").val(),
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_group",
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

    $("#itype").select2({
        placeholder:  $("#textcari_type").val(),
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_type",
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

    $("#ilevel").select2({
        placeholder:  $("#textcari_level").val(),
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_level",
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

    $("#istatus").select2({
        placeholder:  $("#textcari_status").val(),
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_status",
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


    $("#iprice").select2({
        placeholder:  $("#textcari_price").val(),
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_price",
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


    $("#ipayment").select2({
        placeholder:  $("#textcari_payment").val(),
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_payment",
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


    $("#ipaygroup").select2({
        placeholder:  $("#textcari_paygroup").val(),
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_paygroup",
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

    $('#chk-samasp').on('ifChanged', function(event) {
        if (event.target.checked == true) {
            $('#e_shipment_address').val($('#alamat').val());
        } else {
            $('#e_shipment_address').val('');
        }
    });

    $('#chk-sama').on('ifChanged', function(event) {
        if (event.target.checked == true) {
            $('#alamat_npwp').val($('#alamat').val());
        } else {
            $('#alamat_npwp').val('');
        }
    });

    $('#chk-flafon').on('ifChanged', function(event) {
        if (event.target.checked == true) {
            $("#ipaygroup").attr("disabled", true);
        } else {
            $("#ipaygroup").attr("disabled", false);
        }
    });


    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $('form').on('submit', function(e) { //bind event on form submit.
        e.preventDefault();
        var formData = new FormData(this);
        if (formData) {
            sweetedit(controller, formData);
        }
    });

    //$('.pickadate-selectors').pickadate({
        // labelMonthNext: 'Bulan Selanjutnya',
        // labelMonthPrev: 'Bulan Sebelumnya',
        // labelMonthSelect: 'Pilih Bulan',
        // labelYearSelect: 'Pilih Tahun',
        // selectMonths: true,
        // selectYears: true,
        // format: 'dd mmmm yyyy',
        // formatSubmit: 'yyyy-mm-dd',
        // monthsFull: [ 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'July', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember' ],
        // monthsShort: [ 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nop', 'Des' ],
        // weekdaysShort: [ 'Ming', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab' ],
        // today: 'Hari ini',
        // clear: 'Bersihkan',
        // close: 'Tutup'

    //});
    $('.pickadate-selectors').pickadate({
        labelMonthNext: g_BulanSelanjutnya,
        labelMonthPrev: g_BulanSebelumnya,
        labelMonthSelect: g_PilihBulan,
        labelYearSelect: g_PilihTahun,
        selectMonths: true,
        selectYears: true,
        format: 'dd-mm-yyyy',
        formatSubmit: 'yyyy-mm-dd',
        monthsFull: [g_Januari,g_Februari,g_Maret,g_April,g_Mei,g_Juni,g_July,g_Agustus,g_September,g_Oktober,g_Nopember,g_Desember ],
        monthsShort: [g_Jan,g_Feb,g_Mar,g_Apr,g_Mei,g_Jun,g_Jul,g_Ags,g_Sep,g_Okt,g_Nop,g_Des ],
        weekdaysShort: [g_Ming,g_Sen,g_Sel,g_Rab,g_Kam,g_Jum,g_Sab ],
        today: g_Hariini,
        clear: g_Bersihkan,
        close: g_Tutup
    });
    // var date = new Date($('#tanggal').val());
    // var picker = $('#tanggal').pickadate('picker');
    // picker.set('select', date);
    /* $("#submit").on("click", function () {
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();

        var form = $('.form-validation').jqBootstrapValidation();
        if (form) {
            sweetadd(controller);
        }
    }); */
});