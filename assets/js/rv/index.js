$(document).ready(function() {
    pickdatetabel();
    var controller = $("#path").val() + "/serverside";
    var linkadd = $("#path").val() + "/add";
    var column = $("#serverside thead tr th").length;
    var id_menu = $("#id_menu").val();
    /* var color       = $("#color").val(); */
    var d_from_ = $("#d_from").val();
    var d_to_ = $("#d_to").val();
    var htype = $("#htype").val();
    var harea = $("#harea").val();
    var hcoa = $("#hcoa").val();
    var params = {
        dfrom: $("#dfrom").val(),
        dto: $("#dto").val(),
        i_type: $("#i_rv_type").val(),
        i_area: $("#i_area").val(),
        i_coa: $("#i_coa").val(),
    };
    var right = [5];
    if (id_menu != "") {
        // datatableaddparams(controller, column, linkadd, params, right);
        datatableadd5(controller, column, linkadd, params, right,d_from_,d_to_,htype,harea,hcoa);
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
    } else {
        // datatableparams(controller, column, params);
        datatable5(controller, column, params, d_from_,d_to_,htype,harea,hcoa);
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
    }
    $("#i_rv_type").select2({
        dropdownAutoWidth: true,
        width: "100%",
        /* containerCssClass: "select-sm", */
    });
    $("#i_coa").select2({
        dropdownAutoWidth: true,
        width: "100%",
        /* containerCssClass: "select-sm", */
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_coa2",
            dataType: "json",
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
                    i_rv_type: $('#i_rv_type').val(),
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
});

function refreshview(link) {
    // window.location = link;
    window.location = link + '/index/' + $("#d_from").val() + '/' + $("#d_to").val() + '/' + $("#i_type").val() + '/' + $("#harea").val()  + '/' + $("#i_coa").val()  ;
}