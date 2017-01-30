<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
Class name: Inventory controller
version : 1.0
Author : Iswan Putera
*/

class Inventory extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->model("Admin_model");
		$this->load->model("inv_model");
		$this->load->library('zetro_auth');
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
		$this->zetro_auth->view($view);
		$this->load->view($view,$this->data);	
		$this->Footer();
	}
	function index(){
		//$this->inv_model->auto_data();
		$this->zetro_auth->menu_id(array('add','list','unit','kunit','hargabeli'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('inventory/material_list');

	}
	function general(){
		$data=array();
		$this->inv_model->create_table();
		$this->zetro_auth->menu_id(array('jenis','kategori','golongan'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('inventory/master_inv');
	}
	function show_konversi(){
	$data=array(); $n=0;
	$id=$_POST['nm_barang'];
	
	$data=$this->Admin_model->show_list('inv_konversi',"where nm_barang='".$id."'");
	
		foreach($data as $row){
			$n++;
			echo tr().td($n,'center').td($row->nm_barang).
			td(rdb('inv_barang_satuan','Satuan','Satuan',"where ID='".$row->nm_satuan."'")).
			td(rdb('inv_barang_satuan','Satuan','Satuan',"where ID='".$row->sat_beli."'")).
			td(number_format($row->isi_konversi,2),'right').
			td("<img src='".base_url()."asset/images/no.png' title='Click for delete' onclick=\"hapus_konv('".$row->id_barang."','".$row->sat_beli."');\">",'center').
			_tr();
		}
	}
	//save data
	
	function simpan_jenis(){
		$data=array();$n=0;
		$nm_jenis=ucwords($_POST['nm_jenis']);
		$induk=$_POST['induk'];
		$data['JenisBarang']=addslashes(strtoupper($nm_jenis));
		//$data['created_by']=$this->session->userdata("userid");
		$this->Admin_model->replace_data('inv_barang_jenis',$data);
		$this->zetro_auth->tab_select('tambahdata');
		if($induk==''){
			dropdown('inv_barang_jenis','ID','JenisBarang','',$nm_jenis);
		}else{
		$data=$this->Admin_model->show_list('inv_barang_jenis','order by JenisBarang');
			foreach($data as $r){
				$n++;
				echo tr().
					 td($n,'center').
					 td($r->JenisBarang).
					 td(img_aksi($r->ID,true,'del'),'center').
					 _tr();	
			}
		}
	}
	function jenis_list(){
		$data=array();$n=0;
		$nTable=empty($_POST['tabel'])?'inv_barang_jenis':$_POST['tabel'];
		$orderby='order by '.$_POST['orderby'];
		$data=$this->Admin_model->show_list($nTable,$orderby);
		$cek_oto=$this->zetro_auth->cek_oto('e',empty($_POST['oto'])?'kategoribarang':$_POST['oto']);
			foreach($data as $r){
				$n++;$cek_used='';
				$cek_used=($nTable=='inv_barang_jenis')?
						  rdb('inv_barang','ID_Jenis','ID_Jenis',"Where ID_Jenis='".$r->ID."'"):
						  rdb('inv_barang','ID_Kategori','ID_Kategori',"Where ID_Kategori='".$r->ID."'");
				echo tr().
					 td($n,'center').
					 td($r->$_POST['orderby']);
				echo  td(($cek_oto!='' && $cek_used=='')?img_aksi($r->ID.'-'.$nTable,true,'del'):'','center');
				echo _tr();	
			}
	}
	function getjenisbarang()
	{
	/**
		added on 06-11-2013
	*/
	
		$data=array();
		$id=$_POST['id'];
		dropdown("inv_barang_jenis_v",'ID','JenisBarang',"where ID_Kategori='".$id."' order by JenisBarang");	
	}

	function hapus_jenis(){
/*		$fld=$_POST['fld'];
*/		$tbl=$_POST['tbl'];
		$where=str_replace('_',' ',$_POST['id']);
		//$this->Admin_model->hapus_table($tbl,$fld,$where);
		$this->Admin_model->hps_data($tbl,"where ID='".$_POST['id']."'");
		echo $_POST['id'];
	}
	function simpan_kategori(){
		$data=array();
		$nm_jenis=($_POST['nm_kategori']);
		$induk=$_POST['induk'];
		$data['Kategori']=addslashes(strtoupper($nm_jenis));
		//$data['created_by']=$this->session->userdata("userid");
		$this->Admin_model->replace_data('inv_barang_kategori',$data);
		if($induk==''){
		dropdown('inv_barang_kategori','ID','Kategori','',$nm_jenis);
		}else{
			$this->_filename();
			$this->zetro_buildlist->icon();
			$this->zetro_buildlist->section('Kategori');
			$sql2="select * from inv_barang_kategori order by Kategori";
			$this->zetro_buildlist->query($sql2);
			$this->zetro_buildlist->list_data('Kategori');
			$this->zetro_buildlist->BuildListData('Kategori');
		}
	}
	function simpan_golongan(){
		$data=array();
		$nm_jenis=strtoupper($_POST['nm_golongan']);
		$induk=$_POST['induk'];
		$data['nm_golongan']=addslashes(strtoupper($nm_jenis));
		//$data['created_by']=$this->session->userdata("userid");
		$this->Admin_model->replace_data('inv_golongan',$data);
		if($induk==''){
		dropdown('inv_golongan','nm_golongan','nm_golongan','',$nm_jenis);
		}else{
			$this->_filename();
			//$this->zetro_buildlist->aksi(true);
			$this->zetro_buildlist->icon('deleted');
			$this->zetro_buildlist->section('Golongan');
			$sql2="select * from inv_golongan order by nm_golongan";
			$this->zetro_buildlist->query($sql2);
			$this->zetro_buildlist->list_data('Golongan');
			$this->zetro_buildlist->BuildListData('nm_golongan');
		}
	}
	function simpan_satuan(){
		$data=array();
		$nm_jenis=strtoupper($_POST['nm_satuan']);
		$linked=$_POST['linked'];
		$induk=$_POST['induk'];
		$data['Satuan']=$nm_jenis;
		//$data['created_by']=$this->session->userdata("userid");
		$this->Admin_model->replace_data('inv_barang_satuan',$data);

	 	$this->_filename();
		$this->zetro_buildlist->section('Satuan');
		$this->zetro_buildlist->icon();
		$sql2="select * from inv_barang_satuan order by Satuan";
		$this->zetro_buildlist->query($sql2);
		$this->zetro_buildlist->list_data('Satuan');
		$this->zetro_buildlist->BuildListData('Satuan');
	}
	function simpan_barang(){
		$data=array();$kat=array();$jen=array();$sat=array();$Stat_bar='';
		$Stat_bar=empty($_POST['id_kategori'])?'':addslashes($_POST['id_kategori']);
        $id_barang="0";
        $id_barang=empty($_POST['id_item'])?'0':$_POST['id_item'];
		$data['ID']			=empty($_POST['id_item'])?'0':$_POST['id_item'];
		$data['ID_Jenis']	  =empty($_POST['id_jenis'])?'0':$_POST['id_jenis'];
		$data['Kode']		  =empty($_POST['id_barang'])?rand(1000,9999):addslashes(strtoupper($_POST['id_barang']));
		$data['ID_Pemasok'] =empty($_POST['id_barcode'])?'':$_POST['id_barcode'];
		$data['ID_Kategori']   =empty($_POST['id_kategori'])?'':addslashes($_POST['id_kategori']);
		$data['Nama_Barang']   =addslashes(strtoupper($_POST['nm_barang']));
		$data['ID_Satuan']	 =empty($_POST['id_satuan'])?'1':$_POST['id_satuan'];
		$data['Status']		=($Stat_bar=='106')?'JASA':'BARANG';
		$data['Harga_Beli']	=empty($_POST['stokmin'])?'0':$_POST['stokmin'];
		$data['Harga_Jual']	=empty($_POST['harga_1'])?'0':$_POST['harga_1'];
		$data['Harga_Partai']  =empty($_POST['harga_3'])?'0':$_POST['harga_3'];
		$data['Harga_Cabang']  =empty($_POST['harga_2'])?'0':$_POST['harga_2'];
		$data['minstok']	   =empty($_POST['minstok'])?'0':$_POST['minstok'];
		$kat['Kategori']	   =empty($_POST['nm_kategori'])?'':addslashes(strtoupper($_POST['nm_kategori']));
		$jen['JenisBarang']	=empty($_POST['nm_kategori'])?'':addslashes(strtoupper($_POST['nm_jenis']));
		$sat['Satuan']		 =empty($_POST['nm_kategori'])?'':addslashes(strtoupper($_POST['nm_satuan']));
		if(rdb('inv_barang_kategori','Kategori','Kategori',"where Kategori='".strtoupper($_POST['nm_kategori'])."'")==''){
			$this->Admin_model->replace_data('inv_barang_kategori',$kat);
		}
		if(rdb('inv_barang_jenis','JenisBarang','JenisBarang',"where JenisBarang='".strtoupper($_POST['nm_jenis'])."'")==''){
			$this->Admin_model->replace_data('inv_barang_jenis',$jen);
		}
		if(rdb('inv_barang_satuan','Satuan','Satuan',"where Satuan='".strtoupper($_POST['nm_satuan'])."'")==''){
			$this->Admin_model->replace_data('inv_barang_satuan',$sat);
		}
        $data["locked"]=($id_barang=="0")?"N":rdb("inv_barang",'locked','locked',"where ID='".$id_barang."'");
        $data["posting"]="0";
		$this->Admin_model->replace_data('inv_barang',$data);
		echo $this->inv_model->total_record('inv_barang','');
	}
	function simpan_barang_detail(){
		$data=array();
/**/	$data['nama']		=addslashes($_POST['nama']);
		$sn			=empty($_POST['sn'])?''		:addslashes($_POST['sn']);	
		$hpp		=empty($_POST['hpp'])?''	:$_POST['hpp'];	
		$htk		=empty($_POST['htk'])?'0'	:$_POST['htk'];	
		$htp		=empty($_POST['htp'])?'0'	:$_POST['htp'];	
		$htc		=empty($_POST['htc'])?'0'	:$_POST['htc'];	
		$mata_uang	=empty($_POST['idr'])?'IDR'	:$_POST['idr'];	
		$garansi	=empty($_POST['garansi'])?'':$_POST['garansi'];	
		$ukuran		=empty($_POST['ukuran'])?''	:addslashes($_POST['ukuran']);	
		$berat		=empty($_POST['berat'])?''	:$_POST['berat'];	
		$warna		=empty($_POST['warna'])?''	:addslashes($_POST['warna']);	

			$set ="set sn='".$sn."', ";
			$set.="hpp='".$hpp."', ";
			$set.="harga_toko='".$htk."', ";
			$set.="harga_partai='".$htp."', ";
			$set.="harga_cabang='".$htc."', ";
			$set.="mata_uang='".$mata_uang."', ";
			$set.="garansi='".$garansi."', ";
			$set.="ukuran='".$ukuran."', ";
			$set.="berat='".$berat."', ";
			$set.="warna='".$warna."' ";
			$where="where nama ='".$_POST['nama']."'";
	
		if($this->Admin_model->upd_data('barang',$set,$where)){
			echo "Data berhasil di simpan....";
			$this->Admin_model->upd_data('inv_barang',"set harga_beli='".$hpp."', harga_jual='".$htk."'","where Nama_Barang='".$_POST['nama']."'");
		}else{
			echo die(mysql_error());	
		}
	}
	function data_hgb(){
		$data=array();
		$nm_barang=$_POST['nm_barang'];
		$this->zetro_auth->frm_filename('asset/bin/zetro_inv.frm');
		$data=$this->zetro_auth->show_data_field('Harga Beli','inv_harga_beli',"where nm_barang='$nm_barang'");
		echo json_encode($data);	
	}
	function data_konversi(){
		$data=array();
		$nm_barang=$_POST['nm_barang'];
		$this->zetro_auth->frm_filename('asset/bin/zetro_inv.frm');
		$data=$this->zetro_auth->show_data_field('Barang','inv_material',"where nm_barang='$nm_barang'");
		echo json_encode($data);	
	}
	function list_hgb(){
	 	$this->_filename();
		$this->zetro_buildlist->section('Harga Beli');
		$sql2="select * from inv_barang where Nama_Barang='".strtoupper($_POST['nm_barang'])."' order by nm_barang";
		$this->zetro_buildlist->query($sql2);
		$this->zetro_buildlist->list_data('Harga Beli');
		$this->zetro_buildlist->BuildListData('Pemasok');
	}
	function list_konversi(){
	 	$this->_filename();
		$this->zetro_buildlist->aksi(true);
		$this->zetro_buildlist->icon('deleted');
		$this->zetro_buildlist->section('Konversi');
		$sql2="select k.nm_barang as nm_barang,s.Satuan as nm_satuan, sb.Satuan as sat_beli,k.isi_konversi
		 from inv_konversi as k
		 left join inv_barang_satuan as s
		 on s.ID=k.nm_satuan
		 left join inv_barang_satuan as sb
		 on sb.ID=k.sat_beli
		 where nm_barang='".strtoupper($_POST['nm_barang'])."' order by nm_barang";
		$this->zetro_buildlist->query($sql2);
		$this->zetro_buildlist->list_data('Konversi');
		$this->zetro_buildlist->BuildListData('sat_beli');
	}
	function simpan_hgb(){
		$data=array();$datax=array();
		$data['nm_barang']   =strtoupper($_POST['nm_barang']);
		$data['nm_produsen'] =strtoupper($_POST['nm_produsen']);
		$data['hg_beli']     =$_POST['hg_beli'];
		$data['sat_beli']    =$_POST['sat_beli'];
		//$data['created_by']  =$this->session->userdata("userid");
		$datax['nm_produsen']=strtoupper($_POST['nm_produsen']);
		$this->Admin_model->replace_data('inv_harga_beli',$data);
		$this->Admin_model->replace_data('mst_produsen',$datax);
		//generate list table
	 	$this->_filename();
		$this->zetro_buildlist->section('Harga Beli');
		$sql2="select * from inv_harga_beli where nm_barang='".strtoupper($_POST['nm_barang'])."' order by nm_barang";
		$this->zetro_buildlist->query($sql2);
		$this->zetro_buildlist->list_data('Harga Beli');
		$this->zetro_buildlist->BuildListData('nm_produsen');
	}
	function simpan_konversi(){
		$data=array();$datax=array();
		$data['id_barang']	 =rdb('inv_barang','ID','ID',"where Nama_Barang='".$_POST['nm_barang']."'");
		$data['nm_barang']   =strtoupper($_POST['nm_barang']);
		$data['nm_satuan']   =$_POST['nm_satuan'];
		$data['isi_konversi']=$_POST['isi_konversi'];
		$data['sat_beli']    =$_POST['sat_beli'];
		//$data['created_by']  =$this->session->userdata("userid");
		$this->Admin_model->replace_data('inv_konversi',$data);
		//generate list table
		$this->list_konversi();
	}

	function data_material(){
		$datax=array();
		$str	=addslashes($_GET['str']);
		$limit	=empty($_GET['limit'])?'':$_GET['limit'];
		$fld	=empty($_GET['fld'])?'':$_GET['fld'];
		$dest	=empty($_GET['dest'])?'':$_GET['dest'];
		$harga	=empty($_GET['hrg'])?'Harga_Jual':$_GET['hrg'];
		$datax=$this->inv_model->get_nm_material($str,$limit,$fld,$dest,$harga);
		echo json_encode($datax);	
	}
	function data_barcode(){
		$datax=array();
		$str	=$_GET['str'];
		$limit	=$_GET['limit'];
		$fld	=$_GET['fld'];
		$hrg=empty($_GET['hrg'])?'Harga_Jual':$_GET['hrg'];
		$dest	=empty($_GET['dest'])?'':$dest;
		$datax=$this->inv_model->get_nm_material($str,$limit,$fld,$dest,$hrg);
		echo ($datax!='[]')?json_encode($datax[0]):json_encode("{'data':''}");	
	}
	
	function detail_material(){
		$datax=array();
		$str	=empty($_POST['ID'])?'':$_POST['ID'];
		$datax=$this->Admin_model->show_list('barang',"where nama='".$str."'");
		echo empty($datax)?json_encode("{'sn':'';'hpp':0}"):json_encode($datax[0]);	
	}
	function data_produsen(){
		$str=addslashes($_POST['str']);
		$induk=$_POST['induk'];
		$fld='nm_produsen';
		$this->inv_model->tabel('mst_produsen');
		$this->inv_model->field($fld);
		$datax=$this->inv_model->auto_sugest($str);
		if($datax->num_rows>0){
			echo "<ul>";
				foreach ($datax->result() as $lst){
					echo '<li onclick="suggest_click(\''.$lst->$fld.'\',\'nm_produsen\',\''.$induk.'\');">'.$lst->$fld."</li>";
				}
			echo "</ul>";
		}		
	}
	function dropdown(){
		$table=$_POST['tbl'];
		$field=$_POST['field'];
		dropdown($table,$field,$field);
	}
	function show_list(){
		$data=array();$n=0; $where ="";
		$id			=empty($_POST['id'])?'123':$_POST['id'];
		$id_jenis	=empty($_POST['id_jenis'])?"":"and id_jenis='".$_POST['id_jenis']."'";
		$stat		=($_POST['stat']=='all')?'':"and status='".$_POST['stat']."'";
		$cari		=empty($_POST['cari'])?'': "and Nama_Barang like '%".$_POST['cari']."%'";
		$group		=empty($_POST['zero'])?"group by Nama_Barang":"group by Nama_Barang,b.ID";//,ms.batch,b.ID
		$withoutZero=empty($_POST['zero'])?"":" and ms.Stock <>'0'";
	if($cari==''){
		if($id!='' && $id_jenis!=''){
			 $where="where ID_Kategori='$id' $id_jenis $stat $cari $withoutZero $group order by ID_Jenis,nama_barang";
		}else if ($id=='' && $id_jenis!=''){
			 $where="where id_jenis='".$_POST['id_jenis']."' $stat $withoutZero $cari $group order by ID_Jenis,nama_barang";
		}else if ($id=='' && $id_jenis==''){
		 	 $where="where Nama_Barang like '".$_POST['cari']."%' $withoutZero $stat $group order by ID_Jenis,nama_barang";
		}else if ($id!='' && $id_jenis==''){
			 $where="where ID_Kategori='$id' $stat $withoutZero $cari $group order by ID_Jenis,nama_barang";
		}
	}else{
			$where="where Nama_Barang like '".$_POST['cari']."%'$group order by ID_Jenis,nama_barang";
	}
	//	 echo $id.'='. $where; /*//for debug only*/
		$data=$this->inv_model->list_barang($where);
		foreach($data as $r){
			$n++;$stock=0;
            $lok=($r->locked=='Y')?
                aksi('asset/img/lock_closed.png','lock','Click for Unlock stock kosong',"lockStock('".$r->ID."','N')"):
                aksi('asset/img/lock_open.png','unlock','Click for Unlock stock kosong',"lockStock('".$r->ID."','Y')");
			//$stock=rdb('inv_material_stok','stock','sum(stock) as stock',"where id_barang='".$r->ID."'");
			echo tr('xx','nm-'.$r->ID).td($n,'center').
				 td($r->Kategori." ".$r->JenisBarang,'kotak\' nowrap=\'false' ). td(($r->ID_Pemasok=='')?strtoupper($r->Kode):$r->ID_Pemasok).
				 td(strtoupper($r->Nama_Barang)).td($r->Satuan).
				 td(number_format($r->stock,2),'right');
            echo ($this->zetro_auth->cek_oto('e','listbarang')!='')?
				 td((!$r->harga_beli)?number_format((int)$r->Harga_Beli,2):number_format($r->harga_beli,2),'right'):
                 td('');
			echo td(number_format($r->Harga_Jual,2),'right').
				 td(number_format($r->Harga_Cabang,2),'right').
				 td(number_format($r->Harga_Partai,2),'right');
			echo ($this->zetro_auth->cek_oto('e','listbarang')!='')?
				($this->session->userdata('menus')=='QWNjb3VudGluZw==')? td():
				 td(
                    $lok.'&nbsp'.
				    aksi('asset/images/editor.png','edit','Click for edit',"upd_barang('".$r->ID."');").'&nbsp;'.
				 	aksi('asset/images/no.png','del','Click for delete',"delet_barang('".$r->ID."','".$r->batch."');"),'center'):'';
			echo _tr();
		}
		
	}
    //update lock Status
    function UpdateLockStatus(){
        $data=array();
        $id=$_POST['id'];
        $st=$_POST['stat'];
        $this->Admin_model->upd_data('inv_barang',"set lock='".$st."'"," where id='".$id."'");
    }
	//edit section
	function get_unit_konv(){
		$data=array();
		$nm_barang=$_POST['nm_barang'];
		$data=$this->inv_model->get_unit_konv($nm_barang);
		foreach($data as $r){
			echo "<option value='".$r->sat_beli."'>".rdb('inv_barang_satuan','Satuan','Satuan',"where ID='".$r->sat_beli."'")."</option>";	
		}
	}
	function edit_material(){
		$data=array();
		$nm_barang=str_replace('_',' ',$_POST['nm_barang']);
		$data=$this->inv_model->update_barang($nm_barang);
		echo json_encode($data[0]);	
	}
	
	function hapus_inv(){
		$fld=$_POST['fld'];
		$tbl=$_POST['tbl'];
		$where=str_replace('_',' ',$_POST['id']);
		$this->Admin_model->hapus_table($tbl,$fld,$where);
/**/		$this->Admin_model->hps_data('inv_material_stok',"where ID_Barang='".$_POST['id']."' and batch='".$_POST['batch']."'");
		echo $_POST['id'];
	}
	function hapus_konversi(){
		$fld=$_POST['fld'];
		$tbl=$_POST['tbl'];
		$this->Admin_model->hps_data('inv_konversi',"where id_barang='$fld' and sat_beli='$tbl'");
	}
	//get additional constantdata for buildlist
	function _filename(){
		$this->zetro_buildlist->config_file('asset/bin/zetro_inv.frm');
		$this->zetro_buildlist->aksi(true);
		$this->zetro_buildlist->icon();
	}
	
	function get_satuan(){
		$id_barang=$_POST['id_barang'];
	}
}