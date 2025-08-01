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

	// $("#nominal").keyup(function(){
	// 	console.log($("#nominal").val());
	// 	var nominal = $("#nominal").val();
	// 	var sisaSaldo = $("#sisaSaldo").val();

	// 	if(parseInt(nominal) > parseInt(sisaSaldo)){
	// 		$("#nominal").val(parseInt(sisaSaldo));
	// 	}else if(nominal < 0){
	// 		$("#nominal").val(0);
	// 	}
	// })

	var _IsValid = function () {
		console.log($("#sisaSaldo").val());
		console.log($("#nominal").val());
		var nominal = $("#nominal").val();
		var sisaSaldo = $("#sisaSaldo").val();
		// select option when null
		if (parseInt(nominal) == 0) {
			$("#nominal").attr("data-title", "Nominal harus diisi !");
			$("#nominal").tooltip("show");
			$("#nominal").focus();
			$("#nominal").removeAttr("data-title");
			return 0;
		}else if (parseInt(nominal) > parseInt(sisaSaldo)) {
			$("#nominal").attr("data-title", "Tarik Saldo tidak boleh melebihi saldo saat ini !");
			$("#nominal").tooltip("show");
			$("#nominal").removeAttr("data-title");
			$("#nominal").focus();
			return 0;
		}else if (parseInt(nominal) < 0) {
			$("#nominal").attr("data-title", "Tarik Saldo tidak boleh Minus !");
			$("#nominal").tooltip("show");
			$("#nominal").removeAttr("data-title");
			$("#nominal").focus();
			return 0;
		}else{
			return 1;
		}
	};

	var _saveData = function () {
		const id = $("#idsaldo").val(),
			idunit = $("#idunit").val(),
			nominal = $("#nominal").val();

		var rey = new FormData();
		rey.set("id", id);
		rey.set("idunit", idunit);
		rey.set("nominal", nominal);
		//      alert(passbaru); return;

		$.ajax({
			url: base_url + "Saldo/savedata",
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
					toastr.success("Saldo berhasil Di Tarik");
					try {
						$("#iframe-page-saldo").contents().find("#submitfilter").click();
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
