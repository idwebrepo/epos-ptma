<?php
//	include ('printer1.php');

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
    	$totaldiskon = $row->totaldiskon;
    	$totalbayar = $row->totalbayar;
    	$kasir = $row->kasir;
    	$now = $row->tglcetak;

	    $querydtl  = "SELECT B.ikode 'noitem',B.inama 'item',A.ipdkeluar 'qty',(A.ipdharga-A.ipddiskon) 'harga',C.skode 'satuan', A.ipdcatatan  
		                 FROM einvoicepenjualand A 
		            LEFT JOIN bitem B ON A.ipditem = B.iid 
		            LEFT JOIN bsatuan C ON A.ipdsatuan=C.sid 
		                WHERE A.ipdidipu = '".$row->id."' ORDER BY A.ipdurutan ASC";

	    $detil = $CI->M_transaksi->get_data_query($querydtl);
	    $detil = json_decode($detil);
	 	if($data!==1) {
?>
<div class="page">
</div>	
<?php
}
?>

<style scoped>
	body{
		font-size: 8pt;
		font-family: arial;
		margin:0;
		padding:0;
		width: 100%;
		background-color: #fff;
	}
	p{
		margin:0;
		padding:0;
		margin-left: 0px;
		width: 60mm;
		text-align: center;		
	}
	.wrapper {
		width: 70mm;
		margin:0mm;
	}	
	.header-report,.content-report,.footer-report{
		width: 70mm;
		text-align: center;
	}
	#main{
		float: left;
		width: 0mm;
		padding: 0mm;
	}
	.page{
		height: 5mm;
        width:70mm;
        page-break-inside: avoid;
        page-break-after:avoid;
    }	
    table{
    	width: 100%;
    }
    table tr td{
    	vertical-align: top;
    }
    h3{
    	margin: 0;
    	padding: 0;
    }
</style>

<div id="wrapper">
	<div class="header-report" style="border-bottom:1px dotted #000">
		<div style="margin-bottom:5pt;">
			<h3><?= $company_name; ?></h3>				
			<span>Telp : <?= $company_phone ?></span>		
		</div>
		<div>
			<table>
				<tbody>
					<tr>
						<td align="left" width="30%"><?= $nomor ?></td>
						<td>&nbsp;</td>
						<td align="right"><?= $tanggal ?></td>												
					</tr>				
					<tr>
						<td colspan="3" align="left"><?= $kontak ?></td>
					</tr>									
				</tbody>
			</table>
		</div>
	</div>
	<div class="content-report" style="border-bottom: 1px dotted #000">
		<table>
			<tbody>
				<?php
					$i = 1;
					$subtotal = 0;
					$totalqty = 0;
				    foreach ($detil->data as $row) {
				?>
					<tr>
						<td colspan="4"><?= $row->item ?></td>						
					</tr>
					<tr>
						<td width="10">&nbsp;</td>
						<td align="right" ><?= $row->qty.' '.$row->satuan.' x' ?></td>							
						<td align="right" >Rp<?= eFormatNumber($row->harga,0).' :' ?></td>
						<td align="right" >Rp<?= eFormatNumber(($row->harga*$row->qty),0) ?></td>
					</tr>
				<?php
					$i++;
					$subtotal += $row->harga*$row->qty;
					$totalqty += $row->qty;				
					}
				?>
			</tbody>
		</table>			
	</div>
	<div class="footer-report" style="border-bottom: 1px dotted #000">
		<table>
			<tbody>
					<tr>
						<td colspan="2" style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">Kasir :</td>
						<td align="right" width="70%">Subtotal : Rp<?= eFormatNumber($subtotal,0) ?></td>						
					</tr>
					<?php if($totalpajak>0){ ?>					
					<tr>
						<td colspan="2" style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis;"><?= $kasir ?></td>
						<td align="right" width="70%">Pajak : Rp<?= eFormatNumber($totalpajak,0) ?></td>						
					</tr>										
					<tr>
						<td colspan="2" style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">&nbsp;</td>
						<td align="right" width="70%">Total : Rp<?= eFormatNumber(($subtotal+$totalpajak),0) ?></td>						
					</tr>				
					<?php } ?>
					<?php if($totaldiskon>0) { ?>											
					<tr>
						<td colspan="2" style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">&nbsp;</td>
						<td align="right" width="70%">Diskon : Rp<?= eFormatNumber($totaldiskon,0) ?></td>						
					</tr>				
					<?php } ?>
					<tr>
						<td></td>
						<td></td>
						<td></td>
					</tr>	
					<tr>
						<td colspan="2" width="50%">Tunai : Rp<?= eFormatNumber($totalbayar-$totaldiskon,0) ?></td>
						<td align="right" width="50%">Kembali : Rp<?= eFormatNumber(abs($subtotal+$totalpajak-$totalbayar),0) ?></td>		
					</tr>	
					<tr>
						<td colspan="2" style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">Item : <?= $i-1; ?></td>
						<td align="right" width="70%">Qty : <?= $totalqty; ?></td>						
					</tr>
					<tr>
						<td colspan="2" style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis;"><?= $_SESSION['nama']; ?></td>
						<td align="right" width="70%"><?= $now; ?></td>						
					</tr>																		
			</tbody>
		</table>			
	</div>
	<p style="margin-top:5pt;">Terimakasih telah mengunjungi dan mempercayai kami</p>	
</div>	
<?php
}
?>