<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<body id="first-page" class="layout-fixed border-0 bg-light" data-panel-auto-height-mode="height" style="overflow-x: hidden;">
	<!-- Custom CSS -->
	<link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/first_page.css'); ?>">


	<style scoped>
		.card,
		.info-box {
			box-shadow: 0 0 1px rgba(0, 0, 0, 0.125), 0 1px 3px rgba(0, 0, 0, 0.2);
			border-radius: 15px;
		}

		.info-box-icon {
			width: 35px !important;
		}
	</style>

	<div class="content-wrapper bg-light">
		<input type="text" id="tgl" name="tgl" class="form-control form-control-sm datepicker d-none" autocomplete="off">
		<?php
		if ($dasbor_msg !== '') {
			echo $dasbor_msg;
		}
		?>

		<div class="row mx-2">
			<div id="widget-penjualan-qty" class="col-md-3 d-none">
				<div class="info-box mb-3 bg-white">
					<span class="info-box-icon px-0 mx-0"><i class="fas fa-tag text-primary"></i></span>
					<div class="info-box-content">
						<span class="info-box-text py-0 my-0 font-weight-bold"><a href="javascript:void(0)" id="bwidgetqty" class="btn btn-sm mx-0 px-0" title="reload widget"><i class="fas fa-redo text-sm text-info"></i></a> Penjualan</span>
						<span class="info-box-number py-0 my-0 font-weight-normal text-sm" style="border-bottom: 1px solid #fff">Hari Ini : <b id="day-qty" class="font-weight-normal text-sm" style="float: right;"></b></span>
						<span class="info-box-number py-0 my-0 font-weight-normal text-sm" style="border-bottom: 1px solid #fff">Bulan Ini : <b id="month-qty" class="font-weight-normal text-sm" style="float: right;"></b></span>
						<span class="info-box-number py-0 my-0 font-weight-normal text-sm">Tahun Ini : <b id="year-qty" class="font-weight-normal" style="float: right;"></b></span>
					</div>
				</div>
			</div>
			<div id="widget-penjualan-omset" class="col-md-3 d-none">
				<div class="info-box mb-3 bg-white">
					<span class="info-box-icon px-0 mx-0"><i class="fas fa-tag text-primary"></i></span>
					<div class="info-box-content">
						<span class="info-box-text py-0 my-0 font-weight-bold"><a href="javascript:void(0)" id="bwidgetomset" class="btn btn-sm mx-0 px-0" title="reload widget"><i class="fas fa-redo text-sm text-info"></i></a> Omset Penjualan</span>
						<span class="info-box-number py-0 my-0 font-weight-normal text-sm" style="border-bottom: 1px solid #fff">Hari Ini : <b id="day-omset" class="font-weight-normal text-sm" style="float: right;"></b></span>
						<span class="info-box-number py-0 my-0 font-weight-normal text-sm" style="border-bottom: 1px solid #fff">Bulan Ini : <b id="month-omset" class="font-weight-normal text-sm" style="float: right;"></b></span>
						<span class="info-box-number py-0 my-0 font-weight-normal text-sm">Tahun Ini : <b id="year-omset" class="font-weight-normal" style="float: right;"></b></span>
					</div>
				</div>
			</div>
			<div id="widget-biaya-biaya" class="col-md-3 d-none">
				<div class="info-box mb-3 bg-white">
					<span class="info-box-icon px-0 mx-0"><i class="fas fa-tag text-primary"></i></span>
					<div class="info-box-content">
						<span class="info-box-text py-0 my-0 font-weight-bold"><a href="javascript:void(0)" id="bwidgetbiaya" class="btn btn-sm mx-0 px-0" title="reload widget"><i class="fas fa-redo text-sm text-info"></i></a> Biaya - Biaya</span>
						<span class="info-box-number py-0 my-0 font-weight-normal text-sm" style="border-bottom: 1px solid #fff">Hari Ini : <b id="day-biaya" class="font-weight-normal" style="float: right;"></b></span>
						<span class="info-box-number py-0 my-0 font-weight-normal text-sm" style="border-bottom: 1px solid #fff">Bulan Ini : <b id="month-biaya" class="font-weight-normal text-sm" style="float: right;"></b></span>
						<span class="info-box-number py-0 my-0 font-weight-normal text-sm">Tahun Ini : <b id="year-biaya" class="font-weight-normal" style="float: right;"></b></span>
					</div>
				</div>
			</div>
			<div id="widget-penjualan-profit" class="col-md-3 d-none">
				<div class="info-box mb-3 bg-white">
					<span class="info-box-icon px-0 mx-0"><i class="fas fa-tag text-primary"></i></span>
					<div class="info-box-content">
						<span class="info-box-text py-0 my-0 font-weight-bold"><a href="javascript:void(0)" id="bwidgetlaba" class="btn btn-sm mx-0 px-0" title="reload widget"><i class="fas fa-redo text-sm text-info"></i></a> Profit Penjualan</span>
						<span class="info-box-number py-0 my-0 font-weight-normal text-sm" style="border-bottom: 1px solid #fff">Hari Ini : <b id="day-profit" class="font-weight-normal" style="float: right;"></b></span>
						<span class="info-box-number py-0 my-0 font-weight-normal text-sm" style="border-bottom: 1px solid #fff">Bulan Ini : <b id="month-profit" class="font-weight-normal text-sm" style="float: right;"></b></span>
						<span class="info-box-number py-0 my-0 font-weight-normal text-sm">Tahun Ini : <b id="year-profit" class="font-weight-normal" style="float: right;"></b></span>
					</div>
				</div>
			</div>
		</div>

		<div class="row px-2">
			<div class="col-sm-12">
				<div id="widget-topitem-chart" class="col-sm-12 d-none">
					<div class="card bg-white collapsed-card">
						<div class="card-header">
							<div class="d-flex">
								<p class="d-flex flex-column">
									<span id="topitemket" class="font-weight-bold  text-secondary"></span>
									<span id="lblyeartopitem" class="text-md"></span>
								</p>
								<p class="ml-auto d-flex flex-row text-right">
								<div class="mr-2" style="width: 60px">
									<select class="select2 form-control form-control-sm" id="tipechart4" name="tipechart4">
										<option value="bar" selected>Bar</option>
										<option value="line">Line</option>
									</select>
								</div>
								<div style="width: 80px">
									<select class="select2 form-control form-control-sm" id="c4tahun" name="c4tahun" data-trigger="manual" data-placement="auto">
									</select>
								</div>
								<a href="javascript:void(0)" id="bwidgettopitem" class="btn btn-sm ml-2" title="reload chart"><i class="fas fa-redo text-sm text-secondary"></i></a>
								</p>
							</div>
							<!-- /.d-flex -->

							<div id='load-chart4' class="col-sm-11 text-center d-none">
								<i class="fas fa-spin fa-spinner text-info" style="font-size:22px"></i>
							</div>

							<div id="topitem-chart-content" class="position-relative mb-2" style="height:200px">
								<canvas id="topitem-chart" height="200">
								</canvas>
							</div>

							<div id="foot-chart4">
								<p class="d-flex flex-row ml-auto">
									<button type="button" class="btn btn-tool btn-primary mt-4" data-card-widget="collapse" title="Filter Chart">
										<span>Filter Lainnya</span>
									</button>
								</p>
							</div>
						</div>
						<div class="card-body">
							<div class="row px-2">
								<label class="col-form-label col-sm-2 font-weight-bold text-sm">Jumlah Item :</label>
								<div class="col-sm-2">
									<select class="select2 form-control form-control-sm" id="c4jumlah" name="c4jumlah" data-trigger="manual" data-placement="auto">
										<option value="5">5</option>
										<option value="10" selected>10</option>
										<option value="15">15</option>
										<option value="20">20</option>
									</select>
								</div>
							</div>
							<div class="row pt-1 px-2">
								<label class="col-form-label col-sm-2 font-weight-bold text-sm">Dari Bulan :</label>
								<div class="col-sm-3">
									<select class="select-bulan select2 form-control form-control-sm" id="c4bulan1" name="c4bulan1" data-trigger="manual" data-placement="auto">
										<option value="01" selected>Januari</option>
										<option value="02">Februari</option>
										<option value="03">Maret</option>
										<option value="04">April</option>
										<option value="05">Mei</option>
										<option value="06">Juni</option>
										<option value="07">Juli</option>
										<option value="08">Agustus</option>
										<option value="09">September</option>
										<option value="10">Oktober</option>
										<option value="11">November</option>
										<option value="12">Desember</option>
									</select>
								</div>
							</div>
							<div class="row pt-1 px-2">
								<label class="col-form-label col-sm-2 font-weight-bold text-sm">Sampai Bulan :</label>
								<div class="col-sm-3">
									<select class="select-bulan select2 form-control form-control-sm" id="c4bulan2" name="c4bulan2" data-trigger="manual" data-placement="auto">
										<option value="01">Januari</option>
										<option value="02">Februari</option>
										<option value="03">Maret</option>
										<option value="04">April</option>
										<option value="05">Mei</option>
										<option value="06">Juni</option>
										<option value="07">Juli</option>
										<option value="08">Agustus</option>
										<option value="09">September</option>
										<option value="10">Oktober</option>
										<option value="11">November</option>
										<option value="12" selected>Desember</option>
									</select>
								</div>
							</div>
							<div class="row pt-1 px-2">
								<div class="col-sm-2"></div>
								<div class="col-sm-2">
									<button id="topitemfsubmit" type="button" class="btn btn-primary btn-sm" title="Submit">
										<span>Submit</span>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row mx-2">
			<div class="col-sm-6">
				<div id="widget-saldo-akun" class="col-sm-12 d-none">
					<div class="card bg-white">
						<div class="card-body">
							<div class="d-flex">
								<p class="d-flex flex-column">
									<span class="font-weight-bold text-secondary">Saldo Akun Neraca</span>
									<span id="lblsaldoakun" class="text-md"></span>
								</p>
								<p class="ml-auto d-flex flex-row text-right">
								<div class="mr-2" style="width: 60px">
								</div>
								<div style="width: 80px">
								</div>
								<a href="javascript:void(0)" id="bwidgetsaldoakun" class="btn btn-sm ml-2" title="reload widget"><i class="fas fa-redo text-sm text-secondary"></i></a>
								</p>
							</div>
							<!-- /.d-flex -->

							<div id='load-saldoakun' class="col-sm-11 text-center d-none">
								<i class="fas fa-spin fa-spinner text-info" style="font-size:22px"></i>
							</div>

							<div id="saldoakun-content" class="position-relative mb-2">
								<table id="saldoakun-table" class="table table-striped table-hover table-sm w-100">
									<thead>
										<tr>
											<th class="d-none"></th>
											<th></th>
											<th class="text-sm">Keterangan</th>
											<th class="text-sm">Saldo</th>
										</tr>
									</thead>
								</table>
							</div>

							<div id="foot-saldoakun" class="d-none">
								<p class="d-flex flex-row ml-auto">
									<span class="mr-2 text-sm">
									</span>
									<span class="text-sm">
									</span>
								</p>
							</div>
						</div>
					</div>
				</div>

				<div id="widget-barang-terlaris" class="col-sm-12 d-none">
					<div class="card bg-white collapsed-card">
						<div class="card-header">
							<div class="d-flex">
								<p class="d-flex flex-column">
									<span class="font-weight-bold text-secondary">Barang Terjual</span>
									<span id="lblterlaris" class="text-md"></span>
								</p>
								<p class="ml-auto d-flex flex-row text-right">
								<div class="mr-2" style="width: 60px">
								</div>
								<div style="width: 80px">
								</div>
								<a href="javascript:void(0)" id="bwidgetterlaris" class="btn btn-sm ml-2" title="reload widget"><i class="fas fa-redo text-sm text-secondary"></i></a>
								</p>
							</div>
							<!-- /.d-flex -->

							<div id='load-terlaris' class="col-sm-11 text-center d-none">
								<i class="fas fa-spin fa-spinner text-info" style="font-size:22px"></i>
							</div>

							<div id="terlaris-content" class="position-relative mb-2">
								<table id="terlaris-table" class="table table-striped table-hover table-sm w-100">
									<thead>
										<tr>
											<th class="d-none"></th>
											<th></th>
											<th class="text-sm">SKU</th>
											<th class="text-sm">Nama Item</th>
											<th class="text-sm">Terjual</th>
										</tr>
									</thead>
								</table>
							</div>

							<div id="foot-terlaris">
								<p class="d-flex flex-row ml-auto">
									<button type="button" class="btn btn-tool btn-primary mt-4 px-4" data-card-widget="collapse" title="Filter Chart">
										<span>Filter</span>
									</button>
								</p>
							</div>
						</div>
						<div class="card-body">
							<div class="row pt-1 px-2">
								<label class="col-form-label col-sm-4 font-weight-bold text-sm">Dari Tanggal :</label>
								<div class="col-sm-4">
									<div class="input-group date">
										<input id="tgl1terlaris" type="text" class="form-control form-control-sm datepicker">
										<div id="btgl1terlaris" class="input-group-append" role="button">
											<div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
										</div>
									</div>
								</div>
							</div>
							<div class="row pt-1 px-2">
								<label class="col-form-label col-sm-4 font-weight-bold text-sm">Sampai Tanggal :</label>
								<div class="col-sm-4">
									<div class="input-group date">
										<input id="tgl2terlaris" type="text" class="form-control form-control-sm datepicker">
										<div id="btgl2terlaris" class="input-group-append" role="button">
											<div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
										</div>
									</div>
								</div>
							</div>
							<div class="row pt-1 px-2">
								<div class="col-sm-4"></div>
								<div class="col-sm-4">
									<button id="barangterlarissubmit" type="button" class="btn btn-primary btn-sm" title="Submit">
										<span>Submit</span>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div id="widget-jasa-terlaris" class="col-sm-12 d-none">
					<div class="card bg-white collapsed-card">
						<div class="card-header">
							<div class="d-flex">
								<p class="d-flex flex-column">
									<span class="font-weight-bold  text-secondary">Jasa / Service Terjual</span>
									<span id="lblterlarisjasa" class="text-md"></span>
								</p>
								<p class="ml-auto d-flex flex-row text-right">
								<div class="mr-2" style="width: 60px">
								</div>
								<div style="width: 80px">
								</div>
								<a href="javascript:void(0)" id="bwidgetterlarisjasa" class="btn btn-sm ml-2" title="reload widget"><i class="fas fa-redo text-sm text-secondary"></i></a>
								</p>
							</div>
							<!-- /.d-flex -->

							<div id='load-terlarisjasa' class="col-sm-11 text-center d-none">
								<i class="fas fa-spin fa-spinner text-info" style="font-size:22px"></i>
							</div>

							<div id="terlarisjasa-content" class="position-relative mb-2">
								<table id="terlarisjasa-table" class="table table-striped table-hover table-sm w-100">
									<thead>
										<tr>
											<th class="d-none"></th>
											<th></th>
											<th class="text-sm">SKU</th>
											<th class="text-sm">Nama Jasa</th>
											<th class="text-sm">Terjual</th>
										</tr>
									</thead>
								</table>
							</div>

							<div id="foot-terlarisjasa">
								<p class="d-flex flex-row ml-auto">
									<button type="button" class="btn btn-tool btn-primary mt-4 px-4" data-card-widget="collapse" title="Filter Chart">
										<span>Filter</span>
									</button>
								</p>
							</div>
						</div>
						<div class="card-body">
							<div class="row pt-1 px-2">
								<label class="col-form-label col-sm-4 font-weight-bold text-sm">Dari Tanggal :</label>
								<div class="col-sm-4">
									<div class="input-group date">
										<input id="tgl1jasaterlaris" type="text" class="form-control form-control-sm datepicker">
										<div id="btgl1jasaterlaris" class="input-group-append" role="button">
											<div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
										</div>
									</div>
								</div>
							</div>
							<div class="row pt-1 px-2">
								<label class="col-form-label col-sm-4 font-weight-bold text-sm">Sampai Tanggal :</label>
								<div class="col-sm-4">
									<div class="input-group date">
										<input id="tgl2jasaterlaris" type="text" class="form-control form-control-sm datepicker">
										<div id="btgl2jasaterlaris" class="input-group-append" role="button">
											<div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
										</div>
									</div>
								</div>
							</div>
							<div class="row pt-1 px-2">
								<div class="col-sm-4"></div>
								<div class="col-sm-4">
									<button id="jasaterlarissubmit" type="button" class="btn btn-primary btn-sm" title="Submit">
										<span>Submit</span>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div id="widget-expired-item" class="col-sm-12 d-none">
					<div class="card bg-white">
						<div class="card-body">
							<div class="d-flex">
								<p class="d-flex flex-column">
									<span class="font-weight-bold  text-secondary">Barang Expired 1 Bulan Ke Depan</span>
									<span id="lblreminder" class="text-md"></span>
								</p>
								<p class="ml-auto d-flex flex-row text-right">
								<div class="mr-2" style="width: 60px">
								</div>
								<div style="width: 80px">
								</div>
								<a href="javascript:void(0)" id="bwidgetexpired" class="btn btn-sm ml-2" title="reload widget"><i class="fas fa-redo text-sm text-secondary"></i></a>
								</p>
							</div>
							<!-- /.d-flex -->

							<div id='load-expired' class="col-sm-11 text-center d-none">
								<i class="fas fa-spin fa-spinner text-info" style="font-size:22px"></i>
							</div>

							<div id="expired-content" class="position-relative mb-2">
								<table id="expired-table" class="table table-striped table-hover table-sm w-100">
									<thead>
										<tr>
											<th class="d-none"></th>
											<th></th>
											<th class="text-sm">SKU</th>
											<th class="text-sm">Nama Item</th>
											<th class="text-sm">Tgl Expired</th>
											<th class="text-sm">Qty</th>
										</tr>
									</thead>
								</table>
							</div>

							<div id="foot-expired" class="d-none">
								<p class="d-flex flex-row ml-auto">
									<span class="mr-2 text-sm">
									</span>
									<span class="text-sm">
									</span>
								</p>
							</div>
						</div>
					</div>
				</div>

				<div id="widget-neraca" class="col-sm-12 d-none">
					<div class="card bg-white">
						<div class="card-body">
							<div class="d-flex">
								<p class="d-flex flex-column">
									<span class="font-weight-bold text-secondary">Daftar Posisi Keuangan</span>
									<span id="lblneraca" class="text-md"></span>
								</p>
								<p class="ml-auto d-flex flex-row text-right">
								<div class="mr-2" style="width: 60px">
								</div>
								<div style="width: 80px">
								</div>
								<a href="javascript:void(0)" id="bwidgetneraca" class="btn btn-sm ml-2" title="reload widget"><i class="fas fa-redo text-sm text-secondary"></i></a>
								</p>
							</div>
							<!-- /.d-flex -->

							<div id='load-neraca' class="col-sm-11 text-center d-none">
								<i class="fas fa-spin fa-spinner text-info" style="font-size:22px"></i>
							</div>

							<div id="neraca-content" class="position-relative mb-2">
								<table id="neraca-table" class="table table-striped table-hover table-sm w-100">
									<thead>
										<tr>
											<th class="d-none"></th>
											<th></th>
											<th class="text-sm">Keterangan</th>
											<th class="text-sm">Saldo</th>
										</tr>
									</thead>
								</table>
							</div>

							<div id="foot-neraca" class="d-none">
								<p class="d-flex flex-row ml-auto">
									<span class="mr-2 text-sm">
									</span>
									<span class="text-sm">
									</span>
								</p>
							</div>
						</div>
					</div>
				</div>

			</div>
			<div class="col-sm-6">
				<div id="widget-sales-chart" class="col-sm-12 d-none">
					<div class="card bg-white">
						<div class="card-body">
							<div class="d-flex">
								<p class="d-flex flex-column">
									<span class="font-weight-bold  text-secondary">Penjualan</span>
									<span id="lblyearsales" class="text-md"></span>
								</p>
								<p class="ml-auto d-flex flex-row text-right">
								<div class="mr-2" style="width: 60px">
									<select class="select2 form-control form-control-sm" id="tipechart1" name="tipechart1">
										<option value="bar" selected>Bar</option>
										<option value="line">Line</option>
										<option value="pie">Pie</option>
									</select>
								</div>
								<div style="width: 80px">
									<select class="select2 form-control form-control-sm" id="c1tahun" name="c1tahun" data-trigger="manual" data-placement="auto">
									</select>
								</div>
								<a href="javascript:void(0)" id="bwidgetsales" class="btn btn-sm ml-2" title="reload chart"><i class="fas fa-redo text-sm text-secondary"></i></a>
								</p>
							</div>
							<!-- /.d-flex -->

							<div id='load-chart1' class="col-sm-11 text-center d-none">
								<i class="fas fa-spin fa-spinner text-info" style="font-size:22px"></i>
							</div>

							<div id="sales-chart-content" class="position-relative mb-2" style="height:200px">
								<canvas id="sales-chart" height="200">
								</canvas>
							</div>

							<div id="foot-chart1" class="d-none">
								<p class="d-flex flex-row ml-auto">
									<span class="mr-2 text-sm">
										<i class="fas fa-square text-primary"></i> Penjualan
									</span>
									<span class="text-sm">
										<i class="fas fa-square" style="color:#ced4da;"></i> Pesanan Penjualan
									</span>
								</p>
							</div>
						</div>
					</div>
				</div>

				<div id="widget-profit-chart" class="col-sm-12 d-none">
					<div class="card bg-white">
						<div class="card-body">
							<div class="d-flex">
								<p class="d-flex flex-column">
									<span class="font-weight-bold  text-secondary">Laba Rugi</span>
									<span id="lblyearprofit" class="text-md"></span>
								</p>
								<p class="ml-auto d-flex flex-row text-right">
								<div class="mr-2" style="width: 60px">
									<select class="select2 form-control form-control-sm" id="tipechart3" name="tipechart3">
										<option value="bar">Bar</option>
										<option value="line">Line</option>
										<option value="pie" selected>Pie</option>
									</select>
								</div>
								<div style="width: 80px">
									<select class="select2 form-control form-control-sm" id="c3tahun" name="c3tahun" data-trigger="manual" data-placement="auto">
									</select>
								</div>
								<a href="javascript:void(0)" id="bwidgetprofit" class="btn btn-sm ml-2" title="reload chart"><i class="fas fa-redo text-sm text-secondary"></i></a>
								</p>
							</div>

							<!-- /.d-flex -->

							<div id='load-chart3' class="col-sm-11 text-center d-none">
								<i class="fas fa-spin fa-spinner text-info" style="font-size:22px"></i>
							</div>

							<div id="profit-chart-content" class="position-relative mb-2" style="height:200px">
								<canvas id="profit-chart" height="200">
								</canvas>
							</div>

							<div id="foot-chart3" class="d-none">
								<p class="d-flex flex-row ml-auto">
									<span class="mr-2 text-sm">
										<i class="fas fa-square text-primary"></i> Total Pendapatan
									</span>
									<span class="mr-2 text-sm">
										<i class="fas fa-square text-info"></i> Total HPP
									</span>
									<span class="text-sm">
										<i class="fas fa-square" style="color:#ced4da;"></i> Total Biaya
									</span>
								</p>
							</div>
						</div>
					</div>
				</div>

				<div id="widget-cashflow-chart" class="col-sm-12 d-none">
					<div class="card bg-white">
						<div class="card-body">
							<div class="d-flex">
								<p class="d-flex flex-column">
									<span class="font-weight-bold  text-secondary">Kas & Bank</span>
									<span id="lblyearcashflow" class="text-md"></span>
								</p>
								<p class="ml-auto d-flex flex-row text-right">
								<div class="mr-2" style="width: 60px">
									<select class="select2 form-control form-control-sm" id="tipechart2" name="tipechart2">
										<option value="bar">Bar</option>
										<option value="line" selected>Line</option>
									</select>
								</div>
								<div style="width: 80px">
									<select class="select2 form-control form-control-sm" id="c2tahun" name="c2tahun" data-trigger="manual" data-placement="auto">
									</select>
								</div>
								<a href="javascript:void(0)" id="bwidgetcashflow" class="btn btn-sm ml-2" title="reload chart"><i class="fas fa-redo text-sm text-secondary"></i></a>
								</p>
							</div>
							<!-- /.d-flex -->

							<div id='load-chart2' class="col-sm-11 text-center d-none">
								<i class="fas fa-spin fa-spinner text-info" style="font-size:22px"></i>
							</div>

							<div id="cashflow-chart-content" class="position-relative mb-2" style="height:200px">
								<canvas id="cashflow-chart" height="200">
								</canvas>
							</div>

							<div id="foot-chart2" class="d-none">
								<p class="d-flex flex-row ml-auto">
									<span class="mr-2 text-sm">
										<i class="fas fa-square text-primary"></i> Kas
									</span>
									<span class="text-sm">
										<i class="fas fa-square" style="color:#ced4da;"></i> Bank
									</span>
								</p>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
	<script src="<?= base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
	<script src="<?= base_url('assets/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
	<script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
	<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
	<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js'); ?>"></script>
	<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.js'); ?>"></script>
	<script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.js'); ?>"></script>
	<script src="<?= base_url('assets/plugins/datatables-select/js/dataTables.select.js'); ?>"></script>
	<script src="<?= base_url('assets/plugins/datatables-select/js/select.bootstrap4.js'); ?>"></script>
	<script src="<?= base_url('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>
	<script src="<?= base_url('assets/dist/js/adminlte.js'); ?>"></script>
	<script src="<?= base_url('assets/plugins/select2/select2.full.js'); ?>"></script>
	<script src="<?= base_url('assets/plugins/chart.js/Chart.min.js'); ?>"></script>
	<script src="<?= base_url('assets/dist/js/akunting.js'); ?>"></script>


	<script src="<?= base_url('assets/dist/js/modul/first-page.js'); ?>"></script>