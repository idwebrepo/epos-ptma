<?php
include('style.php');
$date1 = '01-01-1990';
$date2 = $_POST['tgl'];
$item =  @$_POST['item'];
$gudang =  @$_POST['gudang'];
$tampilNol =  $_POST['saldo'];
$minimum =  $_POST['minimum'];

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

$query  = "SELECT A.iid,A.ikode, A.inama,A.isatuan, A.istockminimal, 
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
		<h4><?= $company_name; ?></h4>
		<h3><?= $title; ?></h3>
		<span>Per Tanggal : <?= $date2; ?></span>
		<br /><span style="padding-top:5px">Gudang : <?= $namagudang; ?></span>
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
			<span>Per Tanggal : <?= $date2; ?></span>
			<br /><span style="padding-top:5px">Gudang : <?= $namagudang; ?></span>
		</div>
	</div>
<?php
}
?>
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th style="vertical-align: middle;">KODE</th>
				<th style="vertical-align: middle;">NAMA ITEM</th>
				<th class="center" width="7%" style="vertical-align: middle;">QTY MASUK</th>
				<th class="center" width="7%" style="vertical-align: middle;">QTY KELUAR</th>
				<th class="center" width="7%" style="vertical-align: middle;">QTY</th>
				<th class="center" width="14%" style="vertical-align: middle;">HARGA</th>
				<th class="center" width="14%" style="vertical-align: middle;">SALDO</th>
				<th class="center" width="7%" style="vertical-align: middle;">QTY MIN</th>
				<th class="center" width="7%" style="vertical-align: middle;">CEKLIS</th>
			</tr>

		</thead>
		<tbody>
			<?php
			$totalstok = 0;
			$totalhppharga = 0;
			$totalhpp = 0;
			$totalmasuk = 0;
			$totalkeluar = 0;
			$totalmin = 0;

			foreach ($datareport->data as $row) {

				//			if($row->saldoqty == 0 && $tampilNol == 0){
				//				continue;
				//			}			

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

				$qtymasuk = 0;
				$qtykeluar = 0;

				foreach ($detilreport->data as $detil) {
					$saldoakhirqty += ($detil->masuk - $detil->keluar);
					$saldoakhir += ($detil->hargamasuk * $detil->masuk) - ($detil->hargakeluar * $detil->keluar);
					if ($saldoakhirqty == 0) {
						$saldoakhirharga = 0;
					} else {
						$saldoakhirharga = $saldoakhir / $saldoakhirqty;
					}

					$qtymasuk += $detil->masuk;
					$qtykeluar += $detil->keluar;
				}
				if ($minimum == 1) {
					if ($row->saldoqty <= $row->istockminimal) {
			?>
						<tr>
							<td><?= $row->ikode ?></td>
							<td><?= $row->inama ?></td>
							<td class="right "><?= eFormatNumber($qtymasuk, $digitqty); ?></td>
							<td class="right "><?= eFormatNumber($qtykeluar, $digitqty); ?></td>
							<td class="right "><?= eFormatNumber($row->saldoqty, $digitqty); ?></td>
							<td class="right "><?= eFormatNumber($saldoakhirharga, 2); ?></td>
							<td class="right "><?= eFormatNumber($saldoakhir, 2); ?></td>
							<td class="right "><?= eFormatNumber($row->istockminimal, $digitqty); ?></td>
							<td class="right px-1"><input type="checkbox" style="font-size:14pt"></td>
						</tr>
					<?php
						$totalstok += $row->saldoqty;
						$totalhppharga += $saldoakhirharga;
						$totalhpp += $saldoakhir;
						$totalmasuk += $qtymasuk;
						$totalkeluar += $qtykeluar;
						$totalmin += $row->istockminimal;
					}
				} else {
					?>
					<tr>
						<td><?= $row->ikode ?></td>
						<td><?= $row->inama ?></td>
						<td class="right "><?= eFormatNumber($qtymasuk, $digitqty); ?></td>
						<td class="right "><?= eFormatNumber($qtykeluar, $digitqty); ?></td>
						<td class="right "><?= eFormatNumber($row->saldoqty, $digitqty); ?></td>
						<td class="right "><?= eFormatNumber($saldoakhirharga, 2); ?></td>
						<td class="right "><?= eFormatNumber($saldoakhir, 2); ?></td>
						<td class="right "><?= eFormatNumber($row->istockminimal, $digitqty); ?></td>
						<td class="right px-1"><input type="checkbox" style="font-size:14pt"></td>
					</tr>
			<?php
					$totalstok += $row->saldoqty;
					$totalhppharga += $saldoakhirharga;
					$totalhpp += $saldoakhir;
					$totalmasuk += $qtymasuk;
					$totalkeluar += $qtykeluar;
					$totalmin += $row->istockminimal;
				}
			}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2" class="left">TOTAL</td>
				<td class="right "><?= eFormatNumber($totalmasuk, $digitqty); ?></td>
				<td class="right "><?= eFormatNumber($totalkeluar, $digitqty); ?></td>
				<td class="right "><?= eFormatNumber($totalstok, $digitqty); ?></td>
				<td class="right "><?= eFormatNumber($totalhppharga, 2); ?></td>
				<td class="right "><?= eFormatNumber($totalhpp, 2); ?></td>
				<td class="right "><?= eFormatNumber($totalmin, $digitqty); ?></td>
				<td class="left"></td>
			</tr>
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>
</div>