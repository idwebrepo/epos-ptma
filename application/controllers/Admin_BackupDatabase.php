<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_BackupDatabase extends CI_Controller
{
    public function __construct() { 
      parent::__construct();
      if(!$this->session->has_userdata('nama')){
          redirect(base_url('exception'));
      }            
      $this->load->model('M_datatables');      
      $this->load->model('M_Admin_BackupDatabase');              
    }

    public function backup()
    {
        echo $this->M_Admin_BackupDatabase->backup();
/*
        $this->load->dbutil();
        $pref = [
            'format' => 'zip',
            'filename' => 'backup-db.sql',
            'add_drop' => false,
            'add_insert' => true
        ];

        $backup     = $this->dbutil->backup($pref);
        $db_name    = 'backup_database_' . date("d-m-Y_H-i-s") . '.zip';
        $save       = './assets/backup/' . $db_name;

        $this->load->helper('file'); // load helper file
        write_file($save, $backup);
*/
//        $this->load->helper("download");
//        force_download($db_name, $backup);

//        echo $db_name;        
    }

    function restore()
    {
        echo $this->M_Admin_BackupDatabase->restore();
    }
}