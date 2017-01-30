<?php

class Settingan extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->model(array('setup_model','admin_model'));
		$this->load->library('zetro_auth');	
		$this->load->library('zetro_manager');	
		$this->userid=$this->session->userdata('idlevel');
	}
	function Header(){
		$this->load->view('admin/header');	
	}
	
	function Footer(){
		$this->load->view('admin/footer');	
	}
	function list_data($data){
		$this->data=$data;
	}
	function View($view){
		$this->Header();
		//$this->zetro_auth->view($view);
		$this->load->view($view,$this->data);	
		$this->Footer();
	}
	function lokasi_gudang(){
		$this->zetro_auth->menu_id(array('lokasigudang'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('controlpanel/master_lokasi');
	}
	function list_lokasi(){
		$data=array();$n=0;$ser='';
		$ID=$_POST['id'];
		$oto=$this->zetro_auth->cek_oto('e','lokasigudang');
		$data=$this->setup_model->show_data('user_lokasi');
		foreach($data as $r){
			$n++;
			echo tr('xx',$r->status).td($n,'center').
				 	  td($r->lokasi).
					  td($r->alamat).
					  td(($oto!='')?($r->status=='Server')?img_aksi($r->ID):img_aksi($r->ID,'del',false):'','center').
				 _tr();
				($r->status=='Server')? $ser=$r->status:'';
		}
		echo "<input type='hidden' id='lok_server' value='$ser'/>";
	}
	function get_lokasi_for_oto(){
		$data=array();$n=0;
		$ID=empty($_POST['id'])?'':$_POST['id'];
		$data=$this->setup_model->show_data('user_lokasi');
		foreach($data as $r){
			$n++;
			$oto=rdb('user_oto_area','c','c',"where userid='".$ID."' and lokasi='".$r->ID."'");
			$cek=$this->zetro_auth->cek_oto('c','userarea');
			$disable=($cek!='')?'':'disabled';
			$checked=($oto=='Y')? 'checked':'';
			echo tr().td($n,'center').
				 	  td($r->lokasi).
					  td("<input type='checkbox' id='c_".$r->ID."' $disable $checked onclick=\"simpan_oto('".$r->ID."');\">",'center').
					  _tr();
		}
	}
	function set_lokasi(){
		$data=array();
		$data['ID']=empty($_POST['id'])?'0':$_POST['id'];
		$data['lokasi']=strtoupper(addslashes($_POST['lokasi']));
		$data['alamat']=addslashes($_POST['alamat']);
		$data['status']=empty($_POST['stat'])?'Client':$_POST['stat'];
		if($_POST['stat']=='Server'){
			$this->Admin_model->upd_data('user_lokasi',"set status='Client'","where Status='Server'");
		}
		$this->admin_model->replace_data('user_lokasi',$data);
		echo ($_POST['stat']=='Client')?'':$_POST['stat'];
	}
	
	function get_lokasi(){
		$ID=$_POST['id'];
		$data=$this->setup_model->show_data('user_lokasi',"Where ID='$ID'");
		echo empty($data)? json_encode("{'lokasi':,'alamat':,'ID':}"):json_encode($data[0]);
	}
	function del_lokasi(){
		$ID=$_POST['id'];
		$this->admin_model->hps_data('user_lokasi',"where ID='$ID'");
	}
	function get_as_server(){
		$data=array();$n=0;
		$ID=$_POST['id'];
		$oto=$this->zetro_auth->cek_oto('e','lokasigudang');
		$data=$this->setup_model->show_data('user_lokasi');
		foreach($data as $r){
			if($r->status=='Server'){
			 echo $r->ID;	
			}
		}
	}
	//preference
	function pengaturan(){
		$this->zetro_auth->menu_id(array('profile'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('controlpanel/master_app');
	}
	function get_profile(){
		$data=array();
		$ID=$_POST['id'];
		$file='asset/bin/zetro_config.dll';
		$data=array(
			  'Name'=>$this->zetro_manager->rContent($ID,'Name',$file),
			  'Address'=>$this->zetro_manager->rContent($ID,'Address',$file),
			  'Kota'=>$this->zetro_manager->rContent($ID,'Kota',$file),
			  'Propinsi'=>$this->zetro_manager->rContent($ID,'Propinsi',$file),
			  'Pobox'=>$this->zetro_manager->rContent($ID,'Pobox',$file),
			  'Telp'=>$this->zetro_manager->rContent($ID,'Telp',$file),
			  'Fax'=>$this->zetro_manager->rContent($ID,'Fax',$file)
			  );
		
		echo json_encode($data);		
	}
	
	function set_profile(){
		$file='asset/bin/zetro_config.dll';
		$this->zetro_manager->add_content($file,'InfoCo','Name',strtoupper($_POST['Name']));
		$this->zetro_manager->add_content($file,'InfoCo','Address',strtoupper($_POST['Address']));
		$this->zetro_manager->add_content($file,'InfoCo','Kota',strtoupper($_POST['Kota']));
		$this->zetro_manager->add_content($file,'InfoCo','Propinsi',strtoupper($_POST['Propinsi']));
		$this->zetro_manager->add_content($file,'InfoCo','Pobox',strtoupper($_POST['Pobox']));
		$this->zetro_manager->add_content($file,'InfoCo','Telp',strtoupper($_POST['Telp']));
		$this->zetro_manager->add_content($file,'InfoCo','Fax',strtoupper($_POST['Fax']));
		echo "Data sudah berhasil di simpan";
	}
}
?>