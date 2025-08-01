<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PJ_Order_Penjualan extends CI_Controller {

   function __construct() { 
  		parent::__construct();
      if(!$this->session->has_userdata('nama')){
        redirect(base_url('exception'));
      }          
  		$this->load->model('M_transaksi');
      $this->load->model('M_PJ_Order_Penjualan');
   }

   function savedata(){
      if($_POST['id']==''){
        echo $this->M_PJ_Order_Penjualan->tambahTransaksi();
      }else{
        echo $this->M_PJ_Order_Penjualan->ubahTransaksi();      
      }
   }

   function deletedata(){
      echo $this->M_PJ_Order_Penjualan->hapusTransaksi();          
   }   

   function deletedatamulti(){
      echo $this->M_PJ_Order_Penjualan->hapusTransaksiMulti();          
   }   

   function get_item() {
        $query  = "SELECT A.isatuan 'idsatuan', B.snama 'namasatuan', 
                          (SELECT kpenlevelharga FROM bkontak WHERE kid='".$_POST['kontak']."') 'level', 
                          A.ihargajual1 'hargajual',A.ihargajual2 'hargajual2',
                          A.ihargajual3 'hargajual3',A.ihargajual4 'hargajual4',
                          A.isat2hargajual1 'sat2hargajual1',A.isat2hargajual2 'sat2hargajual2',
                          A.isat2hargajual3 'sat2hargajual3',A.isat2hargajual4 'sat2hargajual4',
                          A.isat3hargajual1 'sat3hargajual1',A.isat3hargajual2 'sat3hargajual2',
                          A.isat3hargajual3 'sat3hargajual3',A.isat3hargajual4 'sat3hargajual4',
                          A.isat4hargajual1 'sat4hargajual1',A.isat4hargajual2 'sat4hargajual2',
                          A.isat4hargajual3 'sat4hargajual3',A.isat4hargajual4 'sat4hargajual4',
                          A.isat5hargajual1 'sat5hargajual1',A.isat5hargajual2 'sat5hargajual2',
                          A.isat5hargajual3 'sat5hargajual3',A.isat5hargajual4 'sat5hargajual4',                                  
                          A.isat6hargajual1 'sat6hargajual1',A.isat6hargajual2 'sat6hargajual2',
                          A.isat6hargajual3 'sat6hargajual3',A.isat6hargajual4 'sat6hargajual4',                                  
                          A.isatuan2 AS 'idsatuan2', A.isatkonversi2 'konversi2',
                          A.isatuan3 AS 'idsatuan3', A.isatkonversi3 'konversi3',
                          A.isatuan4 AS 'idsatuan4', A.isatkonversi4 'konversi4',
                          A.isatuan5 AS 'idsatuan5', A.isatkonversi5 'konversi5',
                          A.isatuan6 AS 'idsatuan6', A.isatkonversi6 'konversi6',                                                    
                          (SELECT isatuan FROM ainfo ORDER BY iid ASC LIMIT 1) AS 'multisatuan'                          
                     FROM bitem A LEFT JOIN bsatuan B ON A.isatuan=B.sid
                    WHERE A.iid='".$_POST['id']."'";
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }                      

   function getdata(){
   		if(empty($_POST['id'])) {
   			echo _pesanError("Id transaksi tidak ditemukan !");
  			exit;
   		}

      $transcode = $this->M_transaksi->prefixtrans(element('PJ_Order_Penjualan',NID));        
   		$query = "SELECT A.souid 'id', A.sounotransaksi 'nomor', DATE_FORMAT(A.soutanggal,'%d-%m-%Y') 'tanggal',
           						 A.soukontak 'kontakid', B.kkode 'kontakkode', B.knama 'kontak', B.kpenlevelharga 'level',
                       A.souuraian 'uraian', A.soukaryawan 'idkaryawan', C.kkode 'kodekaryawan', C.knama 'namakaryawan', A.sounopopelanggan 'noref',
                       A.soutermin 'idtermin', D.tkode 'termin', A.soucatatan 'catatan', A.soualamat 'alamat', A.souattention 'idperson',
                       E.kanama 'person', A.soupajak 'pajak', A.soustatus 'status', 
                       IFNULL(A.soutotalpajak,0) 'tpajak',
                       IFNULL(A.soutotalpph22,0) 'tpph22',                
                       IFNULL(A.sousubtotal,0) 'tsubtotal',
                       IFNULL(A.soutotaltransaksi,0) 'totaltrans', 
                       F.soditem 'iditem', G.ikode 'kditem', G.inama 'namaitem', F.sodcatatan 'catdetil',
                       F.sodsatuan 'idsatuan', H.skode 'satuan', 
                       IFNULL(F.sodorder,0) 'qtydetil', 
                       IFNULL(F.sodharga,0) 'hargadetil', 
                       IFNULL(F.soddiskon,0) 'diskon', 
                       IFNULL(F.soddiskonpersen,0) 'persendiskon', 
                       ((IFNULL(F.sodharga,0)-IFNULL(F.soddiskon,0))*IFNULL(F.sodorder,0)) 'subtotaldetil',
                       G.ihargajual1 'hargajual',G.ihargajual2 'hargajual2',
                       G.ihargajual3 'hargajual3',G.ihargajual4 'hargajual4',
                       G.isat2hargajual1 'sat2hargajual1',G.isat2hargajual2 'sat2hargajual2',
                       G.isat2hargajual3 'sat2hargajual3',G.isat2hargajual4 'sat2hargajual4',
                       G.isat3hargajual1 'sat3hargajual1',G.isat3hargajual2 'sat3hargajual2',
                       G.isat3hargajual3 'sat3hargajual3',G.isat3hargajual4 'sat3hargajual4',
                       G.isat4hargajual1 'sat4hargajual1',G.isat4hargajual2 'sat4hargajual2',
                       G.isat4hargajual3 'sat4hargajual3',G.isat4hargajual4 'sat4hargajual4',        
                       G.isat5hargajual1 'sat5hargajual1',G.isat5hargajual2 'sat5hargajual2',
                       G.isat5hargajual3 'sat5hargajual3',G.isat5hargajual4 'sat5hargajual4',
                       G.isat6hargajual1 'sat6hargajual1',G.isat6hargajual2 'sat6hargajual2',
                       G.isat6hargajual3 'sat6hargajual3',G.isat6hargajual4 'sat6hargajual4',                                                      
                       G.isatuan2 AS 'idsatuan2', G.isatkonversi2 'konversi2',
                       G.isatuan3 AS 'idsatuan3', G.isatkonversi3 'konversi3',
                       G.isatuan4 AS 'idsatuan4', G.isatkonversi4 'konversi4',
                       G.isatuan5 AS 'idsatuan5', G.isatkonversi5 'konversi5',                       
                       G.isatuan6 AS 'idsatuan6', G.isatkonversi6 'konversi6',
                      (SELECT isatuan FROM ainfo ORDER BY iid ASC LIMIT 1) AS 'multisatuan'                       
                    FROM esalesorderu A 
               LEFT JOIN bkontak B ON A.soukontak=B.kid
               LEFT JOIN bkontak C ON A.soukaryawan=C.kid 
               LEFT JOIN btermin D ON A.soutermin=D.tid
               LEFT JOIN bkontakatention E ON A.souattention=E.kaid 
               LEFT JOIN esalesorderd F ON A.souid=F.sodidsou 
               LEFT JOIN bitem G ON F.soditem=G.iid 
               LEFT JOIN bsatuan H ON F.sodsatuan=H.sid 
                   WHERE A.sousumber='".$transcode."' AND A.souid='".$_POST['id']."' ORDER BY F.sodurutan ASC ";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }

}