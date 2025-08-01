<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_Saldo extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
        // $id = $this->input->post('id');

        // if ($this->session->userdata('user') == 'developer' || $this->session->userdata('user') == 'administrator') {
        //     if ($this->input->post('passbaru') == '') {
        //         $data = array(
        //             'ukode' => $_POST['kode'],
        //             'unama' => $_POST['nama'],
        //             'unamalengkap' => $_POST['namalengkap'],
        //             'uemail' => $_POST['email'],
        //             'uactive' => $_POST['status']
        //         );
        //     } else {
        //         $password = hash('sha512', md5($this->input->post('passbaru')));
        //         $data = array(
        //             'ukode' => $_POST['kode'],
        //             'unama' => $_POST['nama'],
        //             'unamalengkap' => $_POST['namalengkap'],
        //             'uemail' => $_POST['email'],
        //             'uactive' => $_POST['status'],
        //             'upassword' => $password
        //         );
        //     }
        // } else {
        //     $data = array(
        //         'ukode' => $_POST['kode'],
        //         'unama' => $_POST['nama'],
        //         'unamalengkap' => $_POST['namalengkap'],
        //         'uemail' => $_POST['email'],
        //         'uactive' => $_POST['status']
        //     );
        // }

        // $this->db->trans_begin();
        // $this->db->where('uid', $id);
        // $this->db->update('auser', $data);

        // //Hapus ausermenu sesuai id
        // $this->db->where('auiduser', $id);
        // $this->db->delete('ausermenu');

        // $this->db->where('auid', $id);
        // $this->db->delete('auserdashboard');

        // //Insert detilmenu
        // $r = 1;
        // $d = json_decode($_POST['detilmenu']);
        // foreach ($d as $item) {
        //     $data_menu = array(
        //         'auiduser' => $id,
        //         'auidmenu' => $item->idmenu,
        //         'auadd' => $item->tambah,
        //         'auedit' => $item->edit,
        //         'audell' => $item->delete,
        //         'auprint' => $item->print,
        //         'auapprove' => $item->buka
        //     );
        //     $this->db->insert('ausermenu', $data_menu);
        //     $r++;
        // }

        // //Insert detilreport
        // $r = 1;
        // $d = json_decode($_POST['detilreport']);
        // foreach ($d as $item) {
        //     $data_report = array(
        //         'auiduser' => $id,
        //         'auidmenu' => $item->idmenu,
        //         'auapprove' => $item->buka
        //     );
        //     $this->db->insert('ausermenu', $data_report);
        //     $r++;
        // }

        // //Insert detilwidget
        // $r = 1;
        // $d = json_decode($_POST['detilwidget']);
        // foreach ($d as $item) {
        //     $data_widget = array(
        //         'auid' => $id,
        //         'adid' => $item->idwidget,
        //         'audapprove' => $item->buka
        //     );
        //     $this->db->insert('auserdashboard', $data_widget);
        //     $r++;
        // }

        // if ($this->db->trans_status() === FALSE) {
        //     $this->db->trans_rollback();
        //     return "rollback";
        // } else {
        //     $this->db->trans_commit();
        //     return "sukses";
        // }
    }

    function hapusData()
    {
        // $id = $_POST['id'];

        // $this->db->trans_begin();

        // $this->db->where('uid', $id);
        // $this->db->delete('auser');

        // $this->db->where('auiduser', $id);
        // $this->db->delete('ausermenu');

        // if ($this->db->trans_status() === FALSE) {
        //     $this->db->trans_rollback();
        //     return "rollback";
        // } else {
        //     $this->db->trans_commit();
        //     return "sukses";
        // }
    }

    function tambahData()
    {

        $data = array(
            'saldounit' => $_POST['idunit'],
            'saldokredit' => $_POST['nominal'],
            'saldocreateid' => $this->session->userdata('id')
        );

        $this->db->trans_begin();
        $this->db->insert('esaldopenjual', $data);
        $id = $this->db->insert_id();

        // echo $this->db->last_query();
        // exit;

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();
            return "sukses";
        }
    }
}
