<?php
include('style.php');
$date1 = $_POST['tgldari'];
$date2 = $_POST['tglsampai'];
$tampilNol =  $_POST['saldo'];

$selisih_awal = 0;
$selisih_akhir = 0;
$selisih_mutasi = 0;

$CI = &get_instance();
$query = "SELECT (SELECT SUM(ROUND(AA.cddebit,2))-SUM(ROUND(AA.cdkredit,2))  
				  FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal < '" . tgl_database($date1) . "' 
				INNER JOIN bcoa AC ON AA.cdnocoa=AC.cid WHERE AC.ctipe < 7) - 
				(SELECT SUM(ROUND(AA.cdkredit,2))-SUM(ROUND(AA.cddebit,2))  
				  FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal < '" . tgl_database($date1) . "' 
				INNER JOIN bcoa AC ON AA.cdnocoa=AC.cid WHERE AC.ctipe > 6 AND AC.ctipe < 11 ) 'selisih_awal'";
$qselisih = $CI->M_transaksi->get_data_query($query);
$qselisih = json_decode($qselisih);

foreach ($qselisih->data as $row) {
	$selisih_awal = $row->selisih_awal;
}

$query = "SELECT (SELECT SUM(ROUND(AA.cddebit,2))-SUM(ROUND(AA.cdkredit,2))  
				  FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal <= '" . tgl_database($date2) . "' 
				INNER JOIN bcoa AC ON AA.cdnocoa=AC.cid WHERE AC.ctipe < 7) - 
				(SELECT SUM(ROUND(AA.cdkredit,2))-SUM(ROUND(AA.cddebit,2))  
				  FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal <= '" . tgl_database($date2) . "' 
				INNER JOIN bcoa AC ON AA.cdnocoa=AC.cid WHERE AC.ctipe > 6 AND AC.ctipe < 11 ) 'selisih_akhir'";
$qselisih = $CI->M_transaksi->get_data_query($query);
$qselisih = json_decode($qselisih);

foreach ($qselisih->data as $row) {
	$selisih_akhir = $row->selisih_akhir;
}

$selisih_mutasi = $selisih_akhir - $selisih_awal;

$query = "SELECT A.cccoa, B.cnocoa, B.cnama FROM cconfigcoa A INNER JOIN bcoa B ON A.cccoa=B.cid WHERE A.cckode='RL' AND A.ccketerangan='BERJALAN'";
$qbalanceacc = $CI->M_transaksi->get_data_query($query);
$qbalanceacc = json_decode($qbalanceacc);

foreach ($qbalanceacc->data as $row) {
	$balanceacc = $row->cccoa;
	$balancenocoa = $row->cnocoa;
	$balancenama = $row->cnama;
}

?>
<?php
if ($use_logo == 0) {
?>
	<div class="header-report">
		<h4><?= $company_name; ?></h4>
		<h3><?= $title; ?></h3>
		<span>Periode dari <?= $date1; ?> s/d <?= $date2; ?></span>
	</div>
<?php
} else {
?>
	<div class="header-report">
		<div class="logo left">
			<img src="<?php echo app_url('assets/dist/img/logo-utama.png'); ?>" width="80" height="80" />
		</div>
		<div class="left px-1" width="38%">
			<h4><b><?= $company_name; ?></b></h4>
			<h3><?= $title; ?></h3>
			<span>Per Tanggal : <?= $date; ?></span>
		</div>
	</div>
<?php
}
?>
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th colspan="2" rowspan="2" class="left px-1" style="vertical-align: middle;">Keterangan Akun</th>
				<th colspan="2" align="center" class="px-1">Saldo Awal</th>
				<th colspan="2" align="center" class="px-1">Mutasi</th>
				<th colspan="2" align="center" class="px-1">Saldo Akhir</th>
			</tr>
			<tr class="bg-dark">
				<th class="right px-1" align="center">Debet</th>
				<th class="right px-1" align="center">Kredit</th>
				<th class="right px-1" align="center">Debet</th>
				<th class="right px-1" align="center">Kredit</th>
				<th class="right px-1" align="center">Debet</th>
				<th class="right px-1" align="center">Kredit</th>
			</tr>
		</thead>
		<tbody>
			<?php
			echo "<tr>
							<td colspan='8' class='py-1 px-1'><b>Aset</b></td>
					 </tr>";

			$total1 = 0;
			$total2 = 0;
			$total3 = 0;
			$total4 = 0;
			$total5 = 0;
			$total6 = 0;

			$query = "SELECT A.cid, A.cnocoa, A.cnama, A.cgd, 
								  IFNULL((SELECT SUM(ROUND(AA.cddebit,2))-SUM(ROUND(AA.cdkredit,2))  
									 FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal < '" . tgl_database($date1) . "'
									WHERE AA.cdnocoa=A.cid),0) 'saldo_awal',
								  IFNULL((SELECT SUM(ROUND(AA.cddebit,2))  
									 FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal BETWEEN '" . tgl_database($date1) . "' AND '" . tgl_database($date2) . "'
									WHERE AA.cdnocoa=A.cid),0) 'mutasi_debet',
								  IFNULL((SELECT SUM(ROUND(AA.cdkredit,2))  
									 FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal BETWEEN '" . tgl_database($date1) . "' AND '" . tgl_database($date2) . "'
									WHERE AA.cdnocoa=A.cid),0) 'mutasi_kredit',						
								  IFNULL((SELECT SUM(ROUND(AA.cddebit,2))-SUM(ROUND(AA.cdkredit,2))  
									 FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal <= '" . tgl_database($date2) . "'
									WHERE AA.cdnocoa=A.cid),0) 'saldo_akhir'  
							  FROM bcoa A WHERE A.ctipe<7 AND A.cgd='D' AND A.cid <> '" . $balanceacc . "' ORDER BY A.cnocoa ASC";

			$coa = $CI->M_transaksi->get_data_query($query);
			$coa = json_decode($coa);

			foreach ($coa->data as $row) {
				if ($row->saldo_akhir == 0 && $tampilNol == 0) {
				} else {
					echo "<tr>
									<td colspan='2' class='py-1 px-1'>$row->cnocoa &nbsp;&nbsp; $row->cnama</td>";
					if ($row->saldo_awal > 0) {
						echo "<td class='right px-1'>" . eFormatNumber($row->saldo_awal, 2) . "</td>
										<td class='right px-1'>" . eFormatNumber(0, 2) . "</td>";
						$total1 += $row->saldo_awal;
					} else {
						echo "<td class='right px-1'>" . eFormatNumber(0, 2) . "</td>
										<td class='right px-1'>" . eFormatNumber($row->saldo_awal, 2) . "</td>";
						$total2 += $row->saldo_awal;
					}

					echo "<td class='right px-1'>" . eFormatNumber($row->mutasi_debet, 2) . "</td>
									<td class='right px-1'>" . eFormatNumber($row->mutasi_kredit, 2) . "</td>
									";
					$total3 += $row->mutasi_debet;
					$total4 += $row->mutasi_kredit;

					if ($row->saldo_akhir > 0) {
						echo "<td class='right px-1'>" . eFormatNumber($row->saldo_akhir, 2) . "</td>
										<td class='right px-1'>" . eFormatNumber(0, 2) . "</td>";
						$total5 += $row->saldo_akhir;
					} else {
						echo "<td class='right px-1'>" . eFormatNumber(0, 2) . "</td>
										<td class='right px-1'>" . eFormatNumber($row->saldo_akhir, 2) . "</td>";
						$total6 += $row->saldo_akhir;
					}
				}
			}
			?>
			<?php
			echo "<tr>
							<td colspan='8' class='py-1 px-1'><b>Kewajiban</b></td>
					 </tr>";

			$query = "SELECT A.cid, A.cnocoa, A.cnama, A.cgd, 
								  IFNULL((SELECT SUM(ROUND(AA.cdkredit,2))-SUM(ROUND(AA.cddebit,2))  
									 FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal < '" . tgl_database($date1) . "'
									WHERE AA.cdnocoa=A.cid),0) 'saldo_awal',
								  IFNULL((SELECT SUM(ROUND(AA.cddebit,2))  
									 FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal BETWEEN '" . tgl_database($date1) . "' AND '" . tgl_database($date2) . "'
									WHERE AA.cdnocoa=A.cid),0) 'mutasi_debet',
								  IFNULL((SELECT SUM(ROUND(AA.cdkredit,2))  
									 FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal BETWEEN '" . tgl_database($date1) . "' AND '" . tgl_database($date2) . "'
									WHERE AA.cdnocoa=A.cid),0) 'mutasi_kredit',						
								  IFNULL((SELECT SUM(ROUND(AA.cdkredit,2))-SUM(ROUND(AA.cddebit,2))  
									 FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal <= '" . tgl_database($date2) . "'
									WHERE AA.cdnocoa=A.cid),0) 'saldo_akhir'  
							  FROM bcoa A WHERE A.ctipe IN(7,8,9) AND A.cgd='D' AND A.cid <> '" . $balanceacc . "' ORDER BY A.cnocoa ASC";

			$coa = $CI->M_transaksi->get_data_query($query);
			$coa = json_decode($coa);

			foreach ($coa->data as $row) {
				if ($row->saldo_akhir == 0 && $tampilNol == 0) {
				} else {
					echo "<tr>
									<td colspan='2' class='py-1 px-1'>$row->cnocoa &nbsp;&nbsp; $row->cnama</td>";
					if ($row->saldo_awal < 0) {
						echo "<td class='right px-1'>" . eFormatNumber($row->saldo_awal, 2) . "</td>
										<td class='right px-1'>" . eFormatNumber(0, 2) . "</td>";
						$total1 += $row->saldo_awal;
					} else {
						echo "<td class='right px-1'>" . eFormatNumber(0, 2) . "</td>
										<td class='right px-1'>" . eFormatNumber($row->saldo_awal, 2) . "</td>";
						$total2 += $row->saldo_awal;
					}

					echo "<td class='right px-1'>" . eFormatNumber($row->mutasi_debet, 2) . "</td>
									<td class='right px-1'>" . eFormatNumber($row->mutasi_kredit, 2) . "</td>
									";
					$total3 += $row->mutasi_debet;
					$total4 += $row->mutasi_kredit;

					if ($row->saldo_akhir < 0) {
						echo "<td class='right px-1'>" . eFormatNumber($row->saldo_akhir, 2) . "</td>
										<td class='right px-1'>" . eFormatNumber(0, 2) . "</td>";
						$total5 += $row->saldo_akhir;
					} else {
						echo "<td class='right px-1'>" . eFormatNumber(0, 2) . "</td>
										<td class='right px-1'>" . eFormatNumber($row->saldo_akhir, 2) . "</td>";
						$total6 += $row->saldo_akhir;
					}
				}
			}
			?>
			<?php
			echo "<tr>
							<td colspan='8' class='py-1 px-1'><b>Ekuitas</b></td>
					 </tr>";

			$query = "SELECT A.cid, A.cnocoa, A.cnama, A.cgd, 
								  IFNULL((SELECT SUM(ROUND(AA.cdkredit,2))-SUM(ROUND(AA.cddebit,2))  
									 FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal < '" . tgl_database($date1) . "'
									WHERE AA.cdnocoa=A.cid),0) 'saldo_awal',
								  IFNULL((SELECT SUM(ROUND(AA.cddebit,2))  
									 FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal BETWEEN '" . tgl_database($date1) . "' AND '" . tgl_database($date2) . "'
									WHERE AA.cdnocoa=A.cid),0) 'mutasi_debet',
								  IFNULL((SELECT SUM(ROUND(AA.cdkredit,2))  
									 FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal BETWEEN '" . tgl_database($date1) . "' AND '" . tgl_database($date2) . "'
									WHERE AA.cdnocoa=A.cid),0) 'mutasi_kredit',						
								  IFNULL((SELECT SUM(ROUND(AA.cdkredit,2))-SUM(ROUND(AA.cddebit,2))  
									 FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal <= '" . tgl_database($date2) . "'
									WHERE AA.cdnocoa=A.cid),0) 'saldo_akhir'  
							  FROM bcoa A WHERE A.ctipe =10 AND A.cgd='D' AND A.cid <> '" . $balanceacc . "' ORDER BY A.cnocoa ASC";

			$coa = $CI->M_transaksi->get_data_query($query);
			$coa = json_decode($coa);

			foreach ($coa->data as $row) {
				if ($row->saldo_akhir == 0 && $tampilNol == 0) {
				} else {
					echo "<tr>
									<td colspan='2' class='py-1 px-1'>$row->cnocoa &nbsp;&nbsp; $row->cnama</td>";
					if ($row->saldo_awal < 0) {
						echo "<td class='right px-1'>" . eFormatNumber($row->saldo_awal, 2) . "</td>
										<td class='right px-1'>" . eFormatNumber(0, 2) . "</td>";
						$total1 += $row->saldo_awal;
					} else {
						echo "<td class='right px-1'>" . eFormatNumber(0, 2) . "</td>
										<td class='right px-1'>" . eFormatNumber($row->saldo_awal, 2) . "</td>";
						$total2 += $row->saldo_awal;
					}

					echo "<td class='right px-1'>" . eFormatNumber($row->mutasi_debet, 2) . "</td>
									<td class='right px-1'>" . eFormatNumber($row->mutasi_kredit, 2) . "</td>
									";
					$total3 += $row->mutasi_debet;
					$total4 += $row->mutasi_kredit;

					if ($row->saldo_akhir < 0) {
						echo "<td class='right px-1'>" . eFormatNumber($row->saldo_akhir, 2) . "</td>
										<td class='right px-1'>" . eFormatNumber(0, 2) . "</td>";
						$total5 += $row->saldo_akhir;
					} else {
						echo "<td class='right px-1'>" . eFormatNumber(0, 2) . "</td>
										<td class='right px-1'>" . eFormatNumber($row->saldo_akhir, 2) . "</td>";
						$total6 += $row->saldo_akhir;
					}
				}
			}

			echo "<tr>
							<td colspan='2' class='py-1 px-1'>$balancenocoa &nbsp;&nbsp; $balancenama</td>";
			if ($selisih_awal < 0) {
				echo "<td class='right px-1'>" . eFormatNumber($selisih_awal, 2) . "</td>
								<td class='right px-1'>" . eFormatNumber(0, 2) . "</td>";
				$total1 += $selisih_awal;
			} else {
				echo "<td class='right px-1'>" . eFormatNumber(0, 2) . "</td>
								<td class='right px-1'>" . eFormatNumber($selisih_awal, 2) . "</td>";
				$total2 += $selisih_awal;
			}
			if ($selisih_mutasi < 0) {
				echo "<td class='right px-1'>" . eFormatNumber($selisih_mutasi, 2) . "</td>
								<td class='right px-1'>" . eFormatNumber(0, 2) . "</td>";
				$total3 += $selisih_mutasi;
			} else {
				echo "<td class='right px-1'>" . eFormatNumber(0, 2) . "</td>
								<td class='right px-1'>" . eFormatNumber($selisih_mutasi, 2) . "</td>";
				$total4 += $selisih_mutasi;
			}
			if ($selisih_akhir < 0) {
				echo "<td class='right px-1'>" . eFormatNumber($selisih_akhir, 2) . "</td>
								<td class='right px-1'>" . eFormatNumber(0, 2) . "</td>";
				$total5 += $selisih_akhir;
			} else {
				echo "<td class='right px-1'>" . eFormatNumber(0, 2) . "</td>
								<td class='right px-1'>" . eFormatNumber($selisih_akhir, 2) . "</td>";
				$total6 += $selisih_akhir;
			}
			?>
			<tr class="bg-dark">
				<td colspan='2' class='py-2 px-1' style="border-top:.5pt solid #000"><b>GRAND TOTAL</b></td>
				<td class='py-2 px-1' style="border-top:.5pt solid #000"><b><?= eFormatNumber($total1, 2) ?></b></td>
				<td class='py-2 px-1' style="border-top:.5pt solid #000"><b><?= eFormatNumber($total2, 2) ?></b></td>
				<td class='py-2 px-1' style="border-top:.5pt solid #000"><b><?= eFormatNumber($total3, 2) ?></b></td>
				<td class='py-2 px-1' style="border-top:.5pt solid #000"><b><?= eFormatNumber($total4, 2) ?></b></td>
				<td class='py-2 px-1' style="border-top:.5pt solid #000"><b><?= eFormatNumber($total5, 2) ?></b></td>
				<td class='py-2 px-1' style="border-top:.5pt solid #000"><b><?= eFormatNumber($total6, 2) ?></b></td>
			</tr>
		</tbody>
		<tfoot>
		</tfoot>
	</table>
</div>