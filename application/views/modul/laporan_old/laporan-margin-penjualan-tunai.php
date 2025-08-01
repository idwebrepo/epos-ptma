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
    $transcode1 = $CI->M_transaksi->prefixtrans(element('PJ_Penjualan_Tunai',NID));        
    $transcode2 = $CI->M_transaksi->prefixtrans(element('SV_Penjualan_Tunai',NID));            
    $query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak', A.ipuuraian 'uraian', 
                          D.ikode 'kditem', D.inama 'item', C.sdkeluar 'qty',
                          C.sdharga 'harga', C.sddiskon 'diskon',
                          (C.sdharga-C.sddiskon)*C.sdkeluar 'subtotal',
                          SUM(E.shqty*E.shharga) 'hpp'
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid 
                LEFT JOIN fstokd C ON A.ipunobkg=C.sdidsu 
                LEFT JOIN bitem D ON C.sditem=D.iid 
                LEFT JOIN fstokh E ON C.sdid=E.shidsd
	                WHERE A.ipusumber IN ('".$transcode1."', '".$transcode2."')  
	                  AND A.iputanggal BETWEEN '".tgl_database($date1)."' 
	                  AND '".tgl_database($date2)."'";            

    if($kontak != ""){
    	$query .= " AND A.ipukontak='".$kontak."'";
    }

    $query .= " GROUP BY E.shidsd";

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
				<th class="left">Tanggal</th>
				<th class="left">Nomor</th>				
				<th class="left" width="15%">Kontak</th>
				<th class="left">Pembayaran</th>								
				<th class="left">Kode</th>				
				<th class="left" width="15%">Item Barang/Jasa</th>
				<th class="right">Qty</th>
				<th class="right">Harga</th>
				<th class="right">Diskon</th>								
				<th class="right">Harga Jual</th>
				<th class="right">HPP</th>
				<th class="right">Margin Profit</th>												
			</tr>
		</thead>
		<tbody>
			<?	
				$nomor = "";
				$subtotal = 0; $diskon = 0; $harga = 0; $qty = 0; $hpp = 0; $margin = 0;
				foreach ($datareport->data as $row) {
					echo "<tr>";
					if($row->nomor != $nomor){
						echo "<td>".$row->tanggal."</td>";					
						echo "<td>".$row->nomor."</td>";
						echo "<td>".$row->kontak."</td>";
						echo "<td>Tunai/Cash</td>";											
					} else {
						echo "<td></td>";					
						echo "<td></td>";
						echo "<td></td>";						
						echo "<td></td>";												
					}
					echo "<td>".$row->kditem."</td>";
					echo "<td>".$row->item."</td>";					
					echo "<td class='right'>".eFormatNumber($row->qty,2)."</td>";
					echo "<td class='right'>".eFormatNumber($row->harga,2)."</td>";					
					echo "<td class='right'>".eFormatNumber($row->diskon,2)."</td>";
					echo "<td class='right'>".eFormatNumber($row->subtotal,2)."</td>";					
					echo "<td class='right'>".eFormatNumber($row->hpp,2)."</td>";					
					echo "<td class='right'>".eFormatNumber($row->subtotal-$row->hpp,2)."</td>";
					echo "</tr>";								
					$subtotal += $row->subtotal;
					$diskon += $row->diskon;
					$harga += $row->harga;																						
					$qty += $row->qty;					
					$nomor = $row->nomor;
					$hpp += $row->hpp;
					$margin += ($row->subtotal-$row->hpp);
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="6">TOTAL</td>	
				<td class="right"><?= eFormatNumber($qty,$digitqty); ?></td>								
				<td class="right"><?= eFormatNumber($harga,2); ?></td>												
				<td class="right"><?= eFormatNumber($diskon,2); ?></td>
				<td class="right"><?= eFormatNumber($subtotal,2); ?></td>
				<td class="right"><?= eFormatNumber($hpp,2); ?></td>				
				<td class="right"><?= eFormatNumber($margin,2); ?></td>								
			</tr>			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	
</div>