var tabel = null;

$(function () {
	_getDataAksesMenu();
	_getDataAksesReport();
	_getDataAksesWidget();
	_getListUnitToko();

	$("#submit").click(function () {
		if (_IsValid() === 0) return;
		_saveData();
	});

	$("#dEyeNewPwd").click(function () {
		if ($("#newpwd").attr("type") == "text") {
			$("#newpwd").attr("type", "password");
			$("#dEyeNewPwd i").addClass("fa-eye-slash");
			$("#dEyeNewPwd i").removeClass("fa-eye");
		} else if ($("#newpwd").attr("type") == "password") {
			$("#newpwd").attr("type", "text");
			$("#dEyeNewPwd i").removeClass("fa-eye-slash");
			$("#dEyeNewPwd i").addClass("fa-eye");
		}
	});

	$("#dEyePwd").click(function () {
		if ($("#pwd").attr("type") == "text") {
			$("#pwd").attr("type", "password");
			$("#dEyePwd i").addClass("fa-eye-slash");
			$("#dEyePwd i").removeClass("fa-eye");
		} else if ($("#pwd").attr("type") == "password") {
			$("#pwd").attr("type", "text");
			$("#dEyePwd i").removeClass("fa-eye-slash");
			$("#dEyePwd i").addClass("fa-eye");
		}
	});

	$("#btnNewPassword").click(function () {
		$("#rBtnNewPassword").addClass("d-none");
		$("#rNewPassword").removeClass("d-none");
		$("#newpwd").focus();
	});

	$(document).on("click", "#tmenu input[name^='isview']", function (e) {
		var _index = $(this).index(".view");
		var isChecked = $(this).prop("checked");
		$(".add").eq(_index).prop("checked", isChecked);
		$(".edit").eq(_index).prop("checked", isChecked);
		$(".delete").eq(_index).prop("checked", isChecked);
		$(".print").eq(_index).prop("checked", isChecked);
	});

	var _IsValid = function () {
		if ($("#kode").val() == "") {
			$("#kode").attr("data-title", "Kode / Nik user harus diisi !");
			$("#kode").tooltip("show");
			$("#kode").focus();
			return 0;
		}
		if ($("#nama").val() == "") {
			$("#nama").attr("data-title", "Nama user harus diisi !");
			$("#nama").tooltip("show");
			$("#nama").focus();
			return 0;
		}
		// select option when null
		if ($("#unitToko").val() == null) {
			$("#unitToko").attr("data-title", "Unit toko harus diisi !");
			$("#unitToko").tooltip("show");
			$("#unitToko").focus();
			return 0;
		}
		return 1;
	};

	var _saveData = function () {
		const id = $("#id").val(),
			kode = $("#kode").val(),
			nama = $("#nama").val(),
			namalengkap = $("#namalengkap").val(),
			email = $("#email").val(),
			pass = $("#pwd").val();
			unitToko = $("#unitToko").val();
			passbaru = $("#newpwd").val();

			console.log('passbaru');

		var status = 1;

		if ($("#aktif").prop("checked") == false) status = 0;

		var detilmenu = [],
			detilreport = [],
			detilwidget = [];

		$("input[name^='idmenu']").each(function (index, element) {
			var isview = 0,
				isadd = 0,
				isedit = 0,
				isdelete = 0,
				isprint = 0;
			if ($("input[name^='isview']").eq(index).prop("checked") == true)
				isview = 1;
			if ($("input[name^='isadd']").eq(index).prop("checked") == true)
				isadd = 1;
			if ($("input[name^='isedit']").eq(index).prop("checked") == true)
				isedit = 1;
			if ($("input[name^='isdelete']").eq(index).prop("checked") == true)
				isdelete = 1;
			if ($("input[name^='isprint']").eq(index).prop("checked") == true)
				isprint = 1;

			detilmenu.push({
				idmenu: this.value,
				buka: isview,
				tambah: isadd,
				edit: isedit,
				delete: isdelete,
				print: isprint,
			});
		});

		$("input[name^='idreport']").each(function (index, element) {
			var isreport = 0;
			if ($("input[name^='isreport']").eq(index).prop("checked") == true)
				isreport = 1;
			detilreport.push({
				idmenu: this.value,
				buka: isreport,
			});
		});

		$("input[name^='idwidget']").each(function (index, element) {
			var iswidget = 0;
			if ($("input[name^='iswidget']").eq(index).prop("checked") == true)
				iswidget = 1;

			detilwidget.push({
				idwidget: this.value,
				buka: iswidget,
			});
		});

		detilmenu = JSON.stringify(detilmenu);
		detilreport = JSON.stringify(detilreport);
		detilwidget = JSON.stringify(detilwidget);

		var rey = new FormData();
		rey.set("id", id);
		rey.set("kode", kode);
		rey.set("nama", nama);
		rey.set("namalengkap", namalengkap);
		rey.set("email", email);
		rey.set("unitToko", unitToko);
		rey.set("status", status);
		rey.set("pass", pass);
		rey.set("passbaru", passbaru);
		rey.set("detilmenu", detilmenu);
		rey.set("detilreport", detilreport);
		rey.set("detilwidget", detilwidget);
		    //  alert(passbaru); return;

		$.ajax({
			url: base_url + "Admin_User/savedata",
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
				toastr.error("Perbaiki masalah ini : " + xhr.status + " " + error);
				console.log(xhr.responseText);
				return;
			},
			success: async function (result) {
				if (result == "sukses") {
					$("#modal").modal("hide");
					toastr.success("Data user berhasil disimpan");
					try {
						$("#iframe-page-au").contents().find("#submitfilter").click();
					} catch (err) {
						console.log(err);
					}
				} else {
					toastr.error(result);
					return;
				}
				await sidebarmenu_content();
				$(".loader-wrap").addClass("d-none");
			},
		});
	};

	var sidebarmenu_content = () => {
		$.ajax({
			url: base_url + "Dasbor/refreshsidebarmenu",
			type: "POST",
			dataType: "html",
			cache: false,
			error: (xhr) => {
				console.error(xhr.responseText);
				return;
			},
			success: (result) => {
				$("aside").fadeOut(250, function () {
					$(this).append(result).fadeIn(250);
				});
				return;
			},
		});
	};
});

function _getDataAksesMenu() {
	$.ajax({
		url: base_url + "Admin_User/getaksesmenu",
		type: "POST",
		dataType: "json",
		data: "id=" + $("#id").val(),
		cache: false,
		success: function (result) {
			var rows = 0;
			$.each(result.data, function () {
				var newrow = " <tr>";
				newrow +=
					'<td class="border-0 py-1 px-1"><input type="hidden" name="idmenu" value="' +
					result.data[rows]["mid"] +
					'"><i class="fas fa-caret-right"></i></td>';
				newrow +=
					'<td class="border-0 py-1">' + result.data[rows]["mnama"] + "</td>";

				if (result.data[rows]["auapprove"] == 1) {
					newrow +=
						'<td class="border-0 py-1 text-center"><input type="checkbox" name="isview[]" class="view" checked></td>';
				} else {
					newrow +=
						'<td class="border-0 py-1 text-center"><input type="checkbox" name="isview[]" class="view"></td>';
				}
				if (result.data[rows]["auadd"] == 1) {
					newrow +=
						'<td class="border-0 py-1 text-center"><input type="checkbox" name="isadd[]" class="add" checked></td>';
				} else {
					newrow +=
						'<td class="border-0 py-1 text-center"><input type="checkbox" name="isadd[]" class="add"></td>';
				}
				if (result.data[rows]["auedit"] == 1) {
					newrow +=
						'<td class="border-0 py-1 text-center"><input type="checkbox" name="isedit[]" class="edit" checked></td>';
				} else {
					newrow +=
						'<td class="border-0 py-1 text-center"><input type="checkbox" name="isedit[]" class="edit"></td>';
				}
				if (result.data[rows]["audell"] == 1) {
					newrow +=
						'<td class="border-0 py-1 text-center"><input type="checkbox" name="isdelete[]" class="delete" checked></td>';
				} else {
					newrow +=
						'<td class="border-0 py-1 text-center"><input type="checkbox" name="isdelete[]" class="delete"></td>';
				}
				if (result.data[rows]["auprint"] == 1) {
					newrow +=
						'<td class="border-0 py-1 text-center"><input type="checkbox" name="isprint[]" class="print" checked></td>';
				} else {
					newrow +=
						'<td class="border-0 py-1 text-center"><input type="checkbox" name="isprint[]" class="print"></td>';
				}

				newrow += "</tr>";
				$("#tmenu tbody").append(newrow);
				rows++;
			});

			$(".loader-wrap").addClass("d-none");
			return;
		},
	});
}

function _getListUnitToko() {
	$.ajax({
		url: base_url + "Admin_User/getdataunittoko",
		type: "GET",
		dataType: "json",
		cache: false,
		error: function (xhr, status, error) {
			return;
		},
		success: function (result) {
			// make select option appending data
			console.log("sdasda");
			console.log(result);
			$.each(result.data, function (index, row) {
				$("#unitToko").append(
					'<option value="' + row["utid"] + '">' + row["utnama"] + "</option>"
				);
			});
		},
	});
}

function _getDataAksesReport() {
	$.ajax({
		url: base_url + "Admin_User/getaksesreport",
		type: "POST",
		dataType: "json",
		data: "id=" + $("#id").val(),
		cache: false,
		success: function (result) {
			var rows = 0;
			$.each(result.data, function () {
				var newrow = " <tr>";
				newrow +=
					'<td class="border-0 py-1 px-1"><input type="hidden" name="idreport" value="' +
					result.data[rows]["mid"] +
					'"><i class="fas fa-caret-right"></i></td>';
				newrow +=
					'<td class="border-0 py-1">' + result.data[rows]["mnama"] + "</td>";

				if (result.data[rows]["auapprove"] == 1) {
					newrow +=
						'<td class="border-0 py-1 text-center"><input type="checkbox" name="isreport" checked></td>';
				} else {
					newrow +=
						'<td class="border-0 py-1 text-center"><input type="checkbox" name="isreport"></td>';
				}

				newrow += "</tr>";
				$("#treport tbody").append(newrow);
				rows++;
			});

			return;
		},
	});
}

function _getDataAksesWidget() {
	$.ajax({
		url: base_url + "Admin_User/getakseswidget",
		type: "POST",
		dataType: "json",
		data: "id=" + $("#id").val(),
		cache: false,
		success: function (result) {
			var rows = 0;
			$.each(result.data, function () {
				var newrow = " <tr>";
				newrow +=
					'<td class="border-0 py-1 px-1"><input type="hidden" name="idwidget" value="' +
					result.data[rows]["adid"] +
					'"><i class="fas fa-caret-right"></i></td>';
				newrow +=
					'<td class="border-0 py-1">' + result.data[rows]["adket"] + "</td>";

				if (result.data[rows]["audapprove"] == 1) {
					newrow +=
						'<td class="border-0 py-1 text-center"><input type="checkbox" name="iswidget" checked></td>';
				} else {
					newrow +=
						'<td class="border-0 py-1 text-center"><input type="checkbox" name="iswidget"></td>';
				}

				newrow += "</tr>";
				$("#twidget tbody").append(newrow);
				rows++;
			});

			return;
		},
	});
}
