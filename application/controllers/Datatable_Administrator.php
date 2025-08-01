<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Datatable_Administrator extends CI_Controller
{

     function __construct()
     {
          parent::__construct();
          $this->load->model('M_datatables');
          if (!$this->session->has_userdata('nama')) {
               redirect(base_url('exception'));
          }
     }

     function view_table_unit()
     {
          $query  = "SELECT utid, utkode, utnama, uttelepon, IF(utactive='1','Aktif','Tidak Aktif') AS 'utactive'
                    FROM aunit";
          $search = array('utkode', 'utnama');
          $where  = null;
          if ($_SESSION['kode'] == 0) {
               $isWhere = null;
          } else {
               $isWhere = "utkode<>0";
          }
          header('Content-Type: application/json');
          // dd($this->M_datatables->get_tables_query($query, $search, $where, $isWhere));
          echo $this->M_datatables->get_tables_query($query, $search, $where, $isWhere);
     }

     function view_table_user()
     {
          $query  = "SELECT uid,ukode,unama,unamalengkap,utnama,IF(uactive='1','Aktif','Tidak Aktif') AS 'uactive' 
                     FROM auser JOIN aunit ON auser.kodeunit=aunit.utid";
          $search = array('ukode', 'unama');
          $where  = null;
          if ($_SESSION['kode'] == 0) {
               $isWhere = null;
          } else {
               $isWhere = "ukode<>0";
          }
          header('Content-Type: application/json');
          echo $this->M_datatables->get_tables_query($query, $search, $where, $isWhere);
     }

     function view_table_menu()
     {
          $query  = "SELECT mid,mnama,mdescription,
                          CASE WHEN mtype=0 THEN 'Module' 
                               WHEN mtype=1 THEN 'Laporan' 
                               WHEN mtype=2 THEN 'Transaksi'
                               WHEN mtype=3 THEN 'Master Data'                               
                               ELSE 'Administrator' 
                          END AS 'mtype',
                          micon,murutan,IF(mactive='1','Aktif','Tidak Aktif') AS 'mactive' 
                     FROM amenu";
          $search = array('mnama', 'mdescription');
          $where  = null;
          $isWhere = "mnama LIKE '%" . $this->input->post('nama') . "%'";

          if (!empty($this->input->post('tipe')) && $this->input->post('tipe') != '') {
               $isWhere .= " AND mtype='" . $this->input->post('tipe') . "'";
          }

          header('Content-Type: application/json');
          echo $this->M_datatables->get_tables_query($query, $search, $where, $isWhere);
     }

     function view_table_report()
     {
          $query  = "SELECT arid 'id', arname 'nama', arname2 'alias',
                          CASE WHEN arpaperorinted=0 THEN 'Default' 
                               WHEN arpaperorinted=1 THEN 'Portrait' 
                               WHEN arpaperorinted=2 THEN 'Landscape' 
                          END 'orientasi',
                          CASE WHEN arpapersize=1 THEN 'Letter' 
                               WHEN arpapersize=2 THEN 'Legal' 
                               WHEN arpapersize=3 THEN 'A4' 
                          END 'ukuran',
                          IF(aractive='1','Aktif','Tidak Aktif') 'aktif' 
                     FROM areport";
          $search = array('arname');
          $where  = null;
          $isWhere = "arname LIKE '%" . $this->input->post('nama') . "%'";
          header('Content-Type: application/json');
          echo $this->M_datatables->get_tables_query($query, $search, $where, $isWhere);
     }

     function view_table_userlog()
     {
          $query  = "SELECT ulid AS 'id',ulusername AS 'user',ulcomputer AS 'komputer',DATE_FORMAT(uldate,'%d/%m/%Y') AS 'tanggal',
                          DATE_FORMAT(ultime,'%r') AS 'jam',ulactivity AS 'kegiatan',
                          CASE WHEN ullevel=0 THEN 'Hapus' 
                               WHEN ullevel=1 THEN 'Tambah' 
                               WHEN ullevel=2 THEN 'Edit' 
                               WHEN ullevel=3 THEN 'Cetak' 
                          END AS 'level' 
                     FROM auserlog";
          $search = array('ulusername', 'ulcomputer');
          $where  = null;
          $isWhere = null;
          header('Content-Type: application/json');
          echo $this->M_datatables->get_tables_query($query, $search, $where, $isWhere);
     }
}
