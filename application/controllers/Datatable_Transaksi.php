<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datatable_Transaksi extends CI_Controller {

   function __construct() { 
      parent::__construct();
      if(!$this->session->has_userdata('nama')){
          redirect(base_url('exception'));
      }            
      $this->load->model('M_datatables');
      $this->load->model('M_transaksi');      
   }

   function view_saldo_akun_dasbor(){
     $query="SELECT cid 'id', cnama 'keterangan', dk 'DK', saldo 'total' FROM (
     SELECT      B.cgid, B.cgnama, A.cid, A.cnocoa, A.cnama, 
          CASE WHEN B.cgid<7 THEN 'D' ELSE 'K' END 'dk',
          IFNULL(
            (SELECT SUM(ROUND(AA.cddebit,2))-SUM(ROUND(AA.cdkredit,2))  
               FROM ctransaksid AA 
         INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal <= CURDATE()
              WHERE AA.cdnocoa=A.cid),0) 'saldo'  
  FROM bcoa A INNER JOIN bcoagrup B ON A.ctipe=B.cgid 
 WHERE A.ctipe<11 AND A.cgd='D' AND A.cdasbor=1 AND A.cid NOT IN (
      SELECT A.cccoa 
        FROM cconfigcoa A INNER JOIN bcoa B ON A.cccoa=B.cid 
       WHERE A.cckode='RL' AND A.ccketerangan='BERJALAN'  
)
 UNION 
SELECT B.cgid, B.cgnama, A.cid, A.cnocoa, A.cnama, 
          'D' AS 'dk', (SELECT IFNULL(SUM(ROUND(AA.cddebit,2))-SUM(ROUND(AA.cdkredit,2)),0)  
               FROM ctransaksid AA 
      INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal <= CURDATE() 
       INNER JOIN bcoa AC ON AA.cdnocoa=AC.cid WHERE AC.ctipe < 7) - 
           (SELECT IFNULL(SUM(ROUND(AA.cdkredit,2))-SUM(ROUND(AA.cddebit,2)),0)  
               FROM ctransaksid AA 
      INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal <= CURDATE() 
       INNER JOIN bcoa AC ON AA.cdnocoa=AC.cid WHERE AC.ctipe > 6 AND AC.ctipe < 11) +
           (SELECT IFNULL(SUM(ROUND(AA.cdkredit,2))-SUM(ROUND(AA.cddebit,2)),0)  
               FROM ctransaksid AA 
      INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal <= CURDATE() 
       INNER JOIN bcoa AC ON AA.cdnocoa=AC.cid WHERE AC.cid = (   SELECT A.cccoa FROM cconfigcoa A INNER JOIN bcoa B ON A.cccoa=B.cid WHERE A.cckode='RL' AND A.ccketerangan='BERJALAN')) 
    AS 'saldo'
  FROM bcoa A INNER JOIN bcoagrup B ON A.ctipe=B.cgid 
 WHERE A.ctipe<11 AND A.cgd='D' AND A.cdasbor=1 AND A.cid IN (
      SELECT A.cccoa 
        FROM cconfigcoa A INNER JOIN bcoa B ON A.cccoa=B.cid 
       WHERE A.cckode='RL' AND A.ccketerangan='BERJALAN'  
)
) TABEL    ";      

     header('Content-Type: application/json');
     echo $this->M_datatables->get_tables_query_p($query);     
   }


   function view_posisi_keuangan(){
        $query  = "SELECT cgid 'id', cgnama 'keterangan', dk 'DK', SUM(saldo) 'total' FROM (
    SELECT B.cgid, B.cgnama, A.cid, A.cnocoa, A.cnama, 
    CASE WHEN B.cgid<7 THEN 'D' ELSE 'K' END 'dk',
     IFNULL((SELECT SUM(ROUND(AA.cddebit,2))-SUM(ROUND(AA.cdkredit,2))  
                 FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal <= CURDATE()
                WHERE AA.cdnocoa=A.cid),0) 'saldo'  
  FROM bcoa A INNER JOIN bcoagrup B ON A.ctipe=B.cgid 
 WHERE A.ctipe<11 AND A.cgd='D' AND A.cid NOT IN (
      SELECT A.cccoa FROM cconfigcoa A INNER JOIN bcoa B ON A.cccoa=B.cid WHERE A.cckode='RL' AND A.ccketerangan='BERJALAN'
     UNION 
      SELECT A.cccoa FROM cconfigcoa A INNER JOIN bcoa B ON A.cccoa=B.cid WHERE A.cckode='RL' AND A.ccketerangan='DITAHAN'  
     UNION
      SELECT A.cccoa FROM cconfigcoa A INNER JOIN bcoa B ON A.cccoa=B.cid WHERE A.cckode='RL' AND A.ccketerangan='PRIVE'       
 )
 UNION 
 SELECT 11 AS 'cgid', 'Laba Ditahan' AS 'cgnama', NULL AS 'cid', NULL AS 'cnocoa', NULL AS 'cnama', 'K' AS 'dk', (SELECT SUM(ROUND(AA.cddebit,2))-SUM(ROUND(AA.cdkredit,2))  
               FROM ctransaksid AA 
      INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal <= CURDATE() 
       INNER JOIN bcoa AC ON AA.cdnocoa=AC.cid WHERE AC.cid = (   SELECT A.cccoa FROM cconfigcoa A INNER JOIN bcoa B ON A.cccoa=B.cid WHERE A.cckode='RL' AND A.ccketerangan='DITAHAN')) 
    AS 'saldo'
 UNION 
 SELECT 12 AS 'cgid', 'Laba Berjalan' AS 'cgnama', NULL AS 'cid', NULL AS 'cnocoa', NULL AS 'cnama', 'D' AS 'dk', (SELECT IFNULL(SUM(ROUND(AA.cddebit,2))-SUM(ROUND(AA.cdkredit,2)),0)  
               FROM ctransaksid AA 
      INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal <= CURDATE() 
       INNER JOIN bcoa AC ON AA.cdnocoa=AC.cid WHERE AC.ctipe < 7) - 
           (SELECT IFNULL(SUM(ROUND(AA.cdkredit,2))-SUM(ROUND(AA.cddebit,2)),0)  
               FROM ctransaksid AA 
      INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal <= CURDATE() 
       INNER JOIN bcoa AC ON AA.cdnocoa=AC.cid WHERE AC.ctipe > 6 AND AC.ctipe < 11) +
           (SELECT IFNULL(SUM(ROUND(AA.cdkredit,2))-SUM(ROUND(AA.cddebit,2)),0)  
               FROM ctransaksid AA 
      INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal <= CURDATE() 
       INNER JOIN bcoa AC ON AA.cdnocoa=AC.cid WHERE AC.cid = (   SELECT A.cccoa FROM cconfigcoa A INNER JOIN bcoa B ON A.cccoa=B.cid WHERE A.cckode='RL' AND A.ccketerangan='BERJALAN')) 
    AS 'saldo' 
) TABEL GROUP BY cgid";  

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query_p($query);     
   } 


   function view_expired_1_month() {
        $query  = "SELECT A.iid 'id',A.ikode 'sku',DATE_FORMAT(A.itanggal1,'%d-%m-%Y') 'tanggal',
                          A.inama 'nama', A.istocktotal 'qty'  
                     FROM bitem A";
        $search = array('ikode','inama');
        $where  = null;         
        $isWhere = " A.itanggal1 <= DATE_ADD(CURDATE(), INTERVAL 1 MONTH) AND A.ijenisitem=0 AND A.istatus=0";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_item_terlaris() {
        $transcode = $this->M_transaksi->prefixtrans(element('PJ_Penjualan_Tunai',NID));        
        $transcode2 = $this->M_transaksi->prefixtrans(element('PJ_Faktur_Penjualan',NID));
        $transcode3 = $this->M_transaksi->prefixtrans(element('SV_Penjualan_Tunai',NID));

        $date1 = tgl_database($this->input->post('dari'));
        $date2 = tgl_database($this->input->post('sampai'));

        $query = "SELECT * FROM (
                      SELECT A.ipditem 'id', C.ikode 'sku', C.inama 'nama', A.ipdkeluar 'qty' 
                        FROM einvoicepenjualand A INNER JOIN einvoicepenjualanu B ON A.ipdidipu=B.ipuid
                   INNER JOIN bitem C ON A.ipditem=C.iid AND C.ijenisitem=0 
                    LEFT JOIN bsatuan D ON C.isatuan=D.sid
                       WHERE B.iputanggal BETWEEN '".$date1."' AND '".$date2."' AND B.ipusumber IN ('".$transcode."','".$transcode2."','".$transcode3."') GROUP BY A.ipditem ) TB";

        $search = array('sku','nama');
        $where  = null;         
        $isWhere = "";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }    

   function view_jasa_terlaris() {
        $transcode = $this->M_transaksi->prefixtrans(element('PJ_Penjualan_Tunai',NID));        
        $transcode2 = $this->M_transaksi->prefixtrans(element('PJ_Faktur_Penjualan',NID));              
        $transcode3 = $this->M_transaksi->prefixtrans(element('SV_Penjualan_Tunai',NID));

        $date1 = tgl_database($this->input->post('dari'));
        $date2 = tgl_database($this->input->post('sampai'));

        $query = "SELECT * FROM (
                      SELECT A.ipditem 'id', C.ikode 'sku', C.inama 'nama', A.ipdkeluar 'qty' 
                        FROM einvoicepenjualand A INNER JOIN einvoicepenjualanu B ON A.ipdidipu=B.ipuid
                   INNER JOIN bitem C ON A.ipditem=C.iid AND C.ijenisitem=1 
                    LEFT JOIN bsatuan D ON C.isatuan=D.sid
                       WHERE B.iputanggal BETWEEN '".$date1."' AND '".$date2."' AND B.ipusumber IN ('".$transcode."','".$transcode2."','".$transcode3."') GROUP BY A.ipditem ) TB";

        $search = array('sku','nama');
        $where  = null;         
        $isWhere = "";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }        

   function view_tunda_pos() {
        $query  = "SELECT A.apuid 'id',DATE_FORMAT(A.apucreated,'%d-%m-%Y') 'tanggal',
                          DATE_FORMAT(A.apucreated,'%H:%i') 'waktu', CONCAT_WS('-',B.kkode,B.knama) 'kontak',
                          A.aputotaltransaksi 'total'  
                     FROM eantripenjualanu A 
                LEFT JOIN bkontak B ON A.apukontak=B.kid";
        $search = array('kkode','knama');
        $where  = null;         
        $isWhere = " A.apukaryawan IS NULL OR A.apukaryawan=0";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_tunda_pos_service() {
        $query  = "SELECT A.apuid 'id',DATE_FORMAT(A.apucreated,'%d-%m-%Y') 'tanggal',
                          DATE_FORMAT(A.apucreated,'%H:%i') 'waktu', 
                          CONCAT_WS('-',B.kkode,B.knama) 'kontak', C.knama 'mekanik',
                          A.aputotaltransaksi 'total'  
                     FROM eantripenjualanu A 
                LEFT JOIN bkontak B ON A.apukontak=B.kid
               INNER JOIN bkontak C ON A.apukaryawan=C.kid";
        $search = array('B.kkode','B.knama','C.knama');
        $where  = null;         
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_data_service() {
        $transcode = element('SV_Perintah_Perbaikan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.souid 'id',A.sounotransaksi 'nomor',DATE_FORMAT(A.soutanggal,'%d-%m-%Y') 'tanggal',
                          B.kid 'idkontak', B.kkode 'kode', IF(A.soupemilik='' OR A.soupemilik IS NULL, B.knama, A.soupemilik) 'kontak', A.souuraian 'uraian', D.knama 'karyawan', A.soucatatan1 'odometer',
                          A.soucatatan2 'merek', A.soucatatan3 'nomesin', A.soucatatan4 'keluhan', A.soucatatan5 'diagnosa',
                          A.soucatatan6 'temuan', A.sounopol 'nopol'
                     FROM esalesorderu A 
                LEFT JOIN bkontak B ON A.soukontak=B.kid
                LEFT JOIN bkontak D ON A.soukaryawan=D.kid ";
        $search = array('A.sounotransaksi','B.kkode','B.knama','D.knama','A.soucatatan2');
        $where  = null;         
        $isWhere = " A.sousumber='".$transcode."' AND A.soustatus=1";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
   }        

    /* Table ctransaksiu */

   function view_kas_masuk() {
        $transcode = element('Fina_Kas_Masuk',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.cuid 'id',A.cunotransaksi 'nomor',DATE_FORMAT(A.cutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'  
                     FROM ctransaksiu A 
                LEFT JOIN bkontak B ON A.cukontak=B.kid";
        $search = array('cunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.cusumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_kas_keluar() {
        $transcode = element('Fina_Kas_Keluar',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.cuid 'id',A.cunotransaksi 'nomor',DATE_FORMAT(A.cutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'  
                     FROM ctransaksiu A 
                LEFT JOIN bkontak B ON A.cukontak=B.kid";
        $search = array('cunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.cusumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }        

   function view_bank_masuk() {
        $transcode = element('Fina_Bank_Masuk',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.cuid 'id',A.cunotransaksi 'nomor',DATE_FORMAT(A.cutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'  
                     FROM ctransaksiu A 
                LEFT JOIN bkontak B ON A.cukontak=B.kid";
        $search = array('cunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.cusumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_bank_keluar() {
        $transcode = element('Fina_Bank_Keluar',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.cuid 'id',A.cunotransaksi 'nomor',DATE_FORMAT(A.cutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'  
                     FROM ctransaksiu A 
                LEFT JOIN bkontak B ON A.cukontak=B.kid";
        $search = array('cunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.cusumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }    

   function view_jurnal_umum() {
        $transcode = element('Fina_Jurnal_Umum',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.cuid 'id',A.cunotransaksi 'nomor',DATE_FORMAT(A.cutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'  
                     FROM ctransaksiu A 
                LEFT JOIN bkontak B ON A.cukontak=B.kid";
        $search = array('cunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.cusumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }    
    /* End ctransaksiu */


    /* Table esalesorderu */
   function view_order_pembelian() {
        $transcode = element('PB_Order_Pembelian',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.souid 'id',A.sounotransaksi 'nomor',DATE_FORMAT(A.soutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM esalesorderu A 
                LEFT JOIN bkontak B ON A.soukontak=B.kid";
        $search = array('sounotransaksi','knama');
        $where  = null;         
        $isWhere = "A.sousumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }    

   function view_order_penjualan() {
        $transcode = element('PJ_Order_Penjualan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.souid 'id',A.sounotransaksi 'nomor',DATE_FORMAT(A.soutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM esalesorderu A 
                LEFT JOIN bkontak B ON A.soukontak=B.kid";
        $search = array('sounotransaksi','knama');
        $where  = null;         
        $isWhere = "A.sousumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }        

   function view_perintah_perbaikan() {
        $transcode = element('SV_Perintah_Perbaikan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.souid 'id',A.sounotransaksi 'nomor',DATE_FORMAT(A.soutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM esalesorderu A 
                LEFT JOIN bkontak B ON A.soukontak=B.kid";
        $search = array('sounotransaksi','knama');
        $where  = null;         
        $isWhere = "A.sousumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }            
    /* End esalesorderu */

    /* Table fstoku */
   function view_penerimaan_barang() {
        $transcode = element('PB_Terima_Barang',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid";
        $search = array('sunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.susumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }        

   function view_keluar_barang_pembelian() {
        $transcode = element('PB_Keluar_Barang',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid";
        $search = array('sunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.susumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }            

   function view_surat_jalan() {
        $transcode = element('PJ_Surat_Jalan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid";
        $search = array('sunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.susumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }          

   function view_terima_retur() {
        $transcode = element('PJ_Terima_Retur',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid";
        $search = array('sunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.susumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }          

   function view_mutasi_barang() {
        $transcode = element('STK_Mutasi_Barang',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid";
        $search = array('sunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.susumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }              

   function view_penyesuaian_barang() {
        $transcode = element('STK_Penyesuaian_Barang',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid";
        $search = array('sunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.susumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }                  
   
    /* End fstoku */

    /* Table einvoicepenjualanu */
   function view_faktur_pembelian() {
        $transcode = element('PB_Faktur_Pembelian',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid";
        $search = array('ipunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.ipusumber='".$transcode."' AND A.ipusaldoawal<>'1'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }    

   function view_retur_pembelian() {
        $transcode = element('PB_Retur_Pembelian',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid";
        $search = array('ipunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.ipusumber='".$transcode."' AND A.ipusaldoawal<>'1'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }    

   function view_penjualan_tunai() {
        $transcode = element('PJ_Penjualan_Tunai',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid";
        $search = array('ipunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.ipusumber='".$transcode."' AND A.ipusaldoawal<>'1'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }               


   function view_faktur_penjualan() {
        $transcode = element('PJ_Faktur_Penjualan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid";
        $search = array('ipunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.ipusumber='".$transcode."' AND A.ipusaldoawal<>'1'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }        

   function view_retur_penjualan() {
        $transcode = element('PJ_Retur_Penjualan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid";
        $search = array('ipunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.ipusumber='".$transcode."' AND A.ipusaldoawal<>'1'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }        

    /* End einvoicepenjualanu */

    /* Table epembayaraninvoiceu */
   function view_pembayaran_hutang() {
        $transcode = element('PB_Pembayaran_Hutang',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.piuid 'id',A.piunotransaksi 'nomor',DATE_FORMAT(A.piutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM epembayaraninvoiceu A 
                LEFT JOIN bkontak B ON A.piukontak=B.kid";
        $search = array('piunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.piusumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }    

   function view_pembayaran_piutang() {
        $transcode = element('PJ_Pembayaran_Piutang',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.piuid 'id',A.piunotransaksi 'nomor',DATE_FORMAT(A.piutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM epembayaraninvoiceu A 
                LEFT JOIN bkontak B ON A.piukontak=B.kid";
        $search = array('piunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.piusumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }    
    /* End epembayaraninvoiceu */


    /* Table fstokopnameu */
   function view_stok_opname() {
        $transcode = element('STK_Stok_Opname',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.souid AS 'id',A.sounotransaksi AS 'nomor',DATE_FORMAT(A.soutanggal,'%d-%m-%Y') AS 'tanggal',B.knama AS 'kontak'         
                     FROM fstokopnameu A 
                LEFT JOIN bkontak B ON A.soukontak=B.kid";
        $search = array('sounotransaksi','knama');
        $where  = null;         
        $isWhere = "A.sousumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }            
    /* End fstokopnameu */

   function view_cari_order_pembelian($idkontak) {
        $transcode = element('PB_Order_Pembelian',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.souid 'id',A.sounotransaksi 'nomor',DATE_FORMAT(A.soutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM esalesorderu A 
                LEFT JOIN bkontak B ON A.soukontak=B.kid";
        $search = array('sounotransaksi','knama');
        $where  = null;         
        $isWhere = "A.sousumber='".$transcode."' AND A.soukontak='".$idkontak."' AND A.soustatus IN (1,2)";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }            

   function view_cari_terima_barang($idkontak) {
        $transcode = element('PB_Terima_Barang',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid ";
        $search = array('sunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.susumber='".$transcode."' AND A.sukontak='".$idkontak."' AND A.sustatus IN (1,2,3)";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }        

   function view_cari_terima_barang_r($idkontak) {
        $transcode = element('PB_Terima_Barang',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid ";
        $search = array('sunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.susumber='".$transcode."' AND A.sukontak='".$idkontak."' AND A.sustatus IN (1,2,3)";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }        

   function view_cari_faktur_pembelian($idkontak) {
        $transcode_saldoawal = $this->M_transaksi->prefixtrans(element('Saldo_Awal_Tagihan',NID));
        $transcode = $this->M_transaksi->prefixtrans(element('PB_Faktur_Pembelian',NID));    
        $query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid";
        $search = array('ipunotransaksi','knama');
        $where  = null; 
        $isWhere = "A.ipusumber IN('".$transcode."','".$transcode_saldoawal."') AND A.ipukontak='".$idkontak."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }            

   function view_cari_faktur_pembelian_dp($idkontak) {
        $transcode_saldoawal = $this->M_transaksi->prefixtrans(element('Saldo_Awal_Tagihan',NID));    
        $transcode_faktur = $this->M_transaksi->prefixtrans(element('PB_Faktur_Pembelian',NID));        
        $transcode_dp = $this->M_transaksi->prefixtrans(element('PB_Uang_Muka_Pembelian',NID));            
        $query  = "SELECT * FROM(
                   SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak'        
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid 
                    WHERE A.ipusumber IN('".$transcode_faktur."','".$transcode_saldoawal."') AND A.ipukontak='".$idkontak."' 
                      AND (A.iputotaltransaksi-A.ipujumlahdp-A.iputotalbayar)>0 
                    UNION 
                   SELECT A.dpid 'id',A.dpnotransaksi 'nomor',DATE_FORMAT(A.dptanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak' 
                     FROM ddp A 
                LEFT JOIN bkontak B ON A.dpkontak=B.kid
                    WHERE A.dpsumber='".$transcode_dp."' AND A.dpkontak='".$idkontak."' AND (A.dpjumlah-A.dpjumlahbayar)>0 
                ) T ";
        $search = array('nomor','kontak');
        $where  = null;
        $isWhere = "";

        if($this->input->post('param') != '' && !empty($this->input->post('param'))) {
            $isWhere = " T.nomor NOT IN (".$this->input->post('param').")";
        }

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }            

   function view_cari_faktur_penjualan_dp($idkontak) {
        $transcode_saldoawal = $this->M_transaksi->prefixtrans(element('Saldo_Awal_Tagihan',NID));                
        $transcode_faktur = $this->M_transaksi->prefixtrans(element('PJ_Faktur_Penjualan',NID));        
        $transcode_dp = $this->M_transaksi->prefixtrans(element('PJ_Uang_Muka_Penjualan',NID));            
        $query  = "SELECT * FROM(
                   SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak'        
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid 
                    WHERE A.ipusumber IN('".$transcode_faktur."','".$transcode_saldoawal."') AND A.ipukontak='".$idkontak."' 
                      AND (A.iputotaltransaksi-A.ipujumlahdp-A.iputotalbayar)>0 
                    UNION 
                   SELECT A.dpid 'id',A.dpnotransaksi 'nomor',DATE_FORMAT(A.dptanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak' 
                     FROM ddp A 
                LEFT JOIN bkontak B ON A.dpkontak=B.kid
                    WHERE A.dpsumber='".$transcode_dp."' AND A.dpkontak='".$idkontak."' AND (A.dpjumlah-A.dpjumlahbayar)>0 
                ) T ";
        $search = array('nomor','kontak');
        $where  = null;
        $isWhere = "";

        if($this->input->post('param') != '' && !empty($this->input->post('param'))) {
            $isWhere = " T.nomor NOT IN (".$this->input->post('param').")";
        }

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_cari_faktur_penjualan($idkontak) {
        $transcode_saldoawal = $this->M_transaksi->prefixtrans(element('Saldo_Awal_Tagihan',NID));            
        $transcode_faktur = $this->M_transaksi->prefixtrans(element('PJ_Faktur_Penjualan',NID));        
        $query  = "SELECT * FROM(
                   SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak'        
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid 
                    WHERE A.ipusumber IN('".$transcode_faktur."','".$transcode_saldoawal."') AND A.ipukontak='".$idkontak."'
                ) T ";
        $search = array('nomor','kontak');
        $where  = null;         
        $isWhere = "";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }                

   function view_cari_retur_pembelian($idkontak) {
        $transcode = element('PB_Retur_Pembelian',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid";
        $search = array('ipunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.ipusumber='".$transcode."' AND A.ipukontak='".$idkontak."'";

        if($this->input->post('param') != '' && !empty($this->input->post('param'))) {
            $isWhere = $isWhere." AND A.ipunotransaksi NOT IN (".$this->input->post('param').")";
        }

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }            

   function view_cari_retur_penjualan($idkontak) {
        $transcode = element('PJ_Retur_Penjualan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid";
        $search = array('ipunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.ipusumber='".$transcode."' AND A.ipukontak='".$idkontak."'";

        if($this->input->post('param') != '' && !empty($this->input->post('param'))) {
            $isWhere = $isWhere." AND A.ipunotransaksi NOT IN (".$this->input->post('param').")";
        }

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }                

   function view_cari_order_penjualan($idkontak) {
        $transcode = element('PJ_Order_Penjualan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.souid AS 'id',A.sounotransaksi AS 'nomor',DATE_FORMAT(A.soutanggal,'%d-%m-%Y') AS 'tanggal',B.knama AS 'kontak'         
                     FROM esalesorderu A 
                LEFT JOIN bkontak B ON A.soukontak=B.kid";
        $search = array('sounotransaksi','knama');
        $where  = null;         
        $isWhere = "A.sousumber='".$transcode."' AND A.soukontak='".$idkontak."' AND A.soustatus IN (1,2)";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }            

   function view_cari_surat_jalan($idkontak) {
        $transcode = element('PJ_Surat_Jalan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid ";
        $search = array('sunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.susumber='".$transcode."' AND A.sukontak='".$idkontak."' AND A.sustatus IN (1,2)";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }        

   function view_cari_surat_jalan_r($idkontak) {
        $transcode = element('PJ_Surat_Jalan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid ";
        $search = array('sunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.susumber='".$transcode."' AND A.sukontak='".$idkontak."' AND A.sustatus IN (1,2,3)";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }        

   function view_cari_terima_barang_new($idkontak) {
        $transcode = element('PB_Terima_Barang',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid 
                INNER JOIN fstokd C ON A.suid=C.sdidsu AND C.sdmasuk-C.sdfaktur > 0 ";
        $search = array('sunotransaksi','knama');
        $where  = null;         
        $isWhere = " A.susumber='".$transcode."' AND A.sukontak='".$idkontak."' ";

        if(!empty($_POST['param'])) {
            $isWhere = $isWhere. " AND A.suid NOT IN (".$this->input->post('param').") ";        
        }

        $isGroup = " A.suid ";

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query_group($query,$search,$where,$isWhere,$isGroup);
    }        

   function view_cari_surat_jalan_new($idkontak) {
        $transcode = element('PJ_Surat_Jalan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid 
                INNER JOIN fstokd C ON A.suid=C.sdidsu AND C.sdkeluar-C.sdfaktur > 0 ";
        $search = array('sunotransaksi','knama');
        $where  = null;         
        $isWhere = " A.susumber='".$transcode."' AND A.sukontak='".$idkontak."' ";

        if(!empty($_POST['param'])) {
            $isWhere = $isWhere. " AND A.suid NOT IN (".$this->input->post('param').") ";        
        }

        $isGroup = " A.suid ";

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query_group($query,$search,$where,$isWhere,$isGroup);
    }        

   function view_cari_order_pembelian_new($idkontak) {
        $transcode = element('PB_Order_Pembelian',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.souid 'id',A.sounotransaksi 'nomor',DATE_FORMAT(A.soutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM esalesorderu A 
                LEFT JOIN bkontak B ON A.soukontak=B.kid 
               INNER JOIN esalesorderd C ON A.souid=C.sodidsou AND C.sodorder-C.sodmasuk > 0                 
                ";
        $search = array('sounotransaksi','knama');
        $where  = null;         
        $isWhere = "A.sousumber='".$transcode."' AND A.soukontak='".$idkontak."' ";

        if(!empty($_POST['param'])) {
            $isWhere = $isWhere. " AND A.souid NOT IN (".$this->input->post('param').") ";        
        }

        $isGroup = " A.souid ";

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query_group($query,$search,$where,$isWhere,$isGroup);
    }                


   function view_cari_order_penjualan_new($idkontak) {
        $transcode = element('PJ_Order_Penjualan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.souid 'id',A.sounotransaksi 'nomor',DATE_FORMAT(A.soutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM esalesorderu A 
                LEFT JOIN bkontak B ON A.soukontak=B.kid 
               INNER JOIN esalesorderd C ON A.souid=C.sodidsou AND C.sodorder-C.sodkeluar > 0                 
                ";
        $search = array('sounotransaksi','knama');
        $where  = null;         
        $isWhere = "A.sousumber='".$transcode."' AND A.soukontak='".$idkontak."' ";

        if(!empty($_POST['param'])) {
            $isWhere = $isWhere. " AND A.souid NOT IN (".$this->input->post('param').") ";        
        }

        $isGroup = " A.souid ";

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query_group($query,$search,$where,$isWhere,$isGroup);
    }                    

   function view_cari_uang_muka_pembelian($idkontak) {
        $transcode = element('PB_Uang_Muka_Pembelian',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.dpid 'id',A.dpnotransaksi 'nomor',DATE_FORMAT(A.dptanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak',
                          A.dpjumlah 'jumlah',A.dpketerangan 'uraian'
                     FROM ddp A 
                LEFT JOIN bkontak B ON A.dpkontak=B.kid 
                   ";
        $search = array('dpnotransaksi','dpketerangan');
        $where  = null;         
        $isWhere = "A.dpsumber='".$transcode."' AND A.dpkontak='".$idkontak."' AND A.dpjumlah-A.dppakaiiv > 0";


        if($this->input->post('param') != '' && !empty($this->input->post('param'))) {
            $isWhere = $isWhere. " AND A.dpnotransaksi NOT IN (".$this->input->post('param').")";
        }

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }            

   function view_uang_muka_pembelian() {
        $transcode = element('PB_Uang_Muka_Pembelian',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.dpid 'id',A.dpnotransaksi 'nomor',DATE_FORMAT(A.dptanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'  
                     FROM ddp A 
                LEFT JOIN bkontak B ON A.dpkontak=B.kid";
        $search = array('dpnotransaksi','knama');
        $where  = null;         
        $isWhere = "A.dpsumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

  function view_cari_uang_muka_penjualan($idkontak) {
        $transcode = element('PJ_Uang_Muka_Penjualan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.dpid 'id',A.dpnotransaksi 'nomor',DATE_FORMAT(A.dptanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak',
                          A.dpjumlah 'jumlah',A.dpketerangan 'uraian'
                     FROM ddp A 
                LEFT JOIN bkontak B ON A.dpkontak=B.kid 
                   ";
        $search = array('dpnotransaksi','dpketerangan');
        $where  = null;         
        $isWhere = "A.dpsumber='".$transcode."' AND A.dpkontak='".$idkontak."' AND A.dpjumlah-A.dppakaiiv > 0";

        if($this->input->post('param') != '' && !empty($this->input->post('param'))) {
            $isWhere = $isWhere. " AND A.dpnotransaksi NOT IN (".$this->input->post('param').")";
        }

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }            

   function view_uang_muka_penjualan() {
        $transcode = element('PJ_Uang_Muka_Penjualan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.dpid 'id',A.dpnotransaksi 'nomor',DATE_FORMAT(A.dptanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'  
                     FROM ddp A 
                LEFT JOIN bkontak B ON A.dpkontak=B.kid";
        $search = array('dpnotransaksi','knama');
        $where  = null;         
        $isWhere = "A.dpsumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_notif_ar() {
        $transcode = $this->M_transaksi->prefixtrans(element('PJ_Faktur_Penjualan',NID));            
        $query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',
                          DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak',
                          A.iputotaltransaksi 'total', 
                          DATE_FORMAT(DATE_ADD(A.iputanggal, INTERVAL C.ttempo DAY),'%d-%m-%Y') 'jatuhtempo'         
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid
                LEFT JOIN btermin C ON A.iputermin=C.tid";
        $search = array('ipunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.ipusumber='".$transcode."' AND DATE_ADD(A.iputanggal, INTERVAL C.ttempo DAY) <= CURDATE() 
                     AND A.iputotalbayar<A.iputotaltransaksi";

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }        

   function view_notif_ap() {
        $transcode = $this->M_transaksi->prefixtrans(element('PB_Faktur_Pembelian',NID));            
        $query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',
                          DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak',
                          A.iputotaltransaksi 'total', 
                          DATE_FORMAT(DATE_ADD(A.iputanggal, INTERVAL C.ttempo DAY),'%d-%m-%Y') 'jatuhtempo'         
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid
                LEFT JOIN btermin C ON A.iputermin=C.tid";
        $search = array('ipunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.ipusumber='".$transcode."' AND DATE_ADD(A.iputanggal, INTERVAL C.ttempo DAY) <= CURDATE() 
                     AND A.iputotalbayar<A.iputotaltransaksi";

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }            

   function view_notif_expired() {
        $query  = "SELECT A.iid 'id',A.ikode 'sku',DATE_FORMAT(A.itanggal1,'%d-%m-%Y') 'expired',
                          A.inama 'nama', A.istocktotal 'qty'  
                     FROM bitem A";
        $search = array('ikode','inama');
        $where  = null;         
        $isWhere = " A.itanggal1 <= CURDATE() AND A.ijenisitem=0 AND A.istatus=0";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_notif_minimum() {
        $query  = "SELECT A.iid 'id',A.ikode 'sku',DATE_FORMAT(A.itanggal1,'%d-%m-%Y') 'expired',
                          A.inama 'nama', A.istocktotal 'qty', A.istockminimal 'min'  
                     FROM bitem A";
        $search = array('ikode','inama');
        $where  = null;         
        $isWhere = " A.istocktotal <= A.istockminimal AND A.ijenisitem=0 AND A.istatus=0";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }    

   function view_notif_orderpembelian() {
        $transcode = $this->M_transaksi->prefixtrans(element('PB_Order_Pembelian',NID));                    
        $query  = "SELECT A.souid 'id', A.sounotransaksi 'nomor', C.knama 'kontak',                    
                          D.ikode 'sku', D.inama 'nama', B.sodorder 'qtyorder', (B.sodmasuk-B.sodkeluar) 'qtyterima', (B.sodorder-B.sodmasuk-B.sodkeluar) 'qtysisa'
                     FROM esalesorderu A
                LEFT JOIN esalesorderd B ON A.souid=B.sodidsou     
                LEFT JOIN bkontak C ON A.soukontak=C.kid
                LEFT JOIN bitem D ON B.soditem=D.iid
                    ";
        $search = array('sounotransaksi','knama','inama');
        $where  = null;         
        $isWhere = " A.sousumber='".$transcode."' 
                      AND B.sodorder-B.sodmasuk-B.sodkeluar > 0";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }    

}