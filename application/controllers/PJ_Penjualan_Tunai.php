<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PJ_Penjualan_Tunai extends CI_Controller {

   function __construct() { 
		parent::__construct();
    if(!$this->session->has_userdata('nama')){
      redirect(base_url('exception'));
    }          
		$this->load->model('M_transaksi');
    $this->load->model('M_PJ_Penjualan_Tunai');
    $this->load->helper(array('sync_toko'));    
   }

   function savedata(){
      if($_POST['id']==''){
         echo $this->M_PJ_Penjualan_Tunai->tambahTransaksi();
      }else{
         echo $this->M_PJ_Penjualan_Tunai->ubahTransaksi();      
      }
   }

   function savedatatunda(){
      echo $this->M_PJ_Penjualan_Tunai->tundaTransaksi();
   }   

   function deletedatatunda(){
      echo $this->M_PJ_Penjualan_Tunai->hapusTunda();
   }      

   function deletedata(){
      echo $this->M_PJ_Penjualan_Tunai->hapusTransaksi();          
   }   

   function deletedatamulti(){
      echo $this->M_PJ_Penjualan_Tunai->hapusTransaksiMulti();          
   }   

   function get_item() {
      $query  = "SELECT A.isatuan AS 'idsatuan', B.skode 'namasatuan', 
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
                        (SELECT isatuan FROM ainfo ORDER BY iid ASC LIMIT 1) AS 'multisatuan',                          
                        (SELECT gid FROM bgudang WHERE gdefault=1 LIMIT 1) 'idgudang',
                        (SELECT gnama FROM bgudang WHERE gdefault=1 LIMIT 1) 'gudang'       
                   FROM bitem A LEFT JOIN bsatuan B ON A.isatuan=B.sid
                  WHERE A.iid='".$this->input->post('id')."'";
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
    }                   

   function get_item_kode() {
      $query  = "SELECT A.iid 'id',A.inama 'nama',A.isatuan AS 'idsatuan', B.skode 'namasatuan',
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
                        (SELECT isatuan FROM ainfo ORDER BY iid ASC LIMIT 1) AS 'multisatuan',                          
                        (SELECT gid FROM bgudang WHERE gdefault=1 LIMIT 1) 'idgudang',
                        (SELECT gnama FROM bgudang WHERE gdefault=1 LIMIT 1) 'gudang'       
                   FROM bitem A LEFT JOIN bsatuan B ON A.isatuan=B.sid
                  WHERE A.ikode='".$this->input->post('id')."' OR A.ibarcode='".$this->input->post('id')."'";
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
    }  

   function getdata(){

      $transcode = $this->M_transaksi->prefixtrans(element('PJ_Penjualan_Tunai',NID));        
      $query = "SELECT A.ipuid 'id', A.ipunotransaksi 'nomor', DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',
                       A.ipukontak 'kontakid', B.kkode 'kontakkode', B.knama 'kontak', B.kpenlevelharga 'level',
                       A.ipujenispajak 'pajak', A.ipustatus 'status',
                       IFNULL(G.sutotalpajak,0) 'tpajak', 
                       IFNULL(G.sutotalbayar,0) 'tbayar', 
                       IFNULL(G.sutotaltransaksi,0) 'totaltrans', 
                       IFNULL(G.sutotalsisa,0) 'tsisa',
                       IFNULL(G.sutotalkas,0) 'bayarcash',
                       IFNULL(G.sutotalkartudebit,0) 'bayardebit',
                       IFNULL(G.sutotalkartukredit,0) 'bayarcredit',  
                       IFNULL(G.sutotaldp,0) 'bayardiskon',                         
                       G.sunokartudebit 'bayardebitno',
                       G.sunokartukredit 'bayarcreditno',
                       G.subankdebit 'bayardebitbank',
                       G.subankkredit 'bayarcreditbank',
                       H.bkode 'bayardebitbankn',
                       I.bkode 'bayarcreditbankn',
                       C.ipditem 'iditem', D.ikode 'kditem', D.inama 'namaitem', 
                       C.ipdsatuan 'idsatuan', E.skode 'satuan', 
                       C.ipdgudang 'idgudang', F.gnama 'gudang',
                       IFNULL(C.ipdkeluar,0) 'qtydetil', 
                       IFNULL(C.ipdharga,0) 'hargadetil',
                       IFNULL(C.ipddiskon,0) 'diskon',
                       IFNULL(C.ipddiskonp,0) 'persendiskon',
                       ((IFNULL(C.ipdharga,0)-IFNULL(C.ipddiskon,0))*IFNULL(C.ipdkeluar,0)) 'subtotaldetil',
                       D.ihargajual1 'hargajual',D.ihargajual2 'hargajual2',
                       D.ihargajual3 'hargajual3',D.ihargajual4 'hargajual4',
                       D.isat2hargajual1 'sat2hargajual1',D.isat2hargajual2 'sat2hargajual2',
                       D.isat2hargajual3 'sat2hargajual3',D.isat2hargajual4 'sat2hargajual4',
                       D.isat3hargajual1 'sat3hargajual1',D.isat3hargajual2 'sat3hargajual2',
                       D.isat3hargajual3 'sat3hargajual3',D.isat3hargajual4 'sat3hargajual4',
                       D.isat4hargajual1 'sat4hargajual1',D.isat4hargajual2 'sat4hargajual2',
                       D.isat4hargajual3 'sat4hargajual3',D.isat4hargajual4 'sat4hargajual4',        
                       D.isat5hargajual1 'sat5hargajual1',D.isat5hargajual2 'sat5hargajual2',
                       D.isat5hargajual3 'sat5hargajual3',D.isat5hargajual4 'sat5hargajual4',
                       D.isat6hargajual1 'sat6hargajual1',D.isat6hargajual2 'sat6hargajual2',
                       D.isat6hargajual3 'sat6hargajual3',D.isat6hargajual4 'sat6hargajual4',
                       D.isatuan2 AS 'idsatuan2', D.isatkonversi2 'konversi2',
                       D.isatuan3 AS 'idsatuan3', D.isatkonversi3 'konversi3',
                       D.isatuan4 AS 'idsatuan4', D.isatkonversi4 'konversi4',
                       D.isatuan5 AS 'idsatuan5', D.isatkonversi5 'konversi5',                       
                       D.isatuan6 AS 'idsatuan6', D.isatkonversi6 'konversi6',                  
                      (SELECT isatuan FROM ainfo ORDER BY iid ASC LIMIT 1) AS 'multisatuan',
                      A.ipukaryawan 'idkaryawan', J.knama 'karyawan', A.ipucatatan 'catatan'                           
                    FROM einvoicepenjualanu A 
               LEFT JOIN bkontak B ON A.ipukontak=B.kid
               LEFT JOIN einvoicepenjualand C ON A.ipuid=C.ipdidipu 
               LEFT JOIN bitem D ON C.ipditem=D.iid 
               LEFT JOIN bsatuan E ON C.ipdsatuan=E.sid 
               LEFT JOIN bgudang F ON C.ipdgudang=F.gid
               LEFT JOIN fstoku G ON A.ipunobkg=G.suid  
               LEFT JOIN bbank H ON G.subankdebit=H.bid 
               LEFT JOIN bbank I ON G.subankkredit=I.bid 
               LEFT JOIN bkontak J ON A.ipukaryawan=J.kid               
                   WHERE A.ipusumber='".$transcode."'";

        if(!empty($_POST['id'])) {
            $query .= " AND A.ipuid='".$this->input->post('id')."'";
        }else{
            $query .= " AND A.ipunotransaksi='".$this->input->post('nomor')."'";
        }

        $query .= " ORDER BY C.ipdurutan ASC";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }                     

   function getdatatunda(){

      $query = "SELECT A.apuid 'id', DATE_FORMAT(A.aputanggal,'%d-%m-%Y') 'tanggal',
                       A.apukontak 'kontakid', G.kkode 'kontakkode', G.knama 'kontak', G.kpenlevelharga 'level',
                       IFNULL(A.aputotalpajak,0) 'tpajak', 
                       IFNULL(A.aputotalbayar,0) 'tbayar', 
                       IFNULL(A.aputotaltransaksi,0) 'totaltrans', 
                       IFNULL(A.apukas,0) 'bayarcash',
                       IFNULL(A.apucard,0) 'bayardebit',
                       IFNULL(A.apudiskon,0) 'bayardiskon',                         
                       A.apucardref 'bayardebitno',
                       A.apubank 'bayardebitbank',
                       C.bkode 'bayardebitbankn',
                       B.apditem 'iditem', D.ikode 'kditem', D.inama 'namaitem', 
                       B.apdsatuan 'idsatuan', E.skode 'satuan', 
                       B.apdgudang 'idgudang', F.gnama 'gudang',
                       IFNULL(B.apdkeluar,0) 'qtydetil', 
                       IFNULL(B.apdharga,0) 'hargadetil',
                       IFNULL(B.apddiskon,0) 'diskon',
                       IFNULL(B.apddiskonp,0) 'persendiskon',
                       ((IFNULL(B.apdharga,0)-IFNULL(B.apddiskon,0))*IFNULL(B.apdkeluar,0)) 'subtotaldetil',
                       D.ihargajual1 'hargajual',D.ihargajual2 'hargajual2',
                       D.ihargajual3 'hargajual3',D.ihargajual4 'hargajual4',
                       D.isat2hargajual1 'sat2hargajual1',D.isat2hargajual2 'sat2hargajual2',
                       D.isat2hargajual3 'sat2hargajual3',D.isat2hargajual4 'sat2hargajual4',
                       D.isat3hargajual1 'sat3hargajual1',D.isat3hargajual2 'sat3hargajual2',
                       D.isat3hargajual3 'sat3hargajual3',D.isat3hargajual4 'sat3hargajual4',
                       D.isat4hargajual1 'sat4hargajual1',D.isat4hargajual2 'sat4hargajual2',
                       D.isat4hargajual3 'sat4hargajual3',D.isat4hargajual4 'sat4hargajual4',        
                       D.isat5hargajual1 'sat5hargajual1',D.isat5hargajual2 'sat5hargajual2',
                       D.isat5hargajual3 'sat5hargajual3',D.isat5hargajual4 'sat5hargajual4',
                       D.isat6hargajual1 'sat6hargajual1',D.isat6hargajual2 'sat6hargajual2',
                       D.isat6hargajual3 'sat6hargajual3',D.isat6hargajual4 'sat6hargajual4',
                       D.isatuan2 AS 'idsatuan2', D.isatkonversi2 'konversi2',
                       D.isatuan3 AS 'idsatuan3', D.isatkonversi3 'konversi3',
                       D.isatuan4 AS 'idsatuan4', D.isatkonversi4 'konversi4',
                       D.isatuan5 AS 'idsatuan5', D.isatkonversi5 'konversi5',                       
                       D.isatuan6 AS 'idsatuan6', D.isatkonversi6 'konversi6',                  
                      (SELECT isatuan FROM ainfo ORDER BY iid ASC LIMIT 1) AS 'multisatuan',
                      A.apukaryawan 'idkaryawan', H.knama 'karyawan', A.apucatatan 'catatan'                        
                    FROM eantripenjualanu A 
               LEFT JOIN eantripenjualand B ON A.apuid=B.apdidapu 
               LEFT JOIN bbank C ON A.apubank=C.bid 
               LEFT JOIN bitem D ON B.apditem=D.iid 
               LEFT JOIN bsatuan E ON B.apdsatuan=E.sid 
               LEFT JOIN bgudang F ON B.apdgudang=F.gid
               LEFT JOIN bkontak G ON A.apukontak=G.kid
               LEFT JOIN bkontak H ON A.apukaryawan=H.kid               
                   WHERE A.apuid='".$this->input->post('id')."' ORDER BY B.apdurutan ASC";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }

   function get_jumlah_tunda() {
      $query  = "SELECT COUNT(A.apuid) AS 'jumlah'      
                   FROM eantripenjualanu A
                  WHERE A.apucreateu='".$this->session->id."' AND (A.apukaryawan IS NULL OR A.apukaryawan=0) ";
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
    }                                           

}