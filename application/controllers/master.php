<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
Class name: Master Data
version : 1.0
Author : Iswan Putera
*/

class Master extends CI_Controller {
	public $userid;
	function __construct(){
		parent::__construct();
		$this->load->model("Admin_model");
		$this->load->model("control_model");
		$this->load->model("akun_model");
		$this->load->model("purch_model");
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
	function tools(){
		$datax=$this->akun_model->get_neraca_head("='0'");
		$data=$this->akun_model->get_neraca_head();
		$this->zetro_auth->menu_id(array('settingshu','settingneraca'));
		$this->list_data($this->zetro_auth->auth(array('head','shu'),array($data,$datax)));
		$this->View('master/master_tools');
	}
	function kas_harian(){
		$this->zetro_auth->menu_id(array('kas_harian','kas_keluar'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('master/master_kas_harian');
	}
	function vendor_n(){
		$this->zetro_auth->menu_id(array('master__vendor_n','listvendor'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('master/master_vendor');
	}
	function vendor_l(){
		$this->zetro_auth->menu_id(array('listvendor','master__vendor_n'));
		$this->list_data($this->zetro_auth->auth('List'));
		$this->View('master/master_vendor_list');
	}
	function general(){
		$this->zetro_auth->menu_id(array('dataakun'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('master/master_general');
	}
	
	function set_config_file($filename){
		$this->filename=$filename;
	}
	function simpan_kas(){
		$data=array();
		$data['id_kas']	=strtoupper($_POST['id_kas']);
		$data['nm_kas']	=strtoupper($_POST['nm_kas']);
		$data['sa_kas']	=($_POST['sa_kas']);
		$data['sl_kas']	=empty($_POST['sl_kas'])?0:$_POST['sl_kas'];
		$data['created_by']	=$this->session->userdata('userid');
			$this->Admin_model->replace_data('mst_kas',$data);
			$this->list_data_akun();
	}
	function simpan_kas_harian(){
		$data=array();$datax=array();
		$data['no_trans']=$_POST['no_trans'];
		$data['id_kas']	=strtoupper($_POST['id_kas']);
		$data['nm_kas']	=strtoupper($_POST['nm_kas']);
		$data['sa_kas']	=($_POST['sa_kas']);
		$data['tgl_kas']=tglToSql($_POST['tgl_kas']);
		$data['id_lokasi']=empty($_POST['lokasi'])?'1':$_POST['lokasi'];
		$data['created_by']	=$this->session->userdata('userid');
		$this->Admin_model->replace_data('mst_kas_harian',$data);
		
		$datax['nomor']=$_POST['no_trans'];
		$datax['jenis_transaksi']	='D'.$this->session->userdata('idlevel');
		$this->Admin_model->replace_data('nomor_transaksi',$datax);
		//proses to jurnal
		$this->no_transaksi($_POST['no_trans']);
		$this->tanggal(tgltoSql($_POST['tgl_kas']));
		$this->JenisBayar('7');

		//$this->process_to_jurnal('0',$_POST['harga_beli'],'');
		$this->list_kas_harian();
	}
	function simpan_kas_keluar(){
		$data=array();$datax=array();$sal_kas=0;$tot_trans=0;
		$sal_kas	=rdb('mst_kas_harian','saldo_kas','sum(sa_kas) as saldo_kas',"where tgl_kas='".tglToSql($_POST['tgl_transaksi'])."'");
		$tot_trans	=rdb('mst_kas_trans','jumlah','sum(jumlah) as jumlah',"where tgl_trans='".tglToSql($_POST['tgl_transaksi'])."'");
		$data['id_kas']		=strtoupper($_POST['akun_transaksi']);
		$data['id_trans']	=($_POST['no_transaksi']);
		$data['jumlah']		=$_POST['harga_beli'];
		$data['saldo_kas']	=($sal_kas-$tot_trans-$_POST['harga_beli']);
		$data['uraian_trans']=ucwords($_POST['ket_transaksi']);
		$data['tgl_trans']	=tglToSql($_POST['tgl_transaksi']);
		$data['created_by']	=$this->session->userdata('userid');
		$data['id_lokasi']	=empty($_POST['lokasi'])?'1':$_POST['lokasi'];
		//update nomor transaski
		$datax['nomor']		=$_POST['no_transaksi'];
		$datax['jenis_transaksi']	='D'.$this->session->userdata('idlevel');
		//print_r($datax);
			$this->Admin_model->replace_data('nomor_transaksi',$datax);
			$this->Admin_model->replace_data('mst_kas_trans',$data);
		//proses to jurnal
		$this->no_transaksi($_POST['no_transaksi']);
		$this->tanggal($_POST['tgl_transaksi']);
		$this->JenisBayar('7');
			//$this->process_to_jurnal('0',$_POST['harga_beli'],ucwords($_POST['ket_transaksi']));

			$this->list_kas_trans();
			
	}
	function no_transaksi($no_trans){
		$this->no_trans=$no_trans;
	}
	function tanggal($tgl){
		$this->tgl=$tgl;
	}
	function JenisBayar($id_jenis){
		$this->id_jenis=$id_jenis;	
	}
	
	function process_to_jurnal($id_anggota,$total,$ket=''){
	/* membuat data untuk diposting dalam jurnal
	  simpan penjualan barang secara kredit
	  inputnya adalah ID anggota yng melakukan pembelian secara kredit
	  dapatkan ID_Perkiraan dari table perkiraan based on ID_Anggota
	  extract data perkiraan menjadi klass, sub klass dan jenis simpanan dalam hal ini
	  jenis simpanan adalah barang [id:4]
	  data yang dihasilkan akan ditampung di table transaksi_temp
	 
		$data=array();$akun='';
		//get ID_perkiraan
		$akun=rdb('perkiraan','ID','ID',"where ID_Agt='$id_anggota' and ID_Simpanan='".$this->id_jenis."'");
		//echo $akun;
		if($akun==''){
			//update database perkiraan
			$this->_update_perkiraan($id_anggota,$this->id_jenis);	
		}
*/		$data['ID_Perkiraan']	='25';//rdb('perkiraan','ID','ID',"where ID_Agt='$id_anggota' and ID_Simpanan='".$this->id_jenis."'");
		$data['ID_Unit']		=rdb('jenis_simpanan','ID_Unit','ID_Unit',"where ID='".$this->id_jenis."'");
		$data['ID_Klas']		=rdb('jenis_simpanan','ID_Klasifikasi','ID_Klasifikasi',"where ID='".$this->id_jenis."'");
		$data['ID_SubKlas']		=rdb('jenis_simpanan','ID_SubKlas','ID_SubKlas',"where ID='".$this->id_jenis."'");
		$data['ID_Dept']		='1';//rdb('mst_anggota','ID_Dept','ID_Dept',"where ID='".$id_anggota."'");
		if($ket==''){
			$data['Kredit']		=$total;//rdb('inv_penjualan','Total','Total',"where ID_Anggota='".$id_anggota."' and NoUrut='".$this->no_trans."'");
		}else{
			$data['Debet']		=$total;
		}
		$data['ID_CC']			='5';
		$data['Keterangan']		=($ket=='')?'Saldo Kas Harian':$ket;
		$data['tanggal']		=tgltoSql($this->tgl);
		$data['ID_Bulan']		=substr($this->tgl,3,2);
		$data['Tahun']			=substr($this->tgl,6,4);
		$data['created_by']		=$this->session->userdata('userid');
		//print_r($data);
		 $this->Admin_model->replace_data('transaksi_temp',$data);
		// $this->_set_pinjaman($id_anggota);
	}
	
	function _update_perkiraan($ID_Agt,$ID_Simpanan){
		$datax=array();
		$datax['ID_Unit']		=rdb('jenis_simpanan','ID_Unit','ID_Unit',"where ID='".$this->id_jenis."'");
		$datax['ID_Klas']		=rdb('jenis_simpanan','ID_Klasifikasi','ID_Klasifikasi',"where ID='".$this->id_jenis."'");
		$datax['ID_SubKlas']	=rdb('jenis_simpanan','ID_SubKlas','ID_SubKlas',"where ID='".$this->id_jenis."'");
		$datax['ID_Dept']		=rdb('mst_anggota','ID_Dept','ID_Dept',"where ID='".$ID_Agt."'");
		$datax['ID_Simpanan']	=$ID_Simpanan;
		$datax['ID_Agt']		=$ID_Agt;
		$datax['ID_Calc']		=rdb('jenis_simpanan','ID_Calc','ID_Calc',"where ID='".$this->id_jenis."'");
		$datax['ID_Laporan']	=rdb('jenis_simpanan','ID_Laporan','ID_Laporan',"where ID='".$this->id_jenis."'");
		$datax['ID_LapDetail']	=rdb('jenis_simpanan','ID_LapDetail','ID_LapDetail',"where ID='".$this->id_jenis."'");
		echo $this->Admin_model->replace_data('perkiraan',$datax);
		//print_r($datax);
	}



	function get_datakas(){
		$data=array();
		$data=$this->Admin_model->show_single_field('mst_kas','id_kas',' order by id_kas');
		echo $data;

	}
	function get_datailkas(){
		$data=array();
		$data=$this->Admin_model->show_list('mst_kas');
		echo json_encode($data[0]);
	}	
	function _filename(){
		//configurasi data untuk generate form & list
		$this->zetro_buildlist->config_file('asset/bin/zetro_master.frm');
		$this->zetro_buildlist->aksi();
		$this->zetro_buildlist->icon();
	}
	function _generate_list($data,$section,$list_key='nm_barang',$icon='deleted',$aksi=true,$sub_total=false){
			//prepare table
			$this->_filename();
			$this->zetro_buildlist->aksi($aksi); 
			$this->zetro_buildlist->section($section);
			$this->zetro_buildlist->icon($icon);
			$this->zetro_buildlist->query($data);
			//bulid subtotal
			$this->zetro_buildlist->sub_total($sub_total);
			$this->zetro_buildlist->sub_total_kolom('4,5');
			$this->zetro_buildlist->sub_total_field('stock,blokstok');
			//show data in table
			$this->zetro_buildlist->list_data($section);
			$this->zetro_buildlist->BuildListData($list_key);
	}
	function list_kas_harian(){
		$data=array();	
		$data=array();$n=0;$total=0;
		$lokasi=empty($_POST['lokasi'])?'':" and id_lokasi='".$_POST['lokasi']."' ";
		$data=$this->Admin_model->show_list('mst_kas_harian',"where month(tgl_kas)='".date('m')."' and year(tgl_kas)='".date('Y')."' $lokasi order by no_trans desc");
		foreach($data as $r){
			$n++;
			echo tr(($r->tgl_kas==date('Y-m-d'))?'list_ganjil':'').td($n,'center').td($r->no_trans,'center').
				 td(tglfromSql($r->tgl_kas),'center').
				 td($r->id_kas).td($r->nm_kas).td(number_format($r->sa_kas,2),'right').
				 td(rdb('user_lokasi','lokasi','lokasi',"where ID='".$r->id_lokasi."'")).
				 _tr();
			$total=($total+$r->sa_kas);
		}
		echo tr('list_genap').td('<b>Total</b>','right\' colspan=\'5').td('<b>'.number_format($total,2),'right').td('')._tr();
	}
	function list_kas_trans(){
		$data=array();	
		$data=array();$n=0;$kredit=0;
		$tanggal=tgltoSql($_POST['tanggal']);
		$lokasi=empty($_POST['lokasi'])?'':" and id_lokasi='".$_POST['lokasi']."' ";
		$data=$this->Admin_model->show_list('mst_kas_trans',"where tgl_trans='".$tanggal."' $lokasi order by created_by,id_trans");
		foreach($data as $r){
			$n++;$saldo_akhir=0;
			$kredit=($kredit+$r->jumlah);
			$saldo_kas=rdb('mst_kas_trans','saldo_kas','saldo_kas',"where uraian_trans='Saldo Awal hari ini' and tgl_trans='".$tanggal."'");
			$saldo_akhir=($r->uraian_trans!='Saldo Awal hari ini')?
						 ($saldo_kas-$kredit):$saldo_kas;
						
			echo tr().td($n,'center').td($r->id_trans,'center').
				 td(tglfromSql($r->tgl_trans),'center').
				 td($r->id_kas).td($r->uraian_trans).
				 td(number_format($r->jumlah,2),'right').
				 td(number_format($saldo_akhir,2),'right').
				 /*td(number_format($r->saldo_kas,2),'right').*/
				 td(rdb('user_lokasi','lokasi','lokasi',"where ID='".$r->id_lokasi."'")).
				 _tr();
		}
	}
	function list_data_akun(){
		$data=array();$n=0;
		$oto=$this->zetro_auth->cek_oto('e','dataakaun');
		$data=$this->Admin_model->show_list('mst_kas','order by id_kas');
		foreach($data as $r){
			$n++;$dependency='';
			$dependency=rdb('mst_kas_harian','id_kas','id_kas',"where id_kas='".$r->id_kas."'");
			echo tr().td($n,'center').
				 td($r->id_kas).td($r->nm_kas).td(number_format($r->sa_kas,2),'right').
				 td(($oto!='')?
				 	($dependency=='')?img_aksi('Kas-'.$r->id_kas,'del',false):img_aksi('Kas-'.$r->id_kas):'','center').
/*				 td("<img src='".base_url()."asset/images/no.png' onclick=\"image_click('".$r->id_kas."','del');\" >",'center').
*/				 _tr();
		}
	}
	function get_akun_kas(){
		$data=array();
		$id=$_POST['id'];
		$data=$this->Admin_model->show_list('mst_kas','order by id_kas');
		echo json_encode($data[0]);	
	}
// seting shu dan neraca
	function get_subneraca(){
		$ID=$_POST['ID'];$n=0;
		$data=$this->akun_model->get_neraca_sub($ID);
		foreach($data as $row){
		$n++;	
			echo "<tr class='xx' align='center'>
				 <td class='kotak'>$n</td>
				 <td class='kotak' align='left'>".$row->SubJenis."</td>
				 <td class='kotak' align='left'>".$row->ID_Calc."</td>
				 <td class='kotak'>".$row->ID_KBR."</td>
				 <td class='kotak'>".$row->ID_USP."</td>
				 </tr>\n";
		}
		
	}
	function get_head_shu(){
		echo $data;	
	}
//vendor transaction
	function get_next_id(){
		$data=0;
		$data=$this->Admin_model->show_single_field('mst_anggota','ID',"where ID_Jenis='2' order by ID desc limit 1");
		$data=($data+1);
		if(strlen($data)==1){
			$data='000'.$data;
		}else if(strlen($data)==2){
			$data='00'.$data;
		}else if(strlen($data)==3){
			$data='0'.$data;
		}else if(strlen($data)>=4){
			$data=$data;
		}
		echo $data;
	}
  	
	function set_data_vendor(){
		$data=array();
		$data['NoUrut']		=round($_POST['ID']);
		$data['No_Agt']		=($_POST['ID']);
		$data['Nama']		=addslashes(strtoupper($_POST['pemasok']));
		$data['Alamat']		=empty($_POST['alamat'])?'':ucwords(addslashes($_POST['alamat']));
		$data['Kota']		=empty($_POST['kota'])?'':ucwords($_POST['kota']);
		$data['Propinsi']	=empty($_POST['propinsi'])?'':ucwords($_POST['propinsi']);
		$data['Telepon']	=empty($_POST['telepon'])?'':$_POST['telepon'];
		$data['Faksimili']	=empty($_POST['faksimili'])?'':$_POST['faksimili'];
		$data['Status'] 	='0';
		$data['ID_Jenis']	='2';
		$data['ID_Aktif']	='1';
		//print_r($data);
		$this->Admin_model->replace_data('mst_anggota',$data);
	}
	function list_vendor(){
		$data=array(); $n=0;
		$nama=empty($_POST['nama'])?$where="where ID_Jenis='2'":$where="where Nama like '%".$_POST['nama']."%' and ID_Jenis='2'";
		$data=$this->Admin_model->show_list('mst_anggota',$where.' order by Nama');
		foreach($data as $row){
			$n++;//tr('xx\' onClick="_show_detail(\''.$row->ID.'\');" attr=\'ax').
			$cek=rdb('inv_pembelian','ID_Pemasok','ID_Pemasok',"where ID_Pemasok='".$row->ID."'");
			$oto=$this->zetro_auth->cek_oto('e','listvendor');
			echo tr().
				 td($n,'center').
				 td($row->No_Agt,'center').
				 td($row->Nama,'xx\' onClick="_show_detail(\''.$row->ID.'\',\''.$row->Nama.'\');" attr=\'ax').
				 td($row->Alamat).td($row->Kota).td($row->Propinsi).
				 td(number_format((int)rdb("pinjaman","total","sum(jml_pinjaman) as total","where ID_Agt='".$row->ID."' and left(ID,1)='5' group by ID_Agt"),2),'right').
				 td(($cek=='')? img_aksi($row->ID.':'.$row->Nama,true):'','center').
				 _tr();
		}
	}
	
	function vendor_detailed(){
		$data=array();$n=0;$total=0;
		$ID=$_POST['id'];
		$data=$this->purch_model->detail_trans_vendor("where p.ID_Pemasok='".$ID."' order by p.Tanggal limit 500");
		foreach($data as $r){
		 $n++;
		 echo tr().td($n,'center').
		 		   td(tglfromSql($r->Tanggal)).
				   td($r->Nomor).
				   td($r->Nama_Barang).
				   td(number_format($r->Jumlah,2),'right').
				   td($r->Satuan).
				   td(number_format(($r->Jumlah*$r->Harga_Beli),2),'right').
				  _tr();	
		$total=($total+($r->Jumlah*$r->Harga_Beli));
		}
		echo tr().td('<b>Total</b>','right\' colspan=\'6','kotak list_genap').td('<b>'.number_format($total,2).'</b>','right')._tr();
	}
}
