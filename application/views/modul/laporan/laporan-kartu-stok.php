<?php
include('style.php');
$date1 = $_POST['tgldari'];
$date2 = $_POST['tglsampai'];
$item =  @$_POST['item'];
$gudang =  @$_POST['gudang'];
$tampilNol =  $_POST['saldo'];
$CI = &get_instance();

if (empty($gudang)) {
	$namagudang = 'Semua';
	$querygudang = "";
	$querygudang2 = "";
} else {
	$namagudang = json_decode($CI->gettabelvalue('bgudang', 'gnama', 'gid', $gudang));
	foreach ($namagudang->data as $row) {
		$namagudang = $row->gnama;
	}
	$querygudang = " AND AA.sdgudang='" . $gudang . "'";
	$querygudang2 = " AND B.sdgudang='" . $gudang . "'";
}

$query  = "SELECT A.iid,A.ikode, A.inama,A.isatuan, 
					  (SELECT IFNULL(SUM(AA.sdmasukd),0)-IFNULL(SUM(AA.sdkeluard),0)  
				         FROM fstokd AA INNER JOIN fstoku AB ON AA.sdidsu=AB.suid AND AB.sutanggal <= '" . tgl_database($date2) . "' 
				        WHERE AA.sditem=A.iid " . $querygudang . ") 'saldo'
				 FROM bitem A WHERE A.ijenisitem=0";

if (!empty($item)) {
	$query .= "  AND A.iid='" . $item . "'";
}

$query .= "  ORDER BY A.ikode ASC";
$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);

?>
<?php
if ($use_logo == 0) {
?>
	<div class="header-report">
		<h4 class="text-blue"><?= $company_name; ?></h4>
		<h3><?= $title; ?></h3>
		<span>Periode : <?= $date1; ?> s/d <?= $date2; ?></span>
	</div>
<?php
} else {
?>
	<div class="header-report">
		<div class="logo left">
			<img src="<?php echo app_url('assets/dist/img/logo-utama.png'); ?>" width="80" height="80" />
		</div>
		<div class="left px-1" width="38%">
			<h4 class="text-blue"><b><?= $company_name; ?></b></h4>
			<h3><?= $title; ?></h3>
			<span>Periode : <?= $date1; ?> s/d <?= $date2; ?></span>
		</div>
	</div>
<?php
}
?>
<div class="content-report">
	<?php
	foreach ($datareport->data as $row) {
		if ($row->saldo == 0 && $tampilNol == 0) {
			continue;
		}
		// Ambil Saldo Awal
		$query = "SELECT IFNULL(SUM(AA.sdmasukd),0)-IFNULL(SUM(AA.sdkeluard),0) 'saldo'   
				         FROM fstokd AA INNER JOIN fstoku AB ON AA.sdidsu=AB.suid AND AB.sutanggal < '" . tgl_database($date1) . "' 
				        WHERE AA.sditem='" . $row->iid . "' " . $querygudang;
		$saldoawal = $CI->M_transaksi->get_data_query($query);
		$saldoawal = json_decode($saldoawal);
		foreach ($saldoawal->data as $saldo) {
			$saldoawalval = $saldo->saldo;
		}
		//
		// Detil Transaksi GL
		$query = "SELECT DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal', A.sunotransaksi 'nomor', C.nketerangan 'uraian', 
							 B.sdmasukd 'masuk', B.sdkeluard 'keluar'  
					    FROM fstoku A INNER JOIN fstokd B ON A.suid=B.sdidsu LEFT JOIN anomor C ON A.susumber=C.nkode  
					   WHERE A.sutanggal BETWEEN '" . tgl_database($date1) . "' AND '" . tgl_database($date2) . "' 
					     AND B.sditem ='" . $row->iid . "' " . $querygudang2 . " ORDER BY A.sutanggal ASC, A.suid ASC";
		$detilreport = $CI->M_transaksi->get_data_query($query);
		$detilreport = json_decode($detilreport);
		$saldoakhir = $saldoawalval;
	?>
		<table class="table">
			<thead>
				<tr class="border-0 bg-dark">
					<th colspan="3" class="left px-1 py-1">
						<?= $row->ikode; ?> : <?= $row->inama; ?>
					</th>
					<th colspan="3" class="left px-1 py-1">
						GUDANG : <?= $namagudang; ?>
					</th>
				</tr>
				<tr>
					<th class="left px-1" width="11%">Tanggal</th>
					<th class="left px-1" width="12%">Nomor</th>
					<th class="left px-1">Jenis/Sumber</th>
					<th class="right px-1" width="18%">Masuk</th>
					<th class="right px-1" width="18%">Keluar</th>
					<th class="right px-1" width="18%">Jumlah</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="3">Stok Awal:</td>
					<td class="right px-1"></td>
					<td class="right px-1"></td>
					<td class="right px-1"><?= eFormatNumber($saldoawalval, $digitqty); ?></td>
				</tr>
				<?php
				foreach ($detilreport->data as $detil) {
					$saldoakhir += $detil->masuk - $detil->keluar;
					echo "<tr>
											<td>$detil->tanggal</td>
											<td>$detil->nomor</td>
											<td>$detil->uraian</td>
											<td class=\"right px-1\">" . eFormatNumber($detil->masuk, $digitqty) . "</td>
											<td class=\"right px-1\">" . eFormatNumber($detil->keluar, $digitqty) . "</td>
											<td class=\"right px-1\">" . eFormatNumber($saldoakhir, $digitqty) . "</td>						
									  </tr>";
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="3" class="py-1">Stok Akhir :</td>
					<td></td>
					<td></td>
					<td class="right px-1 py-1"><?= eFormatNumber($row->saldo, $digitqty); ?></td>
				</tr>
			</tfoot>
		</table>
		<div class="clear">&nbsp;</div>
	<?php
	}
	?>
</div>