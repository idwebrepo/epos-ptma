<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Select_Master extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        if (!$this->session->has_userdata('nama')) {
            redirect(base_url('exception'));
        }
        $this->load->model('M_select2');
    }

    function view_tabel_list()
    {
        header('Content-Type: application/json');
        echo $this->M_select2->get_tabel_db();
    }

    function view_field_list()
    {
        header('Content-Type: application/json');
        echo $this->M_select2->get_field_tabel();
    }

    function view_coa_tipe()
    {
        $query  = "SELECT A.cgid AS 'id',A.cgnama AS 'text',null AS 'kode' 
                     FROM bcoagrup A";
        $search = array('cgnama');
        $isOrder = 'cgid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_mata_uang()
    {
        $query  = "SELECT A.uid AS 'id',CONCAT_WS(' - ',A.ukode,A.unama) AS 'text',null AS 'kode' 
                     FROM buang A";
        $search = array('ukode');
        $isOrder = 'uid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_divisi()
    {
        $query  = "SELECT A.did AS 'id',A.dnama AS 'text',null AS 'kode' 
                     FROM bdivisi A";
        $search = array('dnama');
        $isOrder = 'did';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_divisi_kode()
    {
        $query  = "SELECT A.did AS 'id',A.dkode AS 'text',null AS 'kode' 
                     FROM bdivisi A";
        $search = array('dkode');
        $isOrder = 'did';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_bank()
    {
        $query  = "SELECT A.bid AS 'id',A.bkode AS 'text',null AS 'kode' 
                     FROM bbank A";
        $search = array('bkode');
        $isOrder = 'bid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_coa()
    {
        $query  = "SELECT A.cid AS 'id',A.cnama AS 'text',A.cnocoa AS 'kode' 
                     FROM bcoa A";
        $search = array('cnocoa', 'cnama');
        $isOrder = 'cnocoa';
        $isWhere = "A.cgd='D'";
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_coa_nocoa()
    {
        $query  = "SELECT A.cnocoa AS 'id',A.cnama AS 'text',A.cnocoa AS 'kode' 
                     FROM bcoa A";
        $search = array('cnocoa', 'cnama');
        $isOrder = 'cnocoa';
        $isWhere = "A.cgd='D'";
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_coa_kas()
    {
        $query  = "SELECT A.cid AS 'id',A.cnama AS 'text',A.cnocoa AS 'kode' 
                     FROM bcoa A";
        $search = array('cnocoa', 'cnama');
        $isOrder = 'cnocoa';
        $isWhere = "A.ctipe=0 AND A.cgd='D'";
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_coa_bank()
    {
        $query  = "SELECT A.cid AS 'id',A.cnama AS 'text',A.cnocoa AS 'kode' 
                     FROM bcoa A";
        $search = array('cnocoa', 'cnama');
        $isOrder = 'cnocoa';
        $isWhere = "A.ctipe=1 AND A.cgd='D'";
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_coa_kasbank()
    {
        $query  = "SELECT A.cid AS 'id',A.cnama AS 'text',A.cnocoa AS 'kode' 
                     FROM bcoa A";
        $search = array('cnocoa', 'cnama');
        $isOrder = 'cnocoa';
        $isWhere = "A.ctipe in(0,1) AND A.cgd='D'";
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_coa_nonkasbank()
    {
        $query  = "SELECT A.cid AS 'id',A.cnama AS 'text',A.cnocoa AS 'kode' 
                     FROM bcoa A";
        $search = array('cnocoa', 'cnama');
        $isOrder = 'cnocoa';
        $isWhere = "A.ctipe not in(0,1) AND A.cgd='D'";
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_coa_induk()
    {
        $query  = "SELECT A.cid AS 'id',A.cnama AS 'text',A.cnocoa AS 'kode' 
                     FROM bcoa A";
        $search = array('cnocoa', 'cnama');
        $isOrder = 'cnocoa';
        $isWhere = "A.cgd = 'G'";
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_satuan()
    {
        $query  = "SELECT A.sid AS 'id',A.skode AS 'text',null AS 'kode' 
                     FROM bsatuan A";
        $search = array('snama');
        $isOrder = 'sid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_satuan_filter()
    {
        $query  = "SELECT A.sid AS 'id',A.skode AS 'text',null AS 'kode' 
                     FROM bsatuan A";
        $search = array('snama');
        $isOrder = 'sid';
        $isWhere = "A.sid IN(
                        select isatuan from bitem where iid='" . $_POST['iditem'] . "'
                        union all
                        select isatuan2 from bitem where iid='" . $_POST['iditem'] . "'                        
                        union all 
                        select isatuan3 from bitem where iid='" . $_POST['iditem'] . "'                        
                        union all 
                        select isatuan4 from bitem where iid='" . $_POST['iditem'] . "'
                        union all 
                        select isatuan5 from bitem where iid='" . $_POST['iditem'] . "'                                                
                        union all 
                        select isatuan6 from bitem where iid='" . $_POST['iditem'] . "'                        
                    )";
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_satuan_filter_2()
    {
        $query  = "SELECT A.sid AS 'id',A.skode AS 'text',null AS 'kode' 
                     FROM bsatuan A";
        $search = array('snama');
        $isOrder = 'sid';
        $isWhere = "A.sid NOT IN(" . $this->input->post('where') . ")";
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_termin()
    {
        $query  = "SELECT A.tid AS 'id',A.tnama AS 'text',null AS 'kode' 
                     FROM btermin A";
        $search = array('tnama');
        $isOrder = 'tid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_kategori_item()
    {
        // $kodeunit = $this->session->userdata('kodeunit');
        $query  = "SELECT A.ikid AS 'id',A.iknama AS 'text',null AS 'kode' 
                     FROM bitemkategori A";
        $search = array('ikid');
        $isOrder = 'ikid';
        if (!empty($kodeunit)) {
            $isWhere = "A.kodeunit='" . $kodeunit . "'";
        } else {
            $isWhere = null;
        }
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_unit()
    {
        // $kodeunit = $this->session->userdata('kodeunit');
        $query  = "SELECT A.utid AS 'id',A.utnama AS 'text',null AS 'kode' 
                     FROM aunit A";
        $search = array('utid');
        $isOrder = 'utid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_subkategori_item()
    {
        $query  = "SELECT A.iskid AS 'id',A.iskid AS 'text',null AS 'kode' 
                     FROM bitemsubkategori A";
        $search = array('iskid');
        $isOrder = 'iskid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_item()
    {
        $query  = "SELECT A.iid AS 'id',A.inama AS 'text',A.ikode AS 'kode' 
                     FROM bitem A";
        $search = array('ikode', 'inama');
        $isOrder = 'ikode';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_item_persediaan()
    {
        $kodeunit = $this->session->userdata('kodeunit');
        $query  = "SELECT A.iid AS 'id',A.inama AS 'text',A.ikode AS 'kode' 
                     FROM bitem A";
        $search = array('ikode', 'inama');
        $isOrder = 'ikode';
        $isWhere = " A.ijenisitem=0 AND A.iunit=".$kodeunit;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_kategori_kontak()
    {
        $query  = "SELECT A.ktid AS 'id',A.ktnama AS 'text',null AS 'kode' 
                     FROM bkontaktipe A";
        $search = array('ktnama');
        $isOrder = 'ktid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_kategori_unit()
    {
        $query  = "SELECT A.utid AS 'id',A.utnama AS 'text',null AS 'kode' 
                     FROM aunit A";
        $search = array('utnama');
        $isOrder = 'utid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_kelompok_aktiva()
    {
        $query  = "SELECT A.akid AS 'id',A.aknama AS 'text',null AS 'kode' 
                     FROM baktivakelompok A";
        $search = array('aknama');
        $isOrder = 'akid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_proyek_kode()
    {
        $query  = "SELECT A.pid AS 'id',A.pkode AS 'text',null AS 'kode' 
                     FROM bproyek A";
        $search = array('pkode');
        $isOrder = 'pid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_proyek()
    {
        $query  = "SELECT A.pid AS 'id',A.pnama AS 'text',A.pkode AS 'kode' 
                     FROM bproyek A";
        $search = array('pkode', 'pnama');
        $isOrder = 'pid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_pajak()
    {
        $query  = "SELECT A.pid AS 'id',A.pkode AS 'text',null AS 'kode' 
                     FROM bpajak A";
        $search = array('pkode');
        $isOrder = 'pid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_pajak_ppn()
    {
        $query  = "SELECT A.pid AS 'id',A.pkode AS 'text',null AS 'kode' 
                     FROM bpajak A";
        $search = array('pkode');
        $isOrder = 'pid';
        $isWhere = 'ptipe=1';
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_pajak_pph()
    {
        $query  = "SELECT A.pid AS 'id',A.pkode AS 'text',null AS 'kode' 
                     FROM bpajak A";
        $search = array('pkode');
        $isOrder = 'pid';
        $isWhere = 'ptipe=2';
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_gudang()
    {
        $query  = "SELECT A.gid AS 'id',A.gnama AS 'text',null AS 'kode' 
                     FROM bgudang A";
        $search = array('gnama');
        $isOrder = 'gid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_jenis_penyesuaian_barang()
    {
        $query  = "SELECT A.jid AS 'id',A.jnama AS 'text',null AS 'kode' 
                     FROM bjenispenyesuaian A";
        $search = array('jnama');
        $isOrder = 'jid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_karyawan()
    {
        $query  = "SELECT A.kid AS 'id',A.knama AS 'text',null AS 'kode' 
                     FROM bkontak A";
        $search = array('knama');
        $isOrder = 'kid';
        $isWhere = " A.ktipe=4";
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_tahun_periode()
    {
        $query  = "SELECT A.pid AS 'id',A.ptahun AS 'text',null AS 'kode' 
                     FROM cperiode A";
        $search = array('ptahun');
        $isOrder = 'ptahun';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_tahun_periode2()
    {
        $query  = "SELECT A.ptahun AS 'id',A.ptahun AS 'text',null AS 'kode' 
                     FROM cperiode A";
        $search = array('ptahun');
        $isOrder = 'ptahun';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_parent_menu()
    {
        $query  = "SELECT A.mid AS 'id',A.mnama AS 'text',null AS 'kode' 
                     FROM amenu A";
        $search = array('mnama');
        $isOrder = "mid";
        $isWhere = "mparent=0 AND mtype=" . $_POST['tipe'];
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_menu_list()
    {
        $query  = "SELECT A.mid AS 'id',A.mnama AS 'text',null AS 'kode' 
                     FROM amenu A";
        $search = array('mnama');
        $isOrder = "murutan";
        $isWhere = "mlink<>'null' AND mlink<>''";
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_daftar_laporan()
    {
        $query  = "SELECT A.arid AS 'id',A.arname AS 'text',null AS 'kode' 
                     FROM areport A";
        $search = array('arname');
        $isOrder = 'arid';
        $isWhere = 'aractive=1';
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_jenis_transaksi()
    {
        $nfa = @$_POST['nfa'];
        $query  = "SELECT A.nid AS 'id',A.nketerangan AS 'text',A.nkode AS 'kode' 
                     FROM anomor A";
        $search = array('nketerangan', 'nkode');
        $isOrder = 'nid';
        if (!empty($nfa)) {
            $isWhere = 'A.nfa=' . $nfa;
        } else {
            $isWhere = null;
        }
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_kontak()
    {
        $query  = "SELECT A.kid AS 'id',A.knama AS 'text', CONCAT(A.kkode,' ( ',B.ktnama, ' ) ') AS 'kode'  
                     FROM bkontak A LEFT JOIN bkontaktipe B ON A.ktipe=B.ktid ";
        $search = array('kkode', 'knama');
        $isOrder = 'kkode';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }

    function view_email()
    {
        $query  = "SELECT A.k1email AS 'id',A.k1email AS 'text', NULL AS 'kode'  
                     FROM bkontak A ";
        $search = array('k1email');
        $isOrder = 'k1email';
        $isWhere = " A.k1email<>'' ";
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query, $search, $isWhere, $isOrder);
    }
}
