<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_PB_Faktur_Pembelian extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahTransaksi(){
        $id = $_POST['id'];

        //Update Header Trans
        $data_header = array(
                        'ipusumber' => $this->M_transaksi->prefixtrans(element('PB_Faktur_Pembelian',NID)),
                        'ipunotransaksi' => $_POST['nomor'],
                        'iputanggal' => tgl_database($_POST['tgl']),
                        'ipukontak' => $_POST['kontak'],
                        'ipuuraian' => $_POST['uraian'],
                        'ipukaryawan' => $_POST['karyawan'],
                        'ipunobkg' => $_POST['refnomor'],
                        'iputermin' => $_POST['termin'], 
                        'ipualamat' => $_POST['alamat'],
                        'ipuattention' => $_POST['person'],   
                        'ipujenispajak' => $_POST['pajak'],
                        'ipustatus' => $_POST['status'], 
                        'ipugudang' => $_POST['gudang'], 
                        'ipunofakturpajak' => $_POST['nopajak'], 
                        'iputglpajak' => tgl_database($_POST['tglpajak']),                         
                        'ipucatatan' => $_POST['memo'],                        
                        'iputotalpajak' => $_POST['totalpajak'],
                        'iputotalpph22' => $_POST['totalpph22'],                        
                        'ipujumlahdp' => $_POST['totaldp'],
                        'iputotaltransaksi' => $_POST['totaltrans'], 
                        'ipumodifu' => $this->session->id                
        );        
        $this->db->trans_start();

        $sql="CALL SP_JURNAL_INVOICE_PEMBELIAN_DEL(".$id.")";
        $this->db->query($sql);

        $this->db->where('ipuid', $id);
        $this->db->update('einvoicepenjualanu',$data_header);

        //Delete Old Detil Trans
        $this->db->where('ipdidipu', $id);
        $this->db->delete('einvoicepenjualand');
        $this->db->where('idpidu', $id);
        $this->db->delete('einvoicepenjualandp');        

        // Insert Detil Trans
        $r=1;
        $d = json_decode($_POST['detil']);
        foreach($d as $item){
            $data_detil = array(
                    'ipdidipu' => $id,
                    'ipdurutan' => $r,
                    'ipditem' => $item->item,
                    'ipdmasuk' => $item->qty,
                    'ipdmasukd' => $item->qty,                    
                    'ipdharga' => $item->harga,
                    'ipddiskon' => $item->diskon,
                    'ipdsatuan' => $item->satuan,
                    'ipdsatuand' => $item->satuan,
                    'ipddiskonp' => $item->persen,
                    'ipdbtgu' => $item->noref,
                    'ipdbtgd' => $item->noref2,                                                                                
                    'ipdcatatan' => $item->catatan
            );
            $this->db->insert('einvoicepenjualand',$data_detil);                        
            $r++;
        }

        // INSERT DP INVOICE
        $d = json_decode($_POST['detildp']);
        foreach($d as $item){
            $dpfaktur = array(
                    "idpidu" => $id,
                    "idpiddp" => $item->iddp,
                    "idpjumlahdp" => $item->jumlah                    
            );
            $this->db->insert('einvoicepenjualandp',$dpfaktur);                                        
        }            

        $sql="CALL SP_JURNAL_INVOICE_PEMBELIAN_ADD(".$id.")";
        $this->db->query($sql);


        // USERLOG
        $uactivity = _anomor(element('PB_Faktur_Pembelian',NID));
        $uactivity = $uactivity['keterangan'];
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$this->input->post('nomor'),
            'ullevel'=> 2                                    
        );
        $this->db->insert('auserlog',$userlog);                       

        $this->db->trans_complete();            

        if($this->db->trans_status() === FALSE){
            $callback = array(    
                'pesan'=>'rollback',
                'nomor'=>$id
            );
            return json_encode($callback);            
        } else {
            $callback = array(    
                'pesan'=>'sukses',
                'nomor'=>$id
            );
            return json_encode($callback);            
        }

    }

    function tambahTransaksi()
    {
        if(empty($_POST['nomor'])){
            $nomor = $this->autonumber($_POST['tgl']);
        }else{
            $nomor = $_POST['nomor'];
        }        

        // Insert Header Trans
        $data_header = array(
                        'ipusumber' => $this->M_transaksi->prefixtrans(element('PB_Faktur_Pembelian',NID)),
                        'ipunotransaksi' => $nomor,
                        'iputanggal' => tgl_database($_POST['tgl']),
                        'ipukontak' => $_POST['kontak'],
                        'ipuuraian' => $_POST['uraian'],
                        'ipukaryawan' => $_POST['karyawan'],
                        'ipunobkg' => $_POST['refnomor'],
                        'iputermin' => $_POST['termin'], 
                        'ipualamat' => $_POST['alamat'],
                        'ipuattention' => $_POST['person'],   
                        'ipujenispajak' => $_POST['pajak'],
                        'ipustatus' => 1, 
                        'ipugudang' => $_POST['gudang'], 
                        'ipunofakturpajak' => $_POST['nopajak'], 
                        'iputglpajak' => tgl_database($_POST['tglpajak']),                         
                        'ipucatatan' => $_POST['memo'],                        
                        'iputotalpajak' => $_POST['totalpajak'],
                        'iputotalpph22' => $_POST['totalpph22'],                                                
                        'ipujumlahdp' => $_POST['totaldp'],
                        'iputotaltransaksi' => $_POST['totaltrans'], 
                        'ipucreateu' => $this->session->id                
        );        
        $this->db->trans_start();
        $this->db->insert('einvoicepenjualanu',$data_header);
        $id = $this->db->insert_id();

        // Insert Detil Trans
        $r=1;
        $d = json_decode($_POST['detil']);
        foreach($d as $item){
            // cari satuan dasar dan hitung qty satuan dasar
            $dataitem = _getitemdata($item->item);
            $satuand = $dataitem['isatuan'];
            $satuan2 = $dataitem['isatuan2'];
            $satuan3 = $dataitem['isatuan3'];
            $satuan4 = $dataitem['isatuan4'];
            $konversi2 = $dataitem['isatkonversi2'];
            $konversi3 = $dataitem['isatkonversi3'];            
            $konversi4 = $dataitem['isatkonversi4'];

            if($item->satuan == $satuan2){
                $ipdmasukd = $item->qty*$konversi2;
            }elseif ($item->satuan == $satuan3) {
                $ipdmasukd = $item->qty*$konversi3;
            }elseif ($item->satuan == $satuan4) {
                $ipdmasukd = $item->qty*$konversi4;
            }else{
                $ipdmasukd = $item->qty;
            }

            $data_detil = array(
                    'ipdidipu' => $id,
                    'ipdurutan' => $r,
                    'ipditem' => $item->item,
                    'ipdmasuk' => $item->qty,
                    'ipdmasukd' => $ipdmasukd,                    
                    'ipdharga' => $item->harga,
                    'ipddiskon' => $item->diskon,
                    'ipdsatuan' => $item->satuan,
                    'ipdsatuand' => $satuand,
                    'ipddiskonp' => $item->persen,
                    'ipdbtgu' => $item->noref,
                    'ipdbtgd' => $item->noref2,                                                            
                    'ipdcatatan' => $item->catatan
            );
            $this->db->insert('einvoicepenjualand',$data_detil);                        

            /*
            $data_hargabeli = array(
                    'ihargabeli' => $item->harga
            );
            $this->db->where('iid', $item->item);
            $this->db->update('bitem',$data_hargabeli);
            */
            
            $r++;
        }

        // INSERT DP INVOICE
        $d = json_decode($_POST['detildp']);
        foreach($d as $item){
            $dpfaktur = array(
                    "idpidu" => $id,
                    "idpiddp" => $item->iddp,
                    "idpjumlahdp" => $item->jumlah                    
            );
            $this->db->insert('einvoicepenjualandp',$dpfaktur);                                        
        }            

        $sql="CALL SP_JURNAL_INVOICE_PEMBELIAN_ADD(".$id.")";
        $this->db->query($sql);

        // USERLOG
        $uactivity = _anomor(element('PB_Faktur_Pembelian',NID));
        $uactivity = $uactivity['keterangan'];
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$nomor,
            'ullevel'=> 1                                    
        );
        $this->db->insert('auserlog',$userlog);                       

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            $callback = array(    
                'pesan'=>'rollback',
                'nomor'=>''
            );
            return json_encode($callback);            
        } else {
            $callback = array(    
                'pesan'=>'sukses',
                'nomor'=>$id
            );
            return json_encode($callback);            
        }

    }

    function hapusTransaksi(){

        $id = $_POST['id'];
        $nomor = $_POST['nomor'];        

        $this->db->trans_start();

        $sql="CALL SP_JURNAL_INVOICE_PEMBELIAN_DEL(".$id.")";
        $this->db->query($sql);

        $this->db->where('ipdidipu', $id);
        $hasil = $this->db->get('einvoicepenjualand');
        foreach ($hasil->result() as $row) {
            $sdid = $row->IPDBTGD;
            $query = "SELECT count(*) 'jml' FROM einvoicepenjualand WHERE ipdbtgd='".$sdid."'";
            $harga = $this->db->query($query);
            foreach ($harga->result() as $rh) {
                if($rh->jml > 1){
                } else {
                    $this->db->where('sdid', $sdid);
                    $this->db->update('fstokd', array('SDHARGAINVOICE' => 0, 'SDDISKONINVOICE' => 0));
                }
            }
        }

        //hapus Header Transaksi
        $this->db->where('ipuid', $id);
        $this->db->delete('einvoicepenjualanu');
        //hapus Detil Transaksi
        $this->db->where('ipdidipu', $id);
        $this->db->delete('einvoicepenjualand');

        //Update harga invoice di fstokd = 0 Jika Tidak ada lagi yang sudah difaktur

        // USERLOG
        $uactivity = _anomor(element('PB_Faktur_Pembelian',NID));
        $uactivity = $uactivity['keterangan'];
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$nomor,
            'ullevel'=> 0                                    
        );
        $this->db->insert('auserlog',$userlog);                       

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return "rollback";
        } else {
            return "sukses";
        }

    }

    function autonumber($tgl)
    {
        $nomor = 0;

        $nomor1 = $this->M_transaksi->prefixtrans(element('PB_Faktur_Pembelian', NID)); // Contoh: '25'
        $nomor2 = tgl_notrans($tgl); // Contoh: '0410'

        $prefix = $nomor1 . $nomor2;

        $notrans_length = strlen($prefix); // panjang prefix dinamis

        $sql = "SELECT MAX(RIGHT(ipunotransaksi,4)) as maks 
                  FROM einvoicepenjualanu 
                 WHERE LEFT(ipunotransaksi, $notrans_length) = '$prefix'";

        $query = $this->db->query($sql);
        $maks = $query->row()->maks ?? 0;

        $nomor_baru = (int)$maks + 1;
        $nomor4digit = str_pad($nomor_baru, 4, '0', STR_PAD_LEFT);

        return $prefix . $nomor4digit;
    }

    function ambilidbkg($id){
        $this->db->where('ipuid', $id);
        $hasil = $this->db->get('einvoicepenjualanu');

        foreach ($hasil->result() as $row) {
            $nomor = $row->IPUNOBKG;
            return $nomor;                    
        }                   
    }               

    function ubahTransaksiDua()
    {
        $id = $this->input->post('id');
        $idbkg = $this->ambilidbkg($id);

        if(empty($idbkg)){
            $callback = array(
                'pesan' => 'Transaksi tidak ditemukan',
                'nomor' => ''
            );
            return json_encode($callback);
        }

        $this->db->trans_start();

        $sql="CALL SP_JURNAL_INVOICE_PEMBELIAN_DEL(".$id.")";
        $this->db->query($sql);

        $data_header_bkg = array(
                        'susumber' => $this->M_transaksi->prefixtrans(element('PB_Faktur_Pembelian',NID)),
                        'sutanggal' => tgl_database($_POST['tgl']),                        
                        'sunotransaksi' => $_POST['nomor'],
                        'sukontak' => $_POST['kontak'],
                        'suattention' => $_POST['person'],
                        'suuraian' => $_POST['uraian'],                           
                        'sukaryawan' => $_POST['karyawan'],
                        'sutermin' => $_POST['termin'],
                        'supajak' => $_POST['pajak'],                                                
                        'sunofakturpajak' => $_POST['nopajak'], 
                        'sutglpajak' => tgl_database($_POST['tglpajak']),
                        'sualamat' => $_POST['alamat'],
                        'sustatus' => $_POST['status'],
                        'sugudangtujuan' => $_POST['gudang'],                        
                        'sutotalpajak' => $_POST['totalpajak'], 
                        'sutotalpph22' => $_POST['totalpph22'],                                               
                        'sutotaltransaksi' => $_POST['totaltrans'],    
                        'sutotaldp' => $_POST['totaldp'],    
                        'sutotalsisa' => $_POST['totalsisa'],
                        'sumodifu' => $this->session->id                
        );

        $this->db->where('suid', $idbkg);
        $this->db->update('fstoku',$data_header_bkg);

        $data_header = array(
                        'ipusumber' => $this->M_transaksi->prefixtrans(element('PB_Faktur_Pembelian',NID)),
                        'ipunotransaksi' => $_POST['nomor'],
                        'iputanggal' => tgl_database($_POST['tgl']),
                        'ipukontak' => $_POST['kontak'],
                        'ipuuraian' => $_POST['uraian'],
                        'ipukaryawan' => $_POST['karyawan'],
                        'ipunobkg' => $idbkg,
                        'iputermin' => $_POST['termin'], 
                        'ipualamat' => $_POST['alamat'],
                        'ipuattention' => $_POST['person'],   
                        'ipujenispajak' => $_POST['pajak'],
                        'ipustatus' => 1, 
                        'ipugudang' => $_POST['gudang'], 
                        'ipunofakturpajak' => $_POST['nopajak'], 
                        'iputglpajak' => tgl_database($_POST['tglpajak']),                         
                        'ipucatatan' => $_POST['memo'],                        
                        'iputotalpajak' => $_POST['totalpajak'],
                        'iputotalpph22' => $_POST['totalpph22'],                        
                        'ipujumlahdp' => $_POST['totaldp'],
                        'iputotaltransaksi' => $_POST['totaltrans'],          
                        'ipumodifu' => $this->session->id                
        );        

        $this->db->where('ipuid', $id);
        $this->db->update('einvoicepenjualanu',$data_header);

        //Delete Old Detil Trans
        $this->db->where('sdidsu', $idbkg);
        $this->db->delete('fstokd');        
        $this->db->where('ipdidipu', $id);
        $this->db->delete('einvoicepenjualand');
        $this->db->where('idpidu', $id);
        $this->db->delete('einvoicepenjualandp');                        

        // Insert Detil Trans
        $r=1;
        $d = json_decode($_POST['detil']);
        foreach($d as $item){
            $dataitem = _getitemdata($item->item);
            $satuand = $dataitem['isatuan'];
            $satuan2 = $dataitem['isatuan2'];
            $satuan3 = $dataitem['isatuan3'];
            $satuan4 = $dataitem['isatuan4'];
            $satuan5 = $dataitem['isatuan5'];
            $satuan6 = $dataitem['isatuan6'];                        
            $konversi2 = $dataitem['isatkonversi2'];
            $konversi3 = $dataitem['isatkonversi3'];            
            $konversi4 = $dataitem['isatkonversi4'];
            $konversi5 = $dataitem['isatkonversi5'];
            $konversi6 = $dataitem['isatkonversi6'];                        
            $ipdmasukd = $item->qty;

            if($item->satuan == $satuan2){
                $ipdmasukd = $item->qty * $konversi2;
            }elseif ($item->satuan == $satuan3) {
                $ipdmasukd = $item->qty * $konversi3;
            }elseif ($item->satuan == $satuan4) {
                $ipdmasukd = $item->qty * $konversi4;
            }elseif ($item->satuan == $satuan5) {
                $ipdmasukd = $item->qty * $konversi5;
            }elseif ($item->satuan == $satuan6) {
                $ipdmasukd = $item->qty * $konversi6;                                
            }else{
                $ipdmasukd = $item->qty;
            }

            $data_detil_bkg = array(
                    'sdidsu' => $idbkg,
                    'sdurutan' => $r,
                    'sdsumber' => $this->M_transaksi->prefixtrans(element('PB_Faktur_Pembelian',NID)),                    
                    'sditem' => $item->item,
                    'sdmasuk' => $item->qty,
                    'sdmasukd' => $ipdmasukd,                    
                    'sdharga' => $item->harga,
                    'sddiskon' => $item->diskon,
                    'sddiskonpersen' => $item->persen,                    
                    'sdsatuan' => $item->satuan,
                    'sdsatuand' => $satuand,
                    'sddiskonpersen' => $item->persen,                                        
                    'sdcatatan' => $item->catatan,
                    'sdsodid' => $item->noref2,
                    'sdgudang' => $item->gudangdetil,
                    'sdproyek' => $item->proyekdetil,
                    'sdfaktur' => $item->qty,
                    'sdhargainvoice' => $item->harga,
                    'sddiskoninvoice' => $item->diskon                                        
            );
            $this->db->insert('fstokd',$data_detil_bkg);                        


            $data_detil = array(
                    'ipdidipu' => $id,
                    'ipdurutan' => $r,
                    'ipditem' => $item->item,
                    'ipdmasuk' => $item->qty,
                    'ipdmasukd' => $ipdmasukd,                    
                    'ipdharga' => $item->harga,
                    'ipddiskon' => $item->diskon,
                    'ipdsatuan' => $item->satuan,
                    'ipdsatuand' => $satuand,
                    'ipddiskonp' => $item->persen,
                    'ipdproyek' => $item->proyekdetil,
                    'ipdgudang' => $item->gudangdetil,                    
                    'ipdcatatan' => $item->catatan
            );
            $this->db->insert('einvoicepenjualand',$data_detil);  

            $r++;
        }

        // INSERT DP INVOICE
        $d = json_decode($_POST['detildp']);
        foreach($d as $item){
            $dpfaktur = array(
                    "idpidu" => $id,
                    "idpiddp" => $item->iddp,
                    "idpjumlahdp" => $item->jumlah                    
            );
            $this->db->insert('einvoicepenjualandp',$dpfaktur);                                        
        }            

        $sql="CALL SP_JURNAL_INVOICE_PEMBELIAN_ADD(".$id.")";
        $this->db->query($sql);

        $sql="CALL SP_RECALC_HPP()";
        $this->db->query($sql);        

        $sql="CALL SP_REJURNAL_INVOICE_PENJUALAN('".$this->M_transaksi->prefixtrans(element('PJ_Faktur_Penjualan',NID))."')";
        $this->db->query($sql);

        $sql="CALL SP_REJURNAL_RETUR_PENJUALAN('".$this->M_transaksi->prefixtrans(element('PJ_Retur_Penjualan',NID))."')";
        $this->db->query($sql);        

        $sql="CALL SP_REJURNAL_PENJUALAN_TUNAI('".$this->M_transaksi->prefixtrans(element('PJ_Penjualan_Tunai',NID))."')";
        $this->db->query($sql);                        

        // USERLOG
        $uactivity = _anomor(element('PB_Faktur_Pembelian',NID));
        $uactivity = $uactivity['keterangan'];
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$_POST['nomor'],
            'ullevel'=> 2                                    
        );
        $this->db->insert('auserlog',$userlog);                       

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            $callback = array(    
                'pesan'=>'rollback',
                'nomor'=>''
            );
            return json_encode($callback);            
        } else {
            $callback = array(    
                'pesan'=>'sukses',
                'nomor'=>$id
            );
            return json_encode($callback);            
        }

    }

    function tambahTransaksiDua()
    {
        if(empty($_POST['nomor'])){
            $nomor = $this->autonumber($_POST['tgl']);
        }else{
            $nomor = $_POST['nomor'];
        }        

        // Insert Header Trans
        $data_header_bkg = array(
                        'susumber' => $this->M_transaksi->prefixtrans(element('PB_Faktur_Pembelian',NID)),
                        'sutanggal' => tgl_database($_POST['tgl']),                        
                        'sunotransaksi' => $nomor,
                        'sukontak' => $_POST['kontak'],
                        'suattention' => $_POST['person'],
                        'suuraian' => $_POST['uraian'],                           
                        'sukaryawan' => $_POST['karyawan'],
                        'sutermin' => $_POST['termin'],
                        'supajak' => $_POST['pajak'],                                                
                        'sunofakturpajak' => $_POST['nopajak'], 
                        'sutglpajak' => tgl_database($_POST['tglpajak']),
                        'sualamat' => $_POST['alamat'],
                        'sustatus' => 1,
                        'sugudangtujuan' => $_POST['gudang'],                        
                        'sutotalpajak' => $_POST['totalpajak'],
                        'sutotalpph22' => $_POST['totalpph22'],                                                
                        'sutotaltransaksi' => $_POST['totaltrans'],    
                        'sutotaldp' => $_POST['totaldp'],    
                        'sutotalsisa' => $_POST['totalsisa'],                     
                        'sucreateu' => $this->session->id                
        );        

        $this->db->trans_start();
        $this->db->insert('fstoku',$data_header_bkg);        
        $idbkg = $this->db->insert_id();        


        $data_header = array(
                        'ipusumber' => $this->M_transaksi->prefixtrans(element('PB_Faktur_Pembelian',NID)),
                        'ipunotransaksi' => $nomor,
                        'iputanggal' => tgl_database($_POST['tgl']),
                        'ipukontak' => $_POST['kontak'],
                        'ipuuraian' => $_POST['uraian'],
                        'ipukaryawan' => $_POST['karyawan'],
                        'ipunobkg' => $idbkg,
                        'iputermin' => $_POST['termin'], 
                        'ipualamat' => $_POST['alamat'],
                        'ipuattention' => $_POST['person'],   
                        'ipujenispajak' => $_POST['pajak'],
                        'ipustatus' => 1, 
                        'ipugudang' => $_POST['gudang'], 
                        'ipunofakturpajak' => $_POST['nopajak'], 
                        'iputglpajak' => tgl_database($_POST['tglpajak']),                         
                        'ipucatatan' => $_POST['memo'],                        
                        'iputotalpajak' => $_POST['totalpajak'],
                        'iputotalpph22' => $_POST['totalpph22'],                                                
                        'ipujumlahdp' => $_POST['totaldp'],
                        'iputotaltransaksi' => $_POST['totaltrans'], 
                        'ipucreateu' => $this->session->id                
        );        
        $this->db->insert('einvoicepenjualanu',$data_header);        
        $id = $this->db->insert_id();

        // Insert Detil Trans
        $r=1;
        $d = json_decode($_POST['detil']);
        foreach($d as $item){
            $dataitem = _getitemdata($item->item);
            $satuand = $dataitem['isatuan'];
            $satuan2 = $dataitem['isatuan2'];
            $satuan3 = $dataitem['isatuan3'];
            $satuan4 = $dataitem['isatuan4'];
            $satuan5 = $dataitem['isatuan5'];
            $satuan6 = $dataitem['isatuan6'];                        
            $konversi2 = $dataitem['isatkonversi2'];
            $konversi3 = $dataitem['isatkonversi3'];            
            $konversi4 = $dataitem['isatkonversi4'];
            $konversi5 = $dataitem['isatkonversi5'];
            $konversi6 = $dataitem['isatkonversi6'];                        
            $ipdmasukd = $item->qty;

            if($item->satuan == $satuan2){
                $ipdmasukd = $item->qty * $konversi2;
            }elseif ($item->satuan == $satuan3) {
                $ipdmasukd = $item->qty * $konversi3;
            }elseif ($item->satuan == $satuan4) {
                $ipdmasukd = $item->qty * $konversi4;
            }elseif ($item->satuan == $satuan5) {
                $ipdmasukd = $item->qty * $konversi5;
            }elseif ($item->satuan == $satuan6) {
                $ipdmasukd = $item->qty * $konversi6;                                
            }else{
                $ipdmasukd = $item->qty;
            }

            $data_detil_bkg = array(
                    'sdidsu' => $idbkg,
                    'sdurutan' => $r,
                    'sdsumber' => $this->M_transaksi->prefixtrans(element('PB_Faktur_Pembelian',NID)),                    
                    'sditem' => $item->item,
                    'sdmasuk' => $item->qty,
                    'sdmasukd' => $ipdmasukd,                    
                    'sdharga' => $item->harga,
                    'sddiskon' => $item->diskon,
                    'sddiskonpersen' => $item->persen,                    
                    'sdsatuan' => $item->satuan,
                    'sdsatuand' => $satuand,
                    'sddiskonpersen' => $item->persen,                                        
                    'sdcatatan' => $item->catatan,
                    'sdsodid' => $item->noref2,
                    'sdgudang' => $item->gudangdetil,
                    'sdproyek' => $item->proyekdetil,
                    'sdfaktur' => $item->qty,
                    'sdhargainvoice' => $item->harga,
                    'sddiskoninvoice' => $item->diskon                                        
            );
            $this->db->insert('fstokd',$data_detil_bkg);                        


            $data_detil = array(
                    'ipdidipu' => $id,
                    'ipdurutan' => $r,
                    'ipditem' => $item->item,
                    'ipdmasuk' => $item->qty,
                    'ipdmasukd' => $ipdmasukd,                    
                    'ipdharga' => $item->harga,
                    'ipddiskon' => $item->diskon,
                    'ipdsatuan' => $item->satuan,
                    'ipdsatuand' => $satuand,
                    'ipddiskonp' => $item->persen,
                    'ipdproyek' => $item->proyekdetil,
                    'ipdgudang' => $item->gudangdetil,                    
                    'ipdcatatan' => $item->catatan
            );
            $this->db->insert('einvoicepenjualand',$data_detil);  

            $r++;
        }

        // INSERT DP INVOICE
        $d = json_decode($_POST['detildp']);
        foreach($d as $item){
            $dpfaktur = array(
                    "idpidu" => $id,
                    "idpiddp" => $item->iddp,
                    "idpjumlahdp" => $item->jumlah                    
            );
            $this->db->insert('einvoicepenjualandp',$dpfaktur);                                        
        }            

        $sql="CALL SP_JURNAL_INVOICE_PEMBELIAN_ADD(".$id.")";
        $this->db->query($sql);

        $sql="CALL SP_RECALC_HPP()";
        $this->db->query($sql);        

        $sql="CALL SP_REJURNAL_INVOICE_PENJUALAN('".$this->M_transaksi->prefixtrans(element('PJ_Faktur_Penjualan',NID))."')";
        $this->db->query($sql);

        $sql="CALL SP_REJURNAL_RETUR_PENJUALAN('".$this->M_transaksi->prefixtrans(element('PJ_Retur_Penjualan',NID))."')";
        $this->db->query($sql);        

        $sql="CALL SP_REJURNAL_PENJUALAN_TUNAI('".$this->M_transaksi->prefixtrans(element('PJ_Penjualan_Tunai',NID))."')";
        $this->db->query($sql);                        

        // USERLOG
        $uactivity = _anomor(element('PB_Faktur_Pembelian',NID));
        $uactivity = $uactivity['keterangan'];
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$nomor,
            'ullevel'=> 1                                    
        );
        $this->db->insert('auserlog',$userlog);                       

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            $callback = array(    
                'pesan'=>'rollback',
                'nomor'=>''
            );
            return json_encode($callback);            
        } else {
            $callback = array(    
                'pesan'=>'sukses',
                'nomor'=>$id
            );
            return json_encode($callback);            
        }

    }

    function hapusTransaksiDuaMulti(){
        $data = json_decode($this->input->post('data'));

        $this->db->trans_start();

        foreach($data as $item){
            $id = $item->id;
            $nomor = $item->nomor;

            $idbkg = $this->ambilidbkg($id);

            $sql="CALL SP_JURNAL_INVOICE_PEMBELIAN_DEL(".$id.")";
            $this->db->query($sql);

            $this->db->where('ipdidipu', $id);
            $hasil = $this->db->get('einvoicepenjualand');
            foreach ($hasil->result() as $row) {
                $sdid = $row->IPDBTGD;
                $query = "SELECT count(*) 'jml' FROM einvoicepenjualand WHERE ipdbtgd='".$sdid."'";
                $harga = $this->db->query($query);
                foreach ($harga->result() as $rh) {
                    if($rh->jml > 1){
                    } else {
                        $this->db->where('sdid', $sdid);
                        $this->db->update('fstokd', array('SDHARGAINVOICE' => 0, 'SDDISKONINVOICE' => 0));
                    }
                }
            }

            //hapus Header Transaksi
            $this->db->where('ipuid', $id);
            $this->db->delete('einvoicepenjualanu');
            $this->db->where('suid', $idbkg);
            $this->db->delete('fstoku');        
            //hapus Detil Transaksi
            $this->db->where('ipdidipu', $id);
            $this->db->delete('einvoicepenjualand');
            $this->db->where('sdidsu', $idbkg);
            $this->db->delete('fstokd');        

            // USERLOG
            $uactivity = _anomor(element('PB_Faktur_Pembelian',NID));
            $uactivity = $uactivity['keterangan'];
            $userlog = array(
                'uluser' => $this->session->id,
                'ulusername' => $this->session->nama,
                'ulcomputer' => $this->input->ip_address(),
                'ulactivity' => $uactivity.' '.$nomor,
                'ullevel'=> 0                                    
            );
            $this->db->insert('auserlog',$userlog);                       
        }        

        $this->db->trans_complete();            
        if($this->db->trans_status() === FALSE){
            return "rollback";
        } else {
            return "sukses";
        }
    }

    function hapusTransaksiDua(){

        $id = $_POST['id'];
        $nomor = $_POST['nomor'];        

        $idbkg = $this->ambilidbkg($id);

        if(empty($idbkg)){
            return "Transaksi tidak ditemukan";
        }

        $this->db->trans_start();

        $sql="CALL SP_JURNAL_INVOICE_PEMBELIAN_DEL(".$id.")";
        $this->db->query($sql);

        $this->db->where('ipdidipu', $id);
        $hasil = $this->db->get('einvoicepenjualand');
        foreach ($hasil->result() as $row) {
            $sdid = $row->IPDBTGD;
            $query = "SELECT count(*) 'jml' FROM einvoicepenjualand WHERE ipdbtgd='".$sdid."'";
            $harga = $this->db->query($query);
            foreach ($harga->result() as $rh) {
                if($rh->jml > 1){
                } else {
                    $this->db->where('sdid', $sdid);
                    $this->db->update('fstokd', array('SDHARGAINVOICE' => 0, 'SDDISKONINVOICE' => 0));
                }
            }
        }

        //hapus Header Transaksi
        $this->db->where('ipuid', $id);
        $this->db->delete('einvoicepenjualanu');
        $this->db->where('suid', $idbkg);
        $this->db->delete('fstoku');        
        //hapus Detil Transaksi
        $this->db->where('ipdidipu', $id);
        $this->db->delete('einvoicepenjualand');
        $this->db->where('sdidsu', $idbkg);
        $this->db->delete('fstokd');        

        // USERLOG
        $uactivity = _anomor(element('PB_Faktur_Pembelian',NID));
        $uactivity = $uactivity['keterangan'];
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$nomor,
            'ullevel'=> 0                                    
        );
        $this->db->insert('auserlog',$userlog);                       

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return "rollback";
        } else {
            return "sukses";
        }

    }

}