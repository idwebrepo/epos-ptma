<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resetpassword extends CI_Controller {

    function __construct(){
        parent::__construct();     
        $this->load->helper('url');        
        $this->load->helper('captcha');                
    }

    function index()
    {
        $data = $this->ambildata();

        if($this->input->post('email') != ''){
            $this->send_reset_email();
        }else{                             
            $this->session->unset_userdata('captcha');
            $this->session->set_userdata('captcha',$data['captcha_word']);            
            $this->load->view('include/header', $data);
            $this->load->view('reset-password', $data);                
            $this->load->view('include/footer', $data);
        }
    }

    function send_reset_email(){
        $data = $this->ambildata();

        $namauser = "";

        $user_captcha = $this->input->post('captcha');
        $sess_captcha = $this->session->captcha;

        $this->session->unset_userdata('captcha');
        $this->session->set_userdata('captcha',$data['captcha_word']);

        $isvalidemail = column_value('unama','auser',
            array('uemail' => $this->input->post('email')));
        
        foreach ($isvalidemail->result() as $row) {
            $namauser = $row->unama;
        }

//        echo $this->session->captcha;

        if($user_captcha != $sess_captcha){
            $data['pesan'] = "<i class='fas fa-times'></i> Kode keamanan yang dimasukan salah..";

            $this->load->view('include/header', $data);
            $this->load->view('reset-password', $data);                
            $this->load->view('include/footer', $data);            
        } elseif(empty($namauser)){
            $data['pesan'] = "<i class='fas fa-times'></i> Alamat email yang dimasukkan tidak valid atau tidak terdaftar..";

            $this->load->view('include/header', $data);
            $this->load->view('reset-password', $data);                
            $this->load->view('include/footer', $data);            
        } else {
            $this->load->model('M_Admin_User');        
            $token = $this->M_Admin_User->ubahTokenReset();

            $this->load->model('M_Settings_Info');        
            $infosettings = $this->M_Settings_Info->infoPerusahaan();
            foreach ($infosettings->result() as $row) {
                $company = $row->inama;
                $addr = $row->ialamat1.' '.$row->ikota.' '.$row->ipropinsi;
                $kodepos = $row->ikodepos;
                $email = $row->iemail;
                $phone = $row->itelepon1;
                $gambar = $row->ilogo;
                $protocol = $row->imailprotocol;
                $host = $row->imailhost;          
                $port = $row->imailport; 
                $sender = $row->imailsender;
                $password = $row->imailpassword;                             
            }        

            $config = Array(
                'protocol' => $protocol,
                'smtp_host' => $host,
                'smtp_port' => $port,
                'smtp_user' => $sender,
                'smtp_pass' => $password,
                'mailtype' => 'html',
                'newline' => '\r\n',
                'smtp_timeout' => '20',
                'charset' => 'iso-8859-1',
                'wordwrap' => TRUE
            );

            $message = nl2br("Halo ".$namauser.",\n \nGunakan link dibawah ini untuk melakukan reset password \n \n".base_url().'ubahpassword?keytoken='.$token.
                "\n \n \n <b>".$company."</b> \n".$addr."\n".$phone);
            $this->load->library('email', $config);
            $this->email->set_newline("\r\n");
            $this->email->from($company);
            $this->email->to($this->input->post('email'));                                  
            $this->email->subject('Permintaan Reset Password');
            $this->email->message($message);

            if($this->email->send())
            {
                $data['pesan'] = "<i class='fas fa-check'></i> Permintaan reset password berhasil. Silahkan cek email anda.";
            }
            else
            {
                $data['pesan'] = $this->email->print_debugger();
                $data['pesan'] .= "<i class='fas fa-times'></i> Permintaan reset password gagal.";
            }    

            $this->load->view('include/header', $data);
            $this->load->view('reset-password', $data);                
            $this->load->view('include/footer', $data);
        }
    }

    function ambildata(){
        $config = array(
            'font_path'     => '', 
            'img_path'      => './assets/dist/captcha/',
            'img_url'       => base_url().'assets/dist/captcha/',
            'img_width'     => '120',
            'img_height'    => 40,
            'word_length'   => 5,
            'font_size'     => 16,
            'expiration'    => 7200,            
            'pool'      => '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',            
            'colors'        => [
                    'background'=> [255, 200, 200],
                    'border'    => [255, 255, 255],
                    'text'      => [0, 0, 200],
                    'grid'      => [255, 200, 255]
                ]            
        );                

        $captcha = create_captcha($config);

        $data['title'] = 'Reset Password | '.$this->config->item('app_name'); 
        $data['app_name'] = $this->config->item('app_name');   
        $data['vendor_text'] = $this->config->item('vendor_text');
        $data['captcha'] = $captcha['image'];
        $data['captcha_word'] = $captcha['word'];                                    

        return $data;
    }

}
