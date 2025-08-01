<?php
	include ('style.php');

    $CI =& get_instance();

    $query  = "SELECT A.cuid 'id',A.cunotransaksi 'nomor',DATE_FORMAT(A.cutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak',
                      A.cuuraian 'uraian', A.cutotaltrans 'total', A.cutotaltransv 'totalv',C.cnocoa 'nocoa',
                      C.cnama 'coa', A.curekkas 'coakasbank'   
                 FROM ctransaksiu A 
            LEFT JOIN bkontak B ON A.cukontak=B.kid 
            LEFT JOIN bcoa C ON A.curekkas = C.cid WHERE A.cuid IN (".$id.")";

    $header = $CI->M_transaksi->get_data_query($query);
    $header = json_decode($header);	

    $data = 0;

    foreach ($header->data as $row) {
    	$data++;
    	$nomor = $row->nomor;
    	$tanggal = $row->tanggal;
    	$kontak = $row->kontak;    	    	
    	$uraian = empty($row->uraian) ? "-" : $row->uraian;    
    	$total = $row->total;   
    	$coa = $row->nocoa." - ".$row->coa;  
    	$coabk = $row->coakasbank;

	    $querydtl  = "SELECT B.cnocoa 'nocoa',B.cnama 'coa',(A.cdkredit-A.cddebit) 'total',A.cdcatatan  
	                 FROM ctransaksid A 
	            LEFT JOIN bcoa B ON A.cdnocoa = B.cid 
	                WHERE A.cdidu = '".$row->id."' AND A.cdnocoa<> '".$coabk."' ORDER BY A.cdurutan ASC";

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
			<img src="<?php echo app_url('assets/dist/img/logo-utama.png'); ?>" width="80" height="80" />
		</div>
	<? } ?>
	<div class="left px-1" width="38%">
		<h4 class="text-blue"><b><?= $company_name; ?></b></h4>				
		<span><?= $company_addr ?>, Kode Pos : <?= $company_kodepos ?>, Email : <?= $company_email ?>, Telp : <?= $company_phone ?></span>		
	</div>
	<div class="right">
		<h3><?= $title; ?></h3>		
		<div class="right py-2">
			<table class="table">
				<tbody>
					<tr>
						<td align="center" class="border-1 bg-dark">Nomor</td>
						<td width="10"></td>
						<td align="center" class="border-1 bg-dark">Tanggal</td>					
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
	<div class="line"></div>
	<div class="left">
		<table class="table" style="margin-top: 5px;">
			<tbody>
				<tr>
					<td class="border-1 px-1 bg-dark">Uraian :</td>
				</tr>
				<tr>
					<td class="border-1 px-1"><?= $uraian ?></td>
				</tr>				
				<tr>
					<td></td>
				</tr>
			</tbody>
		</table>					
		<table class="table" style="margin-top: 5px;">
			<tbody>
				<tr>
					<td class="border-1 px-1 bg-dark">Diterima Dari :</td>
					<td width="10"></td>
					<td class="border-1 px-1 bg-dark">Diterima Ke :</td>
					<td width="10"></td>
					<td class="border-1 px-1 bg-dark">Jumlah :</td>					
				</tr>
				<tr>
					<td class="border-1 px-1"><?= $kontak ?></td>
					<td>&nbsp;</td>					
					<td class="border-1 px-1"><?= $coa ?></td>
					<td>&nbsp;</td>
					<td class="border-1 px-1 right"><?= eFormatNumber($total,2) ?></td>												
				</tr>				
			</tbody>
		</table>	
	</div>
</div>
<div class="content-report">
	<table class="table table-border">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1" width="13%">Kode</th>				
				<th class="left px-1">Nama Akun</th>
				<th class="right px-1" width="21%">Jumlah</th>
				<th class="left px-1">Catatan</th>				
			</tr>
		</thead>
		<tbody>
			<? foreach ($detil->data as $row) { ?>
			<tr>
				<td class="left px-1"><?= $row->nocoa ?></td>				
				<td class="left px-1"><?= $row->coa ?></td>
				<td class="right px-1"><?= eFormatNumber($row->total,2) ?></td>
				<td class="left px-1"><?= $row->cdcatatan ?></td>								
			</tr>
			<? } ?>
		</tbody>
		<tfoot>
		</tfoot>
	</table>	
	<table class="table" style="margin-top: 20px;">
		<tbody>
			<tr>
				<td>Terbilang : <?= terbilang($total); ?></td>
			</tr>
			<tr>
				<td></td>
			</tr>
		</tbody>
	</table>
	<table class="table" style="margin-top: 20px; width: 70%">
		<tbody>
			<tr>
				<td>Diterima :</td>
				<td width="10"></td>
				<td>Dibuat :</td>
				<td width="10"></td>
				<td>Disetujui :</td>					
			</tr>
			<tr>
				<td align="center" class="py-4 border-bottom-1"></td>
				<td width="10" class="py-4"></td>
				<td align="center" class="py-4 border-bottom-1"></td>
				<td width="10" class="py-4"></td>
				<td align="center" class="py-4 border-bottom-1"></td>									
			</tr>
		</tbody>
	</table>							
</div>

<?
}
?>