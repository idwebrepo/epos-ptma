<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('M_login');
    }

    function index()
    {
        if ($this->session->has_userdata('nama')) {
            redirect(base_url('dasbor'));
        } else {
            if ($this->input->post('username') !== null && $this->input->post('username') !== '') {
                $this->aksi_login();
            } else {
                $data['title'] = 'Login | ' . $this->config->item('app_name');
                $data['app_name'] = $this->config->item('app_name');
                $data['vendor_text'] = $this->config->item('vendor_text');
                $this->load->view('include/header', $data);
                $this->load->view('login', $data);
                $this->load->view('include/footer', $data);
            }
        }
    }

    function aksi_login()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $id = "";
        $namauser = "";

        $where = array(
            'unama' => $username,
            'upassword' => hash('sha512', md5($password)),
            'uactive' => 1
        );

        $cek = $this->M_login->cek_login("auser", $where);

        if ($cek->num_rows() > 0) {
            foreach ($cek->result() as $row) {
                $id = $row->uid;
                $kode = $row->ukode;
                $nama = $row->unama;
                $namauser = $row->unamalengkap;
                $kodeunit = $row->kodeunit;
                $namaunit = $row->utnama;
            }

            $data_session = array(
                'user' => strtolower($nama),
                'nama' => ucwords($namauser),
                'id' => $id,
                'kode' => $kode,
                'status' => "login",
                'kodeunit' => $kodeunit,
                'namaunit' => $namaunit
            );

            $this->session->set_userdata($data_session);
            redirect(base_url('dasbor'));
        } else {

            $data['pesan'] = "<div class=\"alert alert-danger text-sm rounded-1\">
                                <center>
                                Login gagal, mohon periksa kembali login anda...
                                </center>
                              </div>";

            $data['title'] = 'Login | ' . $this->config->item('app_name');
            $data['app_name'] = $this->config->item('app_name');
            $data['vendor_text'] = $this->config->item('vendor_text');
            $data['username'] = $username;
            $this->load->view('include/header', $data);
            $this->load->view('login', $data);
            $this->load->view('include/footer', $data);
        }
    }

    function aksi_logout()
    {
        $this->session->sess_destroy();
        redirect(base_url());
    }
}
