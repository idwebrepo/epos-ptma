<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Kategori_Item extends CI_Controller {

   function __construct() { 
		parent::__construct();
    if(!$this->session->has_userdata('nama')){
      redirect(base_url('exception'));
    }          
    $this->load->model('M_Master_Kategori_Item');
    $this->load->model('M_transaksi');
   }

   function savedata(){
    if($_POST['id']==''){
      echo $this->M_Master_Kategori_Item->tambahData();
    }else{
      echo $this->M_Master_Kategori_Item->ubahData();      
    }
   }

   function deletedata(){
    echo $this->M_Master_Kategori_Item->hapusData();          
   }

   function getdata(){
      if($_POST['id'] == '' || $_POST['id'] == null) {
        echo _pesanError("Data tidak ditemukan !");
        exit;
      }

      $query = "SELECT A.ikid 'id', A.iknama 'nama'
                  FROM bitemkategori A
                 WHERE A.ikid='".$_POST['id']."'";
     
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }

}