<?php
	include ('style.php');

    $CI =& get_instance();

    $query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.ipucreated,'%d/%m/%Y %H:%m') 'tanggal',B.knama 'kontak',
                      A.ipuuraian 'uraian', A.ipukaryawan 'karyawan', A.iputotaltransaksi 'total', A.iputotalpajak 'totalpajak',
					  A.ipusubtotal 'subtotal',B.k1alamat 'alamat',C.tnama 'termin', 
					  CASE WHEN A.ipujenispajak=0 THEN 'Tanpa Pajak'
					  	   WHEN A.ipujenispajak=1 THEN 'Belum Termasuk Pajak'
					  	   WHEN A.ipujenispajak=2 THEN 'Termasuk Pajak' 
					  END 'tipepajak', F.unama 'kasir',
					  A.ipucatatan 'catatan', A.ipujumlahdp 'totaldp', D.knama 'karyawan',A.iputotalpph22 'totalpph22',
					  DATE_FORMAT(DATE_ADD(A.iputanggal, INTERVAL C.ttempo DAY),'%d-%m-%Y') 'tempo',
					  IFNULL(E.sutotalbayar,0) 'totalbayar', DATE_FORMAT(NOW(),'%d/%m/%Y %H:%m') 'tglcetak',
					  IFNULL(E.sutotaldp,0) 'totaldiskon'					  
                 FROM einvoicepenjualanu A 
            LEFT JOIN bkontak B ON A.ipukontak=B.kid
            LEFT JOIN btermin C ON A.iputermin=C.tid
            LEFT JOIN bkontak D ON A.ipukaryawan=D.kid 
            LEFT JOIN fstoku E ON A.ipunotransaksi=E.sunotransaksi
            LEFT JOIN auser F ON A.ipucreateu=F.uid
                WHERE A.ipuid IN(".$id.")";

    $header = $CI->M_transaksi->get_data_query($query);
    $header = json_decode($header);	

    $digitqty = 0;
    $data = 0;

    foreach ($header->data as $row) {
    	$data++;
    	$nomor = $row->nomor;
    	$tanggal = $row->tanggal;
    	$kontak = $row->kontak;    	    	
    	$alamat = $row->alamat;    	    	    	
    	$termin = $row->termin;
    	$uraian = empty($row->uraian) ? "-" : $row->uraian;    
    	$catatan = empty($row->catatan) ? "-" : $row->catatan;    	
    	$total = $row->total;   
    	$totalpajak = $row->totalpajak;
    	$totalpph22 = $row->totalpph22;    	
    	$totaldp = $row->totaldp;
    	$sisafaktur = $row->total - $row->totaldp;
    	$karyawan = $row->karyawan;
    	$pajak = $row->tipepajak;
    	$tempo = $row->tempo;    	
    	$totalbayar = $row->totalbayar;
    	$totaldiskon = $row->totaldiskon;    	

	    $querydtl  = "SELECT B.ikode 'noitem',B.inama 'item',A.ipdkeluar 'qty',(A.ipdharga-A.ipddiskon) 'harga',C.skode 'satuan', A.ipdcatatan  
		                 FROM einvoicepenjualand A 
		            LEFT JOIN bitem B ON A.ipditem = B.iid 
		            LEFT JOIN bsatuan C ON A.ipdsatuan=C.sid 
		                WHERE A.ipdidipu = '".$row->id."' ORDER BY A.ipdurutan ASC";

	    $detil = $CI->M_transaksi->get_data_query($querydtl);
	    $detil = json_decode($detil);
	 	if($data!==1) {
?>
<pagebreak>
</pagebreak>	
<?
}
?>
<div class="header-report">
	<?
	if($use_logo==1){
	?>
		<div class="logo left">	
			<img src="<?php echo app_url('assets/dist/img/logo-utama.png'); ?>" width="50" height="50" />
		</div>
	<? } ?>
	<div class="left px-1" width="38%">
		<h4><b><?= $company_name; ?></b></h4>				
		<span style="line-height: 5;"><?= $company_addr ?>, Kode Pos : <?= $company_kodepos ?>, Email : <?= $company_email ?>, Telp : <?= $company_phone ?></span>		
	</div>
	<div class="right">
		<div class="right py-2">
			<table class="table">
				<tbody>
					<tr>
						<td align="right"><b>NOMOR :</b></td>
						<td width="10"></td>
						<td align="right"><b>TANGGAL :</b></td>					
					</tr>
					<tr>
						<td align="right"><?= $nomor ?></td>
						<td>&nbsp;</td>
						<td align="right"><?= $tanggal ?></td>												
					</tr>				
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="content-report">
	<table>
		<tbody>
			<tr>
				<th align="center" width="7%" style="border-bottom:1px dotted #000;border-top:1px dotted #000">NO</th>				
				<th align="left" style="border-bottom:1px dotted #000;border-top:1px dotted #000">NAMA BARANG</th>
				<th align="right" width="10%" style="border-bottom:1px dotted #000;border-top:1px dotted #000">QTY</th>
				<th align="right" width="21%" style="border-bottom:1px dotted #000;border-top:1px dotted #000">HARGA (NET)</th>
				<th align="right" width="21%" style="border-bottom:1px dotted #000;border-top:1px dotted #000">SUBTOTAL</th>								
			</tr>
			<?
				$i = 1;
				$subtotal = 0;
				$totalqty = 0;
			    foreach ($detil->data as $row) {
			?>
				<tr>
					<td align="center" class="py-1"><?= $i ?></td>	
					<td class="left py-1"><?= $row->item ?></td>
					<td class="right py-1"><?= $row->qty.' '.$row->satuan ?></td>							
					<td class="right px-1 py-1"><?= eFormatNumber($row->harga,0) ?></td>
					<td class="right px-1 py-1"><?= eFormatNumber(($row->harga*$row->qty),0) ?></td>
				</tr>
			<?
				$i++;
				$subtotal += $row->harga*$row->qty;
				$totalqty += $row->qty;				
				}
			?>
			<tr>
			<?
				if($totalpajak>0 && ($totalbayar-$sisafaktur)>0){
			?>				
				<td colspan="3" rowspan="6" class="left py-1 px-1" style="vertical-align: bottom; border-top:1px dotted #000;">TERBILANG : <?= terbilang($sisafaktur); ?>
				<br/><br/>PERHATIAN : Barang Yang Sudah Dibeli Tidak Dapat Dikembalikan						
				<table style="margin-top: 10px;width: 60%">
					<tbody>
						<tr>
							<td>HORMAT KAMI :</td>
							<td>&nbsp;</td>
							<td>PELANGGAN :</td>				
						</tr>
					</tbody>
				</table>																			
				</td>
			<?
				} elseif(($totalpajak>0 && !($totalbayar-$sisafaktur)>0) || (!$totalpajak>0 && ($totalbayar-$sisafaktur)>0)){
			?>
				<td colspan="3" rowspan="5" class="left py-1 px-1" style="vertical-align: top; border-top:1px dotted #000;">TERBILANG : <?= terbilang($sisafaktur); ?>
				<br/><br/>PERHATIAN : Barang Yang Sudah Dibeli Tidak Dapat Dikembalikan						
				<table style="margin-top: 10px;width: 60%">
					<tbody>
						<tr>
							<td>HORMAT KAMI :</td>
							<td>&nbsp;</td>
							<td>PELANGGAN :</td>				
						</tr>
					</tbody>
				</table>																								
				</td>
			<?
				} else {
			?>			
				<td colspan="3" rowspan="4" class="left py-1 px-1" style="vertical-align: top; border-top:1px dotted #000;">TERBILANG : <?= terbilang($sisafaktur); ?>
				<br/><br/>PERHATIAN : Barang Yang Sudah Dibeli Tidak Dapat Dikembalikan						
				<table style="margin-top: 10px;width: 60%">
					<tbody>
						<tr>
							<td>HORMAT KAMI :</td>
							<td>&nbsp;</td>
							<td>PELANGGAN :</td>				
						</tr>
					</tbody>
				</table>																								
				</td>
			<?
				}
			?>
				<th class="right px-1 py-1" style="border-top:1px dotted #000;">TOTAL QTY</th>
				<th class="right px-1 py-1" style="border-top:1px dotted #000;"><?= eFormatNumber($totalqty,$digitqty) ?></th>				
			</tr>						
			<tr>
				<th class="right px-1 py-1">TOTAL</th>
				<th class="right px-1 py-1">Rp<?= eFormatNumber($subtotal,0) ?></th>				
			</tr>										
			<?
				if($totalpajak>0){
			?>
			<tr>
				<th class="right px-1 py-1">PPN</th>
				<th class="right px-1 py-1">Rp<?= eFormatNumber($totalpajak,0) ?></th>				
			</tr>				
			<?
				}
			?>
			<?
				if($totalpph22>0){
			?>						
			<tr>
				<th class="right px-1 py-1">PPH 22</th>
				<th class="right px-1 py-1">Rp<?= eFormatNumber($totalpph22,0) ?></th>				
			</tr>			
			<?
				}
			?>
			<tr>
				<th class="right px-1 py-1">GRAND TOTAL</th>
				<th class="right px-1 py-1">Rp<?= eFormatNumber($sisafaktur,0) ?></th>				
			</tr>											
			<tr>
				<th class="right px-1 py-1">DIBAYAR</th>
				<th class="right px-1 py-1">Rp<?= eFormatNumber($totalbayar,0) ?></th>				
			</tr>
			<?
				if(($totalbayar-$sisafaktur)>0){
			?>
			<tr>
				<th class="right px-1 py-1">KEMBALI</th>
				<th class="right px-1 py-1">Rp<?= eFormatNumber($totalbayar-$sisafaktur,0) ?></th>				
			</tr>				
			<?
				}
			?>																				
		</tbody>
	</table>	
</div>
<?
}
?>