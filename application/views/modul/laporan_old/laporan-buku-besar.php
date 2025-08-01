<?php
	include ('style.php');
	$date1 = $_POST['tgldari'];
	$date2 = $_POST['tglsampai'];	
	$tampilNol =  $_POST['saldo'];

    $CI =& get_instance();

    $query  = "SELECT A.cid,A.cnocoa, A.cnama, A.ctipe 'tipe', 
    				CASE WHEN A.ctipe<11 THEN
					  (SELECT IFNULL(SUM(ROUND(AA.cddebit,2)),0)-IFNULL(SUM(ROUND(AA.cdkredit,2)),0)   
				         FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal <= '".tgl_database($date2)."' 
				        WHERE AA.cdnocoa=A.cid)
				    ELSE 
					  (SELECT IFNULL(SUM(ROUND(AA.cddebit,2)),0)-IFNULL(SUM(ROUND(AA.cdkredit,2)),0)   
				         FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal BETWEEN '".tgl_database($date1)."' AND '".tgl_database($date2)."'  
				        WHERE AA.cdnocoa=A.cid)
				    END 'saldo'
				 FROM bcoa A WHERE A.cgd='D'";
	if(isset($_POST['coa']) && !isset($_POST['coasampai'])) {
	    $query .= "  AND A.cnocoa='".$_POST['coa']."'";            		
	}
	if(isset($_POST['coa']) && isset($_POST['coasampai'])) {
	    $query .= "  AND A.cnocoa BETWEEN '".$_POST['coa']."' AND '".$_POST['coasampai']."'";            		
	}	
    $query .= "  ORDER BY A.cnocoa";            

    $datareport = $CI->M_transaksi->get_data_query($query);
    $datareport = json_decode($datareport);	

?>
<?
if($use_logo==0){
?>
<div class="header-report">
	<h4 class="text-blue"><?= $company_name; ?></h4>		
	<h3><?= $title; ?></h3>
	<span>Periode : <?= $date1; ?> s/d <?= $date2; ?></span>
</div>
<?
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
<?
}
?>
<div class="content-report">
	<?
		foreach ($datareport->data as $row) {
			if($row->saldo == 0 && $tampilNol == 0){
				continue;
			}
			// Ambil Saldo Awal
			$query = "SELECT IFNULL(SUM(ROUND(AA.cddebit,2)),0)-IFNULL(SUM(ROUND(AA.cdkredit,2)),0) 'saldo'  
				         FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal < '".tgl_database($date1)."' 
				    LEFT JOIN bcoa AC ON AA.cdnocoa=AC.cid  
				        WHERE AA.cdnocoa='".$row->cid."' AND AC.ctipe<11";
			$saldoawal = $CI->M_transaksi->get_data_query($query);
			$saldoawal = json_decode($saldoawal);
			foreach ($saldoawal->data as $saldo) {
				$saldoawalval = $saldo->saldo;
				if($row->tipe==6 || $row->tipe==7 || $row->tipe==8 || $row->tipe==9 || $row->tipe==10) {
					$saldoawalval = $saldoawalval *-1;
				}
			}
			//
			// Detil Transaksi GL
			$query = "SELECT DATE_FORMAT(A.cutanggal,'%d-%m-%Y') 'tanggal', A.cunotransaksi 'nomor', 
							 CASE WHEN B.cdcatatan='' THEN A.cuuraian
							 	  WHEN B.cdcatatan IS NULL THEN A.cuuraian
							 	  ELSE B.cdcatatan
							 END 'catatan',
							 ROUND(B.cddebit,2) 'debit', ROUND(B.cdkredit,2) 'kredit'  
					    FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu 
					   WHERE A.cutanggal BETWEEN '".tgl_database($date1)."' AND '".tgl_database($date2)."' 
					     AND B.cdnocoa ='".$row->cid."' ORDER BY A.cutanggal ASC, A.cuid ASC";
			$detilreport = $CI->M_transaksi->get_data_query($query);
			$detilreport = json_decode($detilreport); 
			$saldoakhir = $saldoawalval;
	?>
				<table class="table">
					<thead>
						<tr class="border-0 bg-dark">
							<th colspan="6" class="left px-1 py-1">
								<b><?= $row->cnocoa." - ".$row->cnama; ?></b>
							</th>
						</tr>
						<tr>
							<th class="left px-1" width="11%">Tanggal</th>
							<th class="left px-1" width="12%">Nomor</th>				
							<th class="left px-1">Catatan/Uraian</th>
							<th class="right px-1" width="18%">Debit</th>
							<th class="right px-1" width="18%">Kredit</th>																
							<th class="right px-1" width="18%">Saldo</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="3">Saldo Awal:</td>
							<td class="right px-1"></td>
							<td class="right px-1"></td>
							<td class="right px-1"><?= eFormatNumber($saldoawalval,2); ?></td>				
						</tr>
						<?
							$totaldb = 0;
							$totalcr = 0;
							foreach ($detilreport->data as $detil) {
								$totaldb += $detil->debit;
								$totalcr += $detil->kredit;								
								if($row->tipe==6 || $row->tipe==7 || $row->tipe==8 || $row->tipe==9 || $row->tipe==10 || $row->tipe==11 || $row->tipe==14){
									$saldoakhir += $detil->kredit - $detil->debit;
								} else {
									$saldoakhir += $detil->debit - $detil->kredit;
								}
								echo "<tr>
											<td>$detil->tanggal</td>
											<td>$detil->nomor</td>
											<td>$detil->catatan</td>
											<td class=\"right px-1\">".eFormatNumber($detil->debit,2)."</td>
											<td class=\"right px-1\">".eFormatNumber($detil->kredit,2)."</td>
											<td class=\"right px-1\">".eFormatNumber($saldoakhir,2)."</td>																				
									  </tr>";
							}
						?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="3" class="py-1">Saldo Akhir <?= $row->cnama; ?>:</td>
							<td class="right px-1 py-1"><?= eFormatNumber($totaldb,2); ?></td>				
							<td class="right px-1 py-1"><?= eFormatNumber($totalcr,2); ?></td>		
						<?	
							if($row->tipe==6 || $row->tipe==7 || $row->tipe==8 || $row->tipe==9 || $row->tipe==10 || $row->tipe==11 || $row->tipe==14){
						?>
							<td class="right px-1 py-1"><?= eFormatNumber(-1*$row->saldo,2); ?></td>
						<? } else { ?>
							<td class="right px-1 py-1"><?= eFormatNumber($row->saldo,2); ?></td>
						<?
						}
						?>				
						</tr>
					</tfoot>
				</table>
				<div class="clear">&nbsp;</div>
	<?
		}
	?>
</div>