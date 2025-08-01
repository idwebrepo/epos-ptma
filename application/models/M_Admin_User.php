<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_Admin_User extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }


    function ubahTokenReset()
    {
        $token = hash('sha512', md5(rand()));
        $data = array(
            'utokenreset' => $token
        );

        $this->db->trans_begin();
        $this->db->where('uemail', $this->input->post('email'));
        $this->db->update('auser', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return "";
        } else {
            $this->db->trans_commit();
            return $token;
        }
    }

    function simpanPasswordReset()
    {
        $password = hash('sha512', md5($this->input->post('password')));
        $data = array(
            'upassword' => $password,
            'utokenreset' => NULL
        );

        $this->db->trans_begin();
        $this->db->where('utokenreset', $this->input->post('key'));
        $this->db->update('auser', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();
            return "sukses";
        }
    }

    function simpanPassword()
    {
        $password = hash('sha512', md5($this->input->post('pass')));
        $data = array(
            'upassword' => $password
        );

        $this->db->trans_begin();
        $this->db->where('uid', $this->session->userdata('id'));
        $this->db->update('auser', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();
            return "sukses";
        }
    }

    function ubahData()
    {
        $id = $this->input->post('id');

        if ($this->session->userdata('user') == 'developer' || $this->session->userdata('user') == 'administrator' || $this->session->userdata('user') == 'adminsuper@tokoepesantren.co.id' ) {
            if ($this->input->post('passbaru') == '') {
                $data = array(
                    'ukode' => $_POST['kode'],
                    'unama' => $_POST['nama'],
                    'unamalengkap' => $_POST['namalengkap'],
                    'uemail' => $_POST['email'],
                    'uactive' => $_POST['status']
                );
            } else {
                $password = hash('sha512', md5($this->input->post('passbaru')));
                $data = array(
                    'ukode' => $_POST['kode'],
                    'unama' => $_POST['nama'],
                    'unamalengkap' => $_POST['namalengkap'],
                    'uemail' => $_POST['email'],
                    'uactive' => $_POST['status'],
                    'upassword' => $password
                );
            }
        } else {
            $data = array(
                'ukode' => $_POST['kode'],
                'unama' => $_POST['nama'],
                'unamalengkap' => $_POST['namalengkap'],
                'uemail' => $_POST['email'],
                'uactive' => $_POST['status']
            );
        }

        $this->db->trans_begin();
        $this->db->where('uid', $id);
        $this->db->update('auser', $data);

        //Hapus ausermenu sesuai id
        $this->db->where('auiduser', $id);
        $this->db->delete('ausermenu');

        $this->db->where('auid', $id);
        $this->db->delete('auserdashboard');

        //Insert detilmenu
        $r = 1;
        $d = json_decode($_POST['detilmenu']);
        foreach ($d as $item) {
            $data_menu = array(
                'auiduser' => $id,
                'auidmenu' => $item->idmenu,
                'auadd' => $item->tambah,
                'auedit' => $item->edit,
                'audell' => $item->delete,
                'auprint' => $item->print,
                'auapprove' => $item->buka
            );
            $this->db->insert('ausermenu', $data_menu);
            $r++;
        }

        //Insert detilreport
        $r = 1;
        $d = json_decode($_POST['detilreport']);
        foreach ($d as $item) {
            $data_report = array(
                'auiduser' => $id,
                'auidmenu' => $item->idmenu,
                'auapprove' => $item->buka
            );
            $this->db->insert('ausermenu', $data_report);
            $r++;
        }

        //Insert detilwidget
        $r = 1;
        $d = json_decode($_POST['detilwidget']);
        foreach ($d as $item) {
            $data_widget = array(
                'auid' => $id,
                'adid' => $item->idwidget,
                'audapprove' => $item->buka
            );
            $this->db->insert('auserdashboard', $data_widget);
            $r++;
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();
            return "sukses";
        }
    }

    function hapusData()
    {
        $id = $_POST['id'];

        $this->db->trans_begin();

        $this->db->where('uid', $id);
        $this->db->delete('auser');

        $this->db->where('auiduser', $id);
        $this->db->delete('ausermenu');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();
            return "sukses";
        }
    }

    function tambahData()
    {

        $password = hash('sha512', md5($_POST['pass']));
        $data = array(
            'ukode' => $_POST['kode'],
            'unama' => $_POST['nama'],
            'unamalengkap' => $_POST['namalengkap'],
            'uemail' => $_POST['email'],
            'upassword' => $password,
            'uactive' => $_POST['status'],
            'kodeunit' => $_POST['unitToko']
        );

        $this->db->trans_begin();
        $this->db->insert('auser', $data);
        $id = $this->db->insert_id();

        //Hapus ausermenu sesuai id
        $this->db->where('auiduser', $id);
        $this->db->delete('ausermenu');

        //Insert detilmenu
        $r = 1;
        $d = json_decode($_POST['detilmenu']);
        foreach ($d as $item) {
            $data_menu = array(
                'auiduser' => $id,
                'auidmenu' => $item->idmenu,
                'auadd' => $item->tambah,
                'auedit' => $item->edit,
                'audell' => $item->delete,
                'auprint' => $item->print,
                'auapprove' => $item->buka
            );
            $this->db->insert('ausermenu', $data_menu);
            $r++;
        }



        //Insert detilreport
        $r = 1;
        $d = json_decode($_POST['detilreport']);
        foreach ($d as $item) {
            $data_report = array(
                'auiduser' => $id,
                'auidmenu' => $item->idmenu,
                'auapprove' => $item->buka
            );
            $this->db->insert('ausermenu', $data_report);
            $r++;
        }

        //Insert detilwidget
        $r = 1;
        $d = json_decode($_POST['detilwidget']);
        foreach ($d as $item) {
            $data_widget = array(
                'auid' => $id,
                'adid' => $item->idwidget,
                'audapprove' => $item->buka
            );
            $this->db->insert('auserdashboard', $data_widget);
            $r++;
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();
            return "sukses";
        }
    }
}
