<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Master_Aktiva extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function importData($sheetData)
    {

        $db_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;

        for($i = 1;$i < count($sheetData);$i++)
        {
            $kode = $sheetData[$i]['0'];
            $nama = $sheetData[$i]['1'];
            $kelompok = $sheetData[$i]['2'];
            $barcode = $sheetData[$i]['3'];
            $metode = $sheetData[$i]['4'];
            $tglbeli = $sheetData[$i]['5'];
            $tglpakai = $sheetData[$i]['6'];
            $qty = $sheetData[$i]['7'];
            $perolehan = $sheetData[$i]['8'];                                    
            $residu = $sheetData[$i]['9'];
            $akumulasi = $sheetData[$i]['10'];
            $umur = $sheetData[$i]['11'];
            $lokasi = $sheetData[$i]['12']; 
            $coaaktiva = $sheetData[$i]['13'];             
            $coaakum = $sheetData[$i]['14'];                         
            $coabiaya = $sheetData[$i]['15'];                                     

            // Metode, Tanpa Penyusutan = 0, Garis Lurus = 1, Saldo Menurun = 2
            if(trim(strtolower($metode))=='tanpa penyusutan') {
                $metode = 0;
            } elseif(trim(strtolower($metode))=='garis lurus') {
                $metode = 1;
            } else {
                $metode = 2;
            }

            // ID Kelompok
            $vkelompok = column_value('akid','baktivakelompok',array('aknama' => $kelompok));
            foreach ($vkelompok->result() as $row) {
                $kelompok = $row->akid;
            }

            // COA Aset
            $vcoaaktiva = column_value('cid','bcoa',array('cnocoa' => $coaaktiva));
            foreach ($vcoaaktiva->result() as $row) {
                $coaaktiva = $row->cid;
            }            

            // COA Akumulasi
            $vcoaakum = column_value('cid','bcoa',array('cnocoa' => $coaakum));
            foreach ($vcoaakum->result() as $row) {
                $coaakum = $row->cid;
            }            

            // COA Biaya Peny.
            $vcoabiaya = column_value('cid','bcoa',array('cnocoa' => $coabiaya));
            foreach ($vcoabiaya->result() as $row) {
                $coabiaya = $row->cid;
            }            

            if($metode==1){
                $penyperbulan = ($perolehan-$residu)/$umur;
            } else {
                $penyperbulan = 0;
            }

            $data = array(
                    'akode' => $kode,
                    'anama' => $nama,
                    'anomor' => $barcode,
                    'akelompok' => $kelompok,                                                
                    'alokasi' => $lokasi,
                    'atglbeli' => tgl_database($tglbeli),  
                    'atglpakai' => tgl_database($tglpakai),
                    'ahargabeli' => $perolehan,     
                    'abebanperbulan' => $penyperbulan,
                    'anilairesidu' => $residu,  
                    'atipepenyusutan' => $metode,                                         
                    'ajmlaktiva' => $qty,  
                    'atgl15' => 0,                                                      
                    'aaktivatidakberwujud' => 0,
                    'aumur' => $umur,                        
                    'acoaaktiva' => $coaaktiva,
                    'acoadepresiasiakum' => $coaakum,
                    'acoadepresiasi' => $coabiaya,
                    'acreateu' => $this->session->id                
            );        
            $this->db->trans_begin();
            $this->db->insert('baktiva',$data);

            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();            
            }            
        }

        $this->db->db_debug = $db_debug; //restore setting        
        return "sukses";
    }        

    function ubahData()
    {
    	$id = $_POST['id'];
        $umur = ($_POST['utahun']*12)+$_POST['ubulan'];
        $data = array(
                'akode' => $_POST['kode'],
                'anama' => $_POST['nama'],
                'anomor' => $_POST['serial'],
                'adivisi' => $_POST['divisi'],  
                'akelompok' => $_POST['kelompok'],                                                
                'alokasi' => $_POST['lokasi'],
                'acatatan' => $_POST['lokasi'],                    
                'atglbeli' => tgl_database($_POST['tglbeli']),  
                'atglpakai' => tgl_database($_POST['tglpakai']),
                'ahargabeli' => $_POST['hargabeli'],     
                'abebanperbulan' => $_POST['bebanpenyusutan'],
                'anilairesidu' => $_POST['residu'],  
                'atipepenyusutan' => $_POST['metode'],                                                                      
                'ajmlaktiva' => $_POST['qty'],  
                'atgl15' => $_POST['tgl15'],                                                      
                'aaktivatidakberwujud' => $_POST['intangible'],
                'aumur' => $umur,                        
                'acoaaktiva' => $_POST['coaaktiva'],
                'acoadepresiasiakum' => $_POST['coaakum'],
                'acoadepresiasi' => $_POST['coabiaya'],
                'acoawriteoff' => $_POST['coawo'],  
                'amodifu' => $this->session->id                
        );        
        $this->db->trans_begin();
        $this->db->where('aid',$id);        
        $this->db->update('baktiva',$data);

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
        $this->db->where('aid', $id);
        $this->db->delete('baktiva');

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
        $umur = ($_POST['utahun']*12)+$_POST['ubulan'];
        $data = array(
                'akode' => $_POST['kode'],
                'anama' => $_POST['nama'],
                'anomor' => $_POST['serial'],
                'adivisi' => $_POST['divisi'],  
                'akelompok' => $_POST['kelompok'],                                                
                'alokasi' => $_POST['lokasi'],
                'acatatan' => $_POST['lokasi'],                    
                'atglbeli' => tgl_database($_POST['tglbeli']),  
                'atglpakai' => tgl_database($_POST['tglpakai']),
                'ahargabeli' => $_POST['hargabeli'],     
                'abebanperbulan' => $_POST['bebanpenyusutan'],
                'anilairesidu' => $_POST['residu'],  
                'atipepenyusutan' => $_POST['metode'],                                                                      
                'ajmlaktiva' => $_POST['qty'],  
                'atgl15' => $_POST['tgl15'],                                                      
                'aaktivatidakberwujud' => $_POST['intangible'],
                'aumur' => $umur,                        
                'acoaaktiva' => $_POST['coaaktiva'],
                'acoadepresiasiakum' => $_POST['coaakum'],
                'acoadepresiasi' => $_POST['coabiaya'],
                'acoawriteoff' => $_POST['coawo'],  
                'acreateu' => $this->session->id                
        );        
        $this->db->trans_begin();
        $this->db->insert('baktiva',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }
    }    
}