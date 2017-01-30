<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Neraca extends CI_Controller{
	
	 function __construct()
	  {
		parent::__construct();
		$this->load->model("inv_model");
		$this->load->model("report_model");
		$this->load->helper("print_report");
		$this->load->model("akun_model");
		$this->load->model("neraca_model");
		$this->load->library("zetro_auth");
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
	function faktur(){
		$this->zetro_auth->menu_id(array('rekaplaporan'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('laporan/lap_faktur');
	}
	
	function neraca_index(){
		$this->zetro_auth->menu_id(array('neraca'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('laporan/neracane');
	}
	function neraca_lajur(){
		$this->zetro_auth->menu_id(array('neracalajur'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('laporan/neraca_lajur_bener');
	}
	function rekap_simpanan(){
		$this->zetro_auth->menu_id(array('laporan__rekap_simpanan'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('laporan/neraca_lajur');
	}
	function neraca_lajur_data(){
		//bukan neraca lajur tetapi laporan rekap simpanan anggota per departemen
		$data=array();$n=0;
		$periode=tglToSql($_POST['periode']);
		$data=$this->neraca_model->get_rekap_data($periode);
		//return $data;
		foreach($data as $row){
			$saldo=0;$n++;
			echo tr().td($n,'center').td($row->ID_Dept);
			for ($i=1;$i<=3;$i++){
				$field=str_replace('. ','_',rdb("jenis_simpanan",'Jenis','Jenis',"where ID='".$i."'"));
				echo td(number_format($row->$field,2),'right');
				$saldo=($saldo+$row->$field);
			}
			
			echo td(number_format($saldo,2),'right')._tr();	
		}
	}
	function get_data_nc_lajur(){
		//neraca lajur simpanan anggota per departemen
		$datax=array();$n=0;$saldo=0;
		$periode=tglToSql($_POST['tgl_start']);
		$tgl_stop=empty($_POST['tgl_stop'])?$periode:tglToSql($_POST['tgl_stop']);
		$id_dept=$_POST['id_dept'];
		$ID_Stat=empty($_POST['stat_agt'])?'':$_POST['stat_agt'];
		$akun	=empty($_POST['akun'])?'':$_POST['akun'];
		$datax=$this->neraca_model->get_nc_lajure($periode,$id_dept);
		print_r($datax);
		foreach($datax as $r){
			$n++;
			$saldoawal=rdb("perkiraan",'SaldoAwal','sum(SaldoAwal) as SaldoAwal',"where ID_Agt='".$r->id_agt."' and ID_Dept='".$r->ID_Dept."' and ID_Simpanan='".$r->id_simpanan."'");
			$kode=rdb('klasifikasi','Kode','Kode',"where ID='".$r->ID_Klas."'");
			$kode.=rdb('sub_klasifikasi','Kode','Kode',"where ID='".$r->ID_SubKlas."'");
			$kode.=rdb('unit_jurnal','Kode','Kode',"where ID='".$r->ID_Dept."'");
			$kode.=rdb('mst_departemen','Kode','Kode',"where ID='".$r->ID_Unit."'");
			$kode.=rdb('mst_anggota','No_Perkiraan','No_Perkiraan',"where ID='".$r->id_agt."'");
			$simp=rdb('jenis_simpanan','Jenis','Jenis',"where ID='".$r->id_simpanan."'");
			$saldo=($r->ID_Calc=='2')?($r->Kredit-$r->Debet):($r->Debet-$r->Kredit);
			echo tr().td($n,'center').
					  td($kode,'center').
					  td(rdb('mst_anggota','Nama','Nama',"where ID='".$r->id_agt."'")." - ".$simp,"left' nowrap").
					  td(number_format($saldoawal),'right').
					  td(number_format($r->Debet,2),'right').
					  td(number_format($r->Kredit,2),'right').
					  td(number_format($saldoawal+$saldo,2),'right');
			echo _tr();		  
					 	
		}/**/
	}
	function print_lap_pdf(){
		$data['tanggal']=$this->input->post('tgl_start');
		$periode=tglToSql($this->input->post('tgl_start'));
		$data['temp_rec']=$this->neraca_model->get_rekap_data($periode);
		$this->zetro_auth->menu_id(array('trans_beli'));
		$this->list_data($data);
		$this->View("laporan/rekap_simpanan_print");
	
	}
	function print_neraca(){
		$data['unit']		=$this->input->post('unite');
		$data['periode']	=$this->input->post('tgl_start');
		$data['pembanding']	=$this->input->post('tgl_banding');
		$data['users']		=$this->session->userdata('userid');
		$unite				=$this->input->post('unite');
		($unite!=3)?$unite	=rdb("unit_jurnal",'Unit','Unit',"where ID='".$unite."'"):$unite='';
		$unte	=$this->input->post('unite');
		$periode			=tglToSql($this->input->post('tgl_start'));
		$data['awal']		=getPrevDays($periode,365);
		$awal				=getPrevDays($periode,365);
		$this->neraca_model->build_data($periode);
		$this->neraca_model->tmp_balance();
		$this->neraca_model->generate_shu($awal,$periode,$unte);
		//$data['temp_rec']	=$this->neraca_model->neraca_kalkulasi($periode,$unite);
		$this->zetro_auth->menu_id(array('trans_beli'));
		$this->list_data($data);
		($this->input->post('tgl_banding')=='')?
		$this->View("laporan/neraca_print"):
		$this->View("laporan/neraca_print_banding");
	}
	function print_neraca_gabungan(){
		$data['periode']	=$this->input->post('tgl_start');
		$data['pembanding']	=$this->input->post('tgl_banding');
		$data['users']		=$this->session->userdata('userid');
		$unte				=$this->input->post('unite');
		$periode			=tglToSql($this->input->post('tgl_start'));
		$data['awal']		=getPrevDays($periode,365);
		$awal				=getPrevDays($periode,365);
		$this->neraca_model->build_data($periode);
		$this->neraca_model->tmp_balance();
		$this->neraca_model->generate_shu($awal,$periode,$unte);
		//$data['temp_rec']	=$this->neraca_model->neraca_kalkulasi($periode,$unite);
		$this->zetro_auth->menu_id(array('trans_beli'));
		$this->list_data($data);
		$this->View("laporan/neraca_print_gabung");
	}
	function shu(){
		$this->zetro_auth->menu_id(array('sisahasilusaha'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('laporan/shu');
	}
	
	function print_shu(){
		$data['unit']		=$this->input->post('unite');
		$data['periode']	=$this->input->post('tgl_start');
		$data['akhir']		=$this->input->post('tgl_stop');
		$data['users']		=$this->session->userdata('userid');
		$periode			=tglToSql($this->input->post('tgl_stop'));
		$this->neraca_model->build_data($periode);
		$this->zetro_auth->menu_id(array('trans_beli'));
		$this->list_data($data);
		($this->input->post('unite')==3)?
		$this->View("laporan/shu_print_gabungan"):
		$this->View("laporan/shu_print");
	}
	function graph_shu(){
		//$this->neraca_model->data_grap_shu();
		$this->zetro_auth->menu_id(array('grafikshu'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('laporan/shu_graph');
	}
	function graph_shu_data(){
		$this->neraca_model->data_grap_shu();
	}
	
//generate data for grafik
	function data_XML(){
		$n=0;$x=0;
		$user=$this->session->userdata('userid');
		$xml=fopen(base_url()."application/log/".$user.'_graph.xml','wb');
		fwrite($xml,"<graph caption='".$this->judul."' xAxisName='".$this->xAxis."' yAxisName='".$this->yAxis."' numberPrefix='' showvalues='1'  numDivLines='4' formatNumberScale='0' decimalPrecision='0' anchorSides='10' anchorRadius='3' anchorBorderColor='00990'>\r\n");
		foreach($this->datasec as $sec=>$par_tip){
			fwrite($xml,"<set name='".$sec.'\' value=\''.$par_tip."'/>\r\n");
			$n++;
		}
		fwrite($xml,"</graph>\r\n");
	}
	
	
	function _judul_grafik($judul=''){
		$this->judul=$judul;
	}
	function _judul_axis($xAxis='',$yAxis=''){
		$this->xAxis=$xAxis;
		$this->yAxis=$yAxis;
	}
	function _data_cat($datacat){
		if(is_array($datacat)){
			 $this->datacat=$datacat;
		}else{
			return false;
		}
	}
	function _data_sec($datasec){
		if(is_array($datasec)){
			$this->datasec=$datasec;
		}else{
			return false;
		}			
	}

}