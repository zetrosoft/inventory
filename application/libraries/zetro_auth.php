<?php
/* class control authorisation
	Author : Iswan Putera
	
*/
class Zetro_auth extends CI_model
{	
	function __construct(){
		//parent::__construct();
		$this->load->model('Admin_model');
		$this->load->model('control_model');
		$this->userid=$this->session->userdata('idlevel');
	}

	function menu_id($menuid){
		
		$this->menu=$menuid;
	}
	
	function field($field){
		$this->field=$field;	
	}
	function view($link){
		$this->link=$link;
	}
	function cek_login(){
		if($this->session->userdata('idlevel')=='') $this->logout();		
	}
	public function frm_filename($filename){
		$this->filename=$filename;	
	}
	public function oto($menuid='',$field=''){
		$this->menu_id($menuid);
		$this->field($field);
		($this->menu!='All')?
		$this->Admin_model->is_oto($this->menu,$this->field):
		$this->Admin_model->is_oto_all($this->menu,$this->link);
	}
   public function tab_select($prs=''){
		return $this->prs=$prs;	
	}
    function logout() {
        $data = array
            (
            'userid' => 0,
            'username' => 0,
            'type' => 0,
			'idlevel'=>0,
            'login' => FALSE
        );
        $this->session->sess_destroy();
        $this->session->unset_userdata($data);
        redirect('admin/process_login');
    }
//function authenication	
	function cek_oto($field,$menu,$userid=''){
		($userid=='')?
		$this->control_model->userid=$this->userid:
		$this->control_model->userid=$userid;
		$datax=$this->control_model->cek_oto($field,str_replace('/','__',$menu));
			return($this->userid!='')? $datax:$this->zetro_auth->cek_login();
	}
	function lock($field,$menu){
		if($this->userid!='1'){
		return($this->cek_oto($field,$menu,$this->userid)=='')? "disabled='disabled'":'';	
		}
	}
	function auth($key='',$value=''){
		$data=array();$n=0;
		foreach($this->menu as $mnu){
			$data['c_'.$mnu]=$this->cek_oto('c',$mnu);
			$data['e_'.$mnu]=$this->cek_oto('e',$mnu);
			$data['v_'.$mnu]=$this->cek_oto('v',$mnu);
			$data['p_'.$mnu]=$this->cek_oto('p',$mnu);
			$data['all_'.$mnu]=$this->cek_oto('c',$mnu).$this->cek_oto('e',$mnu).$this->cek_oto('v',$mnu).$this->cek_oto('p',$mnu);
		}
	if(is_array($key)){
		foreach($key as $k){
			$data[$k]=$value[$n];
			$n++;	
		}
	}
		return $data;
	// print_r($data);
	}
	function cek_area(){
		$data=array();$area='';$n=0;
		$data=($this->session->userdata('idlevel')!=1)?
		$this->Admin_model->show_list('user_oto_area',"where userid='".$this->session->userdata('userid')."' and c='Y'"):
		$this->Admin_model->show_list('user_lokasi','order by ID', "ID as lokasi");
		
		foreach($data as $r){
			$n++;
			$koma=($n==count($data))?"":",";
			$area .="'".$r->lokasi."'".$koma."";	
		}
		return $area;
		echo $area;
	}
// get data read variable form zetro_*.frm
   public function get_data_field($section,$table){
		$data=array();
		$jml=$this->zetro_manager->Count($section,$this->filename);
		for ($i=1;$i<$jml;$i++){
			$fld=explode(",",$this->zetro_manager->rContent($section,$i,$this->filename));
			$fld2=explode(" ",$fld[2]);
			($fld2[0]=='date')?
			$result=tglToSql($this->input->post($fld[3])):
			$result=$this->input->post($fld[3]);
			$data[$fld[3]]=$result;
		}
		$data["created_by"]=$this->session->userdata("userid");
		$this->Admin_model->simpan_data($table,$data);
		//print_r($data);
	}
	public function show_data_field($section,$table,$where){
		$data=array();
		$jml=$this->zetro_manager->Count($section,$this->filename);
		for ($i=1;$i<=$jml;$i++){
			$fld=explode(",",$this->zetro_manager->rContent($section,$i,$this->filename));
			$fld2=explode(" ",$fld[2]);
			($fld2[0]=='date')?
			$result=tglfromSql($this->Admin_model->show_single_field($table,$fld[3],$where)):
			$result=$this->Admin_model->show_single_field($table,$fld[3],$where);
		   $data[$fld[3]]=$result;
		}
		 return $data;
	}
	public function update_data_field($section,$table){
		$data=array();
		$jml=$this->zetro_manager->Count($section,$this->filename);
		for ($i=1;$i<$jml;$i++){
			$fld=explode(",",$this->zetro_manager->rContent($section,$i,$this->filename));
			$fld2=explode(" ",$fld[2]);
			($fld2[0]=='date')?
			$result=tglToSql($this->input->post($fld[3])):
			$result=$this->input->post($fld[3]);
			$data[$fld[3]]=$result;
		}
		$data["created_by"]=$this->session->userdata("userid");
		$this->Admin_model->replace_data($table,$data);
	}

}
