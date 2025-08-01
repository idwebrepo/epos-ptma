<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_PJ_Penjualan_Tunai extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function ubahTransaksi()
    {
        $id = $this->input->post('id');
        $idbkg = $this->ambilidbkg($id);
        if (empty($idbkg)) {
            return "sukses";
        }

        //Update Header Trans Penjualan Tunai
        $data_header = array(
            'ipusumber' => $this->M_transaksi->prefixtrans(element('PJ_Penjualan_Tunai', NID)),
            'ipunotransaksi' => $_POST['nomor'],
            'iputanggal' => tgl_database($_POST['tgl']),
            'ipukontak' => $_POST['kontak'],
            'ipukaryawan' => $_POST['karyawan'],
            'ipucatatan' => $_POST['catatan'],
            'ipuuraian' => 'Point Of Sale',
            'ipujenispajak' => $_POST['pajak'],
            'ipustatus' => $_POST['status'],
            'iputotalpajak' => $_POST['totalpajak'],
            'iputotaltransaksi' => $_POST['totaltrans'],
            'ipumodifu' => $this->session->id
        );

        $this->db->trans_start();

        $sql = "CALL SP_HITUNG_HPP_DEL(" . $idbkg . ")";
        $this->db->query($sql);

        $sql = "CALL SP_JURNAL_HPP_OUT_DEL(" . $idbkg . ")";
        $this->db->query($sql);

        $this->db->where('ipuid', $id);
        $this->db->update('einvoicepenjualanu', $data_header);

        $data_header_bkg = array(
            'susumber' => $this->M_transaksi->prefixtrans(element('PJ_Penjualan_Tunai', NID)),
            'sutanggal' => tgl_database($_POST['tgl']),
            'sunotransaksi' => $_POST['nomor'],
            'sukontak' => $_POST['kontak'],
            //'sukaryawan' => $_POST['karyawan'],        
            //'sucatatan' => $_POST['catatan'],                        
            'suuraian' => 'Point Of Sale',
            'sustatus' => $_POST['status'],
            'supajak' => $_POST['pajak'],
            'sutotalkas' => $_POST['cash'],
            'sutotalkartudebit' => $_POST['debit'],
            'sunokartudebit' => $_POST['debitno'],
            'subankdebit' => $_POST['debitbank'],
            'sutotalkartukredit' => $_POST['credit'],
            'sunokartukredit' => $_POST['creditno'],
            'subankkredit' => $_POST['creditbank'],
            'sutotaltransaksi' => $_POST['totaltrans'],
            'sutotalpajak' => $_POST['totalpajak'],
            'sutotalbayar' => $_POST['totalbayar'],
            'sutotalsisa' => $_POST['totalsisa'],
            'sutotaldp' => $_POST['diskon'],
            'sumodifu' => $this->session->id
        );

        $this->db->where('suid', $idbkg);
        $this->db->update('fstoku', $data_header_bkg);

        //Delete Old Detil Trans
        $this->db->where('ipdidipu', $id);
        $this->db->delete('einvoicepenjualand');
        $this->db->where('sdidsu', $idbkg);
        $this->db->delete('fstokd');

        // Insert Detil Trans
        $r = 1;
        $d = json_decode($_POST['detil']);
        foreach ($d as $item) {
            $dataitem = _getitemdata($item->item);
            $satuand = $dataitem['isatuan'];
            $satuan2 = $dataitem['isatuan2'];
            $satuan3 = $dataitem['isatuan3'];
            $satuan4 = $dataitem['isatuan4'];
            $satuan5 = $dataitem['isatuan5'];
            $satuan6 = $dataitem['isatuan6'];
            $konversi2 = $dataitem['isatkonversi2'];
            $konversi3 = $dataitem['isatkonversi3'];
            $konversi4 = $dataitem['isatkonversi4'];
            $konversi5 = $dataitem['isatkonversi5'];
            $konversi6 = $dataitem['isatkonversi6'];
            $ipdkeluard = $item->qty;

            if ($item->satuan == $satuan2) {
                $ipdkeluard = $item->qty * $konversi2;
            } elseif ($item->satuan == $satuan3) {
                $ipdkeluard = $item->qty * $konversi3;
            } elseif ($item->satuan == $satuan4) {
                $ipdkeluard = $item->qty * $konversi4;
            } elseif ($item->satuan == $satuan5) {
                $ipdkeluard = $item->qty * $konversi5;
            } elseif ($item->satuan == $satuan6) {
                $ipdkeluard = $item->qty * $konversi6;
            } else {
                $ipdkeluard = $item->qty;
            }


            $data_detil = array(
                'ipdidipu' => $id,
                'ipdurutan' => $r,
                'ipditem' => $item->item,
                'ipdkeluar' => $item->qty,
                'ipdkeluard' => $ipdkeluard,
                'ipdharga' => $item->harga,
                'ipddiskon' => $item->diskon,
                'ipdsatuan' => $item->satuan,
                'ipdsatuand' => $satuand,
                'ipddiskonp' => $item->persen,
                'ipdgudang' => $item->gudang,
                'ipdsju' => $idbkg
            );
            $this->db->insert('einvoicepenjualand', $data_detil);

            $data_detil_bkg = array(
                'sdidsu' => $idbkg,
                'sdurutan' => $r,
                'sdsumber' => $this->M_transaksi->prefixtrans(element('PJ_Penjualan_Tunai', NID)),
                'sditem' => $item->item,
                'sdkeluar' => $item->qty,
                'sdkeluard' => $ipdkeluard,
                'sdharga' => $item->harga,
                'sddiskon' => $item->diskon,
                'sddiskonpersen' => $item->persen,
                'sdsatuan' => $item->satuan,
                'sdsatuand' => $satuand,
                'sdgudang' => $item->gudang,
                'sdfaktur' => $item->qty,
                'sdhargainvoice' => $item->harga,
                'sddiskoninvoice' => $item->diskon
            );
            $this->db->insert('fstokd', $data_detil_bkg);
            $r++;
        }

        $sql = "CALL SP_HITUNG_HPP_ADD(" . $idbkg . ")";
        $this->db->query($sql);

        $sql = "CALL SP_JURNAL_PENJUALAN_TUNAI_ADD(" . $idbkg . ")";
        $this->db->query($sql);

        // USERLOG
        $uactivity = _anomor(element('PJ_Penjualan_Tunai', NID));
        $uactivity = $uactivity['keterangan'];
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity . ' ' . $this->input->post('nomor'),
            'ullevel' => 2
        );
        $this->db->insert('auserlog', $userlog);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $callback = array(
                'pesan' => 'rollback',
                'nomor' => $id
            );
            return json_encode($callback);
        } else {
            $callback = array(
                'pesan' => 'sukses',
                'nomor' => $id
            );
            return json_encode($callback);
        }


        if ($this->db->trans_status() === FALSE) {
            $callback = array(
                'pesan' => 'rollback',
                'nomor' => $id
            );
            return json_encode($callback);
        } else {
            $callback = array(
                'pesan' => 'sukses',
                'nomor' => $id
            );
            return json_encode($callback);
        }
    }

    function tambahTransaksi()
    {
        if (empty($_POST['nomor'])) {
            $nomor = $this->autonumber($_POST['tgl']);
        } else {
            $nomor = $_POST['nomor'];
        }

        $kartu = $_POST['debitbank'];
        if ($kartu != "") {
            $bayar = $_POST['totalbayar'];
            $rfid = $_POST['debitno'];
            $kontak = $_POST['kontak'];
            $pin = md5($_POST['creditno']);

            $totalTrs = $_POST['totaltrans'];


            $cust = $this->db->query(
                "SELECT bk.KRFID, bk.KPIN, bk.KSALDO, bk.KLIMIT, bk.KKODE as nis, IFNULL(SUM(ep.IPUTOTALTRANSAKSI), 0) AS totalTransaksi 
                FROM bkontak bk
                LEFT JOIN einvoicepenjualanu ep ON ep.IPUKONTAK = bk.KID AND ep.IPUTANGGAL = CURDATE()
                WHERE KID = '$kontak'
                GROUP BY bk.KID, bk.KKODE, bk.KNAMA"
            )->row();

            $totalTrx = $totalTrs + $cust->totalTransaksi;

            if ($cust->KRFID != $rfid) {
                $callback = array(
                    'pesan' => 'invalid_rfid'
                );
                return json_encode($callback);
            } elseif ($cust->KPIN != $pin) {
                $callback = array(
                    'pesan' => 'invalid_pin'
                );
                return json_encode($callback);
            } elseif ($cust->KSALDO < $bayar) {
                $callback = array(
                    'pesan' => 'invalid_saldo'
                );
                return json_encode($callback);
            }
            elseif ( $totalTrs > $cust->KLIMIT || $totalTrx > $cust->KLIMIT ) {
                $callback = array(
                    'pesan' => 'limit_reached'
                );
                return json_encode($callback);
            } else {

                $this->db->query("UPDATE bkontak SET KSALDO = KSALDO - $bayar WHERE KRFID = '$rfid' AND KPIN = '$pin'");

                // Insert Header Trans
                $data_header = array(
                    'ipusumber' => $this->M_transaksi->prefixtrans(element('PJ_Penjualan_Tunai', NID)),
                    'ipunotransaksi' => $nomor,
                    'iputanggal' => tgl_database($_POST['tgl']),
                    'ipukontak' => $_POST['kontak'],
                    'ipukaryawan' => $_POST['karyawan'],
                    'ipucatatan' => $_POST['catatan'],
                    'ipuuraian' => 'Point Of Sale',
                    'ipujenispajak' => $_POST['pajak'],
                    'iputipepenjualan' => 1,
                    'ipustatus' => 1,
                    'iputransaksi' => 1,
                    'iputotalpajak' => $_POST['totalpajak'],
                    'iputotaltransaksi' => $_POST['totaltrans'],
                    'ipucreateu' => $this->session->id
                );
                $this->db->trans_start();
                $this->db->insert('einvoicepenjualanu', $data_header);
                $id = $this->db->insert_id();

                $key = KEY_TOKO;

                $data = array(
                    'key' => $key,
                    "nis" => $cust->nis,
                    "nominal" => $bayar,
                    "invoice" => base_url('invoice/preview/'.$id)
                );

                $saving = toko_epesantren("use_saving", $data);

                $data_transaksi_rfid = array(
                    'saldounit' => $this->session->kodeunit,
                    'saldodebit' => $_POST['totaltrans'],
                    'saldokredit' => 0,
                    'saldocreateid' => $this->session->id
                );
                $this->db->trans_start();
                $this->db->insert('esaldopenjual', $data_transaksi_rfid);


                // Insert Header Trans
                $data_header_bkg = array(
                    'susumber' => $this->M_transaksi->prefixtrans(element('PJ_Penjualan_Tunai', NID)),
                    'sutanggal' => tgl_database($_POST['tgl']),
                    'sunotransaksi' => $nomor,
                    'sukontak' => $_POST['kontak'],
                    'suuraian' => 'Point Of Sale',
                    'sustatus' => 1,
                    'supajak' => $_POST['pajak'],
                    'sutotalkas' => $_POST['cash'],
                    'sutotalkartudebit' => $_POST['debit'],
                    'sunokartudebit' => $_POST['debitno'],
                    'subankdebit' => $_POST['debitbank'],
                    'sutotalkartukredit' => $_POST['credit'],
                    'sunokartukredit' => $_POST['creditno'],
                    'subankkredit' => $_POST['creditbank'],
                    'sutotaltransaksi' => $_POST['totaltrans'],
                    'sutotalpajak' => $_POST['totalpajak'],
                    'sutotalbayar' => $_POST['totalbayar'],
                    'sutotalsisa' => $_POST['totalsisa'],
                    'sutotaldp' => $_POST['diskon'],
                    'sucreateu' => $this->session->id
                );
                $this->db->insert('fstoku', $data_header_bkg);
                $idbkg = $this->db->insert_id();

                // Update idbkg di header invoice
                $invoice = array(
                    'ipunobkg' => $idbkg
                );
                $this->db->where('ipuid', $id);
                $this->db->update('einvoicepenjualanu', $invoice);

                // Insert Detil Trans
                $r = 1;
                $d = json_decode($_POST['detil']);
                foreach ($d as $item) {
                    $dataitem = _getitemdata($item->item);
                    $satuand = $dataitem['isatuan'];
                    $satuan2 = $dataitem['isatuan2'];
                    $satuan3 = $dataitem['isatuan3'];
                    $satuan4 = $dataitem['isatuan4'];
                    $satuan5 = $dataitem['isatuan5'];
                    $satuan6 = $dataitem['isatuan6'];
                    $konversi2 = $dataitem['isatkonversi2'];
                    $konversi3 = $dataitem['isatkonversi3'];
                    $konversi4 = $dataitem['isatkonversi4'];
                    $konversi5 = $dataitem['isatkonversi5'];
                    $konversi6 = $dataitem['isatkonversi6'];
                    $ipdkeluard = $item->qty;

                    if ($item->satuan == $satuan2) {
                        $ipdkeluard = $item->qty * $konversi2;
                    } elseif ($item->satuan == $satuan3) {
                        $ipdkeluard = $item->qty * $konversi3;
                    } elseif ($item->satuan == $satuan4) {
                        $ipdkeluard = $item->qty * $konversi4;
                    } elseif ($item->satuan == $satuan5) {
                        $ipdkeluard = $item->qty * $konversi5;
                    } elseif ($item->satuan == $satuan6) {
                        $ipdkeluard = $item->qty * $konversi6;
                    } else {
                        $ipdkeluard = $item->qty;
                    }

                    $data_detil = array(
                        'ipdidipu' => $id,
                        'ipdurutan' => $r,
                        'ipditem' => $item->item,
                        'ipdkeluar' => $item->qty,
                        'ipdkeluard' => $ipdkeluard,
                        'ipdharga' => $item->harga,
                        'ipddiskon' => $item->diskon,
                        'ipdsatuan' => $item->satuan,
                        'ipdsatuand' => $satuand,
                        'ipddiskonp' => $item->persen,
                        'ipdgudang' => $item->gudang,
                        'ipdsju' => $idbkg
                    );
                    $this->db->insert('einvoicepenjualand', $data_detil);

                    $data_detil_bkg = array(
                        'sdidsu' => $idbkg,
                        'sdurutan' => $r,
                        'sdsumber' => $this->M_transaksi->prefixtrans(element('PJ_Penjualan_Tunai', NID)),
                        'sditem' => $item->item,
                        'sdkeluar' => $item->qty,
                        'sdkeluard' => $ipdkeluard,
                        'sdharga' => $item->harga,
                        'sddiskon' => $item->diskon,
                        'sddiskonpersen' => $item->persen,
                        'sdsatuan' => $item->satuan,
                        'sdsatuand' => $satuand,
                        'sdgudang' => $item->gudang,
                        'sdfaktur' => $item->qty,
                        'sdhargainvoice' => $item->harga,
                        'sddiskoninvoice' => $item->diskon
                    );
                    $this->db->insert('fstokd', $data_detil_bkg);
                    $r++;
                }

                $sql = "CALL SP_HITUNG_HPP_ADD(" . $idbkg . ")";
                $this->db->query($sql);

                $sql = "CALL SP_JURNAL_PENJUALAN_TUNAI_ADD(" . $idbkg . ")";
                $this->db->query($sql);

                // USERLOG
                $uactivity = _anomor(element('PJ_Penjualan_Tunai', NID));
                $uactivity = $uactivity['keterangan'];
                $userlog = array(
                    'uluser' => $this->session->id,
                    'ulusername' => $this->session->nama,
                    'ulcomputer' => $this->input->ip_address(),
                    'ulactivity' => $uactivity . ' ' . $nomor,
                    'ullevel' => 1
                );
                $this->db->insert('auserlog', $userlog);

                if (!empty($_POST['idtunda']) || $_POST['idtunda'] != '') {
                    //hapus id tunda sebelumnya
                    $this->db->where('apuid', $this->input->post('idtunda'));
                    $this->db->delete('eantripenjualanu');
                    $this->db->where('apdidapu', $this->input->post('idtunda'));
                    $this->db->delete('eantripenjualand');
                }

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    $callback = array(
                        'pesan' => 'rollback',
                        'nomor' => ''
                    );
                    return json_encode($callback);
                } else {
                    $callback = array(
                        'pesan' => 'sukses',
                        'nomor' => $id
                    );
                    return json_encode($callback);
                }
            }
        } else {
            // Insert Header Trans
            $data_header = array(
                'ipusumber' => $this->M_transaksi->prefixtrans(element('PJ_Penjualan_Tunai', NID)),
                'ipunotransaksi' => $nomor,
                'iputanggal' => tgl_database($_POST['tgl']),
                'ipukontak' => $_POST['kontak'],
                'ipukaryawan' => $_POST['karyawan'],
                'ipucatatan' => $_POST['catatan'],
                'ipuuraian' => 'Point Of Sale',
                'ipujenispajak' => $_POST['pajak'],
                'iputipepenjualan' => 1,
                'ipustatus' => 1,
                'iputransaksi' => 2,
                'iputotalpajak' => $_POST['totalpajak'],
                'iputotaltransaksi' => $_POST['totaltrans'],
                'ipucreateu' => $this->session->id
            );
            $this->db->trans_start();
            $this->db->insert('einvoicepenjualanu', $data_header);
            $id = $this->db->insert_id();

            // Insert Header Trans
            $data_header_bkg = array(
                'susumber' => $this->M_transaksi->prefixtrans(element('PJ_Penjualan_Tunai', NID)),
                'sutanggal' => tgl_database($_POST['tgl']),
                'sunotransaksi' => $nomor,
                'sukontak' => $_POST['kontak'],
                'suuraian' => 'Point Of Sale',
                'sustatus' => 1,
                'supajak' => $_POST['pajak'],
                'sutotalkas' => $_POST['cash'],
                'sutotalkartudebit' => $_POST['debit'],
                'sunokartudebit' => $_POST['debitno'],
                'subankdebit' => $_POST['debitbank'],
                'sutotalkartukredit' => $_POST['credit'],
                'sunokartukredit' => $_POST['creditno'],
                'subankkredit' => $_POST['creditbank'],
                'sutotaltransaksi' => $_POST['totaltrans'],
                'sutotalpajak' => $_POST['totalpajak'],
                'sutotalbayar' => $_POST['totalbayar'],
                'sutotalsisa' => $_POST['totalsisa'],
                'sutotaldp' => $_POST['diskon'],
                'sucreateu' => $this->session->id
            );
            $this->db->insert('fstoku', $data_header_bkg);
            $idbkg = $this->db->insert_id();

            // Update idbkg di header invoice
            $invoice = array(
                'ipunobkg' => $idbkg
            );
            $this->db->where('ipuid', $id);
            $this->db->update('einvoicepenjualanu', $invoice);

            // Insert Detil Trans
            $r = 1;
            $d = json_decode($_POST['detil']);
            foreach ($d as $item) {
                $dataitem = _getitemdata($item->item);
                $satuand = $dataitem['isatuan'];
                $satuan2 = $dataitem['isatuan2'];
                $satuan3 = $dataitem['isatuan3'];
                $satuan4 = $dataitem['isatuan4'];
                $satuan5 = $dataitem['isatuan5'];
                $satuan6 = $dataitem['isatuan6'];
                $konversi2 = $dataitem['isatkonversi2'];
                $konversi3 = $dataitem['isatkonversi3'];
                $konversi4 = $dataitem['isatkonversi4'];
                $konversi5 = $dataitem['isatkonversi5'];
                $konversi6 = $dataitem['isatkonversi6'];
                $ipdkeluard = $item->qty;

                if ($item->satuan == $satuan2) {
                    $ipdkeluard = $item->qty * $konversi2;
                } elseif ($item->satuan == $satuan3) {
                    $ipdkeluard = $item->qty * $konversi3;
                } elseif ($item->satuan == $satuan4) {
                    $ipdkeluard = $item->qty * $konversi4;
                } elseif ($item->satuan == $satuan5) {
                    $ipdkeluard = $item->qty * $konversi5;
                } elseif ($item->satuan == $satuan6) {
                    $ipdkeluard = $item->qty * $konversi6;
                } else {
                    $ipdkeluard = $item->qty;
                }

                $data_detil = array(
                    'ipdidipu' => $id,
                    'ipdurutan' => $r,
                    'ipditem' => $item->item,
                    'ipdkeluar' => $item->qty,
                    'ipdkeluard' => $ipdkeluard,
                    'ipdharga' => $item->harga,
                    'ipddiskon' => $item->diskon,
                    'ipdsatuan' => $item->satuan,
                    'ipdsatuand' => $satuand,
                    'ipddiskonp' => $item->persen,
                    'ipdgudang' => $item->gudang,
                    'ipdsju' => $idbkg
                );
                $this->db->insert('einvoicepenjualand', $data_detil);

                $data_detil_bkg = array(
                    'sdidsu' => $idbkg,
                    'sdurutan' => $r,
                    'sdsumber' => $this->M_transaksi->prefixtrans(element('PJ_Penjualan_Tunai', NID)),
                    'sditem' => $item->item,
                    'sdkeluar' => $item->qty,
                    'sdkeluard' => $ipdkeluard,
                    'sdharga' => $item->harga,
                    'sddiskon' => $item->diskon,
                    'sddiskonpersen' => $item->persen,
                    'sdsatuan' => $item->satuan,
                    'sdsatuand' => $satuand,
                    'sdgudang' => $item->gudang,
                    'sdfaktur' => $item->qty,
                    'sdhargainvoice' => $item->harga,
                    'sddiskoninvoice' => $item->diskon
                );
                $this->db->insert('fstokd', $data_detil_bkg);
                $r++;
            }

            $sql = "CALL SP_HITUNG_HPP_ADD(" . $idbkg . ")";
            $this->db->query($sql);

            $sql = "CALL SP_JURNAL_PENJUALAN_TUNAI_ADD(" . $idbkg . ")";
            $this->db->query($sql);

            // USERLOG
            $uactivity = _anomor(element('PJ_Penjualan_Tunai', NID));
            $uactivity = $uactivity['keterangan'];
            $userlog = array(
                'uluser' => $this->session->id,
                'ulusername' => $this->session->nama,
                'ulcomputer' => $this->input->ip_address(),
                'ulactivity' => $uactivity . ' ' . $nomor,
                'ullevel' => 1
            );
            $this->db->insert('auserlog', $userlog);

            if (!empty($_POST['idtunda']) || $_POST['idtunda'] != '') {
                //hapus id tunda sebelumnya
                $this->db->where('apuid', $this->input->post('idtunda'));
                $this->db->delete('eantripenjualanu');
                $this->db->where('apdidapu', $this->input->post('idtunda'));
                $this->db->delete('eantripenjualand');
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $callback = array(
                    'pesan' => 'rollback',
                    'nomor' => ''
                );
                return json_encode($callback);
            } else {
                $callback = array(
                    'pesan' => 'sukses',
                    'nomor' => $id
                );
                return json_encode($callback);
            }
        }
    }


    function hapusTransaksiMulti()
    {
        $data = json_decode($this->input->post('data'));

        foreach ($data as $item) {
            $id = $item->id;
            $nomor = $item->nomor;

            $idbkg = $this->ambilidbkg($id);

            if (empty($idbkg)) {
                //               return "sukses";
            }

            $this->db->trans_start();

            $sql = "CALL SP_HITUNG_HPP_DEL(" . $idbkg . ")";
            $this->db->query($sql);

            $sql = "CALL SP_JURNAL_HPP_OUT_DEL(" . $idbkg . ")";
            $this->db->query($sql);

            //hapus Header Transaksi Penjualan
            $this->db->where('ipuid', $id);
            $this->db->delete('einvoicepenjualanu');

            //hapus Header Transaksi BKG
            $this->db->where('suid', $id);
            $this->db->delete('fstoku');

            //hapus Detil Transaksi Penjualan
            $this->db->where('ipdidipu', $id);
            $this->db->delete('einvoicepenjualand');

            //hapus Detil Transaksi BKG
            $this->db->where('sdidsu', $id);
            $this->db->delete('fstokd');

            // USERLOG
            $uactivity = _anomor(element('PJ_Penjualan_Tunai', NID));
            $uactivity = $uactivity['keterangan'];
            $userlog = array(
                'uluser' => $this->session->id,
                'ulusername' => $this->session->nama,
                'ulcomputer' => $this->input->ip_address(),
                'ulactivity' => $uactivity . ' ' . $nomor,
                'ullevel' => 0
            );
            $this->db->insert('auserlog', $userlog);

            $this->db->trans_complete();
        }

        if ($this->db->trans_status() === FALSE) {
            return "rollback";
        } else {
            return "sukses";
        }
    }

    function hapusTransaksi()
    {

        $id = $_POST['id'];
        $nomor = $_POST['nomor'];

        $idbkg = $this->ambilidbkg($id);

        if (empty($idbkg)) {
            return "sukses";
        }

        $this->db->trans_start();

        $sql = "CALL SP_HITUNG_HPP_DEL(" . $idbkg . ")";
        $this->db->query($sql);

        $sql = "CALL SP_JURNAL_HPP_OUT_DEL(" . $idbkg . ")";
        $this->db->query($sql);

        //hapus Header Transaksi Penjualan
        $this->db->where('ipuid', $id);
        $this->db->delete('einvoicepenjualanu');

        //hapus Header Transaksi BKG
        $this->db->where('suid', $id);
        $this->db->delete('fstoku');

        //hapus Detil Transaksi Penjualan
        $this->db->where('ipdidipu', $id);
        $this->db->delete('einvoicepenjualand');

        //hapus Detil Transaksi BKG
        $this->db->where('sdidsu', $id);
        $this->db->delete('fstokd');

        // USERLOG
        $uactivity = _anomor(element('PJ_Penjualan_Tunai', NID));
        $uactivity = $uactivity['keterangan'];
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity . ' ' . $nomor,
            'ullevel' => 0
        );
        $this->db->insert('auserlog', $userlog);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return "rollback";
        } else {
            return "sukses";
        }
    }

    function hapusTunda()
    {

        $id = $_POST['id'];

        $this->db->trans_start();

        //hapus Header Transaksi Tunda
        $this->db->where('apuid', $id);
        $this->db->delete('eantripenjualanu');

        //hapus Detil Transaksi Tunda
        $this->db->where('apdidapu', $id);
        $this->db->delete('eantripenjualand');

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return "rollback";
        } else {
            return "sukses";
        }
    }

    function autonumber($tgl)
    {
        // Ambil prefix dari database berdasarkan NID jenis transaksi
        $nomor1 = $this->M_transaksi->prefixtrans(element('PJ_Penjualan_Tunai', NID)); // contoh: '25'

        // Ubah tanggal menjadi format YYMMDD → 250531
        $nomor2 = tgl_notrans($tgl); // hasil: '250531'

        // Gabungkan prefix dengan tanggal
        $prefix = $nomor1 . $nomor2; // contoh: '25250531'

        // Panjang prefix untuk keperluan query
        $notrans_length = strlen($prefix); // 8 karakter

        // SQL: cari nomor terbesar (4 digit terakhir) berdasarkan prefix
        $sql = "SELECT MAX(RIGHT(ipunotransaksi, 4)) as maks 
                  FROM einvoicepenjualanu 
                 WHERE LEFT(ipunotransaksi, $notrans_length) = '$prefix'";

        $query = $this->db->query($sql);
        $maks = $query->row()->maks ?? 0;

        // Hitung nomor berikutnya
        $nomor_baru = (int)$maks + 1;

        // Amankan maksimal 4 digit
        if ($nomor_baru > 9999) {
            throw new Exception("Nomor transaksi sudah mencapai batas maksimal (9999) pada tanggal ini.");
        }

        // Format ke 4 digit, misal: 3 → 0003
        $nomor4digit = str_pad($nomor_baru, 4, '0', STR_PAD_LEFT);

        // Hasil akhir: prefix + nomor 4 digit
        return $prefix . $nomor4digit;
    }

    function ambilidbkg($id)
    {
        $this->db->where('ipuid', $id);
        $hasil = $this->db->get('einvoicepenjualanu');

        foreach ($hasil->result() as $row) {
            $nomor = $row->IPUNOBKG;
            return $nomor;
        }
    }


    function tundaTransaksi()
    {
        $this->db->trans_start();
        // Insert Header Trans
        $data_header = array(
            'aputanggal' => tgl_database($this->input->post('tgl')),
            'apukontak' => $this->input->post('kontak'),
            'apukaryawan' => $this->input->post('karyawan'),
            'apucatatan' => $this->input->post('catatan'),
            'aputotalpajak' => $this->input->post('totalpajak'),
            'aputotaltransaksi' => $this->input->post('totaltrans'),
            'apucreateu' => $this->session->id
        );
        $this->db->insert('eantripenjualanu', $data_header);
        $id = $this->db->insert_id();

        // Insert Detil Trans
        $r = 1;
        $d = json_decode($_POST['detil']);
        foreach ($d as $item) {
            $data_detil = array(
                'apdidapu' => $id,
                'apdurutan' => $r,
                'apditem' => $item->item,
                'apdkeluar' => $item->qty,
                'apdharga' => $item->harga,
                'apddiskon' => $item->diskon,
                'apddiskonp' => $item->persen,
                'apdsatuan' => $item->satuan,
                'apdgudang' => $item->gudang
            );
            $this->db->insert('eantripenjualand', $data_detil);
            $r++;
        }

        if (!empty($_POST['idtunda']) || $_POST['idtunda'] != '') {
            //hapus id tunda sebelumnya
            $this->db->where('apuid', $this->input->post('idtunda'));
            $this->db->delete('eantripenjualanu');
            $this->db->where('apdidapu', $this->input->post('idtunda'));
            $this->db->delete('eantripenjualand');
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $callback = array(
                'pesan' => 'rollback',
                'nomor' => ''
            );
            return json_encode($callback);
        } else {
            $callback = array(
                'pesan' => 'sukses',
                'nomor' => $id
            );
            return json_encode($callback);
        }
    }
}
