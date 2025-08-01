<?php
	include ('style.php');
	$date1 = $_POST['tgldari'];
	$date2 = $_POST['tglsampai'];
	$tampilNol =  $_POST['saldo'];

    $CI =& get_instance();

    $query  = "SELECT * FROM bcoagrup WHERE cgid>10";
    $query .= "  ORDER BY cgid ASC";            

    $grup = $CI->M_transaksi->get_data_query($query);
    $grup = json_decode($grup);
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
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th colspan="2" class="left px-1">Keterangan</th>				
				<th class="right px-1" width="20%">Saldo</th>
			</tr>
		</thead>
		<tbody>
			<?
				$grandtotal = 0;
				foreach ($grup->data as $row_grup) {
					if($row_grup->CGID == 13){
						echo "<tr>
									<td colspan='2' class='px-1 py-2'><b>Laba / Rugi Kotor</b></td>
									<td class='right px-1 py-2' style=\"border-top:.5px solid black\"><b>".eFormatNumber($grandtotal*-1,2)."</b></td>
							 </tr>";					    				    												
					}
					echo "<tr>
								<td colspan='3' class='py-1 px-1 text-blue'>$row_grup->CGNAMA</td>
						 </tr>";	
					$query = "SELECT A.cid, A.cnocoa, A.cnama, A.cgd, A.ctipe,
									  (SELECT IFNULL(SUM(ROUND(AA.cddebit,2)),0)-IFNULL(SUM(ROUND(AA.cdkredit,2)),0)  
										 FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal BETWEEN '".tgl_database($date1)."' AND '".tgl_database($date2)."' 
										WHERE AA.cdnocoa=A.cid) 'saldo' 
								  FROM bcoa A WHERE A.ctipe='".$row_grup->CGID."' AND A.cgd='D' ORDER BY A.cnocoa ASC";
				    $coa = $CI->M_transaksi->get_data_query($query);
				    $coa = json_decode($coa);		
				    $total = 0;
				    foreach ($coa->data as $row) {
						if($row->ctipe==11 || $row->ctipe==14){
							$saldo = $row->saldo*-1;
						} else {
							$saldo = $row->saldo;
						}
				    	if($row->cgd == 'G'){
							echo "<tr>
										<td colspan='2' class='px-2'>$row->cnama</td>
										<td></td>									
								 </tr>";					    
				    	} else {
							if(abs($row->saldo) == 0 && $tampilNol == 0){
							} else {
								echo "<tr>
											<td colspan='2' class='px-3'>$row->cnocoa &nbsp;&nbsp; $row->cnama</td>
											<td class='right px-1'>".eFormatNumber($saldo,2)."</td>									
									 </tr>";					    				    		
							}
				    	}

						$total += $saldo;
						$grandtotal += $row->saldo;
					}
					echo "<tr>
								<td colspan='2' class='py-1 px-1 text-blue'>Total $row_grup->CGNAMA</td>
								<td class='right px-1 py-1' style=\"border-top:.5px solid black\">".eFormatNumber($total,2)."</td>
						 </tr>";																			
				}
				echo "<tr>
							<td colspan='2' class='px-1 py-2'><b>Laba / Rugi Bersih Sebelum Pajak</b></td>
							<td class='right px-1 py-1' style=\"border-top:.5px solid black\"><b>".eFormatNumber($grandtotal*-1,2)."</b></td>
					 </tr>";					    				    						
			?>
		</tbody>
		<tfoot>
		</tfoot>
	</table>
</div>