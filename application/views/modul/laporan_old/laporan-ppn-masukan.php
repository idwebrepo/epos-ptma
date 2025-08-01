<?php
	include ('style.php');
	$date1 = $_POST['tgldari'];
	$date2 = $_POST['tglsampai'];	
    if(isset($_POST['idkontak'])){
    	$kontak = $_POST['idkontak'];
    } else {
    	$kontak = "";
    }	

    $CI =& get_instance();
    $transcode = element('PB_Faktur_Pembelian',NID); // Lihat di global_helper
    $transcode = $CI->M_transaksi->prefixtrans($transcode);        
    $query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak', A.ipuuraian 'uraian', IFNULL(A.iputotaltransaksi-A.iputotalpajak-A.iputotalpph22,0) 'total', 0 'totalv',
                          CASE WHEN A.iputotalbayar=0 AND (A.iputotaltransaksi-IFNULL(A.ipujumlahdp,0)) > 0  THEN 'Belum Dibayar' 
                               WHEN (A.iputotaltransaksi-IFNULL(A.ipujumlahdp,0))-A.iputotalbayar=0 OR (A.iputotaltransaksi-IFNULL(A.ipujumlahdp,0))=0   THEN 'Lunas'
                               WHEN (A.iputotaltransaksi-IFNULL(A.ipujumlahdp,0))-A.iputotalbayar<0 THEN 'Lebih Dibayar'
                               ELSE 'Dibayar Sebagian' 
                          END 'status', IFNULL(A.ipujumlahdp,0) 'totaldp', IFNULL(A.iputotalpph22,0) 'totalpph22', 
                          IFNULL(A.iputotalpajak,0) 'totalppn',
                          '-' 'posting', ROUND((IFNULL(A.iputotaltransaksi,0)-IFNULL(A.ipujumlahdp,0)),2) 'totaltagihan',
                          A.ipunofakturpajak 'fakturpajak', DATE_FORMAT(A.iputglpajak,'%d-%m-%Y') 'tglpajak'
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid 
	                WHERE A.ipusumber = '".$transcode."'  
	                  AND A.iputanggal BETWEEN '".tgl_database($date1)."' 
	                  AND '".tgl_database($date2)."'
	                  AND A.iputotalpajak>0";            

    if($kontak != ""){
    	$query .= " AND A.ipukontak='".$kontak."'";
    }

    $query .= " GROUP BY A.ipuid,A.ipunotransaksi,A.iputanggal";

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
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1" width="10%">Tanggal</th>
				<th class="left px-1" width="11%">Nomor</th>				
				<th class="left px-1">Kontak</th>
				<th class="left px-1">Tgl Faktur Pjk</th>				
				<th class="left px-1">No. Faktur Pjk</th>
				<th class="right px-1">PPN</th>
			</tr>
		</thead>
		<tbody>
			<?	
				$total = 0; $totaldp = 0; $totaltagihan = 0; $totalppn = 0; $totalpph22 = 0;
				foreach ($datareport->data as $row) {
					echo "<tr>";
					echo "<td>".$row->tanggal."</td>";					
					echo "<td>".$row->nomor."</td>";
					echo "<td>".$row->kontak."</td>";
					echo "<td>".$row->tglpajak."</td>";										
					echo "<td>".$row->fakturpajak."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->totalppn,2)."</td>";					
					echo "</tr>";								
					$total += $row->total;
					$totaldp += $row->totaldp;
					$totaltagihan += $row->totaltagihan;																						
					$totalppn += $row->totalppn;					
					$totalpph22 += $row->totalpph22;					
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="5" class="px-1">Total</td>
				<td class="right px-1"><?= eFormatNumber($totalppn,2); ?></td>	
			</tr>			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	
</div>