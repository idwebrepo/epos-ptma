<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Client extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper(array('sync_toko'));
    }

    public function index()
    {
        echo 'Hello';
    }

    public function fetch_data()
    {

        $key = KEY_TOKO;

        $data = array(
            'key' => $key
        );

        $data = toko_epesantren("get_data", $data);

        $customers = json_decode($data, true);

        if ($customers['status'] == 1) {

            foreach ($customers['data'] as $customer) {
                $input = array(
                    'KKODE'      => $customer['nis'],
                    'KNAMA'      => $customer['nama'],
                    'KSALDO'     => $customer['saldo'],
                    'KLIMIT'     => $customer['limit'],
                    'KRFID'      => $customer['rfid'],
                    'KPIN'       => $customer['pin'],
                    'KSTATUS'    => $customer['status'],
                    'KTIPE'      => '2'
                );

                // Cek apakah KKODE sudah ada
                $existing = $this->db->get_where('bkontak', ['KKODE' => $customer['nis']])->row();

                if ($existing) {
                    // Jika ada, lakukan update
                    $this->db->where('KKODE', $customer['nis']);
                    $insert = $this->db->update('bkontak', $input);
                } else {
                    // Jika tidak ada, lakukan insert
                    $input['KKODE'] = $customer['nis'];
                    $insert = $this->db->insert('bkontak', $input);
                }
            }

            if ($insert) {
                echo 'Success';
            } else {
                echo 'Failed';
            }
        } else {
            echo "Cant connect to sync data";
        }
    }

    function fetch_person()
    {
        $nis = $this->input->post('nis');
        $key = KEY_TOKO;

        $data = array(
            'key' => $key,
            "nis" => $nis
        );

        $data = toko_epesantren("get_data_first", $data);

        $customers = json_decode($data, true);

        if ($customers['status'] == 1) {

            $customer = $customers['data'];

            $input = array(
                'KNAMA'      => $customer['nama'],
                'KSALDO'     => $customer['saldo'],
                'KRFID'      => $customer['rfid'],
                'KPIN'       => $customer['pin'],
                'KSTATUS'    => $customer['status']
            );

            if (isset($customer['limit']))  $input['KLIMIT']  = $customer['limit'];

            $this->db->where('KKODE', $customer['nis']);
            $update = $this->db->update('bkontak', $input);

            echo $this->db->last_query();
            exit;
            if ($update) {
                echo 'Success';
            } else {
                echo 'Failed';
            }
        }
    }
}
