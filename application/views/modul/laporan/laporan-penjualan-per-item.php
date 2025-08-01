<?php
include('style.php');
$date1 = $_POST['tgldari'];
$date2 = $_POST['tglsampai'];
if (isset($_POST['item'])) {
	$item = $_POST['item'];
} else {
	$item = "";
}

$CI = &get_instance();
$transcode = element('PJ_Faktur_Penjualan', NID); // Lihat di global_helper
$transcode = $CI->M_transaksi->prefixtrans($transcode);
$transcode2 = element('PJ_Penjualan_Tunai', NID); // Lihat di global_helper
$transcode2 = $CI->M_transaksi->prefixtrans($transcode2);
$transcode3 = element('SV_Penjualan_Tunai', NID); // Lihat di global_helper
$transcode3 = $CI->M_transaksi->prefixtrans($transcode3);
$query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak',C.ipditem 'iid',D.ikode, D.inama
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid 
                LEFT JOIN einvoicepenjualand C ON A.ipuid=C.ipdidipu
                LEFT JOIN bitem D ON C.ipditem=D.iid  
	                WHERE A.ipusumber IN ('" . $transcode . "', '" . $transcode2 . "', '" . $transcode3 . "')  
	                  AND A.iputanggal BETWEEN '" . tgl_database($date1) . "' 
	                  AND '" . tgl_database($date2) . "'";

if ($item != "") {
	$query .= " AND C.ipditem='" . $item . "'";
}

$query .= " GROUP BY C.ipditem ORDER BY C.ipditem ASC";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);

?>
<?php
if ($use_logo == 0) {
?>
	<div class="header-report">
		<h4 class="text-blue"><?= $company_name; ?></h4>
		<h3><?= $title; ?></h3>
		<span>Periode : <?= $date1; ?> s/d <?= $date2; ?></span>
	</div>
<?php
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
<?php
}
?>
<div class="content-report">
	<?php
	foreach ($datareport->data as $row) {
		$qty = 0;
		$harga = 0;
		$diskon = 0;
		$subtotal = 0;
	?>
		<table class="table">
			<thead>
				<tr class="bg-dark">
					<th colspan="7" class="left px-1"><?= $row->ikode . " - " . $row->inama; ?></th>
				</tr>
			</thead>
			<thead>
				<tr>
					<th class="left px-1" width="10%">Tanggal</th>
					<th class="left px-1" width="10%">Nomor</th>
					<th class="left px-1">Pelanggan</th>
					<th class="right px-1">Qty</th>
					<th class="right px-1">Harga</th>
					<th class="right px-1">Diskon</th>
					<th class="right px-1">Sub Total</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',
			                          B.knama 'kontak',C.ipdkeluar 'qty',C.ipdharga 'harga',C.ipddiskon 'diskon',
			                          (C.ipdharga-C.ipddiskon)*C.ipdkeluar 'subtotal', D.ikode, D.inama
			                     FROM einvoicepenjualanu A 
			                LEFT JOIN bkontak B ON A.ipukontak=B.kid 
			                LEFT JOIN einvoicepenjualand C ON A.ipuid=C.ipdidipu
			                LEFT JOIN bitem D ON C.ipditem=D.iid  
					                WHERE A.ipusumber IN ('" . $transcode . "', '" . $transcode2 . "', '" . $transcode3 . "')
				                  AND A.iputanggal BETWEEN '" . tgl_database($date1) . "' 
				                  AND '" . tgl_database($date2) . "'";

				$query .= " AND C.ipditem='" . $row->iid . "'";
				$query .= " ORDER BY A.iputanggal ASC";

				$datadetil = $CI->M_transaksi->get_data_query($query);
				$datadetil = json_decode($datadetil);
				foreach ($datadetil->data as $rowdetil) {
					echo "<tr>";
					echo "<td>" . $rowdetil->tanggal . "</td>";
					echo "<td>" . $rowdetil->nomor . "</td>";
					echo "<td>" . $rowdetil->kontak . "</td>";
					echo "<td class='right px-1'>" . eFormatNumber($rowdetil->qty, $digitqty) . "</td>";
					echo "<td class='right px-1'>" . eFormatNumber($rowdetil->harga, 2) . "</td>";
					echo "<td class='right px-1'>" . eFormatNumber($rowdetil->diskon, 2) . "</td>";
					echo "<td class='right px-1'>" . eFormatNumber($rowdetil->subtotal, 2) . "</td>";
					echo "</tr>";
					$qty += $rowdetil->qty;
					$harga += $rowdetil->harga;
					$diskon += $rowdetil->diskon;
					$subtotal += $rowdetil->subtotal;
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="3" class="px-1">Jumlah</td>
					<td class="right px-1"><?= eFormatNumber($qty, $digitqty); ?></td>
					<td class="right px-1"><?= eFormatNumber($harga, 2); ?></td>
					<td class="right px-1"><?= eFormatNumber($diskon, 2); ?></td>
					<td class="right px-1"><?= eFormatNumber($subtotal, 2); ?></td>
				</tr>
			</tfoot>
		</table>
		<div class="clear">&nbsp;</div>
	<?php
	}
	?>
</div>