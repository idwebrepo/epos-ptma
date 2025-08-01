<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Modal extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        if (!$this->session->has_userdata('nama')) {
?>
            <script>
                window.parent.location.href = "<? echo base_url('login/aksi_logout'); ?>";
            </script>
<?php
        }
    }

    function loader($link)
    {
        $this->load->view($link);
    }

    // Modal Notif Order Pembelian
    function notiforderpembelian()
    {
        $this->loader('include/modal-notif-orderpembelian');
    }

    // Modal Notif Daftar Stok Minimum
    function notifstokminimum()
    {
        $this->loader('include/modal-notif-stokminimum');
    }

    // Modal Notif Daftar Stok Expired
    function notifstokexpired()
    {
        $this->loader('include/modal-notif-stokexpired');
    }

    // Modal Notif Daftar AR
    function notifAR()
    {
        $this->loader('include/modal-notif-ar');
    }

    // Modal Notif Daftar AP
    function notifAP()
    {
        $this->loader('include/modal-notif-ap');
    }

    // Modal Catatan Mekanik (Service)
    function form_catatan_mekanik()
    {
        $this->loader('modul/transaksi/service/catatan-mekanik');
    }

    // Modal Print Barcode
    function form_print_barcode()
    {
        $this->loader('modul/master/form-print-barcode');
    }

    // Modal Struk POS
    function struk_pos_58()
    {
        $this->loader('modul/laporan/formulir-pos-printer58');
    }

    // Modal Profil User
    function form_profil()
    {
        $this->loader('modul/administrator/form-profil');
    }

    // Modal Cari Transaksi
    function cari_transaksi()
    {
        $this->loader('include/modal-transaksi');
    }

    // Modal Jurnal
    function lihat_jurnal()
    {
        $this->loader('include/modal-transaksi-jurnal');
    }

    // Modal Cari Transaksi Multi
    function cari_transaksi_multiple()
    {
        $this->loader('include/modal-transaksi-multiple');
    }

    // Modal Cari Uang Muka
    function cari_transaksi_uangmuka()
    {
        $this->loader('include/modal-uangmuka');
    }

    // Modal Cari Transaksi Referensi
    function cari_transaksi_r()
    {
        $this->loader('include/modal-transaksi-referensi');
    }

    // Modal Cari Faktur
    function cari_faktur()
    {
        $this->loader('include/modal-carifaktur');
    }

    // Modal Cari Kontak Filter Laporan
    function cari_kontak_report()
    {
        $this->loader('include/modal-kontak');
    }

    // Modal Cari Kontak
    function cari_kontak()
    {
        $this->loader('include/modal-kontak-p');
    }

    // Modal Cari Kontak
    function cari_unit()
    {
        $this->loader('include/modal-unit-p');
    }

    // Modal Cari Kontak Attention
    function cari_kontak_attention()
    {
        $this->loader('include/modal-kontak-person-p');
    }

    // Modal Form Penomoran
    function form_penomoran()
    {
        $this->loader('modul/administrator/form-penomoran');
    }

    // Modal Form Aktiva Tetap
    function form_aktiva()
    {
        $this->loader('modul/master/form-aktiva');
    }

    // Modal Form Kelompok Aktiva Tetap
    function form_kelompok_aktiva()
    {
        $this->loader('modul/master/form-kelompok-aktiva');
    }

    // Modal Form COA
    function form_coa()
    {
        $this->loader('modul/master/form-coa');
    }

    // Modal Form Item & Jasa
    function form_item()
    {
        $this->loader('modul/master/form-item');
    }

    // Modal Form Informasi Item
    function form_view_item()
    {
        $this->loader('modul/master/form-info-item');
    }

    // Modal Form Kontak
    function form_kontak()
    {
        $this->loader('modul/master/form-kontak');
    }

    // Modal Form Bank
    function form_bank()
    {
        $this->loader('modul/master/form-bank');
    }

    // Modal Form Mata Uang
    function form_uang()
    {
        $this->loader('modul/master/form-uang');
    }

    // Modal Form Termin
    function form_termin()
    {
        $this->loader('modul/master/form-termin');
    }

    // Modal Form pajak
    function form_pajak()
    {
        $this->loader('modul/master/form-pajak');
    }

    // Modal Form Proyek
    function form_proyek()
    {
        $this->loader('modul/master/form-proyek');
    }

    // Modal Form Gudang
    function form_gudang()
    {
        $this->loader('modul/master/form-gudang');
    }

    // Modal Form Satuan
    function form_satuan()
    {
        $this->loader('modul/master/form-satuan');
    }

    // Modal Form Kategori Kontak
    function form_kategori_kontak()
    {
        $this->loader('modul/master/form-kategori-kontak');
    }

    // Modal Form Kategori Kontak
    function form_kategori_item()
    {
        $this->loader('modul/master/form-kategori-item');
    }

    // Modal Form Divisi
    function form_divisi()
    {
        $this->loader('modul/master/form-divisi');
    }

    // Modal Form Divisi
    function form_jenis_penyesuaian()
    {
        $this->loader('modul/master/form-jenis-penyesuaian');
    }

    // Modal Uang Muka Pembelian
    function uang_muka_pembelian()
    {
        $this->loader('modul/transaksi/pembelian/uang-muka-pembelian');
    }

    // Modal Potongan PPH Pembelian
    function pph_pembelian()
    {
        $this->loader('modul/transaksi/pembelian/potongan-pph');
    }

    // Modal Potongan PPH Penjualan
    function pph_penjualan()
    {
        $this->loader('modul/transaksi/penjualan/potongan-pph');
    }

    // Modal Pembayaran POS
    function pembayaran_pos()
    {
        $this->loader('modul/transaksi/penjualan/pembayaran-pos');
    }

    // Modal Tunda POS
    function cari_tunda_pos()
    {
        $this->loader('modul/transaksi/penjualan/daftar-tunda-pos');
    }

    // Modal Tunda POS Service
    function cari_tunda_pos_service()
    {
        $this->loader('modul/transaksi/service/daftar-tunda-pos-service');
    }

    // Modal Pembayaran POS Service
    function pembayaran_pos_service()
    {
        $this->loader('modul/transaksi/service/pembayaran-pos-service');
    }


    // Modal Data Service
    function cari_data_service()
    {
        $this->loader('modul/transaksi/service/daftar-service');
    }

    // Modal Form Periode
    function form_periode()
    {
        $this->loader('modul/administrator/form-periode');
    }

    // Modal Form Admin Menu
    function form_menu()
    {
        $this->loader('modul/administrator/form-menu');
    }

    // Modal Form Admin User
    function form_user()
    {
        $this->loader('modul/administrator/form-user');
    }

    // Modal Form Tarik Tunai
    function form_tarik()
    {
        $this->loader('modul/administrator/form-tarik');
    }

    // Modal From Unit
    function form_unit()
    {
        $this->loader('modul/administrator/form-unit');
    }

    // Modal Form Admin Laporan
    function form_report()
    {
        $this->loader('modul/administrator/form-report');
    }

    // Modal Form Restore DB
    function form_restore()
    {
        $this->loader('include/modal-restore');
    }

    // Modal Form Config Email
    function form_config_mail()
    {
        $this->load->model('M_Settings_Info');
        $infosettings = $this->M_Settings_Info->infoPerusahaan();
        foreach ($infosettings->result() as $row) {
            $data['protocol'] = $row->imailprotocol;
            $data['host'] = $row->imailhost;
            $data['port'] = $row->imailport;
            $data['sender'] = $row->imailsender;
            $data['password'] = $row->imailpassword;
        }
        $this->load->view('modul/administrator/form-config-email', $data);
    }
}
