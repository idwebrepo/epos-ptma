<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Admin_BackupDatabase extends CI_Model {

    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");        
    }

    function backup()
    {
        $tables = $this->db->list_tables();
        $content = ""; 
        $content_last = "";
        foreach($tables as $table){
            if($table != 'bitem') {
                $content .= ' TRUNCATE TABLE `' . $table . '`; ';
                //$content .= $this->db->query('SHOW CREATE TABLE ' . $table)->row_array()['Create Table'] . ';'; 
                $fields = $this->db->list_fields($table);
                $table_data = $this->db->query('SELECT * FROM ' . $table)->result_array();
                $insert_field = '';
                $insert_values = '';
                if($fields && $table_data){
                    $insert_field .= 'INSERT INTO `' . $table . '` (';

                    foreach($fields as $field){
                        $insert_field .= '`' . $field . '`,';
                    }
                    $insert_field = substr($insert_field,0,-1);
                    $insert_field .= ')';
                    
                    $insert_values .= ' VALUES ';
                    foreach($table_data as $table_row){
                        $insert_values .= '(' ;
                        foreach($table_row as $column => $value){
                            $insert_values .= "'" . addslashes($value) . "',";
                        }
                        $insert_values = substr($insert_values,0,-1);
                        $insert_values .= '),';
                    }
                    $insert_values = substr($insert_values,0,-1) . '; ';
                }
                $content .= $insert_field . $insert_values;
            } else {
                $content_last .= ' TRUNCATE TABLE `' . $table . '`; ';
                $fields = $this->db->list_fields($table);
                $table_data = $this->db->query('SELECT * FROM ' . $table)->result_array();
                $insert_field = '';
                $insert_values = '';
                if($fields && $table_data){
                    $insert_field .= 'INSERT INTO `' . $table . '` (';

                    foreach($fields as $field){
                        $insert_field .= '`' . $field . '`,';
                    }
                    $insert_field = substr($insert_field,0,-1);
                    $insert_field .= ')';
                    
                    $insert_values .= ' VALUES ';
                    foreach($table_data as $table_row){
                        $insert_values .= '(' ;
                        foreach($table_row as $column => $value){
                            $insert_values .= "'" . addslashes($value) . "',";
                        }
                        $insert_values = substr($insert_values,0,-1);
                        $insert_values .= '),';
                    }
                    $insert_values = substr($insert_values,0,-1) . '; ';
                }
                $content_last .= $insert_field . $insert_values;
            }
        }

        $content .= $content_last;
        $file_path = "./assets/backup/db-backup-" . date("d-m-Y_H-i-s") . ".backup";
        $myfile = fopen($file_path, "w");
        fwrite($myfile, $content);
        fclose($myfile);

        $this->load->library('zip');

        // Add file
        $this->zip->read_file($file_path);

        // Download
        $filename = "db-backup-" . date("d-m-Y_H-i-s") .".zip";
        $this->zip->archive("./assets/backup/".$filename);

        unlink($file_path);
        return $filename;
//        return "db-backup-" . date("d-m-Y_H-i-s") . ".backup";

    }

    function restore(){
        $lokasi_file = @$_FILES['file']['tmp_name'];
        $tipe_file = @$_FILES['file']['type'];
        $nama_file = @$_FILES['file']['name'];       

        if($tipe_file != 'application/octet-stream'){
            return "File backup tidak valid..";
            exit;
        }

        $vdir_upload = "././assets/uploads/";
        $vfile_upload = $vdir_upload . $nama_file;

        move_uploaded_file($lokasi_file, $vfile_upload);

        $sql = file_get_contents($vfile_upload);

        $sqls = explode(';', $sql);
        array_pop($sqls);

        $this->db->query("DROP TRIGGER IF EXISTS `EINVOICEPENJUALAND_ADD`");
        $this->db->query("DROP TRIGGER IF EXISTS `EINVOICEPENJUALANU_FSTOKU`");
        $this->db->query("DROP TRIGGER IF EXISTS `EPEMBAYARANINVOICEI_EINVOICEU`");
        $this->db->query("DROP TRIGGER IF EXISTS `EPEMBAYARANINVOICER_ADD`");
        $this->db->query("DROP TRIGGER IF EXISTS `ESALESORDERD_ADD`");
        $this->db->query("DROP TRIGGER IF EXISTS `dp_add`");
        $this->db->query("DROP TRIGGER IF EXISTS `epembayaraninvoiceu_add`");
        $this->db->query("DROP TRIGGER IF EXISTS `fstokd_add`");                                

        foreach($sqls as $statement){
            $statment = $statement . ";";
            $this->db->query($statement);   
        }

        $this->db->query("UPDATE bitem SET itanggal1=NULL WHERE itanggal1='0000-00-00'");

        $this->db->query("
        CREATE TRIGGER `dp_add` BEFORE INSERT ON `einvoicepenjualandp` FOR EACH ROW BEGIN update ddp set dppakaiiv = dppakaiiv+NEW.IDPJUMLAHDP where dpid = new.IDPIDDP; END
        ");

        $this->db->query("
        CREATE TRIGGER `EINVOICEPENJUALANU_FSTOKU` AFTER INSERT ON `einvoicepenjualanu` FOR EACH ROW begin UPDATE fstoku SET fstoku.SUSTATUS = 3 WHERE fstoku.SUID = NEW.IPUNOBKG; end
        ");

        $this->db->query("
        CREATE TRIGGER `EPEMBAYARANINVOICER_ADD` BEFORE INSERT ON `epembayaraninvoicer` FOR EACH ROW begin UPDATE einvoicepenjualanu SET IPUTOTALBAYAR = IPUTOTALBAYAR + NEW.PIRJMLBAYAR WHERE IPUID = NEW.PIRIDRETURPENJUALAN; UPDATE einvoicepenjualanu SET IPUTOTALBAYAR = IPUTOTALBAYAR + NEW.PIRJMLBAYAR WHERE IPUID = NEW.PIRIDRETURPEMBELIAN; end
        ");

        $this->db->query("
        CREATE TRIGGER `EPEMBAYARANINVOICEI_EINVOICEU` AFTER INSERT ON `epembayaraninvoicei` FOR EACH ROW begin UPDATE ddp SET DPJUMLAHBAYAR = DPJUMLAHBAYAR + NEW.PIIJMLBAYAR WHERE DPID = NEW.PIIIDDP; UPDATE einvoicepenjualanu SET IPUTOTALBAYAR = IPUTOTALBAYAR + NEW.PIIJMLBAYAR WHERE IPUID = NEW.PIIIDINVOICE; UPDATE fstoku SET SUTOTALBAYAR = SUTOTALBAYAR + NEW.PIIJMLBAYAR WHERE SUID = NEW.PIIIDPEMBELIAN; end
        ");        

        $this->db->query("
        CREATE TRIGGER `epembayaraninvoiceu_add` BEFORE INSERT ON `epembayaraninvoiceu` FOR EACH ROW begin update ddp set DPJUMLAHBAYAR=DPJUMLAHBAYAR+NEW.PIUTOTALDP where DPID= new.PIUIDDP; end
        ");        

        $this->db->query("
        CREATE TRIGGER `fstokd_add` BEFORE INSERT ON `fstokd` FOR EACH ROW begin DECLARE tmpVar INTEGER; update bitem set ISTOCKTOTAL=ISTOCKTOTAL+(new.SDMASUKD-new.SDKELUARD) where IID=NEW.SDITEM; update esalesorderd set SODKELUAR = SODKELUAR + NEW.SDKELUAR where sodid = new.SDSODID;update esalesorderd set SODMASUK= SODMASUK + NEW.SDMASUK where sodid = new.SDSODID ; end
        ");                

        $this->db->query("
        CREATE TRIGGER `EINVOICEPENJUALAND_ADD` AFTER INSERT ON `einvoicepenjualand` FOR EACH ROW begin UPDATE fstoku SET SUSTATUS = 3 WHERE SUID = new.IPDBTGU; UPDATE fstokd set SDFAKTUR = SDFAKTUR + ABS(NEW.IPDMASUK-NEW.IPDKELUAR) where sdid = new.IPDBTGD ; UPDATE fstokd set SDHARGAINVOICE = NEW.IPDHARGA where sdid = new.IPDBTGD ; UPDATE fstokd set SDDISKONINVOICE = NEW.IPDDISKON where sdid = new.IPDBTGD ; UPDATE fstokd set SDFAKTUR = SDFAKTUR + ABS(NEW.IPDMASUK-NEW.IPDKELUAR) where sdid = new.IPDSJD ; UPDATE fstokd set SDHARGAINVOICE = NEW.IPDHARGA where sdid = new.IPDSJD ; UPDATE fstokd set SDDISKONINVOICE = NEW.IPDDISKON where sdid = new.IPDSJD ; end
        ");        

        $this->db->query("
        CREATE TRIGGER `ESALESORDERD_ADD` BEFORE INSERT ON `esalesorderd` FOR EACH ROW begin UPDATE fpermintaanbarangd SET PBDQTYPAKAI = PBDQTYPAKAI + NEW.SODORDER WHERE PBDID = NEW.SODIDPB; end
        ");        

        return "sukses";
    }

}