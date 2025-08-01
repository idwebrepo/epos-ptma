<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

   function __construct() { 
    	parent::__construct();
      if(!$this->session->has_userdata('nama')){
        redirect(base_url('404'));
      }          
  	    $this->load->model('M_transaksi');
   }

   function index($idreport='',$id='',$mode='',$emailto=''){

      if($this->input->post('mode') !== null){
        $mode = $this->input->post('mode');
      }

      if($idreport==''){
        $idreport = $_POST['id'];
      }

      $r = $this->gettabelreport($idreport);
      $r = json_decode($r);

      foreach ($r->data as $key) {
          $title = $key->ARNAME;
          $title2 = $key->ARNAME2;
          $link = $key->ARLINK;
          $orientasi = $key->ARPAPERORINTED == 1 ? 'P' : 'L' ;
          $size = $key->ARPAPERSIZE == 1 ? 'Letter' : ($key->ARPAPERSIZE == 2 ? 'Legal' : 'A4') ; 
          $ml = $key->ARMARGINLEFT;
          $mt = $key->ARMARGINTOP;
          $useLogo = $key->ARLOGO;
      }

      $this->load->model('M_Settings_Info');        
      $infosettings = $this->M_Settings_Info->infoPerusahaan();
      foreach ($infosettings->result() as $row) {
          $company = $row->inama;
          $addr = $row->ialamat1.' '.$row->ikota.' '.$row->ipropinsi;
          $kodepos = $row->ikodepos;
          $email = $row->iemail;
          $phone = $row->itelepon1;
          $digitqty = $row->idecimalqty;
          $gambar = $row->ilogo;
          $protocol = $row->imailprotocol;
          $host = $row->imailhost;          
          $port = $row->imailport; 
          $sender = $row->imailsender;
          $password = $row->imailpassword;                             
      }

      $data['id'] = $id;      
      $data['title'] = $title;
      $data['title2'] = $title2;
      $data['company_name'] = $company;
      $data['company_addr'] = $addr;      
      $data['company_kodepos'] = $kodepos;            
      $data['company_email'] = $email; 
      $data['company_phone'] = $phone;
      $data['digitqty'] = $digitqty;   
      $data['logo'] = $gambar;         
      $data['use_logo'] = $useLogo;
      
      // var_dump($data);
      // exit;

      if($mode==1 || empty($mode)){ // pdf print preview
          $report = $this->load->view('modul/laporan/'.$link, $data, TRUE);
          $filename = $title.".pdf";
          if($link=='formulir-pos-printer1'){
            $mpdf = new \Mpdf\Mpdf(['format' => [48,200],'margin_left' => $ml,'margin_top' => $mt,'margin_right' => 4,'margin_bottom' => 0]);
            $mpdf->simpleTables = true;                      
          }elseif($link=='formulir-pos-printer2'){
            $mpdf = new \Mpdf\Mpdf(['format' => [70,200],'margin_left' => $ml,'margin_top' => $mt,'margin_right' => 4,'margin_bottom' => 0]);
            $mpdf->simpleTables = true;                      
          }else{
            $mpdf = new \Mpdf\Mpdf(['format' => $size.'-'.$orientasi,'margin_left' => $ml,'margin_top' => $mt,'margin_right' => 10,'margin_bottom' => 0,'margin_header' => 1]);
          }
          $mpdf->useSubstitutions = false;          
          $mpdf->SetTitle($title);  
          $mpdf->SetAuthor($this->config->item('hak_cipta'));  
          //$mpdf->SetFooter('Page : {PAGENO}');    
          //$mpdf->SetHTMLHeader('<center>Ini Header Page</center>','EVEN', TRUE);
          ini_set("pcre.backtrack_limit", "5000000");
          $mpdf->WriteHTML($report);
          $mpdf->Output($filename,\Mpdf\Output\Destination::INLINE);    
      }elseif($mode==3){ // download excel
          header("Content-Type: application/vnd.ms-excel; charset=utf-8");
          header("Content-Disposition: attachment; filename=".$title.".xls");
          header("Expires: 0");
          header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
          header("Cache-Control: private",false);        
          echo $this->load->view('modul/laporan/xls/'.$link, $data, TRUE);
      }elseif($mode==4){ // Send PDF to email

          $report = $this->load->view('modul/laporan/'.$link, $data, TRUE);
          $mpdf = new \Mpdf\Mpdf(['format' => $size.'-'.$orientasi,'margin_left' => $ml,'margin_top' => $mt]);
          $mpdf->SetTitle($title);  
          $mpdf->SetAuthor($this->config->item('hak_cipta'));  
          $mpdf->useSubstitutions = false;          
          //$mpdf->SetFooter('Page : {PAGENO}');    
          ini_set("pcre.backtrack_limit", "5000000");          
          $mpdf->WriteHTML($report);
          $content = $mpdf->Output('','S');
          $filename = $title.".pdf";

          $config = Array(
            'protocol' => $protocol,
            'smtp_host' => $host,
            'smtp_port' => $port,
            'smtp_user' => $sender,
            'smtp_pass' => $password,
            'mailtype' => 'html',
            'charset' => 'iso-8859-1',
            'wordwrap' => TRUE
          );

          $message = nl2br($this->input->post('contentmail'));
          $this->load->library('email', $config);
          $this->email->set_newline("\r\n");
          $this->email->from($company);

          if($this->input->post('mailto') !== null){
            $this->email->to($this->input->post('mailto'));                      
          } else {
            $this->email->to($emailto);                                  
          }

          if(empty($this->input->post('subject')) || $this->input->post('subject')=='') {
            $this->email->subject($title);
          } else {
            $this->email->subject($this->input->post('subject'));
          }
          $this->email->message($message);
          $this->email->attach($content, 'attachment', $filename, 'application/pdf');

          if($this->email->send())
          {
            echo "sukses";
          }
          else
          {
            echo $this->email->print_debugger();
          }    

      } else { // download pdf
          $report = $this->load->view('modul/laporan/'.$link, $data, TRUE);
          $filename = $title.".pdf";
          $mpdf = new \Mpdf\Mpdf(['format' => $size.'-'.$orientasi,'margin_left' => $ml,'margin_top' => $mt]);
          $mpdf->SetTitle($title);  
          $mpdf->SetAuthor($this->config->item('hak_cipta'));  
          $mpdf->useSubstitutions = false;          
          //$mpdf->SetFooter('Page : {PAGENO}');    
          ini_set("pcre.backtrack_limit", "5000000");          
          $mpdf->WriteHTML($report);
          $mpdf->Output($filename,\Mpdf\Output\Destination::DOWNLOAD);    
      }

   }

   function preview($link,$id){

      $m = $this->getreportid($link);
      $m = json_decode($m);
      $idreport = "";

      foreach ($m->data as $key) {
        $idreport = $key->mreport;
      }

      $this->index($idreport,$id);
   }

   function multipreview(){
      $no="";
      for($i=4;$i<=$this->uri->total_segments();$i++){
        $no .= $this->uri->segment($i).","; 
      }

      $no = substr($no, 0,-1);
      $m = $this->getreportid($this->uri->segment(3));
      $m = json_decode($m);
      $idreport = "";

      foreach ($m->data as $key) {
        $idreport = $key->mreport;
      }

      $this->index($idreport,$no);

   }

   function multimail(){
      $no="";
      for($i=5;$i<=$this->uri->total_segments();$i++){
        $no .= $this->uri->segment($i).","; 
      }

      $no = substr($no, 0,-1);
      $m = $this->getreportid($this->uri->segment(3));
      $m = json_decode($m);
      $idreport = "";

      foreach ($m->data as $key) {
        $idreport = $key->mreport;
      }

      $idkontak = $this->uri->segment(4);
      $query = "SELECT k1email FROM bkontak
                 WHERE kid=".$idkontak;
     
      $emailto = json_decode($this->M_transaksi->get_data_query($query));

      foreach ($emailto->data as $key) {
        $emailto = $key->k1email;
      }

      $this->index($idreport,$no, 4,$emailto);

   }

   function getparent(){
        $query = "SELECT A.* FROM amenu A INNER JOIN ausermenu B ON A.MID=B.AUIDMENU AND B.AUAPPROVE=1 AND B.AUIDUSER=".$this->session->id." 
        			 WHERE A.mtype=1 AND A.mactive=1 AND A.mparent=0 ORDER BY A.murutan ASC";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }

   function getreportlist(){
        $query = "SELECT A.*,C.arname 'file',C.arid 'rptid' FROM amenu A INNER JOIN ausermenu B ON A.MID=B.AUIDMENU AND B.AUAPPROVE=1 AND B.AUIDUSER=".$this->session->id." 
        			 LEFT JOIN areport C ON A.mreport=C.arid  
                   WHERE A.mtype=1 AND A.mactive=1 AND A.mparent=".$_POST['id']." ORDER BY A.murutan ASC";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }   

   function getinforeport(){
        if($_POST['id'] == '' || $_POST['id'] == null) {
          echo _pesanError("Data tidak ditemukan !");
          exit;
        }

        $query = "SELECT A.*
                    FROM areport A
                   WHERE A.arid='".$_POST['id']."'";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);    
   }

   function gettabelreport($id){
        $query = "SELECT A.*
                    FROM areport A
                   WHERE A.arid='".$id."'";
       
        return $this->M_transaksi->get_data_query($query);    
   }

   function getreportid($param){
        $param = str_replace('-', '/', $param);
        $query = "SELECT A.mreport 
                    FROM amenu A
                   WHERE A.mlink='".$param."'";
        return $this->M_transaksi->get_data_query($query);        
   }

   function gettabelvalue($tabel,$kolom,$where,$iswhere){
        $query = "SELECT $kolom 
                    FROM $tabel
                   WHERE $where='".$iswhere."'";
        return $this->M_transaksi->get_data_query($query);              
   }

   function barcode(){

      $data['iditem'] = $this->uri->segment(3);
      $data['ukuran'] =  $this->uri->segment(4);
      $data['jumlah'] = $this->uri->segment(5);
      $data['bentuk'] = $this->uri->segment(6);

      if($this->uri->segment(6)==1){
        $report = $this->load->view('modul/laporan/label-barcode-1', $data, TRUE);
      } else {
        $report = $this->load->view('modul/laporan/label-barcode-2', $data, TRUE);        
      }
      $filename = "barcode.pdf";
      $mpdf = new \Mpdf\Mpdf(['format' => [$data['ukuran'],2000],'margin_left' => 0,'margin_top' => 0,'margin_right' => 0,'margin_bottom' => 0]);
      $mpdf->simpleTables = true;                            
      $mpdf->useSubstitutions = false;          
      $mpdf->SetTitle("Cetak Barcode");  
      $mpdf->SetAuthor($this->config->item('hak_cipta'));  
      ini_set("pcre.backtrack_limit", "5000000");
      $mpdf->WriteHTML($report);
      $mpdf->Output($filename,\Mpdf\Output\Destination::INLINE);    

   }

}
