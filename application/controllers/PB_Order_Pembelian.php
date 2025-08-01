<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PB_Order_Pembelian extends CI_Controller {

   function __construct() { 
  		parent::__construct();
      if(!$this->session->has_userdata('nama')){
        redirect(base_url('exception'));
      }          
  		$this->load->model('M_transaksi');
      $this->load->model('M_PB_Order_Pembelian');
   }

   function savedata(){
      if($_POST['id']==''){
        echo $this->M_PB_Order_Pembelian->tambahTransaksi();
      }else{
        echo $this->M_PB_Order_Pembelian->ubahTransaksi();      
      }
   }

   function deletedata(){
      echo $this->M_PB_Order_Pembelian->hapusTransaksi();          
   }

   function deletedatamulti(){
      echo $this->M_PB_Order_Pembelian->hapusTransaksiMulti();          
   }

   function get_item() {
        $query  = "SELECT A.isatuan AS 'idsatuan', B.snama 'namasatuan', A.ihargabeli 'harga',
                          A.isatuan2 AS 'idsatuan2', 0 'konversi2',
                          A.isatuan3 AS 'idsatuan3', 0 'konversi3',
                          A.isatuan4 AS 'idsatuan4', 0 'konversi4',
                          A.isatuan5 AS 'idsatuan5', 0 'konversi5',
                          A.isatuan6 AS 'idsatuan6', 0 'konversi6',                                                    
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

      $transcode = $this->M_transaksi->prefixtrans(element('PB_Order_Pembelian',NID));        
   		$query = "SELECT A.souid 'id', A.sounotransaksi 'nomor', DATE_FORMAT(A.soutanggal,'%d-%m-%Y') 'tanggal',
   						 A.soukontak 'kontakid', B.kkode 'kontakkode', B.knama 'kontak', A.souuraian 'uraian',
   						 A.soukaryawan 'idkaryawan', C.kkode 'kodekaryawan', C.knama 'namakaryawan', A.sounoref 'noref',
               A.soutermin 'idtermin', D.tkode 'termin', A.soucatatan 'catatan', A.soualamat 'alamat', A.souattention 'idperson',
               E.kanama 'person', A.soupajak 'pajak', A.soustatus 'status', 
               IFNULL(A.soutotalpajak,0) 'tpajak', 
               IFNULL(A.soutotalpph22,0) 'tpph22',                
               IFNULL(A.sousubtotal,0) 'tsubtotal',
               IFNULL(A.soutotaltransaksi,0) 'totaltrans', G.ihargabeli 'hargadef',
               G.isatuan2 AS 'idsatuan2', 0 'konversi2',
               G.isatuan3 AS 'idsatuan3', 0 'konversi3',
               G.isatuan4 AS 'idsatuan4', 0 'konversi4',               
               G.isatuan5 AS 'idsatuan5', 0 'konversi5',               
               G.isatuan6 AS 'idsatuan6', 0 'konversi6',                                             
               F.soditem 'iditem', G.ikode 'kditem', G.inama 'namaitem', F.sodcatatan 'catdetil',
               F.sodsatuan 'idsatuan', H.skode 'satuan', 
               IFNULL(F.sodorder,0) 'qtydetil', 
               IFNULL(F.sodharga,0) 'hargadetil',
               IFNULL(F.soddiskon,0) 'diskon',
               IFNULL(F.soddiskonpersen,0) 'persendiskon',
               ((IFNULL(F.sodharga,0)-IFNULL(F.soddiskon,0))*IFNULL(F.sodorder,0)) 'subtotaldetil'               
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

   function getorderprocess(){
        $transcode = $this->M_transaksi->prefixtrans(element('PB_Order_Pembelian',NID));            

        $query = "SELECT COUNT(souid) 'row' FROM (
                     SELECT A.souid                    
                       FROM esalesorderu A
                       LEFT JOIN esalesorderd B ON A.souid=B.sodidsou     
                       WHERE A.sousumber='".$transcode."' AND B.sodorder-B.sodmasuk-B.sodkeluar > 0 GROUP BY A.souid) TABEL
                  ";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);

   }


}