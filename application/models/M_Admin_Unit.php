<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_Admin_Unit extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function getDataUnit($query)
    {
        $sql = $this->db->query($query);
        $data = $sql->result_array();
        $callback = array(
            'data' => $data
        );
        return json_encode($callback);
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

        $data = array(            
            'utkode' => $_POST['utkode'],
            'utnama' => $_POST['utnama'],
            'utnamalengkap' => $_POST['utnamalengkap'],
            'uttelepon' => $_POST['uttelepon'],
            'utactive' => $_POST['status']
        );

        // dd($data);

        $this->db->trans_begin();
        $this->db->where('utid', $id);
        $this->db->update('aunit', $data);

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

        $this->db->where('utid', $id);
        $this->db->delete('aunit');

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
        // $password = hash('sha512', md5($_POST['pass']));
        $data = array(
            'utkode' => $_POST['utkode'],
            'utnama' => $_POST['utnama'],
            'utnamalengkap' => $_POST['utnamalengkap'],
            'uttelepon' => $_POST['uttelepon'],
            'utactive' => $_POST['status']
        );

        $this->db->trans_begin();
        $this->db->insert('aunit', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();
            return "sukses";
        }
    }
}
