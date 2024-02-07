$(document).ready(function() {
    $('.select2').select2({
        width: "100%",
        containerCssClass: 'select-sm',
    });
    var controller = $("#path").val();
    $("#i_area").select2({
        width: "100%",
        dropdownAutoWidth: true,
        containerCssClass: 'select-sm',
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
    });

    for (let i = 1; i <= $('#jml').val(); i++) {
        $("#i_city" + i).select2({
            placeholder: "Select",
            dropdownAutoWidth: true,
            width: '100%',
            containerCssClass: 'select-sm',
            allowClear: true,
            ajax: {
                url: base_url + controller + "/get_city",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        i_area: $('#i_area').val(),
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
        }).change(function(event) {
            var z = $(this).data("nourut");
            var ada = false;
            for (var x = 1; x <= $("#jml").val(); x++) {
                if ($(this).val() != null) {
                    if ($(this).val() == $("#i_city" + x).val() && $("#i_salesman" + z).val() == $("#i_salesman" + x).val() && z != x) {
                        Swal.fire({
                            type: "error",
                            title: "Sorry :(",
                            text: "Data already exist :)",
                            confirmButtonClass: "btn btn-danger",
                        });
                        ada = true;
                        break;
                    }
                }
            }
            if (ada) {
                $(this).val("");
                $(this).html("");
                $('#i_salesman' + z).html("");
                $('#i_salesman' + z).html("");
            }
        });
        $("#i_salesman" + i).select2({
            placeholder: "Select",
            dropdownAutoWidth: true,
            width: '100%',
            containerCssClass: 'select-sm',
            allowClear: true,
            ajax: {
                url: base_url + controller + "/get_salesman",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        i_area: $('#i_area').val(),
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
        }).change(function(event) {
            var z = $(this).data("nourut");
            var ada = false;
            for (var x = 1; x <= $("#jml").val(); x++) {
                if ($(this).val() != null) {
                    if ($(this).val() == $("#i_city" + x).val() && $("#i_salesman" + z).val() == $("#i_salesman" + x).val() && z != x) {
                        Swal.fire({
                            type: "error",
                            title: "Sorry :(",
                            text: "Data already exist :)",
                            confirmButtonClass: "btn btn-danger",
                        });
                        ada = true;
                        break;
                    }
                }
            }
            if (ada) {
                $(this).val("");
                $(this).html("");
            }
        });
    }

    var Detail = $(function() {
        var i = $("#jml").val();
        $("#addrow").on("click", function() {
            i++;
            var no = $("#tablecover tr").length;
            $("#jml").val(i);
            var newRow = $("<tr>");
            var cols = "";
            cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
            cols += `<td><select data-nourut="${i}" required class="form-control" name="i_city[]" id="i_city${i}"><option value=""></option></select></td>`;
            cols += `<td><select data-urut="${i}" required class="form-control" name="i_salesman[]" id="i_salesman${i}"><option value=""></option></select></td>`;
            cols += `<td><input type="text" class="form-control text-right" value="0" onkeyup="onlyangka(this); reformat(this);" placeholder="Rp. 0,00" id="v_target_city${i}" name="v_target_city[]" maxlength="16" autocomplete="off" required onblur=\"if(this.value==''){this.value='0';}\" onfocus=\"if(this.value=='0'){this.value='';}\"></td>`;
            cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-2x text-danger ibtnDel"></i></td>`;
            newRow.append(cols);
            $("#tablecover").append(newRow);
            $("#i_city" + i).select2({
                placeholder: "Select",
                dropdownAutoWidth: true,
                width: '100%',
                containerCssClass: 'select-sm',
                allowClear: true,
                ajax: {
                    url: base_url + controller + "/get_city",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
                            i_area: $('#i_area').val(),
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
            }).change(function(event) {
                var z = $(this).data("nourut");
                var ada = false;
                for (var x = 1; x <= $("#jml").val(); x++) {
                    if ($(this).val() != null) {
                        if ($(this).val() == $("#i_city" + x).val() && $("#i_salesman" + z).val() == $("#i_salesman" + x).val() && z != x) {
                            Swal.fire({
                                type: "error",
                                title: "Sorry :(",
                                text: "Data already exist :)",
                                confirmButtonClass: "btn btn-danger",
                            });
                            ada = true;
                            break;
                        }
                    }
                }
                if (ada) {
                    $(this).val("");
                    $(this).html("");
                    $('#i_salesman' + z).html("");
                    $('#i_salesman' + z).html("");
                }
            });
            $("#i_salesman" + i).select2({
                placeholder: "Select",
                dropdownAutoWidth: true,
                width: '100%',
                containerCssClass: 'select-sm',
                allowClear: true,
                ajax: {
                    url: base_url + controller + "/get_salesman",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
                            i_area: $('#i_area').val(),
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
            }).change(function(event) {
                var z = $(this).data("nourut");
                var ada = false;
                for (var x = 1; x <= $("#jml").val(); x++) {
                    if ($(this).val() != null) {
                        if ($(this).val() == $("#i_city" + x).val() && $("#i_salesman" + z).val() == $("#i_salesman" + x).val() && z != x) {
                            Swal.fire({
                                type: "error",
                                title: "Sorry :(",
                                text: "Data already exist :)",
                                confirmButtonClass: "btn btn-danger",
                            });
                            ada = true;
                            break;
                        }
                    }
                }
                if (ada) {
                    $(this).val("");
                    $(this).html("");
                }
            });
        });

        /*----------  Hapus Baris Data Saudara  ----------*/

        $("#tablecover").on("click", ".ibtnDel", function(event) {
            $(this).closest("tr").remove();

            $("#jml").val(i);
            var obj = $("#tablecover tr:visible").find("spanx");
            $.each(obj, function(key, value) {
                id = value.id;
                $("#" + id).html(key + 1);
            });
        });
    });

    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $('form').on('submit', function(e) { //bind event on form submit.
        let tabel = $("#tablecover tbody tr").length;
        if (tabel < 1) {
            swal("Sorry :(", "Input item Departemen & Level minimum 1!", "error");
            return false;
        }
        e.preventDefault();
        var formData = new FormData(this);
        if (formData) {
            sweeteditv2(controller, formData);
        }
    });
});