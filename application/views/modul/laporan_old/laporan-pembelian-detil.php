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
                          B.knama 'kontak', A.ipuuraian 'uraian', 
                          D.ikode 'kditem', D.inama 'item', C.ipdmasuk 'qty',
                          C.ipdharga 'harga', C.ipddiskon 'diskon',
                          (C.ipdharga-C.ipddiskon)*C.ipdmasuk 'subtotal'
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid 
                LEFT JOIN einvoicepenjualand C ON A.ipuid=C.ipdidipu 
                LEFT JOIN bitem D ON C.ipditem=D.iid 
	                WHERE A.ipusumber = '".$transcode."'  
	                  AND A.iputanggal BETWEEN '".tgl_database($date1)."' 
	                  AND '".tgl_database($date2)."'";            

    if($kontak != ""){
    	$query .= " AND A.ipukontak='".$kontak."'";
    }

//    $query .= " GROUP BY A.ipuid,A.ipunotransaksi,A.iputanggal";

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
				<th class="left" width="11%">Tanggal</th>
				<th class="left" width="11%">Nomor</th>				
				<th class="left" width="14%">Kontak</th>
				<th class="left">Kode</th>				
				<th class="left" width="14%">Barang/Jasa</th>
				<th class="right">Qty</th>
				<th class="right">Harga</th>
				<th class="right">Diskon</th>								
				<th class="right">Sub Total</th>
			</tr>
		</thead>
		<tbody>
			<?	
				$total = 0; $diskon = 0; $harga = 0; $qty = 0;
				foreach ($datareport->data as $row) {
					echo "<tr>";
					echo "<td>".$row->tanggal."</td>";					
					echo "<td>".$row->nomor."</td>";
					echo "<td>".$row->kontak."</td>";
					echo "<td>".$row->kditem."</td>";
					echo "<td>".$row->item."</td>";					
					echo "<td class='right'>".eFormatNumber($row->qty,$digitqty)."</td>";
					echo "<td class='right'>".eFormatNumber($row->harga,2)."</td>";					
					echo "<td class='right'>".eFormatNumber($row->diskon,2)."</td>";
					echo "<td class='right'>".eFormatNumber($row->subtotal,2)."</td>";					
					echo "</tr>";								
					$total += $row->subtotal;
					$diskon += $row->diskon;
					$harga += $row->harga;																						
					$qty += $row->qty;					
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="5">TOTAL</td>
				<td class="right"><?= eFormatNumber($qty,$digitqty); ?></td>													
				<td class="right"><?= eFormatNumber($harga,2); ?></td>
				<td class="right"><?= eFormatNumber($diskon,2); ?></td>													
				<td class="right"><?= eFormatNumber($total,2); ?></td>								
			</tr>			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	
</div>