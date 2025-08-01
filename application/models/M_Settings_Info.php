<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Settings_Info extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
    	$id = $_POST['id'];
        $lokasi_file = $_FILES['ilogo']['tmp_name'];
        $tipe_file = $_FILES['ilogo']['type'];
        $nama_file = $_FILES['ilogo']['name'];       

        $vdir_upload = "././assets/dist/img/";
        $vfile_upload = $vdir_upload . $nama_file;

        //Simpan gambar dalam ukuran sebenarnya
        move_uploaded_file($lokasi_file, $vfile_upload);

        $data = array(
                'inama' => $_POST['nama'],
                'ialamat1' => $_POST['alamat1'],
                'ialamat2' => $_POST['alamat2'],
                'ikota' => $_POST['kota'],
                'ipropinsi' => $_POST['propinsi'],
                'ikodepos' => $_POST['kodepos'],
                'inegara' => $_POST['negara'],
                'itelepon1' => $_POST['telp1'],   
                'itelepon2' => $_POST['telp2'],     
                'ifaks' => $_POST['faks'],
                'iemail' => $_POST['email'],                    
                'ibulanaktif' => $_POST['bulan'],                   
                'itahunaktif' => $_POST['tahun'],
                'icetakpos' => $_POST['icetakpos'],
                'ibarcodepos' => $_POST['ibarcodepos'],                   
                'ikaryawanpos' => $_POST['ikaryawanpos'],
                'ikaryawankatpos' => $_POST['ikaryawankatpos'],                                                   
                'ipajakpos' => $_POST['ipajakpos'],
                'ippnpos' => $_POST['ippnpos'],                
                'ikontakpos' => $_POST['ikontakpos'],
                'ipajakbeli' => $_POST['ipajakbeli'],
                'ippnbeli' => $_POST['ippnbeli'],
                'ipph22beli' => $_POST['ipph22beli'],                
                'ipajakjual' => $_POST['ipajakjual'],
                'ippnjual' => $_POST['ippnjual'],    
                'ipph22jual' => $_POST['ipph22jual'],
                'idivisi' => $_POST['idivisi'],
                'iproyek' => $_POST['iproyek'],                
                'isatuan' => $_POST['isatuan'],
                'igudang' => $_POST['igudang'],                
                'imatauang' => $_POST['imatauang'],
                'idecimalqty' => $_POST['idecimalqty'],
                'idecimal' => $_POST['idecimal']                
        );        

        if($nama_file != ''){
            $logo = array(
                'ilogo' => $nama_file
            );

            $data = array_merge($data,$logo);
        }

        $this->db->trans_begin();
        $this->db->where('iid',$id);        
        $this->db->update('ainfo',$data);

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
        $this->db->where('iid', $id);
        $this->db->delete('ainfo');

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
                'inama' => $_POST['nama'],
                'ialamat1' => $_POST['alamat1'],
                'ialamat2' => $_POST['alamat2'],
                'ikota' => $_POST['kota'],
                'ipropinsi' => $_POST['propinsi'],
                'ikodepos' => $_POST['kodepos'],
                'inegara' => $_POST['negara'],
                'itelepon1' => $_POST['telp1'],   
                'itelepon2' => $_POST['telp2'],     
                'ifaks' => $_POST['faks'],
                'iemail' => $_POST['email'],                    
                'ibulanaktif' => $_POST['bulan'],                   
                'itahunaktif' => $_POST['tahun'],
                'icetakpos' => $_POST['icetakpos'],
                'ibarcodepos' => $_POST['ibarcodepos'],                   
                'ikaryawanpos' => $_POST['ikaryawanpos'],                                   
                'ikaryawankatpos' => $_POST['ikaryawankatpos'],
                'ipajakpos' => $_POST['ipajakpos'],
                'ippnpos' => $_POST['ippnpos'],                
                'ikontakpos' => $_POST['ikontakpos'],
                'ipajakbeli' => $_POST['ipajakbeli'],
                'ippnbeli' => $_POST['ippnbeli'],
                'ipph22beli' => $_POST['ipph22beli'],                
                'ipajakjual' => $_POST['ipajakjual'],
                'ippnjual' => $_POST['ippnjual'],    
                'ipph22jual' => $_POST['ipph22jual'],
                'idivisi' => $_POST['idivisi'],
                'iproyek' => $_POST['iproyek'],                
                'isatuan' => $_POST['isatuan'],
                'imatauang' => $_POST['imatauang'],
                'idecimalqty' => $_POST['idecimalqty']                
        );        
        $this->db->trans_begin();
        $this->db->insert('ainfo',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }
    }    

    function simpanPeriode()
    {
        $data = array(
                'ptahun' => $_POST['tahun']
        );        
        $this->db->trans_begin();
        $this->db->insert('cperiode',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }
    }    

    function infoPerusahaan()
    {
        return $this->db->get('ainfo');
    }

    function simpanconfigmail()
    {
        $data = array(
                'imailprotocol' => $_POST['protocol'],
                'imailhost' => $_POST['host'],
                'imailport' => $_POST['port'],                                
                'imailsender' => $_POST['email'],
                'imailpassword' => $_POST['password']                                                                
        );        
        $this->db->trans_begin();
        $this->db->where('iid', 1);
        $this->db->update('ainfo',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }
    }    

}