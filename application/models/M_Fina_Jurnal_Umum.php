<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Fina_Jurnal_Umum extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahTransaksi(){
        $id = $_POST['id'];
        //Update Header Trans
        $data_header = array(
                        'cusumber' => $this->M_transaksi->prefixtrans(element('Fina_Jurnal_Umum',NID)),
                        'cunotransaksi' => $_POST['nomor'],
                        'cutanggal' => tgl_database($_POST['tgl']),
                        'cukontak' => $_POST['kontak'],
                        'cuuraian' => $_POST['uraian'],
                        'cutotaltrans' => $_POST['totaldb'],
                        'custatus' => $_POST['status'],
                        'cumodifu' => $this->session->id                
        );        
        $this->db->trans_begin();
        $this->db->where('cuid', $id);
        $this->db->update('ctransaksiu',$data_header);

        //Delete Old Detil Trans
        $this->db->where('cdidu', $id);
        $this->db->delete('ctransaksid');

        // Insert Detil Trans
        $r=1;
        $d = json_decode($_POST['detil']);
        foreach($d as $item){
            $data_detil = array(
                    'cdidu' => $id,
                    'cdurutan' => $r,
                    'cdnocoa' => $item->coa,
                    'cddebit' => $item->jmldb,
                    'cdkredit' => $item->jmlcr,
                    'cddvisi' => $item->divisi,
                    'cdproyek' => $item->proyek,
                    'cdcatatan' => $item->catatan
            );
            $this->db->insert('ctransaksid',$data_detil);                        
            $r++;
        }

        // USERLOG
        $uactivity = _anomor(element('Fina_Jurnal_Umum',NID));
        $uactivity = $uactivity['keterangan'];                
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$this->input->post('nomor'),
            'ullevel'=> 2                                                                                    
        );
        $this->db->insert('auserlog',$userlog);                       

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $callback = array(    
                'pesan'=>'rollback',
                'nomor'=>$id
            );
            return json_encode($callback);            
        } else {
            $this->db->trans_commit();            
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
                        'cusumber' => $this->M_transaksi->prefixtrans(element('Fina_Jurnal_Umum',NID)),
                        'cunotransaksi' => $nomor,
                        'cutanggal' => tgl_database($_POST['tgl']),
                        'cukontak' => $_POST['kontak'],
                        'cuuraian' => $_POST['uraian'],
                        'cutotaltrans' => $_POST['totaldb'],
                        'custatus' => 0,
                        'cucreateu' => $this->session->id                
        );        
        $this->db->trans_begin();
        $this->db->insert('ctransaksiu',$data_header);
        $id = $this->db->insert_id();

        // Insert Detil Trans
        $r=1;
        $d = json_decode($_POST['detil']);
        foreach($d as $item){
            $data_detil = array(
                    'cdidu' => $id,
                    'cdurutan' => $r,
                    'cdnocoa' => $item->coa,
                    'cddebit' => $item->jmldb,
                    'cdkredit' => $item->jmlcr,
                    'cddvisi' => $item->divisi,
                    'cdproyek' => $item->proyek,
                    'cdcatatan' => $item->catatan
            );
            $this->db->insert('ctransaksid',$data_detil);                        
            $r++;
        }

        // USERLOG
        $uactivity = _anomor(element('Fina_Jurnal_Umum',NID));
        $uactivity = $uactivity['keterangan'];                
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$nomor,
            'ullevel'=> 1                                                                                    
        );
        $this->db->insert('auserlog',$userlog);                       

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $callback = array(    
                'pesan'=>'rollback',
                'nomor'=>''
            );
            return json_encode($callback);            
        } else {
            $this->db->trans_commit();            
            $callback = array(    
                'pesan'=>'sukses',
                'nomor'=>$id
            );
            return json_encode($callback);            
        }
    }

    function hapusTransaksiMulti(){
        $data = json_decode($this->input->post('data'));

        $this->db->trans_start();

        foreach($data as $item){
            $id = $item->id;
            $nomor = $item->nomor;

            //hapus Header Transaksi
            $this->db->where('cuid', $id);
            $this->db->delete('ctransaksiu');

            //hapus Detil Transaksi
            $this->db->where('cdidu', $id);
            $this->db->delete('ctransaksid');

            // USERLOG
            $uactivity = _anomor(element('Fina_Jurnal_Umum',NID));
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

    function hapusTransaksi(){

        $id = $_POST['id'];
        $nomor = $_POST['nomor'];

        $this->db->trans_begin();

        //hapus Header Transaksi
        $this->db->where('cuid', $id);
        $this->db->delete('ctransaksiu');

        //hapus Detil Transaksi
        $this->db->where('cdidu', $id);
        $this->db->delete('ctransaksid');

        // USERLOG
        $uactivity = _anomor(element('Fina_Jurnal_Umum',NID));
        $uactivity = $uactivity['keterangan'];                
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$nomor,
            'ullevel'=> 0                                                                                    
        );
        $this->db->insert('auserlog',$userlog);                       

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }

    }

    function autonumber($tgl){
        $nomor = 0;
        $nomor1 = $this->M_transaksi->prefixtrans(element('Fina_Jurnal_Umum',NID));
        $nomor2 = tgl_notrans($tgl);  

        $notrans_length = strlen($nomor1)+4;

        $sql = "SELECT MAX(RIGHT(cunotransaksi,4)) as 'maks' 
                  FROM ctransaksiu 
                 WHERE LEFT(cunotransaksi,".$notrans_length.")='".$nomor1.$nomor2."'";

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