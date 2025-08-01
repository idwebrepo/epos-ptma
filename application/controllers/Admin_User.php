<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_User extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('M_Admin_Unit');
        if (!$this->session->has_userdata('nama')) {
            redirect(base_url('exception'));
        }
        $this->load->model('M_Admin_User');
        $this->load->model('M_transaksi');
    }

    function savedata()
    {
        if ($this->input->post('id') == '') {
            echo $this->M_Admin_User->tambahData();
        } else {
            echo $this->M_Admin_User->ubahData();
        }
    }

    function savepassword()
    {
        echo $this->M_Admin_User->simpanPassword();
    }

    function deletedata()
    {
        echo $this->M_Admin_User->hapusData();
    }

    function getdata()
    {
        if ($this->input->post('id') == '' || $this->input->post('id') == null) {
            echo _pesanError("Data tidak ditemukan !");
            exit;
        }

        $query = "SELECT A.*, B.mnama 'parent'
                    FROM amenu A LEFT JOIN amenu B ON A.mparent=B.mid 
                   WHERE A.mid='" . $this->input->post('id') . "'";

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

    function getaksesmenu()
    {
        if ($_SESSION['kode'] == 0) {
            $query = "SELECT B.mid,B.mparent,B.mtype,B.mnama,A.auapprove,A.auadd,A.auedit,A.audell,A.auprint 
                      FROM ausermenu A 
                RIGHT JOIN amenu B on A.auidmenu=B.mid AND A.auiduser='" . $this->input->post('id') . "' 
                     WHERE B.mtype<>1 AND B.mactive=1 ORDER BY B.murutan";
        } else {
            $query = "SELECT B.mid,B.mparent,B.mtype,B.mnama,A.auapprove,A.auadd,A.auedit,A.audell,A.auprint 
                      FROM ausermenu A 
                RIGHT JOIN amenu B on A.auidmenu=B.mid AND A.auiduser='" . $this->input->post('id') . "' 
                     WHERE B.mtype<>1 AND B.mid<>201 ORDER BY B.murutan";
        }

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

    function getaksesreport()
    {
        $query = "SELECT B.mid,B.mparent,B.mtype,B.mnama,A.auapprove,A.auadd,A.auedit,A.audell,A.auprint 
                    FROM ausermenu A RIGHT JOIN amenu B on A.auidmenu=B.mid AND A.auiduser='" . $this->input->post('id') . "' 
                   WHERE B.mtype=1 
                ORDER BY B.murutan";

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

    function getakseswidget()
    {
        $query = "SELECT B.adid,B.adnama,A.audapprove, B.adket   
                    FROM auserdashboard A RIGHT JOIN adashboard B on A.adid=B.adid AND A.auid='" . $this->input->post('id') . "' 
                   WHERE B.adactive=1 
                ORDER BY B.adid";

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

    function getakseswidgetsess()
    {
        $query = "SELECT B.adid,B.adnama,A.audapprove, B.adket  
                    FROM auserdashboard A RIGHT JOIN adashboard B on A.adid=B.adid AND A.auid='" . $this->session->id . "' 
                   WHERE B.adactive=1 AND A.audapprove=1
                ORDER BY B.adid";

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

    function getinfouser()
    {
        $query = "SELECT * 
                    FROM auser WHERE uid = " . $this->input->post('id');

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

    function getdataunittoko()
    {
        $query = "SELECT * FROM aunit WHERE utactive=1 ";
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }
}
