$(document).ready(function () {
	pickdatetabel();
	number();
	$("#addrow").hide();

	$("#i_supplier")
		.select2({
			dropdownAutoWidth: true,
			width: "100%",
			containerCssClass: "select-sm",
			allowClear: true,
			ajax: {
				url: base_url + $("#path").val() + "/get_supplier",
				dataType: "json",
				delay: 250,
				data: function (params) {
					var query = {
						q: params.term,
					};
					return query;
				},
				processResults: function (data) {
					return {
						results: data,
					};
				},
				cache: false,
			},
		})
		.change(function (event) {
			clear_tabel();
			$.ajax({
				type: "post",
				data: {
					i_supplier: $(this).val(),
				},
				url: base_url + $("#path").val() + "/get_detail_supplier",
				dataType: "json",
				success: function (data) {
					if (data["data"] != null) {
						var ppn = "Tidak";
						var pkp = data["data"]["f_supplier_pkp"];
						$("#f_supplier_pkp").val(pkp);
						$("#n_ppn").val(data["data"]["n_ppn"]);
						$("#n_supplier_top").val(data["data"]["n_supplier_top"]);
						if (pkp == "t") {
							ppn = "Ya";
						}
						$("#status_ppn").val(ppn);
						$("#d_jatuh_tempo").val(jatuhtempo());
						$("#addrow").show();
					}
				},
				error: function () {
					swal("500 internal server error : (");
				},
			});
		});

	$("#d_document").change(function () {
		$("#d_jatuh_tempo").val(jatuhtempo());
	});

	$("#d_document").change(function () {
		number();
	});

	var Detail = $(function () {
		var i = $("#jml").val();
		$("#addrow").on("click", function () {
			i++;
			var no = $("#tabledetail tbody tr").length + 1;
			$("#jml").val(i);
			var newRow = $("<tr>");
			var cols = "";
			cols += `<td class="text-center" valign="center"><spanx id="snum${i}">${no}</spanx></td>`;
			cols += `<td><select data-nourut="${i}" required class="form-control select2-size-sm nota" name="i_nota[]" id="i_nota${i}"><option value=""></option></select></td>`;
			cols += `<td><input type="text" readonly class="form-control form-control-sm" id="d_nota${i}" name="d_nota[]" readonly></td>`;
			cols += `<td><input type="text" readonly class="form-control form-control-sm text-right v_jumlah" value="0" id="v_jumlah_item${i}" name="v_jumlah_item[]" readonly></td>`;
			cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>`;
			newRow.append(cols);
			$("#tabledetail").append(newRow);
			$("#i_nota" + i)
				.select2({
					placeholder: g_pilihdata,
					dropdownAutoWidth: true,
					width: "100%",
					containerCssClass: "select-xs",
					allowClear: true,
					ajax: {
						url: base_url + $("#path").val() + "/get_gr",
						dataType: "json",
						delay: 250,
						data: function (params) {
							var query = {
								q: params.term,
								i_supplier: $("#i_supplier").val(),
							};
							return query;
						},
						processResults: function (data) {
							return {
								results: data,
							};
						},
						cache: false,
					},
				})
				.change(function (event) {
					var z = $(this).data("nourut");
					var ada = false;
					for (var x = 1; x <= $("#jml").val(); x++) {
						if ($(this).val() != null) {
							if ($(this).val() == $("#i_nota" + x).val() && z != x) {
								Swal.fire({
									type: "error",
									title: g_maaf,
									text: g_exist,
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
					} else {
						$.ajax({
							type: "post",
							data: {
								i_gr: $(this).val(),
							},
							url: base_url + $("#path").val() + "/get_detail_gr",
							dataType: "json",
							success: function (data) {
								$("#d_nota" + z).val(data["detail"][0]["d_refference"]);
								$("#v_jumlah_item" + z).val(
									number_format(data["detail"][0]["v_jumlah"], 2, ".", ",")
								);
								hetang();
								get_rincian();
							},
						});
					}
				});
		});

		/*----------  Hapus Baris Data Saudara  ----------*/

		$("#tabledetail").on("click", ".ibtnDel", function (event) {
			$(this).closest("tr").remove();

			$("#jml").val(i);
			var obj = $("#tabledetail tr:visible").find("spanx");
			$.each(obj, function (key, value) {
				id = value.id;
				$("#" + id).html(key + 1);
			});
			hetang();
			get_rincian();			
		});
	});

	$("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
	$("form").on("submit", function (e) {
		//bind event on form submit.
		let tabel = $("#tabledetail tbody tr").length;
		let ada = false;
		if (tabel < 1) {
			Swal.fire({
				type: "error",
				title: g_maaf,
				text: g_detailmin,
				confirmButtonClass: "btn btn-danger",
			});
			return false;
		}

		$("#tabledetail tbody tr").each(function () {
			$(this)
				.find("td select")
				.each(function () {
					if ($(this).val() == "" || $(this).val() == null) {
						Swal.fire({
							type: "error",
							title: g_maaf,
							text: "Item not empty",
							confirmButtonClass: "btn btn-danger",
						});
						ada = true;
					}
				});
		});

		if (!ada) {
			e.preventDefault();
			var formData = new FormData(this);
			if (formData) {
				sweetaddv33(
					$("#path").val(),
					$("#d_from").val(),
					$("#d_to").val(),
					$("#hsup").val(),
					formData
				);
			}
		} else {
			return false;
		}
	});
});

function jatuhtempo() {
	var tgl = $("#d_document").val();
	var top = parseInt($("#n_supplier_top").val());

	var fields = tgl.split("-");
	// var date = new Date(fields[2], fields[1] - 1, fields[0]);
	var date = new Date($("#d_document").val());
	// alert(date);
	date.setDate(date.getDate() + top);

	return (
		("0" + date.getDate()).slice(-2) +
		"-" +
		("0" + (date.getMonth() + 1)).slice(-2) +
		"-" +
		date.getFullYear()
	);
}

function hetang() {
	let v_jumlah = 0;
	$("#tabledetail tbody tr td .v_jumlah").each(function () {
		v_jumlah += parseFloat(formatulang($(this).val()));
	});
	$("#tfoot_subtotal").val(number_format(v_jumlah, 2, ".", ","));

	var foot_vdisc = parseFloat($("#tfoot_v_diskon").val().replaceAll(",", ""));
	var dpp = v_jumlah - foot_vdisc;
	var ppn = 0;
	if ($("#f_supplier_pkp").val() == "t") {
		ppn = dpp * (parseFloat($("#n_ppn").val()) / 100);
	}
	// alert(ppn);
	var total_final = dpp + ppn;
	$("#tfoot_v_dpp").val(number_format(dpp, 2, ".", ","));
	$("#tfoot_v_ppn").val(number_format(ppn, 2, ".", ","));
	$("#tfoot_total").val(number_format(total_final, 2, ".", ","));
}

function change_ndisc() {
	var sub_total = $("#tfoot_subtotal").val().replaceAll(",", "");
	var foot_vdisc = $("#tfoot_n_diskon").val().replaceAll(",", "");
	var v_disc = 0;
	if (foot_vdisc > 0.0) v_disc = (sub_total * foot_vdisc) / 100;
	$("#tfoot_v_diskon").val(number_format(v_disc, 2, ".", ","));
}

function change_vdisc() {
	$("#tfoot_n_diskon").val(0);
}

function number() {
	$.ajax({
		type: "post",
		data: {
			tanggal: $("#d_document").val(),
		},
		url: base_url + $("#path").val() + "/number ",
		dataType: "json",
		success: function (data) {
			$("#i_document").val(data);
		},
		error: function () {
			Swal.fire({
				type: "error",
				title: g_maaf,
				confirmButtonClass: "btn btn-danger",
			});
		},
	});
}

function clear_tabel() {
	$("#tabledetail tbody").empty();
	$("#jml").val("0");
}

function clear_tabel2() {
	$("#tabledetail2 tbody").empty();
	$("#jml2").val("0");
}

function get_rincian() {
	let nota = [];
	$("#tabledetail tbody tr td .nota").each(function () {
		nota.push($(this).val());
	});
	$.ajax({
		type: "post",
		data: {
			i_gr: nota,
		},
		url: base_url + $("#path").val() + "/get_rincian_gr",
		dataType: "json",
		success: function (data) {
			clear_tabel2();
			if (data["detail"].length > 0) {
				$("#jml2").val(data["detail"].length);
				for (let i = 0; i < data["detail"].length; i++) {
					var newRow = $("<tr>");
					var cols = "";
					cols += `<td class="text-center" valign="center">${i + 1}</td>`;
					cols += `<td><input type="hidden" readonly class="form-control form-control-sm" id="i_gr${i}" name="i_gr[]" value="${data["detail"][i]["i_gr"]}" readonly>
							<input type="hidden" readonly class="form-control form-control-sm" id="i_product${i}" name="i_product[]" value="${data["detail"][i]["i_product"]}" readonly>
							<input type="hidden" readonly class="form-control form-control-sm" id="i_product_grade${i}" name="i_product_grade[]" value="${data["detail"][i]["i_product_grade"]}" readonly>
							<input type="hidden" readonly class="form-control form-control-sm" id="i_product_motif${i}" name="i_product_motif[]" value="${data["detail"][i]["i_product_motif"]}" readonly>
							<input type="text" readonly class="form-control form-control-sm" id="r_id${i}" name="r_id[]" value="${data["detail"][i]["r_id"]}" readonly></td>`;
					cols += `<td><input type="text" readonly class="form-control form-control-sm" id="r_name${i}" name="r_name[]" value="${data["detail"][i]["r_name"]}" readonly></td>`;
					cols += `<td><input type="text" readonly class="form-control form-control-sm text-right" id="r_harga${i}" name="r_harga[]" value="${number_format(data["detail"][i]["r_harga"], 2, '.', ',')}" readonly></td>`;
					cols += `<td><input type="text" readonly class="form-control form-control-sm" id="r_qty${i}" name="r_qty[]" value="${data["detail"][i]["r_qty"]}" readonly></td>`;
					cols += `<td><input type="text" readonly class="form-control form-control-sm" id="r_dis${i}" name="r_dis[]" value="${data["detail"][i]["r_dis"]}" readonly>
							<input type="hidden" readonly class="form-control form-control-sm" id="r_dc${i}" name="r_dc[]" value="${data["detail"][i]["r_dc"]}" readonly></td>`;
					cols += `<td><input type="text" readonly class="form-control form-control-sm text-right" id="r_tot${i}" name="r_tot[]" value="${number_format(data["detail"][i]["r_tot"], 2, '.', ',')}" readonly></td>`;
					value="${number_format(v['v_gr_discount'], 2, '.', ',')}"
					newRow.append(cols);
					$("#tabledetail2").append(newRow);
				}
			} else {
				// Swal.fire('MAAF TIDAK ADA DATA !!!');
            	return false;
			}
			
		},
	});
}
