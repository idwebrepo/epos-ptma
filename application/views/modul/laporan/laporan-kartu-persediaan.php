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
				        WHERE AA.sditem=A.iid " . $querygudang . ") 'saldoqty'				        
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

		if ($row->saldoqty == 0 && $tampilNol == 0) {
			continue;
		}

		// Saldo Awal Kartu Persediaan
		$query = "SELECT IFNULL((SUM(masuk)-SUM(keluar)),0) 'qty', 
							 IFNULL((SUM(saldomasuk)-SUM(saldokeluar)),0) 'saldo'  
						FROM (
							 SELECT B.sdmasukd 'masuk', B.sdkeluard 'keluar',         
									B.sdmasukd * IFNULL((SELECT IF(AA.sdsatuan=AA.sdsatuand,AA.sdharga,AA.sdharga/AA.sdmasukd) 
										                  FROM fstokd AA 
										                 WHERE AA.sdid=B.sdid AND AA.sdmasukd>0 ),0) 'saldomasuk',
							        B.sdkeluard * IFNULL((SELECT IFNULL(SUM(AH.shharga*AH.shqty)/SUM(AH.shqty), AB.sdharga) 
										             	   FROM fstokd AB 
										              LEFT JOIN fstokh AH ON AB.sdid=AH.shidsd 
							        			          WHERE AB.sdid=B.sdid AND AB.sdkeluard>0 ),0) 'saldokeluar'
							   FROM fstoku A 
						 INNER JOIN fstokd B ON A.suid=B.sdidsu 
							  WHERE A.sutanggal < '" . tgl_database($date1) . "' 
							    AND B.sditem ='" . $row->iid . "' " . $querygudang2 . "
							 ) SaldoAwal";

		$saldoawal = $CI->M_transaksi->get_data_query($query);
		$saldoawal = json_decode($saldoawal);

		foreach ($saldoawal->data as $saldo) {
			$saldoawalqty = $saldo->qty;
			$saldoawal = $saldo->saldo;
			if ($saldoawalqty == 0) {
				$saldoawalharga = 0;
			} else {
				$saldoawalharga = $saldoawal / $saldoawalqty;
			}
		}
		// ==========================

		// Detil Transaksi Persediaan
		$query = "SELECT DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal', A.sunotransaksi 'nomor', C.nketerangan 'uraian', 
							 B.sdmasukd 'masuk', 
                             IFNULL((SELECT IF(AA.sdsatuan=AA.sdsatuand,AA.sdharga,AA.sdharga/AA.sdmasukd) FROM fstokd AA WHERE AA.sdid=B.sdid AND AA.sdmasukd>0 ),0) 'hargamasuk',
                             B.sdkeluard 'keluar', 
                             IFNULL((SELECT IFNULL(SUM(AH.shharga*AH.shqty)/SUM(AH.shqty), AB.sdharga) FROM fstokd AB LEFT JOIN fstokh AH ON AB.sdid=AH.shidsd WHERE AB.sdid=B.sdid AND AB.sdkeluard>0 ),0) 'hargakeluar'  
					    FROM fstoku A INNER JOIN fstokd B ON A.suid=B.sdidsu LEFT JOIN anomor C ON A.susumber=C.nkode  
					   WHERE A.sutanggal BETWEEN '" . tgl_database($date1) . "' AND '" . tgl_database($date2) . "' 
					     AND B.sditem ='" . $row->iid . "' " . $querygudang2 . " ORDER BY A.sutanggal ASC, A.suid ASC";
		$detilreport = $CI->M_transaksi->get_data_query($query);
		$detilreport = json_decode($detilreport);
		$saldoakhirqty = $saldoawalqty;
		$saldoakhirharga = $saldoawalharga;
		$saldoakhir = $saldoawal;
	?>
		<table class="table">
			<thead>
				<tr class="border-0 bg-dark">
					<th colspan="6" class="left px-1 py-1">
						<?= $row->ikode; ?> : <?= $row->inama; ?>
					</th>
					<th colspan="6" class="left px-1 py-1">
						GUDANG : <?= $namagudang; ?>
					</th>
				</tr>
				<tr>
					<th class="left px-1"></th>
					<th class="left px-1"></th>
					<th class="left px-1"></th>
					<th colspan="3" align="center">Masuk</th>
					<th colspan="3" align="center">Keluar</th>
					<th colspan="3" align="center">Saldo</th>
				</tr>
				<tr>
					<th width="8%">Tanggal</th>
					<th width="8%">Nomor</th>
					<th>Jenis/Sumber</th>
					<th class="right" width="6%">Qty</th>
					<th class="right" width="8%">Harga</th>
					<th class="right" width="10%">Jumlah</th>
					<th class="right" width="6%">Qty</th>
					<th class="right" width="8%">Harga</th>
					<th class="right" width="10%">Jumlah</th>
					<th class="right" width="6%">Qty</th>
					<th class="right" width="8%">Harga</th>
					<th class="right" width="10%">Jumlah</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="3">Saldo Awal :</td>
					<td></td>
					<td></td>
					<td class="right"></td>
					<td class="right"></td>
					<td class="right"></td>
					<td class="right"></td>
					<td class="right"><?= eFormatNumber($saldoawalqty, $digitqty); ?></td>
					<td class="right"><?= eFormatNumber($saldoawalharga, 2); ?></td>
					<td class="right"><?= eFormatNumber($saldoawal, 2); ?></td>
				</tr>
				<?php
				foreach ($detilreport->data as $detil) {
					$saldoakhirqty += ($detil->masuk - $detil->keluar);
					$saldoakhir += ($detil->hargamasuk * $detil->masuk) - ($detil->hargakeluar * $detil->keluar);
					if ($saldoakhirqty == 0) {
						$saldoakhirharga = 0;
					} else {
						$saldoakhirharga = $saldoakhir / $saldoakhirqty;
					}

					echo "<tr>
											<td>$detil->tanggal</td>
											<td>$detil->nomor</td>
											<td>$detil->uraian</td>
											<td class=\"right\">" . eFormatNumber($detil->masuk, $digitqty) . "</td>
											<td class=\"right\">" . eFormatNumber($detil->hargamasuk, 2) . "</td>
											<td class=\"right\">" . eFormatNumber($detil->masuk * $detil->hargamasuk, 2) . "</td>
											<td class=\"right\">" . eFormatNumber($detil->keluar, $digitqty) . "</td>
											<td class=\"right\">" . eFormatNumber($detil->hargakeluar, 2) . "</td>
											<td class=\"right\">" . eFormatNumber($detil->keluar * $detil->hargakeluar, 2) . "</td>
											<td class=\"right\">" . eFormatNumber($saldoakhirqty, $digitqty) . "</td>
											<td class=\"right\">" . eFormatNumber($saldoakhirharga, 2) . "</td>
											<td class=\"right\">" . eFormatNumber($saldoakhir, 2) . "</td>						
									  </tr>";
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="3" class="py-1">Saldo Akhir :</td>
					<td colspan="6"></td>
					<td class="right py-1"><?= eFormatNumber($row->saldoqty, $digitqty); ?></td>
					<td class="right py-1"><?= eFormatNumber($saldoakhirharga, 2); ?></td>
					<td class="right py-1"><?= eFormatNumber($saldoakhir, 2); ?></td>
				</tr>
			</tfoot>
		</table>
		<div class="clear">&nbsp;</div>
	<?php
	}
	?>
</div>