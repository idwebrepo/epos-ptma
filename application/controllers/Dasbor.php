<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dasbor extends CI_Controller
{

	function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		parent::__construct();
		$this->load->model('M_transaksi');
	}

	function index()
	{
		if ($this->session->has_userdata('nama')) {
			$company = $this->M_transaksi->column_value('inama', 'ainfo', array('iid' => 1));
			// join aunit when kodeunit is not null
			$queryUnit = "SELECT B.* FROM auser A LEFT JOIN aunit B ON A.kodeunit=B.utid WHERE A.uid=" . $this->session->id;
			$unit = $this->M_transaksi->get_data_query($queryUnit);
			$unit = json_decode($unit);
			$unit = $unit->data[0]->utnama;
			if ($unit == null) {
				$unit = $this->config->item('app_name');
			}


			foreach ($company->result() as $row) {
				$data['copy'] = $row->inama;
				$data['vendor_text'] = $this->config->item('vendor_text');
			}

			$info = _ainfo(1);
			$data['decimal'] = $info['idecimal'];
			$data['decimalqty'] = $info['idecimalqty'];
			$data['multisatuan'] = $info['isatuan'];
			$data['app_name'] = $unit;
			$data['title'] = 'Dasbor | ' . $unit;
			$data['title2'] = 'Dasbor';
			$data['versi'] = $this->config->item('versi');
			$data['app_url'] = $this->config->item('app_url');

			$this->load->helper('url');
			$this->load->view('include/header', $data);
			$this->load->view('dasbor', $data);
			$this->load->view('include/sidebar', $data);
			$this->load->view('include/footer', $data);
		} else {
			redirect(base_url());
		}
	}

	function refreshsidebarmenu()
	{
		$company = $this->M_transaksi->column_value('inama', 'ainfo', array('iid' => 1));
		foreach ($company->result() as $row) {
			$data['copy'] = $row->inama;
			$data['vendor_text'] = $this->config->item('vendor_text');
			//$data['vendor_text'] = $row->inama;								
		}
		$data['app_name'] = $this->config->item('app_name');
		$data['title'] = 'Dasbor | ' . $data['app_name'];
		$data['title2'] = 'Dasbor';
		$data['versi'] = $this->config->item('versi');
		$data['app_url'] = $this->config->item('app_url');
		$this->load->view('include/sidebar', $data);
	}

	function getmenutransaksi()
	{
		$query = "SELECT A.* FROM amenu A INNER JOIN ausermenu B ON A.MID=B.AUIDMENU AND B.AUIDUSER=" . $this->session->id . " 
      			 WHERE A.mtype=2 AND A.mactive=1 ORDER BY A.murutan ASC, A.mparent ASC";

		header('Content-Type: application/json');
		echo $this->M_transaksi->get_data_query($query);
	}

	function getchildmenu()
	{
		$query = "SELECT A.* FROM amenu A INNER JOIN ausermenu B ON A.MID=B.AUIDMENU AND B.AUIDUSER=" . $this->session->id . " 
      			 WHERE A.mparent=" . $this->input->post('id') . " ORDER BY A.murutan ASC";

		header('Content-Type: application/json');
		echo $this->M_transaksi->get_data_query($query);
	}

	function totalNotif()
	{
		$transcode = $this->M_transaksi->prefixtrans(element('PJ_Faktur_Penjualan', NID));
		$transcode2 = $this->M_transaksi->prefixtrans(element('PB_Faktur_Pembelian', NID));
		$transcode3 = $this->M_transaksi->prefixtrans(element('PB_Order_Pembelian', NID));

		$query = "SELECT SUM(TABEL.row) 'total' FROM (
        		  SELECT COUNT(A.iid) 'row'                                 
                    FROM bitem A 
                   WHERE A.itanggal1 <= CURDATE() AND A.ijenisitem=0 AND A.istatus=0
                  UNION ALL
				  SELECT COUNT(A.iid) 'row'                                 
                    FROM bitem A 
                   WHERE A.istocktotal <= A.istockminimal AND A.ijenisitem=0 AND A.istatus=0
                  UNION ALL
				  SELECT COUNT(A.ipuid) 'row'                   
                    FROM einvoicepenjualanu A     
              INNER JOIN btermin B ON A.iputermin=B.tid  
                   WHERE A.ipusumber='" . $transcode . "' 
                     AND DATE_ADD(A.iputanggal, INTERVAL B.ttempo DAY) <= CURDATE() 
                     AND A.iputotalbayar<A.iputotaltransaksi
                  UNION ALL
				  SELECT COUNT(A.ipuid) 'row'                   
                    FROM einvoicepenjualanu A     
              INNER JOIN btermin B ON A.iputermin=B.tid  
                   WHERE A.ipusumber='" . $transcode2 . "' 
                     AND DATE_ADD(A.iputanggal, INTERVAL B.ttempo DAY) <= CURDATE() 
                     AND A.iputotalbayar<A.iputotaltransaksi
                  UNION ALL 
				  SELECT COUNT(T.souid) 'row' FROM (
                     SELECT A.souid                    
                       FROM esalesorderu A
                       LEFT JOIN esalesorderd B ON A.souid=B.sodidsou     
                       WHERE A.sousumber='" . $transcode3 . "' AND B.sodorder-B.sodmasuk-B.sodkeluar > 0 GROUP BY A.souid)T                  
                 ) TABEL";

		header('Content-Type: application/json');
		echo $this->M_transaksi->get_data_query($query);
	}
}
