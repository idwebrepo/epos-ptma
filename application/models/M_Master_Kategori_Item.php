<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_Master_Kategori_Item extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
        $id = $_POST['id'];

        $data = array(
            'iknama' => $_POST['nama']
        );

        $this->db->trans_start();
        $this->db->where('ikid', $id);
        $this->db->update('bitemkategori', $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return "rollback";
        } else {
            return "sukses";
        }
    }

    function hapusData()
    {
        $id = $_POST['id'];

        $this->db->trans_start();
        $this->db->where('ikid', $id);
        $this->db->delete('bitemkategori');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return "rollback";
        } else {
            return "sukses";
        }
    }

    function tambahData()
    {
        $data = array(
            'iknama' => $_POST['nama']
        );

        $this->db->trans_start();
        $this->db->insert('bitemkategori', $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return "rollback";
        } else {
            return "sukses";
        }
    }
}
