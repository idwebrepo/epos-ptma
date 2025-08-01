<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Datatable_Master extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->model('M_datatables');
    if (!$this->session->has_userdata('nama')) {
      redirect(base_url('exception'));
    }
  }

  function view_table_coa()
  {
    $query  = "SELECT A.cid AS 'id',A.cnocoa AS 'nomor',A.cnama AS 'nama',B.usimbol AS 'uang',C.cgnama AS 'tipe' 
                     FROM bcoa A 
                LEFT JOIN buang B ON A.cuang=B.uid
               INNER JOIN bcoagrup C ON A.ctipe=C.cgid";
    $search = array('cnocoa', 'cnama');
    $where  = null;
    $isWhere = "A.cnocoa LIKE '%" . $_POST['kode'] . "%' AND A.cnama LIKE'%" . $_POST['nama'] . "%'";

    if ($this->input->post('tipe') != '' || $this->input->post('tipe') != null) {
      $isWhere .= " AND A.ctipe='" . $this->input->post('tipe') . "'";
    }

    header('Content-Type: application/json');
    echo $this->M_datatables->get_tables_query($query, $search, $where, $isWhere);
  }

  function view_table_item()
  {
    $kodeunit = $this->session->userdata('kodeunit');


    $info     = _ainfo(1);
    $digitqty = $info['idecimalqty'];

    $query    = "SELECT A.iid AS 'id',A.ikode AS 'kode',A.inama AS 'nama',
                          CASE WHEN A.ijenisitem=0 THEN ROUND(IFNULL(A.istocktotal,0),$digitqty) 
                               WHEN A.ijenisitem=1 THEN 0
                               ELSE ROUND(IFNULL(A.istocktotal,0),$digitqty)
                          END AS 'jumlah',
                          ROUND(IFNULL(A.istockminimal,0),$digitqty) 'minqty',
                          B.skode AS 'satuan', D.iknama 'kategori',
                          CASE WHEN A.ijenisitem=0 THEN 'Persediaan Barang' 
                               WHEN A.ijenisitem=1 THEN 'Jasa' 
                               WHEN A.ijenisitem=2 THEN 'Konsinyasi' 
                          END AS 'jenis',
                          CASE WHEN A.istatus=0 THEN 'Aktif' 
                               WHEN A.istatus=1 THEN 'Tidak Terpakai' 
                               WHEN A.istatus=2 THEN 'Tidak Aktif' 
                          END AS 'status',                          
                          ROUND(IFNULL(A.ihargabeli,0),2) AS 'hbeli',ROUND(IFNULL(A.ihargajual1,0),2) AS 'hjual',
                          IFNULL(C.cnocoa,'') AS 'coa',IFNULL(C.cnama,'') AS 'coanama' ,
                          E.utnama AS 'namaunit', E.utkode AS 'kodeunit'      
                     FROM bitem A
                LEFT JOIN bsatuan B ON A.isatuan=B.sid
                LEFT JOIN bcoa C ON A.icoapendapatan=C.cid
                LEFT JOIN bitemkategori D ON A.ikategori=D.ikid
                LEFT JOIN aunit E ON A.iunit=E.utid";
    $search = array('ikode', 'inama');
    $where  = null;
    $isWhere = "A.ikode LIKE '%" . $_POST['kode'] . "%' AND A.inama LIKE'%" . $_POST['nama'] . "%'";

    if ($kodeunit != 0) {
      $isWhere .= " AND A.iunit ='" . $kodeunit . "'";
    }

    if (!empty($this->input->post('kategori')) && $this->input->post('kategori') != null) {
      $isWhere .= " AND A.ikategori='" . $this->input->post('kategori') . "'";
    }

    if (!empty($this->input->post('jenis')) && $this->input->post('jenis') != null) {
      $isWhere .= " AND A.ijenisitem='" . $this->input->post('jenis') . "'";
    }

    if ($this->input->post('aktif') != '' && $this->input->post('aktif') != null) {
      $isWhere .= " AND A.istatus='" . $this->input->post('aktif') . "'";
    }

    header('Content-Type: application/json');
    echo $this->M_datatables->get_tables_query($query, $search, $where, $isWhere);
  }

  function view_table_kontak($katId = "")
  {
    $query  = "SELECT A.krfid AS 'rfid', A.kid AS 'id',A.kkode AS 'kode',A.knama AS 'nama',B.ktnama AS 'tipe',
                          A.k1alamat 'alamat',A.k1kota AS 'kota',A.k1telp1 AS 'telp'
                     FROM bkontak A
               INNER JOIN bkontaktipe B ON A.ktipe=B.ktid";
    $search = array('kkode', 'knama', 'krfid');
    $where  = null;

    if ($katId !== "") {
      $isWhere = "A.ktipe='" . $katId . "'";
    } else {
      $isWhere = "A.krfid LIKE '%" . @$_POST['rfid'] . "%' AND A.kkode LIKE '%" . @$_POST['kkode'] . "%' AND A.knama LIKE'%" . @$_POST['nama'] . "%'";
    }

    if (!empty($this->input->post('kategori')) && $this->input->post('kategori') != null) {
      $isWhere .= " AND A.ktipe='" . $this->input->post('kategori') . "'";
    }

    header('Content-Type: application/json');
    echo $this->M_datatables->get_tables_query($query, $search, $where, $isWhere);
  }

  function view_table_unit($katId = "")
  {
    $query  = "SELECT A.utid AS 'id', A.utkode AS 'kode', A.utnama AS 'nama'
                     FROM aunit A";
    $search = array('utid', 'utkode', 'utnama', 'utactive');
    $where  = null;

    if ($katId !== "") {
      $isWhere = "A.utactive='" . $katId . "'";
    } else {
      $isWhere = "A.utactive LIKE '%" . @$_POST['utactive'] . "%' AND A.utkode LIKE '%" . @$_POST['utkode'] . "%' AND A.utnama LIKE'%" . @$_POST['utnama'] . "%'";
    }

    header('Content-Type: application/json');
    echo $this->M_datatables->get_tables_query($query, $search, $where, $isWhere);
  }

  function view_table_bank()
  {
    $query  = "SELECT bid AS 'id',bkode AS 'kode',bnama AS 'nama' 
                     FROM bbank";
    $search = array('bkode', 'bnama');
    $where  = null;
    $isWhere = "bkode LIKE '%" . $_POST['kode'] . "%' AND bnama LIKE'%" . $_POST['nama'] . "%'";
    header('Content-Type: application/json');
    echo $this->M_datatables->get_tables_query($query, $search, $where, $isWhere);
  }

  function view_table_uang()
  {
    $query  = "SELECT uid AS 'id',ukode AS 'kode',unama AS 'nama',usimbol AS 'simbol' 
                     FROM buang";
    $search = array('ukode', 'unama');
    $where  = null;
    $isWhere = "ukode LIKE '%" . $_POST['kode'] . "%' AND unama LIKE'%" . $_POST['nama'] . "%'";
    header('Content-Type: application/json');
    echo $this->M_datatables->get_tables_query($query, $search, $where, $isWhere);
  }

  function view_table_termin()
  {
    $query  = "SELECT tid AS 'id',tkode AS 'kode',tnama AS 'nama',ttempo AS 'tempo' 
                     FROM btermin";
    $search = array('tkode', 'tnama');
    $where  = null;
    $isWhere = "tkode LIKE '%" . $_POST['kode'] . "%' AND tnama LIKE'%" . $_POST['nama'] . "%'";
    header('Content-Type: application/json');
    echo $this->M_datatables->get_tables_query($query, $search, $where, $isWhere);
  }

  function view_table_pajak()
  {
    $query  = "SELECT pid AS 'id',pkode AS 'kode',pnama AS 'nama',pnilai AS 'nilai' 
                     FROM bpajak";
    $search = array('pkode', 'pnama');
    $where  = null;
    $isWhere = "pkode LIKE '%" . $_POST['kode'] . "%' AND pnama LIKE'%" . $_POST['nama'] . "%'";
    header('Content-Type: application/json');
    echo $this->M_datatables->get_tables_query($query, $search, $where, $isWhere);
  }

  function view_table_fatipe()
  {
    $query  = "SELECT akid AS 'id',akkode AS 'kode',aknama AS 'nama',akumur AS 'umur' 
                     FROM baktivakelompok";
    $search = array('akkode', 'aknama');
    $where  = null;
    $isWhere = "akkode LIKE '%" . $_POST['kode'] . "%' AND aknama LIKE'%" . $_POST['nama'] . "%'";
    header('Content-Type: application/json');
    echo $this->M_datatables->get_tables_query($query, $search, $where, $isWhere);
  }

  function view_table_aktiva()
  {
    $query  = "SELECT A.aid AS 'id',A.akode AS 'kode',A.anama AS 'nama',B.aknama AS 'kelompok',
                          ROUND(A.ahargabeli,2) AS 'nilai', ROUND(A.aakumbeban,2) AS 'akumulasi',
                          A.aumur AS 'umur',ROUND((A.ahargabeli-A.aakumbeban),2) AS 'buku' 
                     FROM baktiva A
               INNER JOIN baktivakelompok B ON A.akelompok=B.akid";
    $search = array('akode', 'anama');
    $where  = null;
    $isWhere = "akode LIKE '%" . $_POST['kode'] . "%' AND anama LIKE'%" . $_POST['nama'] . "%'";

    if (!empty($this->input->post('kelompok')) && $this->input->post('kelompok') != null) {
      $isWhere .= " AND A.akelompok='" . $this->input->post('kelompok') . "'";
    }

    header('Content-Type: application/json');
    echo $this->M_datatables->get_tables_query($query, $search, $where, $isWhere);
  }

  function view_table_proyek()
  {
    $query  = "SELECT pid AS 'id',pkode AS 'kode',pnama AS 'nama' 
                     FROM bproyek";
    $search = array('pkode', 'pnama');
    $where  = null;
    $isWhere = "pkode LIKE '%" . $_POST['kode'] . "%' AND pnama LIKE'%" . $_POST['nama'] . "%'";
    header('Content-Type: application/json');
    echo $this->M_datatables->get_tables_query($query, $search, $where, $isWhere);
  }

  function view_table_gudang()
  {
    $query  = "SELECT gid AS 'id',gkode AS 'kode',gnama AS 'nama',galamat1 AS 'alamat',gkota AS 'kota',gtelp AS 'telp' 
                     FROM bgudang";
    $search = array('gkode', 'gnama');
    $where  = null;
    $isWhere = "gkode LIKE '%" . $_POST['kode'] . "%' AND gnama LIKE'%" . $_POST['nama'] . "%'";
    header('Content-Type: application/json');
    echo $this->M_datatables->get_tables_query($query, $search, $where, $isWhere);
  }

  function view_table_satuan()
  {
    $query  = "SELECT sid AS 'id',skode AS 'kode',snama AS 'nama',ssatuandasar AS 'dasar',snilai AS 'nilai' 
                     FROM bsatuan";
    $search = array('skode', 'snama');
    $where  = null;
    $isWhere = "skode LIKE '%" . $_POST['kode'] . "%' AND snama LIKE'%" . $_POST['nama'] . "%'";
    header('Content-Type: application/json');
    echo $this->M_datatables->get_tables_query($query, $search, $where, $isWhere);
  }

  function view_table_ckontak()
  {
    $query  = "SELECT ktid AS 'id',ktnama AS 'nama' 
                     FROM bkontaktipe";
    $search = array('ktnama');
    $where  = null;
    $isWhere = "ktnama LIKE'%" . $_POST['nama'] . "%'";
    header('Content-Type: application/json');
    echo $this->M_datatables->get_tables_query($query, $search, $where, $isWhere);
  }

  function view_table_divisi()
  {
    $query  = "SELECT did AS 'id',dkode AS 'kode',dnama AS 'nama' 
                     FROM bdivisi";
    $search = array('dkode', 'dnama');
    $where  = null;
    $isWhere = "dkode LIKE '%" . $_POST['kode'] . "%' AND dnama LIKE'%" . $_POST['nama'] . "%'";
    header('Content-Type: application/json');
    echo $this->M_datatables->get_tables_query($query, $search, $where, $isWhere);
  }

  function view_table_kattention($kontak = "")
  {
    $query  = "SELECT kaid AS 'id',kanama AS 'nama',kajabatan AS 'jabatan' 
                     FROM bkontakatention";
    $search = array('kanama', 'kajabatan');
    $where  = null;
    $isWhere = " kaidk ='" . $kontak . "'";
    header('Content-Type: application/json');
    echo $this->M_datatables->get_tables_query($query, $search, $where, $isWhere);
  }

  function view_jenis_penyesuaian()
  {
    $query  = "SELECT jid AS 'id', jkode 'kode', jnama AS 'nama' 
                     FROM bjenispenyesuaian";
    $search = array('jnama');
    $where  = null;
    $isWhere = "jkode LIKE '%" . $_POST['kode'] . "%' AND jnama LIKE'%" . $_POST['nama'] . "%'";
    header('Content-Type: application/json');
    echo $this->M_datatables->get_tables_query($query, $search, $where, $isWhere);
  }

  // function view_table_citem()
  // {
  //   $kodeunit = $this->session->userdata('kodeunit');

  //   $query  = "SELECT b.ikid AS 'id', b.iknama AS 'nama', a.utnama AS 'nama_unit' FROM bitemkategori b LEFT JOIN aunit a ON b.kodeunit = a.utid  AND b.kodeunit IS NOT NULL";
  //   $search = array('iknama');
  //   $where  = null;
  //   if (!empty($kodeunit)) {
  //     $isWhere = "b.kodeunit='" . $kodeunit . "' AND iknama LIKE'%" . $_POST['nama'] . "%'";
  //   } else {
  //     $isWhere = "iknama LIKE'%" . $_POST['nama'] . "%'";
  //   }
  //   header('Content-Type: application/json');
  //   echo $this->M_datatables->get_tables_query($query, $search, $where, $isWhere);
  // }                                   

   function view_table_citem() {
        $query  = "SELECT ikid AS 'id',iknama AS 'nama' 
                     FROM bitemkategori";
        $search = array('iknama');
        $where  = null;         
        $isWhere = "iknama LIKE'%".$_POST['nama']."%'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

  // function view_item_pos()
  // {
  //   $kodeunit = $this->session->userdata('kodeunit');

  //   echo $kodeunit;
  //   exit;
  //   $this->load->model('M_transaksi');
  //   $query  = "SELECT iid AS 'id',ikode AS 'kode', inama 'nama', icustom1 'gambar' 
  //                  FROM bitem WHERE istatus=0 AND ijenisitem=0";

  //   if (!empty($kodeunit)) {

  //     $query2 = $this->db->query("SELECT ikid FROM bitemkategori WHERE kodeunit = '$kodeunit'");
  //     $ikids = array();

  //     if ($query2->num_rows() > 0) {
  //       foreach ($query2->result() as $row) {
  //         $ikids[] = $row->ikid;
  //       }
  //     }

  //     $ikidsStr = implode(',', $ikids);

  //     $query .= " AND ikategori IN ($ikidsStr)";
  //   }

  //   if ($this->input->post('kategori') != '') {
  //     $query .= " AND ikategori=" . $this->input->post('kategori');
  //   }

  //   if ($this->input->post('search') != '') {
  //     $query .= " AND inama LIKE'%" . $this->input->post('search') . "%'";
  //   }

  //   header('Content-Type: application/json');
  //   echo $this->M_transaksi->get_data_query($query);
  // }

  function view_item_pos() {
    $kodeunit = $this->session->userdata('kodeunit');
    $this->load->model('M_transaksi');
    $query  = "SELECT iid AS 'id',ikode AS 'kode', inama 'nama', icustom1 'gambar' 
                 FROM bitem LEFT JOIN aunit ON bitem.iunit=aunit.utid WHERE istatus=0 AND ijenisitem=0";

    if ($kodeunit != 0) {
      $query .= " AND bitem.iunit ='" . $kodeunit . "'";
    }

    if($this->input->post('kategori') != ''){
      $query .= " AND ikategori=".$this->input->post('kategori');
    }

    if($this->input->post('search') != ''){
      $query .= " AND inama LIKE'%".$this->input->post('search')."%'";
    }

    header('Content-Type: application/json');
    echo $this->M_transaksi->get_data_query($query);      
  }

  function view_item_pos_service()
  {
    $this->load->model('M_transaksi');
    $query  = "SELECT iid AS 'id',ikode AS 'kode', inama 'nama', icustom1 'gambar' 
                   FROM bitem WHERE istatus=0";

    if ($this->input->post('kategori') != '') {
      $query .= " AND ikategori=" . $this->input->post('kategori');
    }

    if ($this->input->post('search') != '') {
      $query .= " AND inama LIKE'%" . $this->input->post('search') . "%'";
    }

    header('Content-Type: application/json');
    echo $this->M_transaksi->get_data_query($query);
  }

  function view_kategori_pos()
  {
    $this->load->model('M_transaksi');
    $query  = "SELECT ikid AS 'id', iknama AS 'nama' 
                   FROM bitemkategori";
    header('Content-Type: application/json');
    echo $this->M_transaksi->get_data_query($query);
  }
}
