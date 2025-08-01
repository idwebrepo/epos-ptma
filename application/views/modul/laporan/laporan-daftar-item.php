<?php
include('style.php');

$CI = &get_instance();
$tampilNol =  $_POST['saldo'];
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
                      ROUND(IFNULL(A.ihargabeli,0),2) AS 'hbeli',ROUND(IFNULL(A.ihargajual1,0),2) AS 'hjual'
                 FROM bitem A
            LEFT JOIN bsatuan B ON A.isatuan=B.sid";

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
				<th class="left px-1" width="10%">Kode</th>
				<th class="left px-1">Nama</th>
				<th class="right px-1" width="12%">Qty</th>
				<th class="left px-1" width="12%">Satuan</th>
				<th class="left px-1">Jenis</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($datareport->data as $row) {
				if ($row->jumlah == 0 && $tampilNol == 0) {
					continue;
				}
				echo "<tr>";
				echo "<td>" . $row->kode . "</td>";
				echo "<td>" . $row->nama . "</td>";
				echo "<td class='right px-1'>" . eFormatNumber($row->jumlah, $digitqty) . "</td>";
				echo "<td>" . $row->satuan . "</td>";
				echo "<td>" . $row->jenis . "</td>";
				echo "</tr>";
			}
			?>
		</tbody>
	</table>
	<div class="clear">&nbsp;</div>
</div>