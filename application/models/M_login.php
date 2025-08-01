<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_login extends CI_Model{
    function cek_login($table,$where){   
        $this->db->join('aunit', 'aunit.utid = auser.kodeunit', 'left');  
        return $this->db->get_where($table,$where);
    }

    function get_user_info($select,$table,$where){
    	$this->db->select($select);
        return $this->db->get_where($table,$where);    	
    }  
}

?>