<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Page extends CI_Controller
{

    function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();
        if (!$this->session->has_userdata('nama')) {
            echo '<script>
              window.parent.location.href="' . base_url('login/aksi_logout') . '";    
        </script>';
        }
    }

    function loader($id, $link, $title = null)
    {
        $info = _ainfo(1);
        $menu = _amenu('page/' . $id);
        $copy = $info['inama'];
        $pajakbeli = $info['ipajakbeli'];
        $ppnbeli = $info['ippnbeli'];
        $pph22beli = $info['ipph22beli'];
        $pajakjual = $info['ipajakjual'];
        $ppnjual = $info['ippnjual'];
        $pph22jual = $info['ipph22jual'];
        $multidivisi = $info['idivisi'];
        $multiproyek = $info['iproyek'];
        $multisatuan = $info['isatuan'];
        $multikurs = $info['imatauang'];
        $decimalqty = $info['idecimalqty'];
        $decimal = $info['idecimal'];
        $multigudang = $info['igudang'];
        $logo = $info['ilogo'];
        $karyawan = $info['ikaryawankatpos'];
        $appName = $this->config->item('app_name');
        if ($title == null) {
            $caption = $menu['MCAPTION1'];
        } else {
            $caption = $title;
        }
        $data = array(
            'id' => $id,
            'copy' => $copy,
            'app_name' => $appName,
            'title' => $caption . ' | ' . $appName,
            'title2' => $caption,
            'page_caption' => $caption,
            'pajakbeli' => $pajakbeli,
            'ppnbeli' => $ppnbeli,
            'pph22beli' => $pph22beli,
            'pajakjual' => $pajakjual,
            'ppnjual' => $ppnjual,
            'pph22jual' => $pph22jual,
            'multidivisi' => $multidivisi,
            'multiproyek' => $multiproyek,
            'multisatuan' => $multisatuan,
            'multikurs' => $multikurs,
            'multigudang' => $multigudang,
            'decimalqty' => $decimalqty,
            'decimal' => $decimal,
            'logo' => $logo,
            'karyawan' => $karyawan
        );
        $this->load->view('include/header', $data);
        $this->load->view($link, $data);
    }

    // Daftar Laporan
    function laporan()
    {
        $this->loader('laporan', 'modul/laporan/daftar-laporan', 'Daftar Laporan');
    }

    // Transaksi Fina
    function bkm()
    {
        $this->loader('bkm', 'modul/transaksi/fina/kas-masuk');
    }

    function bkmData()
    {
        $this->loader('bkm', 'modul/transaksi/fina/table-kas-masuk');
    }

    function bkk()
    {
        $this->loader('bkk', 'modul/transaksi/fina/kas-keluar');
    }

    function bkkData()
    {
        $this->loader('bkk', 'modul/transaksi/fina/table-kas-keluar');
    }

    function bbm()
    {
        $this->loader('bbm', 'modul/transaksi/fina/bank-masuk');
    }

    function bbmData()
    {
        $this->loader('bbm', 'modul/transaksi/fina/table-bank-masuk');
    }

    function bbk()
    {
        $this->loader('bbk', 'modul/transaksi/fina/bank-keluar');
    }

    function bbkData()
    {
        $this->loader('bbk', 'modul/transaksi/fina/table-bank-keluar');
    }

    function ju()
    {
        $this->loader('ju', 'modul/transaksi/fina/jurnal-umum');
    }

    function juData()
    {
        $this->loader('ju', 'modul/transaksi/fina/table-jurnal-umum');
    }

    function fa()
    {
        $this->loader('fa', 'modul/transaksi/fina/aset-tetap');
    }

    function fasaldoawal()
    {
        $this->loader('fasaldoawal', 'modul/transaksi/fina/aset-saldo-awal', 'Saldo Awal Aktiva');
    }

    function gl()
    {
        $this->loader('gl', 'modul/transaksi/fina/table-histori-ledger');
    }

    function fapembelian()
    {
        $this->loader('fapembelian', 'modul/transaksi/fina/aset-pembelian', 'Pembelian Aktiva');
    }

    function fapenyusutan()
    {
        $this->loader('fapenyusutan', 'modul/transaksi/fina/aset-penyusutan', 'Penyusutan Aktiva');
    }

    function ag()
    {
        $this->loader('ag', 'modul/transaksi/fina/giro', 'Administrasi Giro');
    }
    /* End Transaksi Fina */

    /* Transaksi Pembelian */
    function opb()
    {
        $this->loader('opb', 'modul/transaksi/pembelian/order-pembelian');
    }

    function opbData()
    {
        $this->loader('opb', 'modul/transaksi/pembelian/table-order-pembelian');
    }

    function umb()
    {
        $this->loader('umb', 'modul/transaksi/pembelian/uangmuka-pembelian');
    }

    function umbData()
    {
        $this->loader('umb', 'modul/transaksi/pembelian/table-uangmuka-pembelian');
    }

    function umj()
    {
        $this->loader('umj', 'modul/transaksi/penjualan/uangmuka-penjualan');
    }

    function umjData()
    {
        $this->loader('umj', 'modul/transaksi/penjualan/table-uangmuka-penjualan');
    }

    function pbb()
    {
        $this->loader('pbb', 'modul/transaksi/pembelian/terima-barang');
    }

    function pbbData()
    {
        $this->loader('pbb', 'modul/transaksi/pembelian/table-terima-barang');
    }

    function pbbnondp()
    {
        $this->loader('pbbnondp', 'modul/transaksi/pembelian/terima-barang-non-dp');
    }

    function pbbnondpData()
    {
        $this->loader('pbbnondp', 'modul/transaksi/pembelian/table-terima-barang-non-dp');
    }

    function fpb()
    {
        $this->loader('fpb', 'modul/transaksi/pembelian/faktur-pembelian');
    }

    function fpbn()
    {
        $this->loader('fpbn', 'modul/transaksi/pembelian/faktur-pembelian-dua');
    }

    function fpbData()
    {
        $this->loader('fpb', 'modul/transaksi/pembelian/table-faktur-pembelian');
    }

    function fpbnData()
    {
        $this->loader('fpbn', 'modul/transaksi/pembelian/table-faktur-pembelian-dua');
    }

    function bkr()
    {
        $this->loader('bkr', 'modul/transaksi/pembelian/keluar-barang');
    }

    function bkrData()
    {
        $this->loader('bkr', 'modul/transaksi/pembelian/table-keluar-barang');
    }

    function rpb()
    {
        $this->loader('rpb', 'modul/transaksi/pembelian/retur-pembelian');
    }

    function rpbData()
    {
        $this->loader('rpb', 'modul/transaksi/pembelian/table-retur-pembelian');
    }

    function phb()
    {
        $this->loader('phb', 'modul/transaksi/pembelian/pembayaran-hutang');
    }

    function phbData()
    {
        $this->loader('phb', 'modul/transaksi/pembelian/table-pembayaran-hutang');
    }
    /* End Transaksi Pembelian */

    /* Transaksi Penjualan */
    function opj()
    {
        $this->loader('opj', 'modul/transaksi/penjualan/order-penjualan');
    }

    function opjData()
    {
        $this->loader('opj', 'modul/transaksi/penjualan/table-order-penjualan');
    }

    function sj()
    {
        $this->loader('sj', 'modul/transaksi/penjualan/surat-jalan');
    }

    function sjData()
    {
        $this->loader('sj', 'modul/transaksi/penjualan/table-surat-jalan');
    }

    function fpj()
    {
        $this->loader('fpj', 'modul/transaksi/penjualan/faktur-penjualan');
    }

    function fpjn()
    {
        $this->loader('fpjn', 'modul/transaksi/penjualan/faktur-penjualan-dua');
    }

    function fpjData()
    {
        $this->loader('fpj', 'modul/transaksi/penjualan/table-faktur-penjualan');
    }

    function fpjnData()
    {
        $this->loader('fpjn', 'modul/transaksi/penjualan/table-faktur-penjualan-dua');
    }

    function btr()
    {
        $this->loader('btr', 'modul/transaksi/penjualan/terima-retur');
    }

    function btrData()
    {
        $this->loader('btr', 'modul/transaksi/penjualan/table-terima-retur');
    }

    function rpj()
    {
        $this->loader('rpj', 'modul/transaksi/penjualan/retur-penjualan');
    }

    function rpjData()
    {
        $this->loader('rpj', 'modul/transaksi/penjualan/table-retur-penjualan');
    }

    function ppj()
    {
        $this->loader('ppj', 'modul/transaksi/penjualan/pembayaran-piutang');
    }

    function ppjData()
    {
        $this->loader('ppj', 'modul/transaksi/penjualan/table-pembayaran-piutang');
    }
    
    function pos()
    {
        if($this->session->kodeunit != 0){
            $this->loader('pos', 'modul/transaksi/penjualan/penjualan-tunai');
        }else{
            echo "<script>";
            echo "alert('Maaf Menu ini sementara masih perbaikan di sisi superadminnya., mohon gunakan akun lain yang sudah terhubung dengan unit toko. ');";
            
            // echo "window.location.href = '" . getBaseUrl() . "/dasbor';";
            echo "</script>";
            echo 'reload ulang';
            $this->loader('dasbor');
        }
    }

    function posdata()
    {
        $this->loader('posdata', 'modul/transaksi/penjualan/table-penjualan-tunai');
    }

    function saldo()
    {
        $this->loader('saldo', 'modul/transaksi/penjualan/table-saldo');
    }

    /* End Transaksi Penjualan */

    /* Transaksi Persediaan */
    function mutasi()
    {
        $this->loader('mutasi', 'modul/transaksi/persediaan/mutasi-gudang');
    }

    function mutasiData()
    {
        $this->loader('mutasi', 'modul/transaksi/persediaan/table-mutasi-gudang');
    }

    function opname()
    {
        $this->loader('opname', 'modul/transaksi/persediaan/stok-opname');
    }

    function opnameData()
    {
        $this->loader('opname', 'modul/transaksi/persediaan/table-stok-opname');
    }

    function stokadj()
    {
        $this->loader('stokadj', 'modul/transaksi/persediaan/penyesuaian-barang');
    }

    function stokadjData()
    {
        $this->loader('stokadj', 'modul/transaksi/persediaan/table-penyesuaian-barang');
    }

    /* End Transaksi Persediaan */

    /* Master Data */
    function coa()
    {
        $this->loader('coa', 'modul/master/table-coa');
    }

    function item()
    {
        $this->loader('item', 'modul/master/table-item');
    }

    function kategoriitem()
    {
        $this->loader('kategoriitem', 'modul/master/table-kategori-item');
    }

    function kontak()
    {
        $this->loader('kontak', 'modul/master/table-kontak');
    }

    function bank()
    {
        $this->loader('bank', 'modul/master/table-bank');
    }

    function matauang()
    {
        $this->loader('matauang', 'modul/master/table-uang');
    }

    function termin()
    {
        $this->loader('termin', 'modul/master/table-termin');
    }

    function pajak()
    {
        $this->loader('pajak', 'modul/master/table-pajak');
    }

    function fatipe()
    {
        $this->loader('fatipe', 'modul/master/table-kelompok-aktiva');
    }

    function fadata()
    {
        $this->loader('fadata', 'modul/master/table-aktiva');
    }

    function proyek()
    {
        $this->loader('proyek', 'modul/master/table-proyek');
    }

    function gudang()
    {
        $this->loader('gudang', 'modul/master/table-gudang');
    }

    function satuan()
    {
        $this->loader('satuan', 'modul/master/table-satuan');
    }

    function tipekontak()
    {
        $this->loader('tipekontak', 'modul/master/table-kategori-kontak');
    }

    function divisi()
    {
        $this->loader('divisi', 'modul/master/table-divisi');
    }

    function jenispenyesuaian()
    {
        $this->loader('jenispenyesuaian', 'modul/master/table-jenis-penyesuaian');
    }
    /* End Master Data */

    /* Administrator */
    function au($aksi = '')
    {
        $this->loader('au', 'modul/administrator/table-user');
    }

    function aut()
    {
        $this->loader('aut', 'modul/administrator/table-unit');
    }

    function am()
    {
        $this->loader('am', 'modul/administrator/table-menu');
    }

    function ar()
    {
        $this->loader('ar', 'modul/administrator/table-report');
    }

    function al()
    {
        $this->loader('al', 'modul/administrator/table-userlog');
    }

    function settings()
    {
        $this->loader('settings', 'modul/administrator/settings');
    }
    /* End Administrator */

    /* Bantuan */
    function manual()
    {
        $this->loader('maanual', 'User Manual', 'User Manual', 'hasilPrint');
    }


    /* Service Kendaraan */
    function sppuk()
    {
        $this->loader('sppuk', 'modul/transaksi/service/perintah-perbaikan');
    }

    function sppukData()
    {
        $this->loader('sppuk', 'modul/transaksi/service/table-perintah-perbaikan');
    }

    function pos2()
    {
        $this->loader('pos2', 'modul/transaksi/service/penjualan-tunai-service');
    }

    function pos2data()
    {
        $this->loader('pos2data', 'modul/transaksi/service/table-penjualan-tunai-service');
    }
}
