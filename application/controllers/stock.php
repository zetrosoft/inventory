<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
Class name: Inventory controller
version : 1.0
Author : Iswan Putera
*/

class Stock extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->model("Admin_model");
		$this->load->model("inv_model");
		$this->load->library('zetro_auth');
		$this->load->model("report_model");
		$this->load->helper("print_report");
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
		$this->zetro_auth->menu_id(array('stock__index','liststock'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('inventory/material_stock');
	}
	function stock_barang(){
		$this->zetro_auth->menu_id(array('liststock','stockadjustment'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('warehouse/material_stocklist');
	}

	function list_stock(){
		$data=array();$n=0;$jml=0;$harga=0;$datax=array();$x=0;
		$id=$_POST['nm_barang'];
		$datax=$this->Admin_model->show_list('user_lokasi',"/*where ID in(".$this->zetro_auth->cek_area().")*/ order by ID");
		foreach($datax as $rw){
			$x++;
			echo tr('list_genap').td('&nbsp;&nbsp;<b>Lokasi :</b>&nbsp;&nbsp;'.$x.'. '.$rw->lokasi,'left\' colspan=\'5')._tr();
			$data=$this->inv_model->get_detail_stock($id,"and id_lokasi='".$rw->ID."'");
            $hargaLain="";
			foreach($data as $row){
				$n++;
                $hargaLain .="Harga 1= ".number_format($row->harga_beli,2)."\n";
                $hargaLain .="Harga 2= ".number_format($row->Harga2,2)."\n";
                $hargaLain .="Harga 3= ".number_format($row->Harga3,2);
				echo tr().td($n,'center').td($row->batch,'center').
					td(number_format($row->stock,2),'right').
					td(rdb('inv_barang_satuan','Satuan','Satuan',"where ID='".$row->nm_satuan."'"),'center').
					td(number_format($row->harga_beli,2),'right','kotak',$hargaLain).
					_tr();
				$jml=($jml+$row->stock);
				$harga=($harga+($row->stock*$row->harga_beli));
			}
		}
		echo tr('list_genap').td('<b>Total</b>','right\' colspan=\'2').
				 td('<b>'.number_format($jml,2).'</b>','right').
				 td('').
				 td('','right').
				 _tr();
	}
	
	function get_bacth(){
		$data=array();
		$id=$_POST['id_barang'];
		$lokasi=empty($_POST['lokasi'])?'1':$_POST['lokasi'];
		$data=$this->inv_model->get_detail_stocked($id,'Desc','1','limit 1',$lokasi);	
		echo (count($data)>0)?json_encode($data[0]):'{"batch":"0"}';
	}
	function list_filtered(){
		$nmj=array(); $data='';$valfld='';$n=0;
		$section=$_POST['section'];
		$kat	=empty($_POST['nm_kategori'])?'':$_POST['nm_kategori'];
		$stat	=empty($_POST['stat_barang'])?'':$_POST['stat_barang'];
		$cari	=empty($_POST['nam_barang'])?'':$_POST['nam_barang'];
		if($kat=='' && $stat==''){
			$where='';
		}else if($stat=='' && $kat!=''){
			$where="where ID_Kategori='$kat'";
		}else if($kat=='' && $stat!=''){
			$where="where Status='$stat'";
		}else{
			$where="where ID_Kategori='$kat' and Status='$stat'";
		}
		if($cari!='' && $where !=''){
			$where .= " and Nama_Barang like '".$cari."%'";
		}else if($cari!='' && $where ==''){
			$where ="where Nama_Barang like '".$cari."%'";
		}
		//echo $where;
		if($kat!='' || $cari!=''){
		$nmj=$this->inv_model->set_stock($where);
			foreach ($nmj as $row){
				$n++;
				echo tr().td($n,'center').td($row->Kode).td(ucwords($row->Nama_Barang)).
					td($row->Satuan).td(number_format($row->stock,0),'right').td($row->Status);
				echo ($section=='stoklistview')?
					td("<img src='".base_url()."asset/images/editor.png' onclick=\"edited('".$row->ID."');\"",'center'):'';
				echo _tr();
			}
		}else{
			echo tr().td('Data terlalu besar untuk ditampilkan pilih dulu Katgeori','left\' colspan=\'7')._tr();
		}
	}
	function get_material_stock(){
		$data=array();$stok=0;$sat='';$datax=array();
		$id_material=$_POST['id_material'];
		$lokasi=empty($_POST['lokasi'])?'':" and s.id_lokasi='".$_POST['lokasi']."'";
		$data=$this->inv_model->get_total_stock($id_material,$lokasi);
		foreach($data as $r){
			$stok	=$r->stock;
			$sat	=$r->satuan;
			$id_barang=$r->ID;
			$datax=array('stock'=>$r->stock,
						   'satuan'=>$r->satuan,
						   'id_barang'=>$r->ID);
			
		}
		($stok=='')?'0':$stok;
		echo json_encode($datax);
	}
	function data_hgb(){
		$data=array();
		$nm_barang=$_POST['nm_barang'];
		$this->zetro_auth->frm_filename('asset/bin/zetro_inv.frm');
		$data=$this->zetro_auth->show_data_field('stokoverview','inv_material',"where nm_barang='$nm_barang'");
		echo json_encode($data);	
	}
	function _filename(){
		$this->zetro_buildlist->config_file('asset/bin/zetro_inv.frm');
		$this->zetro_buildlist->aksi();
		$this->zetro_buildlist->icon();
	}
	
	function counting(){
		$this->zetro_auth->menu_id(array('countsheet','adjust'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('inventory/material_opname');
	}
	function countsheet_prn(){
		$data=array();
		$data['bln']=date('F');
		$data['thn']=date('Y');
		$where=($this->input->post('Kategori')=='')?'':"where b.ID_Kategori='".$this->input->post('Kategori')."'";
		$orderby=($this->input->post('orderby'))?'':"order by concat(".str_replace('-',',',$this->input->post('orderby')).")";
		$orderby.=($this->input->post('urutan'))?'':' '.$this->input->post('urutan');
		$groupby="group by b.ID";
		$data['temp_rec']=$this->inv_model->set_stock($where,$groupby.' '.$orderby);
		$this->zetro_auth->menu_id(array('trans_beli'));
		$this->list_data($data);
		$this->View("inventory/countsheet_print");
	}
	function get_stock(){
		$data=array();$n=0;$where='';
		$lks=empty($_POST['lokasi'])?'1':$_POST['lokasi'];
		$ktg=empty($_POST['kategori'])?'':$_POST['kategori'];
		$zero=empty($_GET['zero'])?'':$_GET['zero'];
		$sto=($zero=='y')? '':"and ms.Stok <>'0'";
		$kat=($zero=='y')? "and im.ID_Kategori='123'":'';
		$where="where ms.id_lokasi='".$lks."' ".$zero;
		$where.=($ktg=='')?$kat:" and im.ID_Kategori='".$ktg."'";
        $where=empty($_POST['cari'])?$where:" where im.Nama_Barang like '%".$_POST['cari']."%'";
		$orderby=empty($_POST['orderby'])?'':" order by ".str_replace('-',',',$_POST['orderby'])." ";
		$orderby.=empty($_POST['urutan'])?'':strtoupper($_POST['urutan']);
		$sesi=$this->session->userdata('menus');
		$edit_true=empty($_POST['edited'])?'':$_POST['edited'];
		$oto	=$this->zetro_auth->cek_oto('e','liststock');
		//echo $where;
		$data=$this->report_model->stock_list($where,'stock',$orderby);
		foreach($data as $r){
			$n++;
			echo tr().td($n,'center').
					  td(strtoupper($r->Kode)).
					  td(strtoupper($r->Nama_Barang)).
					  td(rdb('inv_barang_kategori','Kategori','Kategori',"where ID='".$r->ID_Kategori."'")).
					  td(number_format($r->stock,2),'right').
					  td(rdb('inv_barang_satuan','Satuan','Satuan',"where ID='".$r->ID_Satuan."'")).
					  td(number_format(($r->harga_beli*$r->stock),2),'right');
					  //td($r->Status);
				 if($sesi=='SW52ZW50b3J5' && $edit_true!='' ){
					 	echo td(($oto!='')?img_aksi($r->ID.':'.$r->batch.':'.$r->id_lokasi):'','center');}
				echo  _tr();	 
		}
	}
	function print_stock(){
		$data=array();$n=0; $where='';
		$where="where im.status='BARANG'";
		$where.=($this->input->post('Kategori')=='')?'':"and im.ID_Kategori='".$this->input->post('Kategori')."'";
		$where.=($this->input->post('id_lokasi')!='')?" and ms.id_lokasi='".$this->input->post('id_lokasi')."'":"";
		//$where.=($this->input->post('Stat')=='')?'':" and Status='".$this->input->post('Stat')."'";
        $where=($this->input->post('cari')=='')?$where:" where im.Nama_Barang like '%".$this->input->post('cari')."%'";
		$orderby=($this->input->post('orderby')=='')?'':" order by ".str_replace('-',',',$this->input->post('orderby'))." ";
		$orderby.=($this->input->post('urutan')=='')?'':strtoupper($this->input->post('urutan'));
		$data['kategori']=rdb('inv_barang_kategori','Kategori','Kategori',"where ID='".$this->input->post('Kategori')."'");
		$data['status']	=$this->input->post('Stat');
		$data['temp_rec']=$this->report_model->stock_list($where,'stock',$orderby);
			$this->zetro_auth->menu_id(array('trans_beli'));
			$this->list_data($data);
		    $this->View("laporan/transaksi/lap_stock_print");
	/*	echo $where."<br>";
		print_r($data);
*/	}
	function edit_stock(){
		$data=array();
		$id=explode(':',$_POST['ID']);
		$where=($id[1]=='')?"where im.ID='".$id[0]."'":"where im.ID='".$id[0]."' and ms.batch='".$id[1]."' and ms.id_lokasi='".$id[2]."'";
		$data=$this->report_model->stock_list($where,'edit');
		echo json_encode($data[0]);
	}
	function update_adjust(){
		$data=array();
		$ID		=$_POST['id'];
		$batch	=$_POST['batch'];
		$stock	=$_POST['stock'];
		$lokasi	=empty($_POST['lokasi'])?'1':$_POST['lokasi'];
		$cek=rdb('inv_material_stok','id_barang','id_barang',"where id_barang='".$ID."' and batch='".$batch."' and id_lokasi='".$lokasi."'");
		if($cek!=''){
			$this->Admin_model->upd_data('inv_material_stok',"set stock='".$stock."'","where id_barang='".$ID."' and batch='".$batch."' and id_lokasi='".$lokasi."'");
		}else{
			$data['id_lokasi']	=$lokasi;
			$data['id_barang']	=$_POST['id'];
			$data['nm_barang']	=rdb('inv_barang','Nama_Barang','Nama_Barang',"where ID='".$ID."'");
			$data['batch']		=date('yz').'-'.rand(0,9);
			$data['stock']		=$stock;
			$data['nm_satuan']	=rdb('inv_barang','ID_Satuan','ID_Satuan',"where ID='".$ID."'");
			$data['harga_beli']	=$_POST['harga'];
			$data['created_by']	=$this->session->userdata('userid');
			$this->Admin_model->replace_data('inv_material_stok',$data);
		}
	}
	function stock_limit(){
		$this->zetro_auth->menu_id(array('stocklimit'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('warehouse/material_stock_limit');
	}
	
	function get_stock_limit(){
		$data=array();$n=0;
		$where=($this->input->post('Kategori')=='')?'':"where im.ID_Kategori='".$this->input->post('Kategori')."' and ms.Stock<>'0'";
		$orderby=($this->input->post('orderby'))?'':"order by ".str_replace('-',',',$this->input->post('orderby'))." ";
		$orderby.=($this->input->post('urutan'))?'':' '.$this->input->post('urutan');
		$data['kategori']=rdb('inv_barang_kategori','Kategori','Kategori',"where ID='".$this->input->post('Kategori')."'");
		$data['temp_rec']=$this->report_model->stock_list($where,'stocklimit',$orderby);
			$this->zetro_auth->menu_id(array('trans_beli'));
			$this->list_data($data);
			$this->View("laporan/transaksi/lap_stock_limit_print");
	}

}