<?php
include('style.php');
$date = $_POST['tgl'];
$tampilNol =  $_POST['saldo'];

$CI = &get_instance();
$query = "SELECT (SELECT SUM(ROUND(AA.cddebit,2))-SUM(ROUND(AA.cdkredit,2))  
				  FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal <= '" . tgl_database($date) . "' 
				INNER JOIN bcoa AC ON AA.cdnocoa=AC.cid WHERE AC.ctipe < 7) - 
				(SELECT SUM(ROUND(AA.cdkredit,2))-SUM(ROUND(AA.cddebit,2))  
				  FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal <= '" . tgl_database($date) . "' 
				INNER JOIN bcoa AC ON AA.cdnocoa=AC.cid WHERE AC.ctipe > 6 AND AC.ctipe < 11 ) 'selisih'";
$qselisih = $CI->M_transaksi->get_data_query($query);
$qselisih = json_decode($qselisih);

foreach ($qselisih->data as $row) {
	$selisih = $row->selisih;
}

$query = "SELECT A.cccoa, B.cnama FROM cconfigcoa A INNER JOIN bcoa B ON A.cccoa=B.cid WHERE A.cckode='RL' AND A.ccketerangan='BERJALAN'";
$qbalanceacc = $CI->M_transaksi->get_data_query($query);
$qbalanceacc = json_decode($qbalanceacc);

foreach ($qbalanceacc->data as $row) {
	$balanceacc = $row->cccoa;
}
//    echo $balanceacc;
?>
<?php
if ($use_logo == 0) {
?>
	<div class="header-report">
		<h4 class="text-blue"><?= $company_name; ?></h4>
		<h3><?= $title; ?></h3>
		<span>Per Tanggal : <?= $date; ?></span>
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
			<span>Per Tanggal : <?= $date; ?></span>
		</div>
	</div>
<?php
}
?>
<div class="content-report">
	<table>
		<tr>
			<td style="width: 50%; vertical-align: top;">
				<table class="table">
					<thead>
						<tr class="bg-dark">
							<th colspan="2" class="left px-1">Keterangan</th>
							<th class="right px-1" style="width: 150px;">Saldo</th>
						</tr>
					</thead>
					<tbody>
						<?php
						echo "<tr>
							<td colspan='3' class='py-1 px-1'><b>Aset</b></td>
					 </tr>";
						$query  = "SELECT *, (SELECT COUNT(*) FROM bcoa WHERE ctipe=cgid) 'count' FROM bcoagrup WHERE cgid<7";
						$query .= "  ORDER BY cgid ASC";

						$grup = $CI->M_transaksi->get_data_query($query);
						$grup = json_decode($grup);

						$grandtotal = 0;
						foreach ($grup->data as $row_grup) {
							if ($row_grup->count < 1) {
								continue;
							}

							echo "<tr>
								<td colspan='3' class='py-1 px-1 text-blue'>$row_grup->CGNAMA</td>
						 </tr>";
							$query = "SELECT A.cid, A.cnocoa, A.cnama, A.cgd, 
									  IFNULL((SELECT SUM(ROUND(AA.cddebit,2))-SUM(ROUND(AA.cdkredit,2))  
										 FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal <= '" . tgl_database($date) . "'
										WHERE AA.cdnocoa=A.cid),0) 'saldo'  
								  FROM bcoa A WHERE A.ctipe='" . $row_grup->CGID . "' AND A.cgd='D' ORDER BY A.cnocoa ASC";

							$coa = $CI->M_transaksi->get_data_query($query);
							$coa = json_decode($coa);
							$total = 0;
							foreach ($coa->data as $row) {
								if ($row->saldo == 0 && $tampilNol == 0) {
								} else {
									echo "<tr>
										<td colspan='2' class='px-1'>$row->cnocoa &nbsp;&nbsp; $row->cnama</td>
										<td class='right px-1'>" . eFormatNumber($row->saldo, 2) . "</td>									
								 </tr>";
								}
								$total += $row->saldo;
								$grandtotal += $row->saldo;
							}
							echo "<tr>
								<td colspan='2' class='py-1 px-1 text-blue'>Total $row_grup->CGNAMA</td>
								<td class='right px-1 py-1' style=\"border-top:.5px solid black\">" . eFormatNumber($total, 2) . "</td>
						 </tr>";
						}
						echo "<tr>
							<td colspan='2' class='px-1 py-2'><b>Total Aset</b></td>
							<td class='right px-1 py-2' style=\"border-top:.5px solid black\"><b>" . eFormatNumber($grandtotal, 2) . "</b></td>
					 </tr>";
						?>
					</tbody>
				</table>
			</td>
			<td style="width: 50%; vertical-align: top;">
				<table class="table">
					<thead>
						<tr class="bg-dark">
							<th colspan="2" class="left px-1">Keterangan</th>
							<th class="right px-1" style="width: 150px;">Saldo</th>
						</tr>
					</thead>
					<tbody>
						<?php
						// PASIVA
						echo "<tr>
							<td colspan='3' class='py-1 px-1'><b>Kewajiban & Ekuitas</b></td>
					 </tr>";
						$query  = "SELECT *, (SELECT COUNT(*) FROM bcoa WHERE ctipe=cgid) 'count' FROM bcoagrup WHERE cgid>6 AND cgid<11";
						$query .= "  ORDER BY cgid ASC";

						$grup = $CI->M_transaksi->get_data_query($query);
						$grup = json_decode($grup);

						$grandtotal = 0;

						foreach ($grup->data as $row_grup) {

							if ($row_grup->count < 1) {
								continue;
							}

							echo "<tr>
								<td colspan='3' class='py-1 px-1 text-blue'>$row_grup->CGNAMA</td>
						 </tr>";
							$query = "SELECT A.cid, A.cnocoa, A.cnama, A.cgd, 
									  IFNULL((SELECT SUM(ROUND(AA.cdkredit,2))-SUM(ROUND(AA.cddebit,2))  
										 FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal <= '" . tgl_database($date) . "'
										WHERE AA.cdnocoa=A.cid),0) 'saldo'  
								  FROM bcoa A WHERE A.ctipe='" . $row_grup->CGID . "' AND A.cgd='D' ORDER BY A.cnocoa ASC";

							$coa = $CI->M_transaksi->get_data_query($query);
							$coa = json_decode($coa);
							$total = 0;
							foreach ($coa->data as $row) {
								if ($row->cid == $balanceacc) {
									$saldo = $row->saldo + $selisih;
								} else {
									$saldo = $row->saldo;
								}

								if ($saldo == 0 && $tampilNol == 0) {
								} else {
									echo "<tr>
										<td colspan='2' class='px-1'>$row->cnocoa &nbsp;&nbsp; $row->cnama</td>
										<td class='right px-1'>" . eFormatNumber($saldo, 2) . "</td>									
								 </tr>";
								}
								$total += $saldo;
								$grandtotal += $saldo;
							}
							echo "<tr>
								<td colspan='2' class='py-1 px-1 text-blue'>Total $row_grup->CGNAMA</td>
								<td class='right px-1 py-1' style=\"border-top:.5px solid black\">" . eFormatNumber($total, 2) . "</td>
						 </tr>";
						}
						echo "<tr>
							<td colspan='2' class='px-1 py-2'><b>Total Kewajiban & Ekuitas</b></td>
							<td class='right px-1 py-2' style=\"border-top:.5px solid black\"><b>" . eFormatNumber($grandtotal, 2) . "</b></td>
					 </tr>";
						?>
					</tbody>
				</table>
			</td>
		</tr>
	</table>
</div>