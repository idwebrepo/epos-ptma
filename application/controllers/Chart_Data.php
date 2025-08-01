<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chart_Data extends CI_Controller {

  function __construct() { 
      parent::__construct();
      if(!$this->session->has_userdata('nama')){
        echo "Session login tidak ada..";
      }            
      $this->load->model('M_transaksi');      
  }

  function sales() {
      $tahun = $_POST['year'];
      $transcode = $this->M_transaksi->prefixtrans(element('PJ_Order_Penjualan',NID));        
      $transcode2 = $this->M_transaksi->prefixtrans(element('PJ_Faktur_Penjualan',NID));              
      $transcode3 = $this->M_transaksi->prefixtrans(element('PJ_Penjualan_Tunai',NID));
      $transcode4 = $this->M_transaksi->prefixtrans(element('SV_Penjualan_Tunai',NID));      

      $query = "
    SELECT 1 'bulan', SUM(A.sousubtotal) 'total' FROM esalesorderu A WHERE year(A.soutanggal)='".$tahun."' AND month(A.soutanggal)=1
    AND A.sousumber='".$transcode."'
    UNION
    SELECT 2 'bulan', SUM(A.sousubtotal) 'total' FROM esalesorderu A WHERE year(A.soutanggal)='".$tahun."' AND month(A.soutanggal)=2
    AND A.sousumber='".$transcode."'
    UNION
    SELECT 3 'bulan', SUM(A.sousubtotal) 'total' FROM esalesorderu A WHERE year(A.soutanggal)='".$tahun."' AND month(A.soutanggal)=3
    AND A.sousumber='".$transcode."'
    UNION
    SELECT 4 'bulan', SUM(A.sousubtotal) 'total' FROM esalesorderu A WHERE year(A.soutanggal)='".$tahun."' AND month(A.soutanggal)=4
    AND A.sousumber='".$transcode."'
    UNION
    SELECT 5 'bulan', SUM(A.sousubtotal) 'total' FROM esalesorderu A WHERE year(A.soutanggal)='".$tahun."' AND month(A.soutanggal)=5
    AND A.sousumber='".$transcode."'
    UNION
    SELECT 6 'bulan', SUM(A.sousubtotal) 'total' FROM esalesorderu A WHERE year(A.soutanggal)='".$tahun."' AND month(A.soutanggal)=6
    AND A.sousumber='".$transcode."'
    UNION
    SELECT 7 'bulan', SUM(A.sousubtotal) 'total' FROM esalesorderu A WHERE year(A.soutanggal)='".$tahun."' AND month(A.soutanggal)=7
    AND A.sousumber='".$transcode."'
    UNION
    SELECT 8 'bulan', SUM(A.sousubtotal) 'total' FROM esalesorderu A WHERE year(A.soutanggal)='".$tahun."' AND month(A.soutanggal)=8
    AND A.sousumber='".$transcode."'
    UNION
    SELECT 9 'bulan', SUM(A.sousubtotal) 'total' FROM esalesorderu A WHERE year(A.soutanggal)='".$tahun."' AND month(A.soutanggal)=9
    AND A.sousumber='".$transcode."'
    UNION
    SELECT 10 'bulan', SUM(A.sousubtotal) 'total' FROM esalesorderu A WHERE year(A.soutanggal)='".$tahun."' AND month(A.soutanggal)=10
    AND A.sousumber='".$transcode."'
    UNION
    SELECT 11 'bulan', SUM(A.sousubtotal) 'total' FROM esalesorderu A WHERE year(A.soutanggal)='".$tahun."' AND month(A.soutanggal)=11
    AND A.sousumber='".$transcode."'
    UNION
    SELECT 12 'bulan', SUM(A.sousubtotal) 'total' FROM esalesorderu A WHERE year(A.soutanggal)='".$tahun."' AND month(A.soutanggal)=12
    AND A.sousumber='".$transcode."'
      ";

      $query2 = "
    SELECT 1 'bulan', SUM(A.iputotaltransaksi-A.iputotalpajak) 'total' FROM einvoicepenjualanu A WHERE year(A.iputanggal)='".$tahun."' AND month(A.iputanggal)=1 AND A.ipusumber IN ('".$transcode2."', '".$transcode3."', '".$transcode4."')
    UNION
    SELECT 2 'bulan', SUM(A.iputotaltransaksi-A.iputotalpajak) 'total' FROM einvoicepenjualanu A WHERE year(A.iputanggal)='".$tahun."' AND month(A.iputanggal)=2 AND A.ipusumber IN ('".$transcode2."', '".$transcode3."', '".$transcode4."')
    UNION
    SELECT 3 'bulan', SUM(A.iputotaltransaksi-A.iputotalpajak) 'total' FROM einvoicepenjualanu A WHERE year(A.iputanggal)='".$tahun."' AND month(A.iputanggal)=3 AND A.ipusumber IN ('".$transcode2."', '".$transcode3."', '".$transcode4."')
    UNION
    SELECT 4 'bulan', SUM(A.iputotaltransaksi-A.iputotalpajak) 'total' FROM einvoicepenjualanu A WHERE year(A.iputanggal)='".$tahun."' AND month(A.iputanggal)=4 AND A.ipusumber IN ('".$transcode2."', '".$transcode3."', '".$transcode4."')
    UNION
    SELECT 5 'bulan', SUM(A.iputotaltransaksi-A.iputotalpajak) 'total' FROM einvoicepenjualanu A WHERE year(A.iputanggal)='".$tahun."' AND month(A.iputanggal)=5 AND A.ipusumber IN ('".$transcode2."', '".$transcode3."', '".$transcode4."')
    UNION
    SELECT 6 'bulan', SUM(A.iputotaltransaksi-A.iputotalpajak) 'total' FROM einvoicepenjualanu A WHERE year(A.iputanggal)='".$tahun."' AND month(A.iputanggal)=6 AND A.ipusumber IN ('".$transcode2."', '".$transcode3."', '".$transcode4."')
    UNION
    SELECT 7 'bulan', SUM(A.iputotaltransaksi-A.iputotalpajak) 'total' FROM einvoicepenjualanu A WHERE year(A.iputanggal)='".$tahun."' AND month(A.iputanggal)=7 AND A.ipusumber IN ('".$transcode2."', '".$transcode3."', '".$transcode4."')
    UNION
    SELECT 8 'bulan', SUM(A.iputotaltransaksi-A.iputotalpajak) 'total' FROM einvoicepenjualanu A WHERE year(A.iputanggal)='".$tahun."' AND month(A.iputanggal)=8 AND A.ipusumber IN ('".$transcode2."', '".$transcode3."', '".$transcode4."')
    UNION
    SELECT 9 'bulan', SUM(A.iputotaltransaksi-A.iputotalpajak) 'total' FROM einvoicepenjualanu A WHERE year(A.iputanggal)='".$tahun."' AND month(A.iputanggal)=9 AND A.ipusumber IN ('".$transcode2."', '".$transcode3."', '".$transcode4."')
    UNION
    SELECT 10 'bulan', SUM(A.iputotaltransaksi-A.iputotalpajak) 'total' FROM einvoicepenjualanu A WHERE year(A.iputanggal)='".$tahun."' AND month(A.iputanggal)=10 AND A.ipusumber IN ('".$transcode2."', '".$transcode3."', '".$transcode4."')
    UNION
    SELECT 11 'bulan', SUM(A.iputotaltransaksi-A.iputotalpajak) 'total' FROM einvoicepenjualanu A WHERE year(A.iputanggal)='".$tahun."' AND month(A.iputanggal)=11 AND A.ipusumber IN ('".$transcode2."', '".$transcode3."', '".$transcode4."')
    UNION
    SELECT 12 'bulan', SUM(A.iputotaltransaksi-A.iputotalpajak) 'total' FROM einvoicepenjualanu A WHERE year(A.iputanggal)='".$tahun."' AND month(A.iputanggal)=12 AND A.ipusumber IN ('".$transcode2."', '".$transcode3."', '".$transcode4."')
      ";
       
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query_second($query,$query2);   
  }

/*
  function kasbank() {
      $tahun = $_POST['year'];
      $btahun = $tahun++;
      $query = "
    SELECT 1 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE A.cutanggal<'".$tahun."-02-01' AND C.ctipe=0
    UNION
    SELECT 2 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE A.cutanggal<'".$tahun."-03-01' AND C.ctipe=0
    UNION
    SELECT 3 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE A.cutanggal<'".$tahun."-04-01' AND C.ctipe=0
    UNION
    SELECT 4 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE A.cutanggal<'".$tahun."-05-01' AND C.ctipe=0
    UNION
    SELECT 5 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE A.cutanggal<'".$tahun."-06-01' AND C.ctipe=0
    UNION
    SELECT 6 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE A.cutanggal<'".$tahun."-07-01' AND C.ctipe=0
    UNION
    SELECT 7 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE A.cutanggal<'".$tahun."-08-01' AND C.ctipe=0
    UNION
    SELECT 8 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE A.cutanggal<'".$tahun."-09-01' AND C.ctipe=0
    UNION
    SELECT 9 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE A.cutanggal<'".$tahun."-10-01' AND C.ctipe=0
    UNION
    SELECT 10 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE A.cutanggal<'".$tahun."-11-01' AND C.ctipe=0
    UNION
    SELECT 11 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE A.cutanggal<'".$tahun."-12-01' AND C.ctipe=0
    UNION
    SELECT 12 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE A.cutanggal<'".$btahun."-01-01' AND C.ctipe=0
      ";

      $query2 = "
    SELECT 1 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE A.cutanggal<'".$tahun."-02-01' AND C.ctipe=1
    UNION
    SELECT 2 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE A.cutanggal<'".$tahun."-03-01' AND C.ctipe=1
    UNION
    SELECT 3 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE A.cutanggal<'".$tahun."-04-01' AND C.ctipe=1
    UNION
    SELECT 4 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE A.cutanggal<'".$tahun."-05-01' AND C.ctipe=1
    UNION
    SELECT 5 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE A.cutanggal<'".$tahun."-06-01' AND C.ctipe=1
    UNION
    SELECT 6 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE A.cutanggal<'".$tahun."-07-01' AND C.ctipe=1
    UNION
    SELECT 7 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE A.cutanggal<'".$tahun."-08-01' AND C.ctipe=1
    UNION
    SELECT 8 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE A.cutanggal<'".$tahun."-09-01' AND C.ctipe=1
    UNION
    SELECT 9 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE A.cutanggal<'".$tahun."-10-01' AND C.ctipe=1
    UNION
    SELECT 10 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE A.cutanggal<'".$tahun."-11-01' AND C.ctipe=1
    UNION
    SELECT 11 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE A.cutanggal<'".$tahun."-12-01' AND C.ctipe=1
    UNION
    SELECT 12 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE A.cutanggal<'".$btahun."-01-01' AND C.ctipe=1
      ";
       
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query_second($query,$query2);   
  }
*/

  function kasbank() {
      $tahun = $_POST['year'];
      //$transcode = $this->M_transaksi->prefixtrans(element('PJ_Order_Penjualan',NID));        
      $query = "
    SELECT 1 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=1 AND C.ctipe=0
    UNION
    SELECT 2 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=2 AND C.ctipe=0
    UNION
    SELECT 3 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=3 AND C.ctipe=0
    UNION
    SELECT 4 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=4 AND C.ctipe=0
    UNION
    SELECT 5 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=5 AND C.ctipe=0
    UNION
    SELECT 6 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=6 AND C.ctipe=0
    UNION
    SELECT 7 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=7 AND C.ctipe=0
    UNION
    SELECT 8 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=8 AND C.ctipe=0
    UNION
    SELECT 9 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=9 AND C.ctipe=0
    UNION
    SELECT 10 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=10 AND C.ctipe=0
    UNION
    SELECT 11 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=11 AND C.ctipe=0
    UNION
    SELECT 12 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=12 AND C.ctipe=0
      ";

      $query2 = "
    SELECT 1 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=1 AND C.ctipe=1
    UNION
    SELECT 2 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=2 AND C.ctipe=1
    UNION
    SELECT 3 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=3 AND C.ctipe=1
    UNION
    SELECT 4 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=4 AND C.ctipe=1
    UNION
    SELECT 5 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=5 AND C.ctipe=1
    UNION
    SELECT 6 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=6 AND C.ctipe=1
    UNION
    SELECT 7 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=7 AND C.ctipe=1
    UNION
    SELECT 8 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=8 AND C.ctipe=1
    UNION
    SELECT 9 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=9 AND C.ctipe=1
    UNION
    SELECT 10 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=10 AND C.ctipe=1
    UNION
    SELECT 11 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=11 AND C.ctipe=1
    UNION
    SELECT 12 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=12 AND C.ctipe=1
      ";
       
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query_second($query,$query2);   
  }


  function profitloss() {
      $tahun = $_POST['year'];
      //$transcode = $this->M_transaksi->prefixtrans(element('PJ_Order_Penjualan',NID));        
      $query = "
    SELECT 1 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=1 AND C.ctipe IN (11,14)
    UNION
    SELECT 2 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=2 AND C.ctipe IN (11,14)
    UNION
    SELECT 3 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=3 AND C.ctipe IN (11,14)
    UNION
    SELECT 4 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=4 AND C.ctipe IN (11,14)
    UNION
    SELECT 5 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=5 AND C.ctipe IN (11,14)
    UNION
    SELECT 6 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=6 AND C.ctipe IN (11,14)
    UNION
    SELECT 7 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=7 AND C.ctipe IN (11,14)
    UNION
    SELECT 8 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=8 AND C.ctipe IN (11,14)
    UNION
    SELECT 9 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=9 AND C.ctipe IN (11,14)
    UNION
    SELECT 10 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=10 AND C.ctipe IN (11,14)
    UNION
    SELECT 11 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=11 AND C.ctipe IN (11,14)
    UNION
    SELECT 12 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=12 AND C.ctipe IN (11,14)
      ";

      $query2 = "
    SELECT 1 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=1 AND C.ctipe=12
    UNION
    SELECT 2 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=2 AND C.ctipe=12
    UNION
    SELECT 3 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=3 AND C.ctipe=12
    UNION
    SELECT 4 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=4 AND C.ctipe=12
    UNION
    SELECT 5 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=5 AND C.ctipe=12
    UNION
    SELECT 6 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=6 AND C.ctipe=12
    UNION
    SELECT 7 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=7 AND C.ctipe=12
    UNION
    SELECT 8 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=8 AND C.ctipe=12
    UNION
    SELECT 9 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=9 AND C.ctipe=12
    UNION
    SELECT 10 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=10 AND C.ctipe=12
    UNION
    SELECT 11 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=11 AND C.ctipe=12
    UNION
    SELECT 12 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=12 AND C.ctipe=12
      ";

      $query3 = "
    SELECT 1 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=1 AND C.ctipe IN (13,15)
    UNION
    SELECT 2 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=2 AND C.ctipe IN (13,15)
    UNION
    SELECT 3 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=3 AND C.ctipe IN (13,15)
    UNION
    SELECT 4 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=4 AND C.ctipe IN (13,15)
    UNION
    SELECT 5 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=5 AND C.ctipe IN (13,15)
    UNION
    SELECT 6 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=6 AND C.ctipe IN (13,15)
    UNION
    SELECT 7 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=7 AND C.ctipe IN (13,15)
    UNION
    SELECT 8 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=8 AND C.ctipe IN (13,15)
    UNION
    SELECT 9 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=9 AND C.ctipe IN (13,15)
    UNION
    SELECT 10 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=10 AND C.ctipe IN (13,15)
    UNION
    SELECT 11 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=11 AND C.ctipe IN (13,15)
    UNION
    SELECT 12 'bulan', SUM(B.cddebit-B.cdkredit) 'total' 
      FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu INNER JOIN bcoa C ON B.cdnocoa=C.cid 
     WHERE year(A.cutanggal)='".$tahun."' AND month(A.cutanggal)=12 AND C.ctipe IN (13,15)
      ";

       
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query_third($query,$query2,$query3);   
  }

  function topitem() {
      $tahun = $_POST['year'];
      $limit = $_POST['jumlah'];      
      $dari = $_POST['sbulan'];
      $sampai = $_POST['ebulan'];      
      $transcode = $this->M_transaksi->prefixtrans(element('PJ_Penjualan_Tunai',NID));        
      $transcode2 = $this->M_transaksi->prefixtrans(element('PJ_Faktur_Penjualan',NID));      
      $transcode3 = $this->M_transaksi->prefixtrans(element('SV_Penjualan_Tunai',NID));                      
      $query = "SELECT * FROM (
                      SELECT A.ipditem 'iditem', C.inama 'item', SUM(A.ipdkeluar) 'jumlah' 
                        FROM einvoicepenjualand A INNER JOIN einvoicepenjualanu B ON A.ipdidipu=B.ipuid
                   LEFT JOIN bitem C ON A.ipditem=C.iid 
                       WHERE B.iputanggal BETWEEN '".$tahun."-".$dari."-01' AND LAST_DAY('".$tahun."-". $sampai ."-01') AND B.ipusumber IN ('".$transcode."','".$transcode2."','".$transcode3."') AND C.ijenisitem=0 GROUP BY A.ipditem ) TB ORDER BY TB.jumlah DESC LIMIT ".$limit;
       
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);   
  }

}