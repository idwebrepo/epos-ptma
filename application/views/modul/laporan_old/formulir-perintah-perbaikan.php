<?php
	include ('style.php');

    $CI =& get_instance();

    $query  = "SELECT A.souid 'id',A.sounotransaksi 'nomor',
    									DATE_FORMAT(A.soutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak',
                      B.kkode 'kodekontak',
                      A.souuraian 'uraian', A.soukaryawan 'idkaryawan',
					  					B.k1alamat 'alamat', B.k1telp1 'telp', D.knama 'karyawan',
											A.soucatatan1 'odometer',
											A.soucatatan2 'merek', A.soucatatan3 'nomesin', A.soucatatan4 'keluhan', 
											A.soucatatan5 'diagnosa', A.soucatatan6 'temuan',
											A.sounopol 'nopol', A.sounotelp 'telpalt', A.soualamat 'alamatalt',
											A.soupemilik 'pemilik'
                 FROM esalesorderu A 
            LEFT JOIN bkontak B ON A.soukontak=B.kid
            LEFT JOIN btermin C ON A.soutermin=C.tid 
            LEFT JOIN bkontak D ON A.soukaryawan=D.kid             
                WHERE A.souid IN (".$id.")";

    $header = $CI->M_transaksi->get_data_query($query);
    $header = json_decode($header);	

    $data = 0;

    foreach ($header->data as $row) {
    	$data++;
    	$nomor = $row->nomor;
    	$tanggal = $row->tanggal;
    	if($row->nopol == '' || $row->nopol == null) {
	    	$nopol = $row->kodekontak;
  		} else {
	    	$nopol = $row->nopol;  			
  		}
    	$merek = $row->merek;
    	$odo = $row->odometer;
    	$nomesin = $row->nomesin;   
    	if($row->pemilik == '' || $row->pemilik == null) {
	    	$kontak = $row->kontak;    	    	    		
    	}	else {
	    	$kontak = $row->pemilik;    	    	    		    		
    	}
    	if($row->alamatalt == '' || $row->alamatalt == null) {
    		$alamat = $row->alamat;    	    	    	
    	} else {
    		$alamat = $row->alamatalt;    	    	    	    		
    	}
    	if($row->telpalt == '' || $row->telpalt == null) {    	
    		$telp = $row->telp;
    	} else {
    		$telp = $row->telpalt;    		
    	}
    	$keluhan = empty($row->keluhan) ? "-" : $row->keluhan;    
    	$diagnosa = empty($row->diagnosa) ? "-" : $row->diagnosa;    
    	$temuan = empty($row->temuan) ? "-" : $row->temuan;        	    	
    	$karyawan = $row->karyawan;

	    $querydtl  = "SELECT B.ikode 'noitem',B.inama 'item',A.sodorder 'qty',(A.sodharga-A.soddiskon) 'harga',C.skode 'satuan', A.sodcatatan  
	                 FROM esalesorderd A 
	            LEFT JOIN bitem B ON A.soditem = B.iid 
	            LEFT JOIN bsatuan C ON A.sodsatuan=C.sid 
	                WHERE A.sodidsou = '".$row->id."' ORDER BY A.sodurutan ASC";

	    $detil = $CI->M_transaksi->get_data_query($querydtl);
	    $detil = json_decode($detil);
	 	if($data!==1) {
?>
<pagebreak>
</pagebreak>	
<?
}
?>
<div class="header-report" style="margin-top:0px;padding-top: 0px;">
	<?
	if($use_logo==1){
	?>
		<div class="logo left">	
			<img src="<?php echo app_url('assets/dist/img/logo-utama.png'); ?>" width="80" height="80" />
		</div>
	<? } ?>
	<div class="left px-1" width="38%">
		<h4><b><?= $company_name; ?></b></h4>				
		<span><?= $company_addr ?>, Kode Pos : <?= $company_kodepos ?>, Email : <?= $company_email ?>, Telp : <?= $company_phone ?></span>		
	</div>
	<div class="right">
		<h3><?= $title; ?></h3>		
		<div class="right py-2">
			<table class="table">
				<tbody>
					<tr>
						<td align="center" class="border-1 bg-dark">NOMOR</td>
						<td width="10"></td>
						<td align="center" class="border-1 bg-dark">TANGGAL</td>					
					</tr>
					<tr>
						<td align="center" class="border-1"><?= $nomor ?></td>
						<td>&nbsp;</td>
						<td align="center" class="border-1"><?= $tanggal ?></td>												
					</tr>				
				</tbody>
			</table>
		</div>
	</div>
	<div class="line" style="border:none;margin-bottom: 0px;margin-top: 0px;"></div>
	<div class="left">
		<table class="table" style="margin-top: 0px;">
			<tbody>
				<tr>
					<td align="center" class="border-1 bg-dark">NO. POLISI :</td>
					<td width="10"></td>
					<td align="center" class="border-1 bg-dark">KM :</td>										
					<td width="10"></td>
					<td align="center" class="border-1 bg-dark">MERK & TIPE KENDARAAN :</td>
					<td width="10"></td>
					<td align="center" class="border-1 bg-dark">NO. MESIN / RANGKA :</td>
				</tr>
				<tr>
					<td align="center" class="border-1 px-1"><?= $nopol ?></td>
					<td>&nbsp;</td>
					<td align="center" class="border-1 px-1"><?= eFormatNumber($odo,0) ?></td>								
					<td>&nbsp;</td>					
					<td align="center" class="border-1 px-1"><?= $merek ?></td>				
					<td>&nbsp;</td>					
					<td align="center" class="border-1 px-1"><?= $nomesin ?></td>									
				</tr>				
			</tbody>
		</table>			
		<table class="table" style="margin-top: 5px;">
			<tbody>
				<tr>
					<td align="center" class="border-1 bg-dark" width="50%">PEMILIK & NO HP:</td>
					<td width="10"></td>					
					<td align="center" class="border-1 bg-dark">ALAMAT :</td>					
				</tr>
				<tr>
					<td class="border-1 px-1 py-1"><?= $kontak.'. HP: '.$telp ?></td>
					<td>&nbsp;</td>
					<td class="border-1 px-1 py-1"><?= $alamat ?></td>					
				</tr>				
				<tr>
					<td></td>
				</tr>
			</tbody>
		</table>					
		<table class="table" style="margin-top: 5px;">
			<tbody>
				<tr>
					<td align="center" class="border-1 bg-dark" width="100%">KELUHAN - KELUHAN :</td>
				</tr>
				<tr>
					<td align="center" class="border-1 px-1 py-1"><?= $keluhan ?></td>
				</tr>				
			</tbody>
		</table>							
		<table class="table" style="margin-top: 5px;">
			<tbody>
				<tr>
					<td align="center" class="border-1 bg-dark" width="100%">HASIL CEK/DIAGNOSA MEKANIK :</td>
				</tr>
				<tr>
					<td align="center" class="border-1 px-1 py-1" style="padding-bottom: 40mm;"><?= $diagnosa ?></td>
				</tr>				
			</tbody>
		</table>									
	</div>
</div>
<div class="content-report" style="margin-top:1mm;">
	<table class="table table-border">
		<thead>
			<tr class="bg-dark">
				<th align="center" width="7%">NO</th>				
				<th align="center">ITEM JASA & SPAREPART</th>
				<th align="center" width="10%">QTY</th>
			</tr>
		</thead>
		<tbody>
			<?
				$i = 1;
		    foreach ($detil->data as $row) {
			?>
				<tr>
					<td align="center" class="py-1"><?= $i ?></td>	
					<td class="left py-1 px-1"><?= $row->item ?></td>
					<td class="right px-1 py-1"><?= $row->qty.' '.$row->satuan ?></td>							
				</tr>
			<?
				$i++;
				}
				for($k=$i;$k<=15;$k++){
			?>
				<tr>
					<td align="center" class="py-1"><?= $k ?></td>	
					<td class="left py-1 px-1"></td>
					<td class="right px-1 py-1"></td>							
				</tr>
			<?	} ?>
		</tbody>
	</table>
	<table class="table" style="margin-top: 5px;">
		<tbody>
			<tr>
				<td align="center" class="border-1 bg-dark" width="100%">KONDISI/TEMUAN PADA UNIT :</td>
			</tr>
			<tr>
				<td align="center" class="border-1 px-1 py-1" style="padding-bottom: 40mm;"><?= $temuan ?></td>
			</tr>				
		</tbody>
	</table>											
	<table class="table" style="margin-top: 20px; width: 100%">
		<tbody>
			<tr>
				<td align="center">PELANGGAN</td>
				<td width="10"></td>
				<td align="center">SUPERVISOR</td>					
				<td width="10"></td>
				<td align="center">MEKANIK</td>									
			</tr>
			<tr>
				<td align="center" class="py-4 border-bottom-1"></td>
				<td width="10" class="py-4"></td>
				<td align="center" class="py-4 border-bottom-1"></td>									
				<td width="10" class="py-4"></td>
				<td align="center" class="py-4 border-bottom-1"></td>													
			</tr>
			<tr>
				<td align="center"></td>
				<td width="10"></td>
				<td align="center"></td>					
				<td width="10"></td>
				<td align="center"><?= $karyawan ?></td>									
			</tr>			
		</tbody>
	</table>							
</div>
<?
}
?>