<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Item extends CI_Controller {

    function __construct() { 
        parent::__construct();
        if(!$this->session->has_userdata('nama')) redirect(base_url('exception'));
        $this->load->model('M_Master_Item');
        $this->load->model('M_transaksi');
    }

    function savedata(){
        if($this->input->post('id')==''){
          echo $this->M_Master_Item->tambahData();
        }else{
          echo $this->M_Master_Item->ubahData();      
        }
    }

    function deletedata(){
        echo $this->M_Master_Item->hapusData();          
    }

    function getdefaultcoa(){
        $query = "SELECT A.cccoa 'idcoa', B.cnama 'coa'
                    FROM cconfigcoa A
               LEFT JOIN bcoa B ON A.cccoa=B.cid
                   WHERE A.cckode = '".$this->input->post('id')."'";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);    
    }

    function getmultisatuan(){
        $query = "SELECT A.isatuan 'isatuan'
                    FROM ainfo A ORDER BY iid ASC LIMIT 1";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);    
    }    

    function getdata(){
        if($this->input->post('id') == '' || $this->input->post('id') == null) {
          echo _pesanError("Data tidak ditemukan !");
          exit;
        }

        $query = "SELECT A.iid 'id', A.ikode 'kode', A.inama 'nama', A.inama2 'alias', A.iserial 'serial', A.imerk 'merk', A.isatuan 'idsatuan', 
                         B.skode 'satuan', A.isatuand 'idsatuand', A.ibolehminus 'bolehminus',
                         C.skode 'satuand', A.ijenisitem 'jenis', A.itipeitem 'tipe', A.istatus 'status',
                         M.utnama 'unit', M.utid 'idunit',
                         IFNULL(A.istockminimal,0) 'stokmin', IFNULL(A.istockmaksimal,0) 'stokmaks', 
                         CASE WHEN A.ijenisitem=0 THEN IFNULL(A.istocktotal,0) 
                              WHEN A.ijenisitem=1 THEN 0
                              ELSE IFNULL(A.istocktotal,0)
                         END AS 'stoktotal', 
                         IFNULL(A.istockreorder,0) 'reorder',
                         IFNULL(A.ihargabeli,0) 'hargabeli', IFNULL(A.ihargajual1,0) 'hargajual1',
                         IFNULL(A.ihargajual2,0) 'hargajual2', IFNULL(A.ihargajual3,0) 'hargajual3',
                         IFNULL(A.ihargajual4,0) 'hargajual4', A.icoapersediaan 'idcoapersediaan',
                         A.icoapendapatan 'idcoapendapatan', A.icoahpp 'idcoahpp',
                         D.cnama 'coapersediaan',E.cnama 'coapendapatan',F.cnama 'coahpp',
                         A.ibarcode 'barcode', 
                         A.isatuan2 'idsatuan2', G.skode 'satuan2',
                         A.isatuan3 'idsatuan3', H.skode 'satuan3',
                         A.isatuan4 'idsatuan4', I.skode 'satuan4',
                         A.isatuan5 'idsatuan5', J.skode 'satuan5',
                         A.isatuan6 'idsatuan6', K.skode 'satuan6',                                                  
                         IFNULL(A.isatkonversi2,0) 'konversi2', IFNULL(A.isatkonversi3,0) 'konversi3',
                         IFNULL(A.isatkonversi4,0) 'konversi4', IFNULL(A.isatkonversi5,0) 'konversi5',
                         IFNULL(A.isatkonversi6,0) 'konversi6',
                         IFNULL(A.isat2hargajual1,0) 'sat2hargajual1',
                         IFNULL(A.isat2hargajual2,0) 'sat2hargajual2',
                         IFNULL(A.isat2hargajual3,0) 'sat2hargajual3',
                         IFNULL(A.isat2hargajual4,0) 'sat2hargajual4',
                         IFNULL(A.isat3hargajual1,0) 'sat3hargajual1',
                         IFNULL(A.isat3hargajual2,0) 'sat3hargajual2',
                         IFNULL(A.isat3hargajual3,0) 'sat3hargajual3',
                         IFNULL(A.isat3hargajual4,0) 'sat3hargajual4',                         
                         IFNULL(A.isat4hargajual1,0) 'sat4hargajual1',
                         IFNULL(A.isat4hargajual2,0) 'sat4hargajual2',
                         IFNULL(A.isat4hargajual3,0) 'sat4hargajual3',
                         IFNULL(A.isat4hargajual4,0) 'sat4hargajual4',
                         IFNULL(A.isat5hargajual1,0) 'sat5hargajual1',
                         IFNULL(A.isat5hargajual2,0) 'sat5hargajual2',
                         IFNULL(A.isat5hargajual3,0) 'sat5hargajual3',
                         IFNULL(A.isat5hargajual4,0) 'sat5hargajual4',
                         IFNULL(A.isat6hargajual1,0) 'sat6hargajual1',
                         IFNULL(A.isat6hargajual2,0) 'sat6hargajual2',
                         IFNULL(A.isat6hargajual3,0) 'sat6hargajual3',
                         IFNULL(A.isat6hargajual4,0) 'sat6hargajual4',
                         A.ikategori 'idkategori', L.iknama 'kategori',
                         DATE_FORMAT(A.itanggal1,'%d-%m-%Y') 'expired',
                         A.icustom1 'gambar1'                                  
                    FROM bitem A
               LEFT JOIN bsatuan B ON A.isatuan=B.sid  
               LEFT JOIN bsatuan C ON A.isatuand = C.sid
               LEFT JOIN bcoa D ON A.icoapersediaan=D.cid 
               LEFT JOIN bcoa E ON A.icoapendapatan=E.cid
               LEFT JOIN bcoa F ON A.icoahpp=F.cid
               LEFT JOIN bsatuan G ON A.isatuan2 = G.sid               
               LEFT JOIN bsatuan H ON A.isatuan3 = H.sid                              
               LEFT JOIN bsatuan I ON A.isatuan4 = I.sid                              
               LEFT JOIN bsatuan J ON A.isatuan5 = J.sid
               LEFT JOIN bsatuan K ON A.isatuan6 = K.sid                                      
               LEFT JOIN bitemkategori L ON A.ikategori = L.ikid
               LEFT JOIN aunit M ON A.iunit = M.utid
                   WHERE A.iid='".$this->input->post('id')."'";

        $transcode = $this->M_transaksi->prefixtrans(element('Saldo_Awal_Persediaan',NID));                
        $query2 = "SELECT H.sunotransaksi 'nomorsa', DATE_FORMAT(H.sutanggal,'%d-%m-%Y') 'tglsa',
                          G.sdgudang 'idgudangsa', I.gnama 'gudangsa', G.sdharga 'hargasa', G.sdmasuk 'qtysa',
                          H.sukontak 'idkontaksa',J.knama 'kontaksa'
                     FROM fstokd G 
               INNER JOIN fstoku H ON H.suid=G.sdidsu AND H.susumber='".$transcode."' 
                LEFT JOIN bgudang I ON G.sdgudang=I.gid 
                LEFT JOIN bkontak J ON H.sukontak=J.kid
                    WHERE G.sditem = '".$this->input->post('id')."' 
                  ";

       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query_second($query,$query2);        
    }

    function getdataalt(){
        if($this->input->post('id') == '' || $this->input->post('id') == null) {
          echo _pesanError("Data tidak ditemukan !");
          exit;
        }

        $query = "SELECT COUNT(*) 'row', A.inama 'nama', A.inama2 'alias', 
                         IFNULL(A.ihargajual1,0) 'hargajual1',
                         IFNULL(A.ihargajual2,0) 'hargajual2', 
                         IFNULL(A.ihargajual3,0) 'hargajual3',
                         IFNULL(A.ihargajual4,0) 'hargajual4',        
                         IFNULL(A.istocktotal,0) 'stok',                                 
                         A.icustom1 'gambar1',
                         B.skode 'satuan'                                  
                    FROM bitem A 
               LEFT JOIN bsatuan B ON A.isatuan=B.sid                      
                   WHERE A.ibarcode = '".$this->input->post('id')."'";

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);        

    }

    function getItemExpired(){
        $query = "SELECT COUNT(A.iid) 'row'                                 
                    FROM bitem A 
                   WHERE A.itanggal1 <= CURDATE() AND A.ijenisitem=0 AND A.istatus=0";

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);        

    }    

    function getItemMinStok(){
        $query = "SELECT COUNT(A.iid) 'row'                                 
                    FROM bitem A 
                   WHERE A.istocktotal <= A.istockminimal AND A.ijenisitem=0 AND A.istatus=0";

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);        

    }    

    function getsalesqty(){
        $transcode = $this->M_transaksi->prefixtrans(element('PJ_Penjualan_Tunai',NID));        
        $transcode2 = $this->M_transaksi->prefixtrans(element('PJ_Faktur_Penjualan',NID));
        $transcode3 = $this->M_transaksi->prefixtrans(element('SV_Penjualan_Tunai',NID));                      
        $query = "    SELECT IFNULL(SUM(A.ipdkeluar),0) 'qty', 'day' AS 'keterangan' 
                        FROM einvoicepenjualand A INNER JOIN einvoicepenjualanu B ON A.ipdidipu=B.ipuid
                       WHERE B.ipusumber IN ('".$transcode."','".$transcode2."','".$transcode3."') AND B.iputanggal=CURDATE()
                       UNION 
                      SELECT IFNULL(SUM(A.ipdkeluar),0) 'qty', 'month' AS 'keterangan' 
                        FROM einvoicepenjualand A INNER JOIN einvoicepenjualanu B ON A.ipdidipu=B.ipuid
                       WHERE B.ipusumber IN ('".$transcode."','".$transcode2."','".$transcode3."') AND MONTH(B.iputanggal)=MONTH(CURDATE())
                      UNION 
                      SELECT IFNULL(SUM(A.ipdkeluar),0) 'qty', 'year' AS 'keterangan' 
                        FROM einvoicepenjualand A INNER JOIN einvoicepenjualanu B ON A.ipdidipu=B.ipuid
                       WHERE B.ipusumber IN ('".$transcode."','".$transcode2."','".$transcode3."') AND YEAR(B.iputanggal)=YEAR(CURDATE())
                  ";

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);        

    }    


    function getomset(){
        $query = "    SELECT IFNULL(SUM(B.cdkredit-B.cddebit),0) 'omset', 'day' AS 'keterangan'
                        FROM ctransaksiu A 
                  INNER JOIN ctransaksid B ON A.cuid=B.cdidu 
                  INNER JOIN bcoa C ON B.cdnocoa=C.cid 
                       WHERE A.cutanggal=CURDATE() AND C.ctipe IN (11,14)
                       UNION 
                      SELECT IFNULL(SUM(B.cdkredit-B.cddebit),0) 'omset', 'month' AS 'keterangan'
                        FROM ctransaksiu A 
                  INNER JOIN ctransaksid B ON A.cuid=B.cdidu 
                  INNER JOIN bcoa C ON B.cdnocoa=C.cid 
                       WHERE MONTH(A.cutanggal)=MONTH(CURDATE()) AND C.ctipe IN (11,14)                       
                       UNION 
                      SELECT IFNULL(SUM(B.cdkredit-B.cddebit),0) 'omset', 'year' AS 'keterangan'
                        FROM ctransaksiu A 
                  INNER JOIN ctransaksid B ON A.cuid=B.cdidu 
                  INNER JOIN bcoa C ON B.cdnocoa=C.cid 
                       WHERE YEAR(A.cutanggal)=YEAR(CURDATE()) AND C.ctipe IN (11,14)                       
                  ";

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);        

    }    

    function getlaba(){
        $query = "    SELECT IFNULL(SUM(B.cdkredit-B.cddebit),0) 'laba', 'day' AS 'keterangan'
                        FROM ctransaksiu A 
                  INNER JOIN ctransaksid B ON A.cuid=B.cdidu 
                  INNER JOIN bcoa C ON B.cdnocoa=C.cid 
                       WHERE A.cutanggal=CURDATE() AND C.ctipe > 10
                       UNION 
                      SELECT IFNULL(SUM(B.cdkredit-B.cddebit),0) 'laba', 'month' AS 'keterangan'
                        FROM ctransaksiu A 
                  INNER JOIN ctransaksid B ON A.cuid=B.cdidu 
                  INNER JOIN bcoa C ON B.cdnocoa=C.cid 
                       WHERE MONTH(A.cutanggal)=MONTH(CURDATE()) AND C.ctipe > 10                       
                       UNION 
                      SELECT IFNULL(SUM(B.cdkredit-B.cddebit),0) 'laba', 'year' AS 'keterangan'
                        FROM ctransaksiu A 
                  INNER JOIN ctransaksid B ON A.cuid=B.cdidu 
                  INNER JOIN bcoa C ON B.cdnocoa=C.cid 
                       WHERE YEAR(A.cutanggal)=YEAR(CURDATE()) AND C.ctipe > 10                       
                  ";

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);        

    }    

    function getbiaya(){
        $query = "    SELECT IFNULL(SUM(B.cddebit-B.cdkredit),0) 'biaya', 'day' AS 'keterangan'
                        FROM ctransaksiu A 
                  INNER JOIN ctransaksid B ON A.cuid=B.cdidu 
                  INNER JOIN bcoa C ON B.cdnocoa=C.cid 
                       WHERE A.cutanggal=CURDATE() AND C.ctipe IN (12,13,15)
                       UNION 
                      SELECT IFNULL(SUM(B.cddebit-B.cdkredit),0) 'biaya', 'month' AS 'keterangan'
                        FROM ctransaksiu A 
                  INNER JOIN ctransaksid B ON A.cuid=B.cdidu 
                  INNER JOIN bcoa C ON B.cdnocoa=C.cid 
                       WHERE MONTH(A.cutanggal)=MONTH(CURDATE()) AND C.ctipe IN (12,13,15)
                       UNION 
                      SELECT IFNULL(SUM(B.cddebit-B.cdkredit),0) 'biaya', 'year' AS 'keterangan'
                        FROM ctransaksiu A 
                  INNER JOIN ctransaksid B ON A.cuid=B.cdidu 
                  INNER JOIN bcoa C ON B.cdnocoa=C.cid 
                       WHERE YEAR(A.cutanggal)=YEAR(CURDATE()) AND C.ctipe IN (12,13,15)
                  ";

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);        

    }    

}