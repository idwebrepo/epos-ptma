<?php
include('style.php');

$CI = &get_instance();
$query  = "SELECT A.AKODE, A.ANAMA, DATE_FORMAT(A.ATGLPAKAI,'%d-%m-%Y') 'ATGLPAKAI', A.AUMUR, A.AHARGABELI, A.AAKUMBEBAN, 
    				  (A.AHARGABELI-A.AAKUMBEBAN) 'ANILAIBUKU', B.AKNAMA
                 FROM baktiva A LEFT JOIN baktivakelompok B ON A.AKELOMPOK=B.AKID ORDER BY B.AKID ASC, A.AKODE ASC";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);

?>
<?php
if ($use_logo == 0) {
?>
	<div class="header-report">
		<h4 class="text-blue"><?= $company_name; ?></h4>
		<h3><?= $title; ?></h3>
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
		</div>
	</div>
<?php
}
?>
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1" width="8%">Kode</th>
				<th class="left px-1">Nama</th>
				<th class="left px-1">Kelompok</th>
				<th class="left px-1" width="10%">Tgl Perolehan</th>
				<th class="left px-1" width="6%">Umur</th>
				<th class="right px-1" width="15%">Harga</th>
				<th class="right px-1" width="15%">Akumulasi Peny</th>
				<th class="right px-1" width="15%">Nilai Buku</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$harga = 0;
			$akum = 0;
			$buku = 0;
			foreach ($datareport->data as $row) {
				echo "<tr>";
				echo "<td>" . $row->AKODE . "</td>";
				echo "<td>" . $row->ANAMA . "</td>";
				echo "<td>" . $row->AKNAMA . "</td>";
				echo "<td>" . $row->ATGLPAKAI . "</td>";
				echo "<td>" . $row->AUMUR . "</td>";
				echo "<td class='right px-1'>" . eFormatNumber($row->AHARGABELI, 2) . "</td>";
				echo "<td class='right px-1'>" . eFormatNumber($row->AAKUMBEBAN, 2) . "</td>";
				echo "<td class='right px-1'>" . eFormatNumber($row->ANILAIBUKU, 2) . "</td>";
				echo "</tr>";
				$harga += $row->AHARGABELI;
				$akum += $row->AAKUMBEBAN;
				$buku += $row->ANILAIBUKU;
			}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="5" class="px-1"></td>
				<td class="right px-1"><?= eFormatNumber($harga, 2); ?></td>
				<td class="right px-1"><?= eFormatNumber($akum, 2); ?></td>
				<td class="right px-1"><?= eFormatNumber($buku, 2); ?></td>
			</tr>
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>
</div>