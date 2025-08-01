<?

    $CI =& get_instance();

    $query  = "SELECT A.inama,A.ibarcode FROM bitem A WHERE A.iid='".$iditem."'";

    $header = $CI->M_transaksi->get_data_query($query);
    $header = json_decode($header);	

    $data = 0;

    foreach ($header->data as $row) {
    	$data++;
    	$namaitem = $row->inama;
    	$barcode = $row->ibarcode;
    }

?>

<style>
.barcode {
    vertical-align: top;
}
.barcodecell {
    text-align: center;
    vertical-align: middle;
    padding-top:1.5mm;
    padding-bottom:1.5mm;    
}
span{
	font-family: ocrb; 
	font-size: 5pt;
}
</style>

<?
	for($i=1;$i<=$jumlah;$i++){
?>
<div class="barcodecell">
	<barcode code="<?= $barcode; ?>" type="C39" size="0.65" height="1" class="barcode" /><br />
	<span><?= $barcode; ?></span>
</div>
<?
	}
?>