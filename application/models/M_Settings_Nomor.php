<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Settings_Nomor extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
    	$id = $_POST['id'];
        $data = array(
                'NKODE' => $_POST['kode'],
                'NKETERANGAN' => $_POST['keterangan'],
                'NTABEL' => $_POST['tabel'],
                'NFLDTANGGAL' => $_POST['tanggal'],
                'NFLDSUMBER' => $_POST['sumber'],
                'NFLDNOTRANSAKSI' => $_POST['notrans'],
                'NFLDURAIAN' => $_POST['uraian'],                                
                'NFLDTOTALTRANS' => $_POST['totaltrans'],
                'NFLDKONTAK' => $_POST['kontak'],
                'NFLDID' => $_POST['idtrans'],
                'NFA' => $_POST['nfa'],
                'NFMENU' => $_POST['menu']
        );        
        $this->db->trans_begin();
        $this->db->where('NID',$id);        
        $this->db->update('anomor',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }
    }    

    function hapusData()
    {
        $id = $_POST['id'];

        $this->db->trans_begin();
        $this->db->where('NID', $id);
        $this->db->delete('anomor');

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }    	
    }

    function tambahData()
    {
        $data = array(
                'NKODE' => $_POST['kode'],
                'NKETERANGAN' => $_POST['keterangan'],
                'NTABEL' => $_POST['tabel'],
                'NFLDTANGGAL' => $_POST['tanggal'],
                'NFLDSUMBER' => $_POST['sumber'],
                'NFLDNOTRANSAKSI' => $_POST['notrans'],
                'NFLDURAIAN' => $_POST['uraian'],                                
                'NFLDTOTALTRANS' => $_POST['totaltrans'],
                'NFLDKONTAK' => $_POST['kontak'],
                'NFLDID' => $_POST['idtrans'],
                'NFA' => $_POST['nfa'],
                'NFMENU' => $_POST['menu']
        );        
        $this->db->trans_begin();
        $this->db->insert('anomor',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }
    }

    function resetNomor()
    {
        $this->db->where('nid', $this->input->post('tipe'));
        $anomor = $this->db->get('anomor');

        foreach ($anomor->result() as $key) {
            $tabel = $key->NTABEL;
            $kode = $key->NKODE;
            $sumber = $key->NFLDSUMBER;
            $tanggal = $key->NFLDTANGGAL;
            $nomor = $key->NFLDNOTRANSAKSI;
            $id = $key->NFLDID;
            $nfa = $key->NFA;
        }       

        $query = "SELECT TBL.$id, TBL.$nomor, DATE_FORMAT(TBL.$tanggal,'%d-%m-%Y') 'tanggal',B.cuid 'accId' 
                    FROM $tabel TBL
               LEFT JOIN ctransaksiu B ON TBL.$nomor=B.cunotransaksi 
                   WHERE TBL.$tanggal BETWEEN '".tgl_database($_POST['dari'])."' AND '".tgl_database($_POST['sampai'])."'
                     AND TBL.$sumber='".$kode."' ORDER BY TBL.$tanggal ASC, TBL.$id ASC";

        $data = $this->db->query($query);

        $this->db->trans_start();

        $query = "UPDATE $tabel SET $nomor=NULL WHERE $tanggal BETWEEN '".tgl_database($_POST['dari'])."' AND '".tgl_database($_POST['sampai'])."'
                     AND $sumber='".$kode."'";
        $this->db->query($query);

        if($tabel != 'ctransaksiu' && $nfa==1){
            $query = "UPDATE ctransaksiu SET cunotransaksi=NULL WHERE cutanggal BETWEEN '".tgl_database($_POST['dari'])."' AND '".tgl_database($_POST['sampai'])."' AND cusumber='".$kode."'";
            $this->db->query($query);        
        }

        foreach ($data->result() as $key) {
            $cuid = $key->accId;
            $nomorlama = $key->$nomor;
            $nomorbaru = $this->autonumber($key->tanggal,$tabel,$kode,$nomor);
            $arrnomorbaru = array(
                $nomor => $nomorbaru
            );
            $this->db->where($id, $key->$id);
            $this->db->update($tabel, $arrnomorbaru);

            if($tabel != 'ctransaksiu' && $nfa==1){
                $query = "UPDATE ctransaksiu SET cunotransaksi='".$nomorbaru."' WHERE cuid='".$cuid."'";
                $this->db->query($query);                     
            }
        }

        $this->db->trans_complete();            

        if($this->db->trans_status() === FALSE){
            return "Pesan : Gagal melakukan reset nomor transaksi!";            
        } else {
            return "sukses";            
        }

    }    

    function autonumber($tgl,$tabel,$prefix,$kolnomor){
        $nomor = 0;
        $nomor1 = $prefix;
        $nomor2 = tgl_notrans($tgl);  

        $notrans_length = strlen($nomor1)+4;

        $sql = "SELECT MAX(RIGHT($kolnomor,4)) as 'maks' 
                  FROM $tabel 
                 WHERE LEFT($kolnomor,".$notrans_length.")='".$nomor1.$nomor2."'";

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