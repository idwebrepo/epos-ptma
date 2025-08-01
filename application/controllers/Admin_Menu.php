<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_Menu extends CI_Controller {

   function __construct() { 
		parent::__construct();
    if(!$this->session->has_userdata('nama')){
      redirect(base_url('exception'));
    }          
    $this->load->model('M_Admin_Menu');
    $this->load->model('M_transaksi');
   }

   function savedata(){
    if($this->input->post('id')==''){
      echo $this->M_Admin_Menu->tambahData();
    }else{
      echo $this->M_Admin_Menu->ubahData();      
    }
   }

   function deletedata(){
    echo $this->M_Admin_Menu->hapusData();          
   }

   function getdata(){
      if($this->input->post('id') == '' || $this->input->post('id') == null) {
        echo _pesanError("Data tidak ditemukan !");
        exit;
      }

      $query = "SELECT A.*, B.mnama 'parent', C.arname 'report' 
                  FROM amenu A LEFT JOIN amenu B ON A.mparent=B.mid LEFT JOIN areport C ON A.mreport=C.arid  
                 WHERE A.mid='".$this->input->post('id')."'";
     
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }


}