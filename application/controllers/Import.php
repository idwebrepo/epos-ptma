<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'phpspreadsheet/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

class Import extends CI_Controller
{

	function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		parent::__construct();
	}


	function importcoa()
	{
		if (!empty($_FILES)) {
			$tempFile = $_FILES['file']['tmp_name'];
			$fileName = $_FILES['file']['name'];
			$fileType = $_FILES['file']['type'];
			$fileSize = $_FILES['file']['size'];
			$targetPath = './assets/uploads/';
			$targetFile = $targetPath . $fileName;

			$arr_file = explode('.', $fileName);
			$extension = end($arr_file);

			if ('csv' == $extension) {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
			} else {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			}

			$spreadsheet = $reader->load($tempFile);
			$sheetData = $spreadsheet->getActiveSheet()->toArray();

			$this->load->model('M_Master_Akun');

			for ($i = 1; $i < count($sheetData); $i++) {
				$kode = $sheetData[$i]['0'];
				$nama = $sheetData[$i]['1'];
				$tipe = $sheetData[$i]['2'];
				$gd = $sheetData[$i]['3'];

				$tipecoa = array(
					'cgnama' => trim($tipe)
				);
				$tipe = $this->M_Master_Akun->getcoatipe($tipecoa);

				$data = array(
					'cnocoa' => $kode,
					'cnama' => $nama,
					'ctipe' => $tipe,
					'cgd' => $gd,
					'cactive' => 1,
					'ccreateu' => $this->session->id
				);

				$this->M_Master_Akun->importData($data);
			}

			// Upload file ke server
			move_uploaded_file($tempFile, $targetFile);

			echo "sukses";
		}
	}


	function importkontak()
	{
		if (!empty($_FILES)) {
			$tempFile = $_FILES['file']['tmp_name'];
			$fileName = $_FILES['file']['name'];
			$fileType = $_FILES['file']['type'];
			$fileSize = $_FILES['file']['size'];
			$targetPath = './assets/uploads/';
			$targetFile = $targetPath . $fileName;

			$arr_file = explode('.', $fileName);
			$extension = end($arr_file);

			if ('csv' == $extension) {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
			} else {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			}

			$spreadsheet = $reader->load($tempFile);
			$sheetData = $spreadsheet->getActiveSheet()->toArray();

			$this->load->model('M_Master_Kontak');
			$this->load->model('M_Master_Termin');

			for ($i = 1; $i < count($sheetData); $i++) {
				$kode = $sheetData[$i]['0'];
				$nama = $sheetData[$i]['1'];
				$tipe = $sheetData[$i]['2'];
				$alamat = $sheetData[$i]['3'];
				$kodepos = $sheetData[$i]['4'];
				$telp = $sheetData[$i]['5'];
				$faks = $sheetData[$i]['6'];
				$email = $sheetData[$i]['7'];
				$person = $sheetData[$i]['8'];
				$limithutang = $sheetData[$i]['9'];
				$limitpiutang = $sheetData[$i]['10'];
				$terminbeli = $sheetData[$i]['11'];
				$terminjual = $sheetData[$i]['12'];
				$lvlhargajual = $sheetData[$i]['13'];
				$diskonjual = $sheetData[$i]['14'];

				$tipekontak = array(
					'ktnama' => trim($tipe)
				);
				$tipe = $this->M_Master_Kontak->getkontaktipe($tipekontak);

				$datatermin = array(
					'tkode' => trim($terminbeli)
				);
				$terminbeli = $this->M_Master_Termin->getterminid($datatermin);

				$datatermin = array(
					'tkode' => trim($terminjual)
				);
				$terminjual = $this->M_Master_Termin->getterminid($datatermin);


				$data = array(
					'kkode' => $kode,
					'knama' => $nama,
					'ktipe' => $tipe,
					'k1alamat' => $alamat,
					'k1telp1' => $telp,
					'k1fax' => $faks,
					'k1email' => $email,
					'k1kontak' => $person,
					'kpemtermin' => $terminbeli,
					'kpentermin' => $terminjual,
					'kpenlevelharga' => $lvlhargajual,
					'kpembatashutang' => $limithutang,
					'kpenbataspiutang' => $limitpiutang,
					'kdiskon' => $diskonjual,
					'kcreateu' => $this->session->id
				);

				$this->M_Master_Kontak->importData($data);
			}

			// Upload file ke server
			move_uploaded_file($tempFile, $targetFile);

			echo "sukses";
		}
	}

	function importitem()
	{
		if (!empty($_FILES)) {
			$tempFile = $_FILES['file']['tmp_name'];
			$fileName = $_FILES['file']['name'];
			$fileType = $_FILES['file']['type'];
			$fileSize = $_FILES['file']['size'];
			$targetPath = './assets/uploads/';
			$targetFile = $targetPath . $fileName;

			$arr_file = explode('.', $fileName);
			$extension = end($arr_file);

			if ('csv' == $extension) {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
			} else {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			}

			$spreadsheet = $reader->load($tempFile);
			$sheetData = $spreadsheet->getActiveSheet()->toArray();

			$this->load->model('M_Master_Item');
			$this->load->model('M_transaksi');

			$rowimport = $this->M_Master_Item->importData($sheetData);

			// Upload file ke server
			move_uploaded_file($tempFile, $targetFile);
			echo $rowimport;
		}
	}

	function importfa()
	{
		if (!empty($_FILES)) {
			$tempFile = $_FILES['file']['tmp_name'];
			$fileName = $_FILES['file']['name'];
			$fileType = $_FILES['file']['type'];
			$fileSize = $_FILES['file']['size'];
			$targetPath = './assets/uploads/';
			$targetFile = $targetPath . $fileName;

			$arr_file = explode('.', $fileName);
			$extension = end($arr_file);

			if ('csv' == $extension) {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
			} else {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			}

			$spreadsheet = $reader->load($tempFile);
			$sheetData = $spreadsheet->getActiveSheet()->toArray();

			$this->load->model('M_Master_Aktiva');

			$rowimport = $this->M_Master_Aktiva->importData($sheetData);

			// Upload file ke server
			move_uploaded_file($tempFile, $targetFile);
			echo $rowimport;
		}
	}
}
