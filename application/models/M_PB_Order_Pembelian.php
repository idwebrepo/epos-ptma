<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_PB_Order_Pembelian extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahTransaksi(){
        $id = $_POST['id'];
        //Update Header Trans
        $data_header = array(
                        'sousumber' => $this->M_transaksi->prefixtrans(element('PB_Order_Pembelian',NID)),
                        'sounotransaksi' => $_POST['nomor'],
                        'soutanggal' => tgl_database($_POST['tgl']),
                        'soukontak' => $_POST['kontak'],
                        'souuraian' => $_POST['uraian'],
                        'soukaryawan' => $_POST['karyawan'],
                        'sounoref' => $_POST['refnomor'],
                        'soutermin' => $_POST['termin'], 
                        'soualamat' => $_POST['alamat'],
                        'souattention' => $_POST['person'],   
                        'soupajak' => $_POST['pajak'],
                        'soustatus' => $_POST['status'],                        
                        'soutotalpajak' => $_POST['totalpajak'],
                        'soutotalpph22' => $_POST['totalpph22'],                        
                        'sousubtotal' => $_POST['totalsub'],
                        'soutotaltransaksi' => $_POST['totaltrans'],                        
                        'soumodifu' => $this->session->id                
        );        
        $this->db->trans_start();
        $this->db->where('souid', $id);
        $this->db->update('esalesorderu',$data_header);

        //Delete Old Detil Trans
        $this->db->where('sodidsou', $id);
        $this->db->delete('esalesorderd');

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
            $satuan5 = $dataitem['isatuan5'];
            $satuan6 = $dataitem['isatuan6'];                        
            $konversi2 = $dataitem['isatkonversi2'];
            $konversi3 = $dataitem['isatkonversi3'];            
            $konversi4 = $dataitem['isatkonversi4'];
            $konversi5 = $dataitem['isatkonversi5'];
            $konversi6 = $dataitem['isatkonversi6'];                        

            if($item->satuan == $satuan2){
                $sodorderd = $item->qty*$konversi2;
            }elseif ($item->satuan == $satuan3) {
                $sodorderd = $item->qty*$konversi3;
            }elseif ($item->satuan == $satuan4) {
                $sodorderd = $item->qty*$konversi4;
            }elseif ($item->satuan == $satuan5) {
                $sodorderd = $item->qty*$konversi5;
            }elseif ($item->satuan == $satuan6) {
                $sodorderd = $item->qty*$konversi6;                                
            }else{
                $sodorderd = $item->qty;
            }

            $data_detil = array(
                    'sodidsou' => $id,
                    'sodurutan' => $r,
                    'soditem' => $item->item,
                    'sodorder' => $item->qty,
                    'sodorderd' => $sodorderd,                    
                    'sodharga' => $item->harga,
                    'soddiskon' => $item->diskon,
                    'sodsatuan' => $item->satuan,
                    'sodsatuand' => $satuand,
                    'soddiskonpersen' => $item->persen,                                        
                    'sodcatatan' => $item->catatan
            );

            $this->db->insert('esalesorderd',$data_detil);                        
            $r++;
        }

        // USERLOG
        $uactivity = _anomor(element('PB_Order_Pembelian',NID));
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
                        'sousumber' => $this->M_transaksi->prefixtrans(element('PB_Order_Pembelian',NID)),
                        'sounotransaksi' => $nomor,
                        'soutanggal' => tgl_database($_POST['tgl']),
                        'soukontak' => $_POST['kontak'],
                        'souuraian' => $_POST['uraian'],
                        'soukaryawan' => $_POST['karyawan'],
                        'sounoref' => $_POST['refnomor'],
                        'soutermin' => $_POST['termin'], 
                        'soualamat' => $_POST['alamat'],
                        'souattention' => $_POST['person'],   
                        'soupajak' => $_POST['pajak'],
                        'soustatus' => 1,                        
                        'soutotalpajak' => $_POST['totalpajak'],
                        'soutotalpph22' => $_POST['totalpph22'],                        
                        'sousubtotal' => $_POST['totalsub'],
                        'soutotaltransaksi' => $_POST['totaltrans'],                        
                        'soucreateu' => $this->session->id                                
        );        
        $this->db->trans_start();
        $this->db->insert('esalesorderu',$data_header);
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
            $satuan5 = $dataitem['isatuan5'];
            $satuan6 = $dataitem['isatuan6'];                        
            $konversi2 = $dataitem['isatkonversi2'];
            $konversi3 = $dataitem['isatkonversi3'];            
            $konversi4 = $dataitem['isatkonversi4'];
            $konversi5 = $dataitem['isatkonversi5'];
            $konversi6 = $dataitem['isatkonversi6'];                        

            if($item->satuan == $satuan2){
                $sodorderd = $item->qty*$konversi2;
            }elseif ($item->satuan == $satuan3) {
                $sodorderd = $item->qty*$konversi3;
            }elseif ($item->satuan == $satuan4) {
                $sodorderd = $item->qty*$konversi4;
            }elseif ($item->satuan == $satuan5) {
                $sodorderd = $item->qty*$konversi5;
            }elseif ($item->satuan == $satuan6) {
                $sodorderd = $item->qty*$konversi6;
            }else{
                $sodorderd = $item->qty;
            }

            $data_detil = array(
                    'sodidsou' => $id,
                    'sodurutan' => $r,
                    'soditem' => $item->item,
                    'sodorder' => $item->qty,
                    'sodorderd' => $sodorderd,                    
                    'sodharga' => $item->harga,
                    'soddiskon' => $item->diskon,
                    'sodsatuan' => $item->satuan,
                    'sodsatuand' => $satuand,
                    'soddiskonpersen' => $item->persen,                                        
                    'sodcatatan' => $item->catatan
            );
            $this->db->insert('esalesorderd',$data_detil);                        
            /*
            $data_hargabeli = array(
                    'ihargabeli' => $item->harga
            );
            $this->db->where('iid', $item->item);
            $this->db->update('bitem',$data_hargabeli);
            */
            $r++;
        }

        // USERLOG
        $uactivity = _anomor(element('PB_Order_Pembelian',NID));
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

        $id = $this->input->post('id');
        $nomor = $this->input->post('nomor'); 

        $this->db->trans_start();

        //hapus Header Transaksi
        $this->db->where('souid', $id);
        $this->db->delete('esalesorderu');

        //hapus Detil Transaksi
        $this->db->where('sodidsou', $id);
        $this->db->delete('esalesorderd');

        // USERLOG
        $uactivity = _anomor(element('PB_Order_Pembelian',NID));
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

    function hapusTransaksiMulti(){
        $data = json_decode($this->input->post('data'));

        $this->db->trans_start();

        foreach($data as $item){
            $id = $item->id;
            $nomor = $item->nomor;

            //hapus Header Transaksi
            $this->db->where('souid', $id);
            $this->db->delete('esalesorderu');

            //hapus Detil Transaksi
            $this->db->where('sodidsou', $id);
            $this->db->delete('esalesorderd');

            // USERLOG
            $uactivity = _anomor(element('PB_Order_Pembelian',NID));
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

    function autonumber($tgl){
        $nomor = 0;
        $nomor1 = $this->M_transaksi->prefixtrans(element('PB_Order_Pembelian',NID));
        $nomor2 = tgl_notrans($tgl);  

        $notrans_length = strlen($nomor1)+4;

        $sql = "SELECT MAX(RIGHT(sounotransaksi,4)) as 'maks' 
                  FROM esalesorderu 
                 WHERE LEFT(sounotransaksi,".$notrans_length.")='".$nomor1.$nomor2."'";
        
        $query = $this->db->query($sql);
        foreach ($query->result() as $res) {
            $nomor = number_format($res->maks)+1;
        }

        switch(strlen($nomor)){
        case 1:
          $nomor=$nomor1.$nomor2."000".$nomor;
          break;
        case 2:
          $nomor=$nomor1.$nomor2."00".$nomor;
          break;
        case 3:
          $nomor=$nomor1.$nomor2."0".$nomor;
          break;
        case 4:
          $nomor=$nomor1.$nomor2.$nomor;
          break;
        }
        
        return $nomor;
    }            

}