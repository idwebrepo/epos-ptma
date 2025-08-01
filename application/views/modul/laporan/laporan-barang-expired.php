<?php
include('style.php');

$CI = &get_instance();
$date =  $_POST['tgl'];
$info = _ainfo(1);
$digitqty = $info['idecimalqty'];
$query  = "SELECT A.iid AS 'id',A.ikode AS 'kode',A.inama AS 'nama',
                      CASE WHEN A.ijenisitem=0 THEN ROUND(IFNULL(A.istocktotal,0),$digitqty) 
                           WHEN A.ijenisitem=1 THEN 0
                           ELSE ROUND(IFNULL(A.istocktotal,0),$digitqty)
                      END AS 'jumlah',
                      B.skode AS 'satuan',
                      CASE WHEN A.ijenisitem=0 THEN 'Persediaan' 
                           WHEN A.ijenisitem=1 THEN 'Jasa' 
                           WHEN A.ijenisitem=2 THEN 'Konsinyasi' 
                      END AS 'jenis',
                      ROUND(IFNULL(A.ihargabeli,0),2) AS 'hbeli',ROUND(IFNULL(A.ihargajual1,0),2) AS 'hjual',
                      DATE_FORMAT(A.itanggal1,'%d-%m-%Y') 'expired'
                 FROM bitem A
            LEFT JOIN bsatuan B ON A.isatuan=B.sid
                WHERE A.ijenisitem=0 AND A.itanggal1 <= '" . tgl_database($date) . "'
                ORDER BY A.itanggal1 ASC";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);

?>
<?php
if ($use_logo == 0) {
?>
	<div class="header-report">
		<h4 class="text-blue"><?= $company_name; ?></h4>
		<h3><?= $title; ?></h3>
		<p>Per Tanggal : <?= $date ?></p>
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
			<p>Per Tanggal : <?= $date ?></p>
		</div>
	</div>
<?php
}
?>
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1" width="15%">Kode</th>
				<th class="left px-1">Nama</th>
				<th class="left px-1" width="10%">Tgl Expired</th>
				<th class="right px-1" width="10%">Qty</th>
				<th class="left px-1" width="5%"></th>
				<th class="left px-1" width="17%">Harga Beli</th>
				<th class="left px-1" width="17%">Total</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$total = 0;
			foreach ($datareport->data as $row) {
				echo "<tr>";
				echo "<td>" . $row->kode . "</td>";
				echo "<td>" . $row->nama . "</td>";
				echo "<td>" . $row->expired . "</td>";
				echo "<td class='right px-1'>" . eFormatNumber($row->jumlah, $digitqty) . "</td>";
				echo "<td>" . $row->satuan . "</td>";
				echo "<td>" . eFormatNumber($row->hbeli, 2) . "</td>";
				echo "<td>" . eFormatNumber($row->hbeli * $row->jumlah, 2) . "</td>";
				echo "</tr>";

				$total += ($row->hbeli * $row->jumlah);
			}
			?>
			<tr>
				<td class="px-1" colspan="6" style="border-top:1px solid #000;border-bottom:1px solid #000">TOTAL</td>
				<td style="border-top:1px solid #000;border-bottom:1px solid #000"><?= eFormatNumber($total, 2) ?></td>
			</tr>
		</tbody>
	</table>
	<div class="clear">&nbsp;</div>
</div>