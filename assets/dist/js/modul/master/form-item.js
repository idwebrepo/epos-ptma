var generateBarcode = () => {
	var value = $("#barcode").val();
	var btype = "code39";
	var renderer = "css";

	var settings = {
		output: renderer,
		bgColor: "#FFFFFF",
		color: "#000000",
		barWidth: "1",
		barHeight: "50",
		moduleSize: "5",
		posX: "10",
		posY: "20",
		addQuietZone: "1",
	};
	if ($("#rectangular").is(":checked") || $("#rectangular").attr("checked")) {
		value = { code: value, rect: true };
	}
	if (renderer == "canvas") {
		var canvas = $("#canvasTarget").get(0);
		var ctx = canvas.getContext("2d");
		ctx.lineWidth = 1;
		ctx.lineCap = "butt";
		ctx.fillStyle = "#FFFFFF";
		ctx.strokeStyle = "#000000";
		ctx.clearRect(0, 0, canvas.width, canvas.height);
		ctx.strokeRect(0, 0, canvas.width, canvas.height);

		$("#barcodeTarget").hide();
		$("#canvasTarget").show().barcode(value, btype, settings);
	} else {
		$("#canvasTarget").hide();
		$("#barcodeTarget").html("").show().barcode(value, btype, settings);
		$("#digitBar").css("text-align", "center");
	}
};

var _inputFormat = () => {
	$(".numeric").inputmask({
		alias: "numeric",
		digits: "2",
		digitsOptional: false,
		isNumeric: true,
		prefix: "",
		groupSeparator: ".",
		placeholder: "0",
		radixPoint: ",",
		autoGroup: true,
		autoUnmask: true,
		onBeforeMask: function (value, opts) {
			return value;
		},
		removeMaskOnSubmit: false,
	});

	$(".qty").inputmask({
		alias: "numeric",
		digits: $("#decqty").val(),
		digitsOptional: false,
		isNumeric: true,
		prefix: "",
		groupSeparator: ".",
		placeholder: "0",
		radixPoint: ",",
		autoGroup: true,
		autoUnmask: true,
		onBeforeMask: function (value, opts) {
			return value;
		},
		removeMaskOnSubmit: false,
	});

	$(".datepicker").datepicker();

	$(".datepicker").inputmask({
		alias: "dd/mm/yyyy",
		mask: "1-2-y",
		placeholder: "_",
		leapday: "-02-29",
		separator: "-",
	});

	$(".gudangsa").select2({
		allowClear: true,
		theme: "bootstrap4",
		dropdownParent: $("#tabItem"),
		ajax: {
			url: base_url + "Select_Master/view_gudang",
			type: "post",
			dataType: "json",
			delay: 800,
			data: function (params) {
				return {
					search: params.term,
				};
			},
			processResults: function (data, page) {
				return {
					results: data,
				};
			},
		},
	});

	$(".kontaksa").select2({
		allowClear: true,
		theme: "bootstrap4",
		dropdownParent: $("#tabItem"),
		ajax: {
			url: base_url + "Select_Master/view_kontak",
			type: "post",
			dataType: "json",
			delay: 800,
			data: function (params) {
				return {
					search: params.term,
				};
			},
			processResults: function (data, page) {
				return {
					results: data,
				};
			},
		},
		templateResult: kontakSelect,
	});
};

var _addRow = () => {
	let _harga = Number(
		$("#hargabeli").val().split(".").join("").toString().replace(",", ".")
	);
	let newrow = " <tr>";
	newrow +=
		'<td><input type="text" name="nomorsa[]" class="nomorsa form-control form-control-sm" placeholder="[Auto]" autocomplete="off" data-trigger="manual" data-placement="auto"></td>';
	newrow +=
		'<td><input type="text" name="tanggalsa[]" class="tanggalsa form-control form-control-sm datepicker" autocomplete="off" data-trigger="manual" data-placement="auto"></td>';
	newrow +=
		'<td><select name="gudangsa[]" class="gudangsa form-control select2 form-control-sm" style="width:100%;" data-trigger="manual" data-placement="auto"></select></td>';
	newrow += `<td><input type=\"tel\" name=\"hargasa[]\" class=\"hargasa form-control form-control-sm numeric\" autocomplete=\"off\" value="${_harga}"></td>`;
	newrow +=
		'<td><input type="tel" name="qtysa[]" class="qtysa form-control form-control-sm qty" autocomplete="off" value="0"></td>';
	newrow +=
		'<td><select name="kontaksa[]" class="kontaksa form-control select2 form-control-sm" style="width:100%;" data-trigger="manual" data-placement="auto"></select></td>';
	newrow +=
		'<td><a href="javascript:void(0)" class="btn btn-step1 btn-delrow" onclick="_hapusbaris($(this));" tabindex="-1"><i class="fa fa-minus text-primary"></i></a></td>';
	newrow += "</tr>";
	$("#tsaldo tbody").append(newrow);
};

var _hapusbaris = (obj) => {
	if ($(obj).hasClass("disabled")) return;

	$(obj).parent().parent().remove();
	//_hitungsubtotal();
};

var _viewImageUrl = (input) => {
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			$("#itemImage").attr("src", e.target.result);
		};

		reader.readAsDataURL(input.files[0]);
	}
};

$("#bPrintBarcode").click(function () {
	var html = $("#barcodeTarget").html();
	if (html == "") {
		toastr.error("Tidak ada barcode yang akan dicetak !");
		return;
	}

	var w = window.open();
	w.document.title = "Cetak Barcode";
	$(w.document.body).html(html);
	$(w.document.body).find("#digitBar").css("text-align", "left");
	$(w.document.body).find("#digitBar").css("margin-left", "10px");
	w.print();
	setTimeout(w.close(), 0);
});

$("#bBarcode").click(function () {
	generateBarcode();
});

$("#fileImage").change(function () {
	_viewImageUrl(this);
});

$("#btglexpired").click(function () {
	if ($(this).attr("role")) {
		$("#tglexpired").focus();
	}
});

$("#dTglSaldo").click(function () {
	if ($(this).attr("role")) {
		$("#tglsa").focus();
	}
});

$("#jenis").select2({
	theme: "bootstrap4",
	dropdownParent: $("#tabItem"),
	minimumResultsForSearch: "Infinity",
});

$("#tipe").select2({
	theme: "bootstrap4",
	dropdownParent: $("#tabItem"),
	minimumResultsForSearch: "Infinity",
});

$("#status,#minus").select2({
	theme: "bootstrap4",
	dropdownParent: $("#tabItem"),
	minimumResultsForSearch: "Infinity",
});

$("#satuanD,#satuanDef").select2({
	allowClear: true,
	theme: "bootstrap4",
	dropdownParent: $("#tabItem"),
	ajax: {
		url: base_url + "Select_Master/view_satuan",
		type: "post",
		dataType: "json",
		delay: 800,
		data: function (params) {
			return {
				search: params.term,
			};
		},
		processResults: function (data, page) {
			return {
				results: data,
			};
		},
	},
});

$("#satuan5,#satuan6").select2({
	allowClear: true,
	theme: "bootstrap4",
	dropdownParent: $("#tabItem"),
	ajax: {
		url: base_url + "Select_Master/view_satuan",
		type: "post",
		dataType: "json",
		delay: 800,
		data: function (params) {
			return {
				search: params.term,
			};
		},
		processResults: function (data, page) {
			return {
				results: data,
			};
		},
	},
});

$("#satuan2").select2({
	allowClear: true,
	theme: "bootstrap4",
	dropdownParent: $("#tabItem"),
	ajax: {
		url: base_url + "Select_Master/view_satuan_filter_2",
		type: "post",
		dataType: "json",
		delay: 800,
		data: function (params) {
			let sat1 = $("#satuanD").val();
			let sat3 = $("#satuan3").val() == null ? 0 : $("#satuan3").val();
			let sat4 = $("#satuan4").val() == null ? 0 : $("#satuan4").val();
			return {
				search: params.term,
				where: `${sat1},${sat3},${sat4}`,
			};
		},
		processResults: function (data, page) {
			return {
				results: data,
			};
		},
	},
});

$("#satuan3").select2({
	allowClear: true,
	theme: "bootstrap4",
	dropdownParent: $("#tabItem"),
	ajax: {
		url: base_url + "Select_Master/view_satuan_filter_2",
		type: "post",
		dataType: "json",
		delay: 800,
		data: function (params) {
			let sat1 = $("#satuanD").val();
			let sat2 = $("#satuan2").val() == null ? 0 : $("#satuan2").val();
			let sat4 = $("#satuan4").val() == null ? 0 : $("#satuan4").val();
			return {
				search: params.term,
				where: `${sat1},${sat2},${sat4}`,
			};
		},
		processResults: function (data, page) {
			return {
				results: data,
			};
		},
	},
});

$("#satuan4").select2({
	allowClear: true,
	theme: "bootstrap4",
	dropdownParent: $("#tabItem"),
	ajax: {
		url: base_url + "Select_Master/view_satuan_filter_2",
		type: "post",
		dataType: "json",
		delay: 800,
		data: function (params) {
			let sat1 = $("#satuanD").val();
			let sat2 = $("#satuan2").val() == null ? 0 : $("#satuan2").val();
			let sat3 = $("#satuan3").val() == null ? 0 : $("#satuan3").val();
			return {
				search: params.term,
				where: `${sat1},${sat2},${sat3}`,
			};
		},
		processResults: function (data, page) {
			return {
				results: data,
			};
		},
	},
});

$("#kategori").select2({
	allowClear: true,
	theme: "bootstrap4",
	dropdownParent: $("#tabItem"),
	ajax: {
		url: base_url + "Select_Master/view_kategori_item",
		type: "post",
		dataType: "json",
		delay: 800,
		data: function (params) {
			return {
				search: params.term,
			};
		},
		processResults: function (data, page) {
			return {
				results: data,
			};
		},
	},
});

$("#unit").select2({
	allowClear: true,
	theme: "bootstrap4",
	dropdownParent: $("#tabItem"),
	ajax: {
		url: base_url + "Select_Master/view_unit",
		type: "post",
		dataType: "json",
		delay: 800,
		data: function (params) {
			return {
				search: params.term,
			};
		},
		processResults: function (data, page) {
			return {
				results: data,
			};
		},
	},
});

$("#subkategori").select2({
	allowClear: true,
	theme: "bootstrap4",
	ajax: {
		url: base_url + "Select_Master/view_subkategori_item",
		type: "post",
		dataType: "json",
		delay: 800,
		data: function (params) {
			return {
				search: params.term,
			};
		},
		processResults: function (data, page) {
			return {
				results: data,
			};
		},
	},
});

$("#coapersediaan,#coapendapatan,#coahpp").select2({
	allowClear: true,
	theme: "bootstrap4",
	dropdownParent: $("#tabItem"),
	ajax: {
		url: base_url + "Select_Master/view_coa",
		type: "post",
		dataType: "json",
		delay: 800,
		data: function (params) {
			return {
				search: params.term,
			};
		},
		processResults: function (data, page) {
			return {
				results: data,
			};
		},
	},
	templateResult: textSelect,
});

$("#submit").click(function () {
	if (_IsValid() === 0) return;
	_saveData();
});

$("#baddrow").click(function () {
	_addRow();
	_inputFormat();
	$("input[name^='nomor']").last().focus();
});

$("#jenis").on("select2:select", function () {
	if (this.value == 1) {
		$("#coapersediaan").val("").trigger("change");
		$("#coapendapatan").val("").trigger("change");
		$("#coahpp").val("").trigger("change");
		$("#labelcoa1").html("Akun Biaya *");
		$("#divcoahpp").addClass("d-none");
		get_default_coa("DBJ");
		get_default_coa("DPJ");
	} else {
		$("#coapersediaan").val("").trigger("change");
		$("#coapendapatan").val("").trigger("change");
		$("#coahpp").val("").trigger("change");
		$("#labelcoa1").html("Akun Persediaan *");
		$("#divcoahpp").removeClass("d-none");
		get_default_coa("ITP");
		get_default_coa("ITT");
		get_default_coa("ITH");
	}
});

$("#satuanD").on("select2:select", function () {
	let sat = $("#satuanD").select2("data");
	$(".lbl-satuan-def").text(sat[0].text);
});

$("#satuan2").on("select2:select", function () {
	let sat2 = $("#satuan2").select2("data");
	if ($("#satuanD").val() == "" || $("#satuanD").val() == null) {
		toastr.error("Satuan dasar harus dipilih dahulu");
		$("#satuan2").val("").trigger("change");
	} else {
		$(".lbl-satuan-2").text("/ " + sat2[0].text);
	}
});

$("#satuan3").on("select2:select", function () {
	let sat3 = $("#satuan3").select2("data");
	if ($("#satuan2").val() == "" || $("#satuan2").val() == null) {
		toastr.error("Satuan ke-2 harus dipilih dahulu");
		$("#satuan3").val("").trigger("change");
	} else {
		$(".lbl-satuan-3").text("/ " + sat3[0].text);
	}
});

$("#satuan4").on("select2:select", function () {
	let sat4 = $("#satuan4").select2("data");
	if ($("#satuan3").val() == "" || $("#satuan3").val() == null) {
		toastr.error("Satuan ke-3 harus dipilih dahulu");
		$("#satuan4").val("").trigger("change");
	} else {
		$(".lbl-satuan-4").text("/ " + sat4[0].text);
	}
});

$("#satuan5").on("select2:select", function () {
	let sat5 = $("#satuan5").select2("data");
	$(".lbl-satuan-5").text("/ " + sat5[0].text);
});

$("#satuan6").on("select2:select", function () {
	let sat6 = $("#satuan6").select2("data");
	$(".lbl-satuan-6").text("/ " + sat6[0].text);
});

function textSelect(par) {
	if (!par.id) {
		return par.text;
	}
	var $par = $("<span>(" + par.kode + ") " + par.text + "</span>");
	return $par;
}

function kontakSelect(par) {
	if (!par.id) {
		return par.text;
	}
	var $par = $(
		"<div class='pb-1' style='border-bottom:1px dotted #86cfda;'><span class='font-weight-normal'>" +
			par.kode +
			"</span><br/><span class='font-weight-bold text-sm'>" +
			par.text +
			"</span></div>"
	);
	return $par;
}

function _clearForm() {
	$(":input").not(":button, :submit, :reset, :checkbox, :radio").val("");
	$(":checkbox").prop("checked", false);
	$(".select2").val("").change();
}

function get_default_coa(id) {
	$.ajax({
		url: base_url + "Master_Item/getdefaultcoa",
		type: "POST",
		dataType: "json",
		data: "id=" + id,
		cache: false,
		error: function (xhr, status, error) {
			$(".main-modal-body").html("");
			toastr.error("Perbaiki kesalahan ini : " + xhr.status + " " + error);
			console.error(xhr.responseText);
			return;
		},
		success: function (result) {
			if (typeof result.pesan !== "undefined") {
				// Jika ada pesan maka tampilkan pesan
				toastr.error(result.pesan);
				return;
			} else {
				// Jika tidak ada pesan tampilkan json ke form
				const _coa = $("<option selected='selected'></option>")
					.val(result.data[0]["idcoa"])
					.text(result.data[0]["coa"]);

				if (result.data[0]["coa"] !== null) {
					if (id == "ITP" || id == "DBJ") {
						$("#coapersediaan").append(_coa);
					}
					if (id == "ITT" || id == "DPJ") {
						$("#coapendapatan").append(_coa);
					}
					if (id == "ITH") {
						$("#coahpp").append(_coa);
					}
				}

				/**/
				return;
			}
		},
	});
}

function get_multi_satuan() {
	$.ajax({
		url: base_url + "Master_Item/getmultisatuan",
		type: "POST",
		dataType: "json",
		cache: false,
		error: function (xhr, status, error) {
			$(".main-modal-body").html("");
			toastr.error("Perbaiki kesalahan ini : " + xhr.status + " " + error);
			console.error(xhr.responseText);
			return;
		},
		success: function (result) {
			if (typeof result.pesan !== "undefined") {
				toastr.error(result.pesan);
				return;
			} else {
				if (result.data[0]["isatuan"] == 0) {
					$("#fitur-multisatuan").addClass("d-none");
				} else {
					$("#fitur-multisatuan").removeClass("d-none");
				}

				return;
			}
		},
	});
}

var _IsValid = function () {
	if ($("#nomor").val() == "") {
		$("#nomor").attr("data-title", "Kode barang/jasa harus diisi !");
		$("#nomor").tooltip("show");
		$("#nomor").focus();
		return 0;
	}

	if ($("#nama").val() == "") {
		$("#nama").attr("data-title", "Nama barang/jasa harus diisi !");
		$("#nama").tooltip("show");
		$("#nama").focus();
		return 0;
	}

	if ($("#satuanD").val() == "" || $("#satuanD").val() == null) {
		toastr.error("Pesan : Satuan dasar harus diisi !");
		$('.nav-tabs a[href="#tab-menu"]').tab("show");
		$("#satuanD").select2("focus");
		return 0;
	}

	if (
		$("#status").val() == 2 &&
		Number(
			$("#stoktotal").val().split(".").join("").toString().replace(",", ".")
		) > 0
	) {
		toastr.error("Pesan : Stok masih ada tidak bisa dinonaktifkan !");
		$('.nav-tabs a[href="#tab-menu"]').tab("show");
		$("#status").select2("focus");
		return 0;
	}

	if ($("#jenis").val() == 1) {
		if ($("#coapersediaan").val() == "" || $("#coapersediaan").val() == null) {
			toastr.error("Pesan : Akun biaya harus diisi !");
			$('.nav-tabs a[href="#tab-coa"]').tab("show");
			$("#coapersediaan").select2("focus");
			return 0;
		}
		if ($("#coapendapatan").val() == "" || $("#coapendapatan").val() == null) {
			toastr.error("Pesan : Akun pendapatan harus diisi !");
			$('.nav-tabs a[href="#tab-coa"]').tab("show");
			$("#coapendapatan").select2("focus");
			return 0;
		}
	} else {
		if ($("#coapersediaan").val() == "" || $("#coapersediaan").val() == null) {
			toastr.error("Pesan : Akun persediaan harus diisi !");
			$('.nav-tabs a[href="#tab-coa"]').tab("show");
			$("#coapersediaan").select2("focus");
			return 0;
		}
		if ($("#coapendapatan").val() == "" || $("#coapendapatan").val() == null) {
			toastr.error("Pesan : Akun pendapatan harus diisi !");
			$('.nav-tabs a[href="#tab-coa"]').tab("show");
			$("#coapendapatan").select2("focus");
			return 0;
		}
		if ($("#coahpp").val() == "" || $("#coahpp").val() == null) {
			toastr.error("Pesan : Akun hpp harus diisi !");
			$('.nav-tabs a[href="#tab-coa"]').tab("show");
			$("#coahpp").select2("focus");
			return 0;
		}
	}

	return 1;
};

var _saveData = function () {
	const id = $("#id").val(),
		kode = $("#nomor").val(),
		barcode = $("#barcode").val(),
		nama = $("#nama").val(),
		alias = $("#alias").val(),
		serial = $("#serial").val(),
		merk = $("#merk").val(),
		satuan = $("#satuanD").val(),
		satuand = $("#satuanDef").val(),
		unit = $("#unit").val(),
		unitu = $("#unitu").val(),
		stoktotal = $("#stoktotal").val(),
		kategori = $("#kategori").val(),
		jenis = $("#jenis").val(),
		tipe = $("#tipe").val(),
		stokmin = Number(
			$("#stokmin").val().split(".").join("").toString().replace(",", ".")
		),
		stokmaks = Number(
			$("#stokmaks").val().split(".").join("").toString().replace(",", ".")
		),
		stokreorder = Number(
			$("#stokreorder").val().split(".").join("").toString().replace(",", ".")
		),
		hargabeli = Number(
			$("#hargabeli").val().split(".").join("").toString().replace(",", ".")
		),
		hargajual1 = Number(
			$("#hargajual1").val().split(".").join("").toString().replace(",", ".")
		),
		hargajual2 = Number(
			$("#hargajual2").val().split(".").join("").toString().replace(",", ".")
		),
		hargajual3 = Number(
			$("#hargajual3").val().split(".").join("").toString().replace(",", ".")
		),
		hargajual4 = Number(
			$("#hargajual4").val().split(".").join("").toString().replace(",", ".")
		),
		coapersediaan = $("#coapersediaan").val(),
		coapendapatan = $("#coapendapatan").val(),
		coahpp = $("#coahpp").val(),
		status = $("#status").val(),
		minus = $("#minus").val(),
		satuan2 = $("#satuan2").val(),
		konversi2 = Number(
			$("#konversi2").val().split(".").join("").toString().replace(",", ".")
		),
		satuan3 = $("#satuan3").val(),
		konversi3 = Number(
			$("#konversi3").val().split(".").join("").toString().replace(",", ".")
		),
		satuan4 = $("#satuan4").val(),
		konversi4 = Number(
			$("#konversi4").val().split(".").join("").toString().replace(",", ".")
		),
		satuan5 = $("#satuan5").val(),
		konversi5 = Number(
			$("#konversi5").val().split(".").join("").toString().replace(",", ".")
		),
		satuan6 = $("#satuan6").val(),
		konversi6 = Number(
			$("#konversi6").val().split(".").join("").toString().replace(",", ".")
		),
		sat2hargajual1 = Number(
			$("#hargajualsat2lvl1")
				.val()
				.split(".")
				.join("")
				.toString()
				.replace(",", ".")
		),
		sat2hargajual2 = Number(
			$("#hargajualsat2lvl2")
				.val()
				.split(".")
				.join("")
				.toString()
				.replace(",", ".")
		),
		sat2hargajual3 = Number(
			$("#hargajualsat2lvl3")
				.val()
				.split(".")
				.join("")
				.toString()
				.replace(",", ".")
		),
		sat2hargajual4 = Number(
			$("#hargajualsat2lvl4")
				.val()
				.split(".")
				.join("")
				.toString()
				.replace(",", ".")
		),
		sat3hargajual1 = Number(
			$("#hargajualsat3lvl1")
				.val()
				.split(".")
				.join("")
				.toString()
				.replace(",", ".")
		),
		sat3hargajual2 = Number(
			$("#hargajualsat3lvl2")
				.val()
				.split(".")
				.join("")
				.toString()
				.replace(",", ".")
		),
		sat3hargajual3 = Number(
			$("#hargajualsat3lvl3")
				.val()
				.split(".")
				.join("")
				.toString()
				.replace(",", ".")
		),
		sat3hargajual4 = Number(
			$("#hargajualsat3lvl4")
				.val()
				.split(".")
				.join("")
				.toString()
				.replace(",", ".")
		),
		sat4hargajual1 = Number(
			$("#hargajualsat4lvl1")
				.val()
				.split(".")
				.join("")
				.toString()
				.replace(",", ".")
		),
		sat4hargajual2 = Number(
			$("#hargajualsat4lvl2")
				.val()
				.split(".")
				.join("")
				.toString()
				.replace(",", ".")
		),
		sat4hargajual3 = Number(
			$("#hargajualsat4lvl3")
				.val()
				.split(".")
				.join("")
				.toString()
				.replace(",", ".")
		),
		sat4hargajual4 = Number(
			$("#hargajualsat4lvl4")
				.val()
				.split(".")
				.join("")
				.toString()
				.replace(",", ".")
		),
		sat5hargajual1 = Number(
			$("#hargajualsat5lvl1")
				.val()
				.split(".")
				.join("")
				.toString()
				.replace(",", ".")
		),
		sat5hargajual2 = Number(
			$("#hargajualsat5lvl2")
				.val()
				.split(".")
				.join("")
				.toString()
				.replace(",", ".")
		),
		sat5hargajual3 = Number(
			$("#hargajualsat5lvl3")
				.val()
				.split(".")
				.join("")
				.toString()
				.replace(",", ".")
		),
		sat5hargajual4 = Number(
			$("#hargajualsat5lvl4")
				.val()
				.split(".")
				.join("")
				.toString()
				.replace(",", ".")
		),
		sat6hargajual1 = Number(
			$("#hargajualsat6lvl1")
				.val()
				.split(".")
				.join("")
				.toString()
				.replace(",", ".")
		),
		sat6hargajual2 = Number(
			$("#hargajualsat6lvl2")
				.val()
				.split(".")
				.join("")
				.toString()
				.replace(",", ".")
		),
		sat6hargajual3 = Number(
			$("#hargajualsat6lvl3")
				.val()
				.split(".")
				.join("")
				.toString()
				.replace(",", ".")
		),
		sat6hargajual4 = Number(
			$("#hargajualsat6lvl4")
				.val()
				.split(".")
				.join("")
				.toString()
				.replace(",", ".")
		);
	expired = $("#tglexpired").val();

	var saldoawal = [],
		isValid = null,
		_elFocus = null;

	if (satuan2 !== null) {
		if (satuan2 == satuan || satuan2 == satuan3 || satuan2 == satuan4) {
			toastr.error("Satuan item tidak boleh sama");
			$('.nav-tabs a[href="#tab-satuan"]').tab("show");
			return;
		}
	}

	if (satuan3 !== null) {
		if (satuan3 == satuan || satuan3 == satuan2 || satuan3 == satuan4) {
			toastr.error("Satuan item tidak boleh sama");
			$('.nav-tabs a[href="#tab-satuan"]').tab("show");
			return;
		}
	}

	if (satuan4 !== null) {
		if (satuan4 == satuan || satuan4 == satuan2 || satuan4 == satuan3) {
			toastr.error("Satuan item tidak boleh sama");
			$('.nav-tabs a[href="#tab-satuan"]').tab("show");
			return;
		}
	}

	$("input[name^='nomorsa']").each(function (index, element) {
		let _qty = Number(
			$("input[name^='qtysa']")
				.eq(index)
				.val()
				.split(".")
				.join("")
				.toString()
				.replace(",", ".")
		);
		let _tgl = $("input[name^='tanggalsa']").eq(index).val();
		let _gudang = $("select[name^='gudangsa']").eq(index).val();
		let _harga = Number(
			$("input[name^='hargasa']")
				.eq(index)
				.val()
				.split(".")
				.join("")
				.toString()
				.replace(",", ".")
		);
		let _kontak = $("select[name^='kontaksa']").eq(index).val();

		if (_tgl == "") {
			isValid =
				"Tanggal saldo awal pada baris ke-" +
				Number(index + 1) +
				" harus diisi";
			_elFocus = $("input[name^='tanggalsa']").eq(index);
			return;
		} else if (_gudang == null) {
			isValid =
				"Gudang saldo awal pada baris ke-" + Number(index + 1) + " harus diisi";
			_elFocus = $("select[name^='gudangsa']").eq(index);
			return;
		} else if (_harga <= 0) {
			isValid =
				"Harga pokok saldo awal pada baris ke-" +
				Number(index + 1) +
				" masih 0";
			_elFocus = $("input[name^='hargasa']").eq(index);
			return;
		} else if (_qty <= 0) {
			isValid =
				"Qty saldo awal pada baris ke-" + Number(index + 1) + " masih 0";
			_elFocus = $("input[name^='qtysa']").eq(index);
			return;
		} else if (_kontak == null) {
			isValid =
				"Kontak saldo awal pada baris ke-" + Number(index + 1) + " harus diisi";
			_elFocus = $("select[name^='kontaksa']").eq(index);
			return;
		}

		saldoawal.push({
			nomor: this.value,
			tanggal: _tgl,
			gudang: _gudang,
			harga: _harga,
			qty: _qty,
			kontak: _kontak,
		});
	});

	if (isValid != null) {
		Swal.fire({
			title: isValid,
			showDenyButton: false,
			showCancelButton: false,
			confirmButtonText: `Tutup`,
		}).then((result) => {
			$('.nav-tabs a[href="#tab-sa"]').tab("show");
			setTimeout(function () {
				_elFocus.focus();
			}, 300);
		});
		return;
	}

	saldoawal = JSON.stringify(saldoawal);

	var rey = new FormData();
	rey.set("id", id);
	rey.set("kode", kode);
	rey.set("barcode", barcode);
	rey.set("nama", nama);
	rey.set("alias", alias);
	rey.set("serial", serial);
	rey.set("merk", merk);
	rey.set("satuan", satuan);
	rey.set("satuand", satuand);
	rey.set("unit", unit);
	rey.set("unitu", unitu);
	rey.set("stoktotal", stoktotal);
	rey.set("kategori", kategori);
	rey.set("jenis", jenis);
	rey.set("tipe", tipe);
	rey.set("stokmin", stokmin);
	rey.set("stokmaks", stokmaks);
	rey.set("stokreorder", stokreorder);
	rey.set("hargabeli", hargabeli);
	rey.set("hargajual1", hargajual1);
	rey.set("hargajual2", hargajual2);
	rey.set("hargajual3", hargajual3);
	rey.set("hargajual4", hargajual4);
	rey.set("coapersediaan", coapersediaan);
	rey.set("coapendapatan", coapendapatan);
	rey.set("coahpp", coahpp);
	rey.set("status", status);
	rey.set("minus", minus);
	rey.set("saldoawal", saldoawal);
	rey.set("satuan2", satuan2);
	rey.set("konversi2", konversi2);
	rey.set("satuan3", satuan3);
	rey.set("konversi3", konversi3);
	rey.set("satuan4", satuan4);
	rey.set("konversi4", konversi4);
	rey.set("satuan5", satuan5);
	rey.set("konversi5", konversi5);
	rey.set("satuan6", satuan6);
	rey.set("konversi6", konversi6);
	rey.set("sat2hargajual1", sat2hargajual1);
	rey.set("sat2hargajual2", sat2hargajual2);
	rey.set("sat2hargajual3", sat2hargajual3);
	rey.set("sat2hargajual4", sat2hargajual4);
	rey.set("sat3hargajual1", sat3hargajual1);
	rey.set("sat3hargajual2", sat3hargajual2);
	rey.set("sat3hargajual3", sat3hargajual3);
	rey.set("sat3hargajual4", sat3hargajual4);
	rey.set("sat4hargajual1", sat4hargajual1);
	rey.set("sat4hargajual2", sat4hargajual2);
	rey.set("sat4hargajual3", sat4hargajual3);
	rey.set("sat4hargajual4", sat4hargajual4);
	rey.set("sat5hargajual1", sat5hargajual1);
	rey.set("sat5hargajual2", sat5hargajual2);
	rey.set("sat5hargajual3", sat5hargajual3);
	rey.set("sat5hargajual4", sat5hargajual4);
	rey.set("sat6hargajual1", sat6hargajual1);
	rey.set("sat6hargajual2", sat6hargajual2);
	rey.set("sat6hargajual3", sat6hargajual3);
	rey.set("sat6hargajual4", sat6hargajual4);
	rey.set("expired", expired);
	rey.set("gambar", $("#fileImage")[0].files[0]);

	$.ajax({
		url: base_url + "Master_Item/savedata",
		type: "POST",
		data: rey,
		processData: false,
		contentType: false,
		cache: false,
		beforeSend: function () {
			$(".loader-wrap").removeClass("d-none");
		},
		error: function (xhr, status, error) {
			$(".loader-wrap").addClass("d-none");
			toastr.error("Error : " + xhr.status + " " + error);
			console.log(xhr.responseText);
			return;
		},
		success: function (result) {
			$(".loader-wrap").addClass("d-none");

			if (result == "sukses") {
				$("#modal").modal("hide");
				toastr.success("Data item berhasil disimpan");
				try {
					$("#iframe-page-item").contents().find("#submitfilter").click();
				} catch (err) {
					console.log(err);
				}
				return;
			} else {
				toastr.error(result);
				return;
			}
		},
	});
};

function _getData(id) {
	if (id == "" || id == null) return;

	$.ajax({
		url: base_url + "Master_Item/getdata",
		type: "POST",
		dataType: "json",
		data: "id=" + id,
		cache: false,
		beforeSend: function () {
			$(".loader-wrap").removeClass("d-none");
		},
		error: function (xhr, status, error) {
			$(".main-modal-body").html("");
			toastr.error("Error : " + xhr.status + " " + error);
			console.error(xhr.responseText);
			$(".loader-wrap").addClass("d-none");
			return;
		},
		success: function (result) {
			if (typeof result.pesan !== "undefined") {
				toastr.error(result.pesan);
				$(".loader-wrap").addClass("d-none");
				return;
			} else {
				const _satuan = $("<option selected='selected'></option>")
						.val(result.data[0]["idsatuan"])
						.text(result.data[0]["satuan"]),
					_unit = $("<option selected='selected'></option>")
						.val(result.data[0]["idunit"])
						.text(result.data[0]["unit"]),
					_kategori = $("<option selected='selected'></option>")
						.val(result.data[0]["idkategori"])
						.text(result.data[0]["kategori"]),
					_satuanD = $("<option selected='selected'></option>")
						.val(result.data[0]["idsatuand"])
						.text(result.data[0]["satuand"]),
					_satuan2 = $("<option selected='selected'></option>")
						.val(result.data[0]["idsatuan2"])
						.text(result.data[0]["satuan2"]),
					_satuan3 = $("<option selected='selected'></option>")
						.val(result.data[0]["idsatuan3"])
						.text(result.data[0]["satuan3"]),
					_satuan4 = $("<option selected='selected'></option>")
						.val(result.data[0]["idsatuan4"])
						.text(result.data[0]["satuan4"]),
					_satuan5 = $("<option selected='selected'></option>")
						.val(result.data[0]["idsatuan5"])
						.text(result.data[0]["satuan5"]),
					_satuan6 = $("<option selected='selected'></option>")
						.val(result.data[0]["idsatuan6"])
						.text(result.data[0]["satuan6"]),
					_coapersediaan = $("<option selected='selected'></option>")
						.val(result.data[0]["idcoapersediaan"])
						.text(result.data[0]["coapersediaan"]),
					_coapendapatan = $("<option selected='selected'></option>")
						.val(result.data[0]["idcoapendapatan"])
						.text(result.data[0]["coapendapatan"]),
					_coahpp = $("<option selected='selected'></option>")
						.val(result.data[0]["idcoahpp"])
						.text(result.data[0]["coahpp"]);

				$("#id").val(result.data[0]["id"]);
				$("#nomor").val(result.data[0]["kode"]);
				$("#barcode").val(result.data[0]["barcode"]);
				$("#nama").val(result.data[0]["nama"]);
				$("#alias").val(result.data[0]["alias"]);
				$("#serial").val(result.data[0]["serial"]);
				$("#merk").val(result.data[0]["merk"]);
				$(".lbl-satuan-def").text(result.data[0]["satuan"]);
				if (result.data[0]["satuan"] !== null) $("#satuanD").append(_satuan);
				if (result.data[0]["satuand"] !== null)
					$("#satuanDef").append(_satuanD);
				if (result.data[0]["unit"] !== null)
					$("#unit").append(_unit);
				if (result.data[0]["kategori"] !== null)
					$("#kategori").append(_kategori);
				if (result.data[0]["satuan2"] !== null) {
					$("#satuan2").append(_satuan2);
					$(".lbl-satuan-2").text("/ " + result.data[0]["satuan2"]);
				}
				if (result.data[0]["satuan3"] !== null) {
					$("#satuan3").append(_satuan3);
					$(".lbl-satuan-3").text("/ " + result.data[0]["satuan3"]);
				}
				if (result.data[0]["satuan4"] !== null) {
					$("#satuan4").append(_satuan4);
					$(".lbl-satuan-4").text("/ " + result.data[0]["satuan4"]);
				}
				if (result.data[0]["satuan5"] !== null) {
					$("#satuan5").append(_satuan5);
					$(".lbl-satuan-5").text("/ " + result.data[0]["satuan5"]);
				}
				if (result.data[0]["satuan6"] !== null) {
					$("#satuan6").append(_satuan6);
					$(".lbl-satuan-6").text("/ " + result.data[0]["satuan6"]);
				}

				$("#hargajualsat2lvl1").val(
					result.data[0]["sat2hargajual1"].replace(".", ",")
				);
				if (result.data[0]["sat2hargajual1"] == 0)
					$("#hargajualsat2lvl1").attr("placeholder", "0,00");
				$("#hargajualsat2lvl2").val(
					result.data[0]["sat2hargajual2"].replace(".", ",")
				);
				if (result.data[0]["sat2hargajual2"] == 0)
					$("#hargajualsat2lvl2").attr("placeholder", "0,00");
				$("#hargajualsat2lvl3").val(
					result.data[0]["sat2hargajual3"].replace(".", ",")
				);
				if (result.data[0]["sat2hargajual3"] == 0)
					$("#hargajualsat2lvl3").attr("placeholder", "0,00");
				$("#hargajualsat2lvl4").val(
					result.data[0]["sat2hargajual4"].replace(".", ",")
				);
				if (result.data[0]["sat2hargajual4"] == 0)
					$("#hargajualsat2lvl4").attr("placeholder", "0,00");

				$("#hargajualsat3lvl1").val(
					result.data[0]["sat3hargajual1"].replace(".", ",")
				);
				if (result.data[0]["sat3hargajual1"] == 0)
					$("#hargajualsat3lvl1").attr("placeholder", "0,00");
				$("#hargajualsat3lvl2").val(
					result.data[0]["sat3hargajual2"].replace(".", ",")
				);
				if (result.data[0]["sat3hargajual2"] == 0)
					$("#hargajualsat3lvl2").attr("placeholder", "0,00");
				$("#hargajualsat3lvl3").val(
					result.data[0]["sat3hargajual3"].replace(".", ",")
				);
				if (result.data[0]["sat3hargajual3"] == 0)
					$("#hargajualsat3lvl3").attr("placeholder", "0,00");
				$("#hargajualsat3lvl4").val(
					result.data[0]["sat3hargajual4"].replace(".", ",")
				);
				if (result.data[0]["sat3hargajual4"] == 0)
					$("#hargajualsat3lvl4").attr("placeholder", "0,00");

				$("#hargajualsat4lvl1").val(
					result.data[0]["sat4hargajual1"].replace(".", ",")
				);
				if (result.data[0]["sat4hargajual1"] == 0)
					$("#hargajualsat4lvl1").attr("placeholder", "0,00");
				$("#hargajualsat4lvl2").val(
					result.data[0]["sat4hargajual2"].replace(".", ",")
				);
				if (result.data[0]["sat4hargajual2"] == 0)
					$("#hargajualsat4lvl2").attr("placeholder", "0,00");
				$("#hargajualsat4lvl3").val(
					result.data[0]["sat4hargajual3"].replace(".", ",")
				);
				if (result.data[0]["sat4hargajual3"] == 0)
					$("#hargajualsat4lvl3").attr("placeholder", "0,00");
				$("#hargajualsat4lvl4").val(
					result.data[0]["sat4hargajual4"].replace(".", ",")
				);
				if (result.data[0]["sat4hargajual4"] == 0)
					$("#hargajualsat4lvl4").attr("placeholder", "0,00");

				$("#hargajualsat5lvl1").val(
					result.data[0]["sat5hargajual1"].replace(".", ",")
				);
				if (result.data[0]["sat5hargajual1"] == 0)
					$("#hargajualsat5lvl1").attr("placeholder", "0,00");
				$("#hargajualsat5lvl2").val(
					result.data[0]["sat5hargajual2"].replace(".", ",")
				);
				if (result.data[0]["sat5hargajual2"] == 0)
					$("#hargajualsat5lvl2").attr("placeholder", "0,00");
				$("#hargajualsat5lvl3").val(
					result.data[0]["sat5hargajual3"].replace(".", ",")
				);
				if (result.data[0]["sat5hargajual3"] == 0)
					$("#hargajualsat5lvl3").attr("placeholder", "0,00");
				$("#hargajualsat5lvl4").val(
					result.data[0]["sat5hargajual4"].replace(".", ",")
				);
				if (result.data[0]["sat5hargajual4"] == 0)
					$("#hargajualsat5lvl4").attr("placeholder", "0,00");

				$("#hargajualsat6lvl1").val(
					result.data[0]["sat6hargajual1"].replace(".", ",")
				);
				if (result.data[0]["sat6hargajual1"] == 0)
					$("#hargajualsat6lvl1").attr("placeholder", "0,00");
				$("#hargajualsat6lvl2").val(
					result.data[0]["sat6hargajual2"].replace(".", ",")
				);
				if (result.data[0]["sat6hargajual2"] == 0)
					$("#hargajualsat6lvl2").attr("placeholder", "0,00");
				$("#hargajualsat6lvl3").val(
					result.data[0]["sat6hargajual3"].replace(".", ",")
				);
				if (result.data[0]["sat6hargajual3"] == 0)
					$("#hargajualsat6lvl3").attr("placeholder", "0,00");
				$("#hargajualsat6lvl4").val(
					result.data[0]["sat6hargajual4"].replace(".", ",")
				);
				if (result.data[0]["sat6hargajual4"] == 0)
					$("#hargajualsat6lvl4").attr("placeholder", "0,00");

				$("#konversi2").val(result.data[0]["konversi2"].replace(".", ","));
				if (result.data[0]["konversi2"] == 0)
					$("#konversi2").attr("placeholder", "0,00");
				$("#konversi3").val(result.data[0]["konversi3"].replace(".", ","));
				if (result.data[0]["konversi3"] == 0)
					$("#konversi3").attr("placeholder", "0,00");
				$("#konversi4").val(result.data[0]["konversi4"].replace(".", ","));
				if (result.data[0]["konversi4"] == 0)
					$("#konversi4").attr("placeholder", "0,00");
				$("#konversi5").val(result.data[0]["konversi5"].replace(".", ","));
				if (result.data[0]["konversi5"] == 0)
					$("#konversi5").attr("placeholder", "0,00");
				$("#konversi6").val(result.data[0]["konversi6"].replace(".", ","));
				if (result.data[0]["konversi6"] == 0)
					$("#konversi6").attr("placeholder", "0,00");

				$("#stokmin").val(result.data[0]["stokmin"].replace(".", ","));
				if (result.data[0]["stokmin"] == 0)
					$("#stokmin").attr("placeholder", "0,00");
				$("#stokmaks").val(result.data[0]["stokmaks"].replace(".", ","));
				if (result.data[0]["stokmaks"] == 0)
					$("#stokmaks").attr("placeholder", "0,00");
				$("#stoktotal").val(result.data[0]["stoktotal"].replace(".", ","));
				if (result.data[0]["stoktotal"] == 0)
					$("#stoktotal").attr("placeholder", "0,00");
				$("#stokreorder").val(result.data[0]["reorder"].replace(".", ","));
				if (result.data[0]["reorder"] == 0)
					$("#stokreorder").attr("placeholder", "0,00");
				$("#hargabeli").val(result.data[0]["hargabeli"].replace(".", ","));
				if (result.data[0]["hargabeli"] == 0)
					$("#hargabeli").attr("placeholder", "0,00");
				$("#hargajual1").val(result.data[0]["hargajual1"].replace(".", ","));
				if (result.data[0]["hargajual1"] == 0)
					$("#hargajual1").attr("placeholder", "0,00");
				$("#hargajual2").val(result.data[0]["hargajual2"].replace(".", ","));
				if (result.data[0]["hargajual2"] == 0)
					$("#hargajual2").attr("placeholder", "0,00");
				$("#hargajual3").val(result.data[0]["hargajual3"].replace(".", ","));
				if (result.data[0]["hargajual3"] == 0)
					$("#hargajual3").attr("placeholder", "0,00");
				$("#hargajual4").val(result.data[0]["hargajual4"].replace(".", ","));
				if (result.data[0]["hargajual4"] == 0)
					$("#hargajual4").attr("placeholder", "0,00");
				$("#jenis").val(result.data[0]["jenis"]).trigger("change");
				$("#tipe").val(result.data[0]["tipe"]).trigger("change");
				$("#status").val(result.data[0]["status"]).trigger("change");
				$("#minus").val(result.data[0]["bolehminus"]).trigger("change");

				$("#tglexpired").datepicker("setDate", result.data[0]["expired"]);

				if (
					result.data[0]["gambar1"] !== null &&
					result.data[0]["gambar1"] !== ""
				) {
					$("#itemImage").attr(
						"src",
						base_url + "assets/dist/img/" + result.data[0]["gambar1"]
					);
				}

				if (result.data[0]["coapersediaan"] != null)
					$("#coapersediaan").append(_coapersediaan);
				if (result.data[0]["coapendapatan"] != null)
					$("#coapendapatan").append(_coapendapatan);
				if (result.data[0]["coahpp"] != null) $("#coahpp").append(_coahpp);

				var rows = 0;
				$.each(result.data2, function () {
					var _kontaksa = $("<option selected='selected'></option>")
						.val(result.data2[rows]["idkontaksa"])
						.text(result.data2[rows]["kontaksa"]);
					var _gudangsa = $("<option selected='selected'></option>")
						.val(result.data2[rows]["idgudangsa"])
						.text(result.data2[rows]["gudangsa"]);

					if (result.data2[rows]["nomorsa"] != null) {
						_addRow();
						_inputFormat();

						$("input[name^='nomorsa']")
							.eq(rows)
							.val(result.data2[rows]["nomorsa"]);
						$("select[name^='gudangsa']")
							.eq(rows)
							.append(_gudangsa)
							.trigger("change");
						$("select[name^='kontaksa']")
							.eq(rows)
							.append(_kontaksa)
							.trigger("change");
						$("input[name^='hargasa']")
							.eq(rows)
							.val(result.data2[rows]["hargasa"].replace(".", ","));
						$("input[name^='qtysa']")
							.eq(rows)
							.val(result.data2[rows]["qtysa"].replace(".", ","));
						$("input[name^='tanggalsa']")
							.eq(rows)
							.val(result.data2[rows]["tglsa"]);
					}
					rows++;
				});

				generateBarcode();
				$(".loader-wrap").addClass("d-none");
				return;
			}
		},
	});
}

_inputFormat();

setTimeout(function () {
	$("#nomor").focus();
}, 500);
