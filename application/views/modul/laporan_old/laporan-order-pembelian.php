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
    $transcode = element('PB_Order_Pembelian',NID); // Lihat di global_helper
    $transcode = $CI->M_transaksi->prefixtrans($transcode);        
    $query  = "SELECT A.souid 'id',A.sounotransaksi 'nomor',DATE_FORMAT(A.soutanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak', A.souuraian 'uraian', ROUND(IFNULL(A.soutotaltransaksi,0),2) 'total',0 'totalv',
                          CASE WHEN SUM(IFNULL(C.sodmasuk,0))=0  THEN 'Belum Proses' 
                               WHEN SUM(IFNULL(C.sodorder,0))-(SUM(IFNULL(C.sodmasuk,0))-SUM(IFNULL(C.sodkeluar,0)))=0 THEN 'Selesai' 
                               ELSE 'Diproses Sebagian' 
                          END 'status',                          
                          SUM(IFNULL(C.sodorder,0)) 'qtyorder',
                          SUM(IFNULL(C.sodmasuk,0)) 'qtyterima',
                          SUM(IFNULL(C.sodkeluar,0)) 'qtyretur'
                     FROM esalesorderu A 
                LEFT JOIN bkontak B ON A.soukontak=B.kid
                LEFT JOIN esalesorderd C ON A.souid=C.sodidsou 
	                WHERE A.sousumber = '".$transcode."'  
	                  AND A.soutanggal BETWEEN '".tgl_database($date1)."' 
	                  AND '".tgl_database($date2)."'";            

    if($kontak != ""){
    	$query .= " AND A.soukontak='".$kontak."'";
    }

    $query .= " GROUP BY A.souid,A.sounotransaksi,A.soutanggal";

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
				<th class="left px-1" width="11%">Tanggal</th>
				<th class="left px-1" width="12%">Nomor</th>				
				<th class="left px-1">Kontak</th>
				<th class="left px-1">Keterangan</th>
				<th class="right px-1" width="20%">Total Transaksi</th>
				<th class="left px-1" width="11%">Status</th>				
			</tr>
		</thead>
		<tbody>
			<?	
				$total = 0;
				foreach ($datareport->data as $row) {
					echo "<tr>";
					echo "<td>".$row->tanggal."</td>";					
					echo "<td>".$row->nomor."</td>";
					echo "<td>".$row->kontak."</td>";
					echo "<td>".$row->uraian."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->total,2)."</td>";
					echo "<td>".$row->status."</td>";
					echo "</tr>";								
					$total += $row->total;												
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4" class="px-1">Total</td>
				<td class="right px-1"><?= eFormatNumber($total,2); ?></td>				
				<td class="px-1"></td>				
			</tr>			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	
</div>