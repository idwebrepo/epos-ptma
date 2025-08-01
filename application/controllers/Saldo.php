<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Saldo extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('M_Admin_Unit');
        if (!$this->session->has_userdata('nama')) {
            redirect(base_url('exception'));
        }
        $this->load->model('M_Saldo');
        $this->load->model('M_transaksi');
    }

    function savedata()
    {
        if ($this->input->post('id') == '') {
            // echo $this->input->post('id');
            // echo $this->input->post('nominal');
            // exit;
            echo $this->M_Saldo->tambahData();
        } else {
            echo $this->M_Saldo->ubahData();
        }
    }

    function getsaldo()
    {
        // SELECT A.utid 'id', A.utnama 'namaUnit', A.utkode 'kodeUnit', SUM(B.saldodebit) AS debitAll, SUM(B.saldokredit) AS kreditAll FROM aunit A LEFT JOIN esaldopenjual B ON A.utid=B.saldounit GROUP BY B.saldounit
        $query = "SELECT A.utid 'idUnit', A.utnama 'namaUnit', A.utkode 'kodeUnit', SUM(B.saldodebit) AS debitAll, SUM(B.saldokredit) AS kreditAll
                    FROM aunit A LEFT JOIN esaldopenjual B ON A.utid=B.saldounit WHERE A.utid = " . $this->input->post('id') . " GROUP BY B.saldounit";

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

    function getdataunittoko()
    {
        $query = "SELECT * FROM aunit WHERE utactive=1 ";
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }
}
