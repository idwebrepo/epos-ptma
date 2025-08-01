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
<div class="header-report">
	<table class="table border-0">
		<thead>
			<tr>
				<th colspan="9" class="center"><h3 class="text-blue"><?= $company_name; ?></h3></th>				
			</tr>
			<tr>
				<th colspan="9" class="center"><p><?= $title; ?></p></th>				
			</tr>			
			<tr>
				<th colspan="9" class="center"><span>Periode : <?= $date1; ?> s/d <?= $date2; ?></span></th>				
			</tr>						
		</thead>
	</table>
</div>
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
					echo "<td>".$row->kditem."&nbsp;</td>";
					echo "<td>".$row->item."</td>";					
					echo "<td class='right'>".eFormatNumber($row->qty,2)."</td>";
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
				<td colspan="8">TOTAL</td>	
				<td class="right"><?= eFormatNumber($total,2); ?></td>								
			</tr>			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	
</div>