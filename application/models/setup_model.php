<?php

class Setup_model extends CI_Model{
	
	function __construct(){
		parent:: __construct();	
	}
	
	function show_data($table,$where='order by ID,lokasi'){
		$data=array();
		$sql="select * from $table $where";
		$data=$this->db->query($sql);
		return $data->result();
	}
}