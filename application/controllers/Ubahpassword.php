<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ubahpassword extends CI_Controller {

    function __construct(){
        parent::__construct();     
        $this->load->helper('url');        
    }

    function index()
    {
        $data['title'] = 'Reset Password | '.$this->config->item('app_name'); 
        $data['app_name'] = $this->config->item('app_name');   
        $data['vendor_text'] = $this->config->item('vendor_text');

        if($this->input->post('password') !== null && $this->input->post('password') !== '' && $this->input->post('key') !== ''){
            $this->simpan_password();
        }else{                   
            $data['token'] = $this->input->get('keytoken');            
    
            $this->load->view('include/header', $data);
            $this->load->view('reset-password-verified', $data);                
            $this->load->view('include/footer', $data);                
        }

    }

    function simpan_password(){
            $this->load->model('M_Admin_User');        
            $aksi = $this->M_Admin_User->simpanPasswordReset();

            $data['title'] = 'Reset Password | '.$this->config->item('app_name'); 
            $data['app_name'] = $this->config->item('app_name');   
            $data['vendor_text'] = $this->config->item('vendor_text');
            
            if($aksi=='sukses') {
                redirect(base_url('login'));                
            } else {     
                $data['token'] = $this->input->post('key');           
                $data['pesan'] = "<i class='fas fa-times'></i> Permintaan reset password tidak valid...";     

                $this->load->view('include/header', $data);
                $this->load->view('reset-password-verified', $data);                
                $this->load->view('include/footer', $data);                                
            }                   
    }

}