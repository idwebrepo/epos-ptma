<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_Master_Item extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
        $id = $_POST['id'];

        if ($_POST['expired'] == "" || empty($_POST['expired'])) {
            $expired = NULL;
        } else {
            $expired = tgl_database($_POST['expired']);
        }

        if ($_POST['unit'] == '') {
            $unit = $_POST['unit'];
        } else {
            $unit = $_POST['unitu'];
        }

        $data = array(
            'ikode' => $_POST['kode'],
            'ibarcode' => $_POST['barcode'],
            'inama' => $_POST['nama'],
            'inama2' => $_POST['alias'],
            'iserial' => $_POST['serial'],
            'imerk' => $_POST['merk'],
            'isatuan' => $_POST['satuan'],
            'isatuand' => $_POST['satuand'],
            'ijenisitem' => $_POST['jenis'],
            'itipeitem' => $_POST['tipe'],
            'istatus' => $_POST['status'],
            'ibolehminus' => $_POST['minus'],
            'ikategori' => $_POST['kategori'],
            'iunit' => $unit,
            'istockminimal' => $_POST['stokmin'],
            'istockmaksimal' => $_POST['stokmaks'],
            'istocktotal' => $_POST['stoktotal'],
            'istockreorder' => $_POST['stokreorder'],
            'ihargabeli' => $_POST['hargabeli'],
            'ihargajual1' => $_POST['hargajual1'],
            'ihargajual2' => $_POST['hargajual2'],
            'ihargajual3' => $_POST['hargajual3'],
            'ihargajual4' => $_POST['hargajual4'],
            'icoapersediaan' => $_POST['coapersediaan'],
            'icoapendapatan' => $_POST['coapendapatan'],
            'icoahpp' => $_POST['coahpp'],
            'isatuan2' => $_POST['satuan2'],
            'isatkonversi2' => $_POST['konversi2'],
            'isatuan3' => $_POST['satuan3'],
            'isatkonversi3' => $_POST['konversi3'],
            'isatuan4' => $_POST['satuan4'],
            'isatkonversi4' => $_POST['konversi4'],
            'isatuan5' => $_POST['satuan5'],
            'isatkonversi5' => $_POST['konversi5'],
            'isatuan6' => $_POST['satuan6'],
            'isatkonversi6' => $_POST['konversi6'],
            'isat2hargajual1' => $_POST['sat2hargajual1'],
            'isat2hargajual2' => $_POST['sat2hargajual2'],
            'isat2hargajual3' => $_POST['sat2hargajual3'],
            'isat2hargajual4' => $_POST['sat2hargajual4'],
            'isat3hargajual1' => $_POST['sat3hargajual1'],
            'isat3hargajual2' => $_POST['sat3hargajual2'],
            'isat3hargajual3' => $_POST['sat3hargajual3'],
            'isat3hargajual4' => $_POST['sat3hargajual4'],
            'isat4hargajual1' => $_POST['sat4hargajual1'],
            'isat4hargajual2' => $_POST['sat4hargajual2'],
            'isat4hargajual3' => $_POST['sat4hargajual3'],
            'isat4hargajual4' => $_POST['sat4hargajual4'],
            'isat5hargajual1' => $_POST['sat5hargajual1'],
            'isat5hargajual2' => $_POST['sat5hargajual2'],
            'isat5hargajual3' => $_POST['sat5hargajual3'],
            'isat5hargajual4' => $_POST['sat5hargajual4'],
            'isat6hargajual1' => $_POST['sat6hargajual1'],
            'isat6hargajual2' => $_POST['sat6hargajual2'],
            'isat6hargajual3' => $_POST['sat6hargajual3'],
            'isat6hargajual4' => $_POST['sat6hargajual4'],
            'itanggal1' => $expired,
            'imodifu' => $this->session->id
        );

        $lokasi_file = @$_FILES['gambar']['tmp_name'];
        $tipe_file = @$_FILES['gambar']['type'];
        $nama_file = @$_FILES['gambar']['name'];

        $vdir_upload = "././assets/dist/img/";
        $vfile_upload = $vdir_upload . $nama_file;

        //Simpan gambar dalam ukuran sebenarnya
        move_uploaded_file($lokasi_file, $vfile_upload);

        if ($nama_file != '') {
            $gambar = array(
                'icustom1' => $nama_file
            );

            $data = array_merge($data, $gambar);
        }

        $this->db->trans_start();
        $this->db->where('iid', $id);
        $this->db->update('bitem', $data);

        // Saldo awal
        $d = json_decode($_POST['saldoawal']);

        // Hapus saldo awal item di fstoku dan ctransaksiu
        $query = "SELECT A.suid 
                    FROM fstoku A INNER JOIN fstokd B ON A.suid=B.sdidsu 
                   WHERE B.sditem='" . $id . "' AND A.susaldoawal=1";
        $hasil = $this->db->query($query);
        foreach ($hasil->result() as $row) {
            $query = "CALL SP_JURNAL_PENYESUAIAN_PERSEDIAAN_DEL('" . $row->suid . "')";
            $this->db->query($query);
            $this->db->where('suid', $row->suid);
            $this->db->delete('fstoku');
        }

        foreach ($d as $item) {
            $this->tambahsaldoawal($id, $item->nomor, $item->tanggal, $item->gudang, $item->harga, $item->qty, $item->kontak, $_POST['satuan']);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return "rollback";
        } else {
            return "sukses";
        }
    }

    function hapusData()
    {
        $id = $_POST['id'];

        $this->db->trans_start();

        // Hapus saldo awal item di fstoku dan ctransaksiu
        $query = "SELECT A.suid 
                    FROM fstoku A INNER JOIN fstokd B ON A.suid=B.sdidsu 
                   WHERE B.sditem='" . $id . "' AND A.susaldoawal=1";
        $hasil = $this->db->query($query);
        foreach ($hasil->result() as $row) {
            $query = "CALL SP_JURNAL_PENYESUAIAN_PERSEDIAAN_DEL('" . $row->suid . "')";
            $this->db->query($query);
            $this->db->where('suid', $row->suid);
            $this->db->delete('fstoku');
        }

        $this->db->where('iid', $id);
        $this->db->delete('bitem');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return "rollback";
        } else {
            return "sukses";
        }
    }

    function tambahData()
    {

        if ($_POST['expired'] == "" || empty($_POST['expired'])) {
            $expired = NULL;
        } else {
            $expired = tgl_database($_POST['expired']);
        }

        if ($_POST['unit'] == '') {
            $unit = $_POST['unit'];
        } else {
            $unit = $_POST['unitu'];
        }
        $data = array(
            'ikode' => $_POST['kode'],
            'ibarcode' => $_POST['barcode'],
            'inama' => $_POST['nama'],
            'inama2' => $_POST['alias'],
            'iserial' => $_POST['serial'],
            'imerk' => $_POST['merk'],
            'isatuan' => $_POST['satuan'],
            'isatuand' => $_POST['satuand'],
            'ijenisitem' => $_POST['jenis'],
            'itipeitem' => $_POST['tipe'],
            'istatus' => $_POST['status'],
            'ibolehminus' => $_POST['minus'],
            'ikategori' => $_POST['kategori'],
            'iunit' => $unit,
            'istockminimal' => $_POST['stokmin'],
            'istockmaksimal' => $_POST['stokmaks'],
            'istocktotal' => $_POST['stoktotal'],
            'istockreorder' => $_POST['stokreorder'],
            'ihargabeli' => $_POST['hargabeli'],
            'ihargajual1' => $_POST['hargajual1'],
            'ihargajual2' => $_POST['hargajual2'],
            'ihargajual3' => $_POST['hargajual3'],
            'ihargajual4' => $_POST['hargajual4'],
            'icoapersediaan' => $_POST['coapersediaan'],
            'icoapendapatan' => $_POST['coapendapatan'],
            'icoahpp' => $_POST['coahpp'],
            'isatuan2' => $_POST['satuan2'],
            'isatkonversi2' => $_POST['konversi2'],
            'isatuan3' => $_POST['satuan3'],
            'isatkonversi3' => $_POST['konversi3'],
            'isatuan4' => $_POST['satuan4'],
            'isatkonversi4' => $_POST['konversi4'],
            'isatuan5' => $_POST['satuan5'],
            'isatkonversi5' => $_POST['konversi5'],
            'isatuan6' => $_POST['satuan6'],
            'isatkonversi6' => $_POST['konversi6'],
            'isat2hargajual1' => $_POST['sat2hargajual1'],
            'isat2hargajual2' => $_POST['sat2hargajual2'],
            'isat2hargajual3' => $_POST['sat2hargajual3'],
            'isat2hargajual4' => $_POST['sat2hargajual4'],
            'isat3hargajual1' => $_POST['sat3hargajual1'],
            'isat3hargajual2' => $_POST['sat3hargajual2'],
            'isat3hargajual3' => $_POST['sat3hargajual3'],
            'isat3hargajual4' => $_POST['sat3hargajual4'],
            'isat4hargajual1' => $_POST['sat4hargajual1'],
            'isat4hargajual2' => $_POST['sat4hargajual2'],
            'isat4hargajual3' => $_POST['sat4hargajual3'],
            'isat4hargajual4' => $_POST['sat4hargajual4'],
            'isat5hargajual1' => $_POST['sat5hargajual1'],
            'isat5hargajual2' => $_POST['sat5hargajual2'],
            'isat5hargajual3' => $_POST['sat5hargajual3'],
            'isat5hargajual4' => $_POST['sat5hargajual4'],
            'isat6hargajual1' => $_POST['sat6hargajual1'],
            'isat6hargajual2' => $_POST['sat6hargajual2'],
            'isat6hargajual3' => $_POST['sat6hargajual3'],
            'isat6hargajual4' => $_POST['sat6hargajual4'],
            'itanggal1' => $expired,
            'icreateu' => $this->session->id
        );

        $lokasi_file = @$_FILES['gambar']['tmp_name'];
        $tipe_file = @$_FILES['gambar']['type'];
        $nama_file = @$_FILES['gambar']['name'];

        $vdir_upload = "././assets/dist/img/";
        $vfile_upload = $vdir_upload . $nama_file;

        //Simpan gambar dalam ukuran sebenarnya
        move_uploaded_file($lokasi_file, $vfile_upload);

        if ($nama_file != '') {
            $gambar = array(
                'icustom1' => $nama_file
            );

            $data = array_merge($data, $gambar);
        }

        $this->db->trans_start();
        $this->db->insert('bitem', $data);
        $iid = $this->db->insert_id();

        // Saldo Awal
        $d = json_decode($_POST['saldoawal']);
        //return sizeof($d);
        foreach ($d as $item) {
            $this->tambahsaldoawal($iid, $item->nomor, $item->tanggal, $item->gudang, $item->harga, $item->qty, $item->kontak, $_POST['satuan']);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return "rollback";
        } else {
            return "sukses";
        }
    }

    function tambahsaldoawal($iid, $nomor, $tgl, $gudang, $harga, $qty, $kontak, $satuan)
    {
        if (empty($nomor) || $nomor == "") $nomor = $this->autonumber($tgl);

        // Insert Header Trans
        $data_header = array(
            'susumber' => $this->M_transaksi->prefixtrans(element('Saldo_Awal_Persediaan', NID)),
            'sutanggal' => tgl_database($tgl),
            'sunotransaksi' => $nomor,
            'sukontak' => $kontak,
            'suuraian' => 'SALDO AWAL',
            'sugudangtujuan' => $gudang,
            'sutotaltransaksi' => $harga * $qty,
            'susaldoawal' => 1,
            'sucreateu' => $this->session->id
        );
        $this->db->insert('fstoku', $data_header);
        $id = $this->db->insert_id();

        $data_detil = array(
            'sdidsu' => $id,
            'sdurutan' => 1,
            'sdsumber' => $this->M_transaksi->prefixtrans(element('Saldo_Awal_Persediaan', NID)),
            'sditem' => $iid,
            'sdmasuk' => $qty,
            'sdmasukd' => $qty,
            'sdharga' => $harga,
            'sdsatuan' => $satuan,
            'sdsatuand' => $satuan,
            'sdgudang' => $gudang
        );
        $this->db->insert('fstokd', $data_detil);

        $query = "CALL SP_JURNAL_SALDO_AWAL_STOK_ADD('" . $id . "')";
        $this->db->query($query);

        // USERLOG
        $uactivity = _anomor(element('Saldo_Awal_Persediaan', NID));
        $uactivity = $uactivity['keterangan'];
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity . ' ' . $nomor,
            'ullevel' => 1
        );
        $this->db->insert('auserlog', $userlog);
    }

    function autonumber($tgl)
    {
        $nomor = 0;
        $nomor1 = $this->M_transaksi->prefixtrans(element('Saldo_Awal_Persediaan', NID));
        $nomor2 = tgl_notrans($tgl);

        $notrans_length = strlen($nomor1) + 4;

        $sql = "SELECT IFNULL(MAX(RIGHT(sunotransaksi,4)),0) as 'maks' 
                  FROM fstoku 
                 WHERE LEFT(sunotransaksi," . $notrans_length . ")='" . $nomor1 . $nomor2 . "'";

        $query = $this->db->query($sql);
        foreach ($query->result() as $res) {
            $nomor = number_format($res->maks) + 1;
        }

        switch (strlen($nomor)) {
            case 1:
                $nomor = $nomor1 . $nomor2 . "000" . $nomor;
                break;
            case 2:
                $nomor = $nomor1 . $nomor2 . "00" . $nomor;
                break;
            case 3:
                $nomor = $nomor1 . $nomor2 . "0" . $nomor;
                break;
            case 4:
                $nomor = $nomor1 . $nomor2 . $nomor;
                break;
        }

        return $nomor;
    }

    function getcoabalance()
    {
        $coa = '';
        $where = array(
            'cckode' => 'RL',
            'ccketerangan' => 'BERJALAN'
        );
        $hasil = $this->db->get_where('cconfigcoa', $where);
        foreach ($hasil->result() as $row) {
            $coa = $row->CCCOA;
        }
        return $coa;
    }

    function importData($sheetData)
    {

        $db_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;

        for ($i = 1; $i < count($sheetData); $i++) {
            $jenis = $sheetData[$i][0];
            $kode = $sheetData[$i][1];
            $barcode = $sheetData[$i][2];
            $nama = $sheetData[$i][3];
            $alias = $sheetData[$i][4];
            $merek = $sheetData[$i][5];
            $kategori = $sheetData[$i][6];
            $satuan = $sheetData[$i][7];
            $hargabeli = $sheetData[$i][8];
            $hargajual1 = $sheetData[$i][9];
            $hargajual2 = $sheetData[$i][10];
            $hargajual3 = $sheetData[$i][11];
            $hargajual4 = $sheetData[$i][12];
            $stokmin = $sheetData[$i][31];
            $stok = $sheetData[$i][32];
            $hargapokok = $sheetData[$i][33];
            $pertanggal = $sheetData[$i][34];
            $gudang = $sheetData[$i][35];
            $unit   = $sheetData[$i][36];

            // Jenis Item, Persediaan = 0, Jasa = 1
            if (trim(strtolower($jenis)) == 'persediaan') {
                $jenis = 0;
            } else {
                $jenis = 1;
            }

            // ID Kategori
            $vkategori = column_value('ikid', 'bitemkategori', array('iknama' => $kategori));
            foreach ($vkategori->result() as $row) {
                $kategori = $row->ikid;
            }

            // ID Satuan
            $satuan = trim(strtolower($satuan));
            $vsatuan = column_value('sid', 'bsatuan', array('skode' => $satuan));
            foreach ($vsatuan->result() as $row) {
                $satuan = $row->sid;
            }

            // Default COA Persediaan
            $vcoapersediaan = column_value('cccoa', 'cconfigcoa', array('cckode' => 'ITP', 'ccketerangan' => 'PERSEDIAAN'));
            foreach ($vcoapersediaan->result() as $row) {
                $coapersediaan = $row->cccoa;
            }

            // Default COA Pendapatan
            $vcoapendapatan = column_value('cccoa', 'cconfigcoa', array('cckode' => 'ITT', 'ccketerangan' => 'PENDAPATAN'));
            foreach ($vcoapendapatan->result() as $row) {
                $coapendapatan = $row->cccoa;
            }

            // Default COA HPP
            $vcoahpp = column_value('cccoa', 'cconfigcoa', array('cckode' => 'ITH', 'ccketerangan' => 'HARGAPOKOK'));
            foreach ($vcoahpp->result() as $row) {
                $coahpp = $row->cccoa;
            }

            $sa = array(
                'qty' => $stok,
                'harga' => $hargapokok,
                'tgl' => $pertanggal,
                'gudang' => $gudang
            );

            $data = array(
                'ikode' => $kode,
                'ibarcode' => $barcode,
                'inama' => $nama,
                'inama2' => $alias,
                'imerk' => $merek,
                'ikategori' => $kategori,
                'isatuan' => $satuan,
                'isatuand' => $satuan,
                'ijenisitem' => $jenis,
                'istockminimal' => $stokmin,
                'ihargabeli' => $hargabeli,
                'ihargajual1' => $hargajual1,
                'ihargajual2' => $hargajual2,
                'ihargajual3' => $hargajual3,
                'ihargajual4' => $hargajual4,
                'icoapersediaan' => $coapersediaan,
                'icoapendapatan' => $coapendapatan,
                'icoahpp' => $coahpp,
                'icreateu' => $this->session->id,
                'iunit' => $unit
            );

            $this->db->trans_begin();
            $this->db->insert('bitem', $data);
            $iid = $this->db->insert_id();
            if ($sa['qty'] > 0) {
                $this->tambahsaldoawal($iid, '', $sa['tgl'], $sa['gudang'], $sa['harga'], $sa['qty'], '', $data['isatuan']);
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
        }

        $this->db->db_debug = $db_debug; //restore setting

        return "sukses";
    }
}
