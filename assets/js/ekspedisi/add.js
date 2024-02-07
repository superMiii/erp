$(document).ready(function() {
    
    var controller = $("#path").val();
    var Detailx = $(function() {
        var ii = $("#jmlx").val();
        $("#addriw").on("click", function() {
            ii++;
            var noo = $("#tablecoverarea tr").length;
            $("#jmlx").val(ii);
            var newRow = $("<tr>");
            var cols = "";
            cols += `<td class="text-center"><spanx id="snum${ii}">${noo}</spanx></td>`;
            cols += `<td><select data-nourut="${ii}" required class="form-control" name="i_area${ii}" id="i_area${ii}"><option value=""></option></select></td>`;
            // cols += `<td><select data-urut="${ii}" required class="form-control" multiple name="i_city${ii}[]" id="i_city${ii}"><option value=""></option></select></td>`;
            // cols += `<td class="text-center"><fieldset><div class="float-center"><input type="checkbox" onchange="cek(${ii});" id="f_all_city${ii}" class="switch" data-onstyle="success" data-on-label="Ya" data-off-label="Tidak" data-switch-always /><input type="hidden" id="f_checked${ii}" name="f_checked${ii}" value="f"/></div></fieldset></td>`;
            cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-2x text-danger ibtnDel"></i></td>`;
            newRow.append(cols);
            $("#tablecoverarea").append(newRow);
            $(".switch:checkbox").checkboxpicker();
            $("#i_area" + ii)
                .select2({
                    placeholder: "Select",
                    dropdownAutoWidth: true,
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
                })
                .change(function(event) {
                    var zz = $(this).data("nourut");
                    // $("#i_city" + zz).val("");
                    // $("#i_city" + zz).html("");
                    var ada = false;
                    for (var xx = 1; xx <= $("#jmlx").val(); xx++) {
                        if ($(this).val() != null) {
                            if (
                                $(this).val() ==
                                $(
                                    "#i_area" + xx
                                ).val() /* && $("#i_area" + z).val() == $("#i_area" + x).val() */ &&
                                zz != xx
                            ) {
                                swal("Sorry :(", "Data already exist ..", "error");
                                ada = true;
                                break;
                            }
                        }
                    }
                    if (ada) {
                        $(this).val("");
                        $(this).html("");
                        // $("#i_city" + zz).val("");
                        // $("#i_city" + zz).html("");
                    }
                });

            // $("#i_city" + ii).select2({
            //     placeholder: "Select",
            //     dropdownAutoWidth: true,
            //     width: "100%",
            //     allowClear: true,
            //     ajax: {
            //         url: base_url + controller + "/get_city",
            //         dataType: "json",
            //         delay: 250,
            //         data: function(params) {
            //             var query = {
            //                 q: params.term,
            //                 i_area: $("#i_area" + $(this).data("urut")).val(),
            //             };
            //             return query;
            //         },
            //         processResults: function(data) {
            //             return {
            //                 results: data,
            //             };
            //         },
            //         cache: false,
            //     },
            // });
        });

        $("#tablecoverarea").on("click", ".ibtnDel", function(event) {
            $(this).closest("tr").remove();

            $("#jmlx").val(ii);
            var obj = $("#tablecoverarea tr:visible").find("spanx");
            $.each(obj, function(key, value) {
                id = value.id;
                $("#" + id).html(key + 1);
            });
        });
    });

    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $("form").on("submit", function(e) {
        //bind event on form submit.
        let tabelx = $("#tablecoverarea tbody tr").length;

        if (tabelx < 1) {
            swal("Sorry :(", "Input item Area minimum 1!", "error");
            return false;
        }

        e.preventDefault();
        var formData = new FormData(this);
        if (formData) {
            sweetadd(controller, formData);
        }
    });
});

// function cek(i) {
//     var Checked = $("#f_all_city" + i + ":checkbox:checked").length;
//     if (Checked > 0) {
//         $("#f_checked" + i).val("t");
//         $("#i_city" + i).val("");
//         $("#i_city" + i).html("");
//         $("#i_city" + i).prop("required", false);
//         $("#i_city" + i).prop("disabled", true);
//     } else {
//         $("#f_checked" + i).val("f");
//         $("#i_city" + i).prop("required", true);
//         $("#i_city" + i).prop("disabled", false);
//     }
// }