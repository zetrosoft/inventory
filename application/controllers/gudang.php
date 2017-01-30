<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
Class name: gudang controller
version : 1.0
Author : Iswan Putera
*/

class Gudang extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->model("Admin_model");
		$this->load->model("inv_model");
		$this->load->model("kasir_model");
		$this->load->helper("print_report");
		$this->load->library('zetro_auth');
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
		$this->zetro_auth->view($view);
		$this->load->view($view,$this->data);	
		$this->Footer();
	}
	//tampilkan view
	function index(){
		//tampiklan view tambah barang
		//$this->inv_model->auto_data();
		$this->zetro_auth->menu_id(array('tambahbarang','detailbarang'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('warehouse/material_new');

	}
	function stock_adjust(){
		$this->zetro_auth->menu_id(array('stockadjust','countsheet'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('inventory/material_adjust');
	}
	function list_barang(){
		//tampilkan view list barang
		//$this->inv_model->auto_data();
		$this->zetro_auth->menu_id(array('listbarang'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('inventory/material_list');
	}
	
	function kategori(){
		//tampilkan view kategori barang
		//$this->inv_model->auto_data();
		$this->zetro_auth->menu_id(array('kategoribarang'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('inventory/master_inv');
	}
	function jenis(){
		//tampilkan view jenis barang
		//$this->inv_model->auto_data();
		$this->zetro_auth->menu_id(array('jenisbarang'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('warehouse/material_jenis');
	}
	function satuan(){
		//tampilkan view satuan barang
		//$this->inv_model->auto_data();
		$this->zetro_auth->menu_id(array('satuanbarang'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('warehouse/material_satuan');
	}
	function konversi(){
		//tampilkan view konversi satusn
		//$this->inv_model->auto_data();
		$this->zetro_auth->menu_id(array('konversisatuan'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('warehouse/material_konversi');
	}
    function UpdateLockStatus(){
        $data=array();
        $id=$_POST['id'];
        $st=$_POST['stat'];
        $this->Admin_model->upd_data('inv_barang',"set locked='".$st."'"," where id='".$id."'");
    }
	//mutasi stock
	function mutasi(){
		$this->zetro_auth->menu_id(array('mutasistock','listmutasi'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('warehouse/material_mutasi_stock');
	}
	function set_mutasi(){
		$data=array();$datax=array();
		$ID_Barang=rdb('inv_barang','ID','ID',"where Nama_Barang='".$_POST['nm_barang']."'");
		$batch=rdb('inv_material_stok','batch','batch',"where id_barang='".$ID_Barang."' and stock >'0' order by doc_date Desc");
		$data['ID']			=empty($_POST['ID'])?'0':$_POST['ID'];
		$data['NoTrans']	=$_POST['no_trans'];	
		$data['Tanggal']	=tglToSql($_POST['tanggal']);	
		$data['ID_Barang']	=$ID_Barang;	
		$data['Batch']		=$batch;
		$data['ID_Satuan']	=$_POST['id_satuan'];	
		$data['Jumlah']		=$_POST['jumlah'];	
		$data['ID_Lokasi_asal']	=$_POST['asal'];	
		$data['ID_Lokasi_kirim']=empty($_POST['tujuan'])?'':$_POST['tujuan'];	
		$data['Keterangan']	=$_POST['keterangan'];	
		$data['Created_by'] =$this->session->userdata('userid');
		$this->Admin_model->replace_data('inv_mutasi_stock',$data);
		//update nomor
		$datax['nomor']	=$_POST['no_trans'];
		$datax['jenis_transaksi']='GI';
		$datax['created_by']=$this->session->userdata('userid');
		$this->Admin_model->replace_data('nomor_transaksi',$datax);
		echo $_POST['no_trans'];
		
	}
	function del_mutasi(){
		$ID=$_POST['ID'];
		//update stok
		$ID_Barang=rdb('inv_mutasi_stock','ID_Barang','ID_Barang',"where ID='".$ID."'");
		$bt=rdb('inv_mutasi_stock','Batch','Batch',"where ID='".$ID."'");
		$jml=rdb('inv_mutasi_stock','Jumlah','Jumlah',"where ID='".$ID."'");
		$lokais=rdb('inv_mutasi_stock','ID_Lokasi_asal','ID_Lokasi_asal',"where ID='".$ID."'");
		$existing_stock=rdb('inv_material_stok','stock','stock',"where id_barang='".$ID_Barang."' and batch='".$bt."' and id_lokasi='".$lokasi."'");
		$hasil_konv=rdb('inv_konversi','isi_konversi','isi_konversi',"where id_barang='".$ID_Barang."' and sat_beli='".
					rdb('inv_mutasi_stock','ID_Satuan','ID_Satuan',"where ID='".$ID."'")."'");
		$new_stock=($existing_stock+($hasil_konv*$jml));
		//$new_stock=($new_stock<0)?0:$new_stock;
		$this->Admin_model->upd_data('inv_material_stok',"set stock='".$new_stock."'","where id_barang='".$ID_Barang."' and batch='".$bt."'");
		//hapus data
		$this->Admin_model->hps_data('inv_mutasi_stock',"where ID='".$ID."'");	
		echo ($ret=='2')?'':($existing_stock-(int)$jml);

	}
	function get_mutasi(){
			$data=array();$n=0;
			$ID=$_POST['no_transaksi'];
			$tanggal=tglToSql($_POST['tanggal']);
			$data=$this->Admin_model->show_list('inv_mutasi_stock',"where Tanggal='".$tanggal."' and NoTrans='".$ID."'");
			if($data){
				foreach($data as $r){
					$n++;
					echo tr().td($n,'center').
							  td(rdb('inv_barang','Kode','Kode',"where ID='".$r->ID_Barang."'"),'center').
							  td(rdb('inv_barang','Nama_Barang','Nama_Barang',"where ID='".$r->ID_Barang."'")).
							  td(rdb('inv_barang_satuan','Satuan','Satuan',"where ID='".$r->ID_Satuan."'")).
							  td(number_format($r->Jumlah,2),'right'),
							  td($r->Batch).td().
							  td(img_aksi($r->ID,true,'del'),'center').
							_tr();
				}
			}else{
				echo tr().td('&nbsp;','left\' colspan=\'8\'')._tr();
			}
	}
	function lap_mutasi(){
			$data=array();$n=0;$datax=array();
			$dari=empty($_POST['dari'])?date('Ymd'):tglToSql($_POST['dari']);
			$tanggal=empty($_POST['tanggal'])?$dari:tglToSql($_POST['tanggal']);
			$stat=empty($_POST['status'])?'':" and Status='".$_POST['status']."'";
			$datax=$this->Admin_model->show_list('inv_mutasi_stock',"where Tanggal between '".$dari."' and '".$tanggal."' $stat group by NoTrans order by ID");
			if($datax){
			  foreach($datax as $h){
				$n++;$nn=0;$statuse='';
				 switch($h->Status){
					case 'T':
					$statuse='Diterima';
					break;
					case 'Y':
					$statuse='Terkirim';
					break;
					case 'N':
					$statuse=($this->zetro_auth->cek_oto('e','listmutasi')!='')?
					'Pending'.nbs(2).img_aksi($h->NoTrans.'-'.tglfromSql($h->Tanggal),true,'pros'):
					'Pending';
					break;
				 }
				echo tr(($statuse=='Diterima')?'list_ganjil':'list_genap').td($n,'center').
					 td('No. Transaksi : '.$h->NoTrans.'==> <b>Asal Gudang </b> : '.
					 	rdb('user_lokasi','lokasi','lokasi',"where ID='".$h->ID_Lokasi_asal."'").', <b>Tujuan Kirim </b> : '.
						rdb('user_lokasi','lokasi','lokasi',"where ID='".$h->ID_Lokasi_kirim."'"),
						'left\' colspan=\'2').
					 td('Tanggal Kirim','right').td(tglfromSql($h->Tanggal),'left').
					 td('Status','right').
					 td($statuse,'center').
				    _tr();
						
				$data=$this->Admin_model->show_list('inv_mutasi_stock',"where Tanggal='".$h->Tanggal."' and NoTrans='".$h->NoTrans."' order by ID");
				foreach($data as $r){
					$nn++;
					echo tr().td($nn.nbs(2),'right').
							  td(rdb('inv_barang','Kode','Kode',"where ID='".$r->ID_Barang."'"),'center').
							  td(rdb('inv_barang','Nama_Barang','Nama_Barang',"where ID='".$r->ID_Barang."'")).
							  td(rdb('inv_barang_satuan','Satuan','Satuan',"where ID='".$r->ID_Satuan."'")).
							  td(number_format($r->Jumlah,2),'right'),
							  td($r->Batch);
					 echo ($this->zetro_auth->cek_oto('e','listmutasi')!='')?
							  td(($r->Status=='Y')?img_aksi($r->ID,true,'del'):'','center'):td();
					 echo 	_tr();
				}
			  }
			}else{
				echo tr().td('Tidak ada mutasi hari ini','left\' colspan=\'8\'')._tr();
			}
	}
	function print_mutasi(){
		$data=array();$n=0;
		$nTrans=$_POST['notrans'];
		$tanggal=tglToSql($_POST['tanggal']);
		//update status mutasi
		$this->Admin_model->upd_data('inv_mutasi_stock',"set Status='Y'","where NoTrans='".$nTrans."' and Tanggal='".$tanggal."'");
		//generate PDF
		$data['notrans']=$nTrans;
		$data['tanggal']=$_POST['tanggal'];
		$data['dari']	=rdb('inv_mutasi_stock','ID_Lokasi_asal','ID_Lokasi_asal',"where NoTrans='".$nTrans."' and Tanggal='".$tanggal."'");
		$data['kelokasi']=rdb('inv_mutasi_stock','ID_Lokasi_kirim','ID_Lokasi_kirim',"where NoTrans='".$nTrans."' and Tanggal='".$tanggal."'");
		$data['ket']	=rdb('inv_mutasi_stock','Keterangan','Keterangan',"where NoTrans='".$nTrans."' and Tanggal='".$tanggal."'");
		$data['temp_rec']=$this->Admin_model->show_list('inv_mutasi_stock',"where NoTrans='".$nTrans."' and Tanggal='".$tanggal."'");	
		$data['temp_rec2']=$this->Admin_model->show_list('inv_mutasi_stock',"where NoTrans='".$nTrans."' and Tanggal='".$tanggal."'");	
		$this->zetro_auth->menu_id(array('trans_beli'));
		$this->list_data($data);
		$this->View("warehouse/material_mutasi_print");
	}
	function get_header_mutasi(){
		$data=array();$datax=array();
		$tgl='';$ida='';$idk='';$ket='';
		$nTrans=empty($_POST['notrans'])?'':$_POST['notrans'];
		$tanggal=empty($_POST['tanggal'])?date('Ymd'):tglToSql($_POST['tanggal']);
		$data=$this->Admin_model->show_list('inv_mutasi_stock',"where NoTrans='".$nTrans."' and Tanggal='".$tanggal."'");
		foreach($data as $r){
			$tgl=$r->Tanggal;
			$ida=$r->ID_Lokasi_asal;
			$idk=$r->ID_Lokasi_kirim;
			$ket=$r->Keterangan;	
		}
		$datax=array('Tanggal'=>$tgl,
					 'ID_Lokasi_asal'=>$ida,
					 'ID_Lokasi_kirim'=>$idk,
					 'Keterangan'=>$ket);
		echo json_encode($datax);
	}
	function get_stock(){
		$data=array();$kosong=array();
		$ID=$_POST['ID'];
		$data=$this->Admin_model->show_list('inv_material_stok',"where id_barang='".$ID."' and stock >'0' group by batch order by doc_date asc");	
		if(empty($data)){$kosong[]=array('batch'=>'','stock'=>'0');}else{$kosong=$data;}
		echo json_encode($kosong[0]);
	}
	function terima_mutasi(){
		$this->zetro_auth->menu_id(array('penerimaanmutasi'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('warehouse/material_mutasi_terima');
	}
	function no_proses(){
		echo tr().td(img_aksi('',true,'warning').nbs(3). 'You have not authorization for this page','left\' colspan=\'8\'')._tr();
	}
	function proses_terima_mutasi(){
			$data=array();$n=0;$datax=array();$area='';
			$area=$this->zetro_auth->cek_area();
			$lokasi=($area=='')?'':" where ID_Lokasi_kirim in(".$area.")";
			$notrans=empty($_POST['no_trans'])?'':" and NoTrans='".$_POST['no_trans']."'";
			$where=(strlen($lokasi)==0 && !empty($_POST['no_trans']))?str_replace('and','where',$notrans):$lokasi.$notrans;
			if($area !='' ||
			   $this->session->userdata('idlevel')=='1' ||
			   $this->zetro_auth->cek_oto('c','penerimaanmutasi')!=''){
				$datax=$this->Admin_model->show_list('inv_mutasi_stock',"$where group by NoTrans order by ID");
				if($datax){
				  foreach($datax as $h){
					$n++;$nn=0;$statT=0;$Total=0;
					$statT=rdb('inv_mutasi_stock','Status','count(Status) as Status',"where Tanggal ='".$h->Tanggal."' and NoTrans='".$h->NoTrans."' and Status='T' order by ID");
					$Total=rdb('inv_mutasi_stock','Status','count(Status) as Status',"where Tanggal ='".$h->Tanggal."' and NoTrans='".$h->NoTrans."' order by ID");
					if(($Total-$statT)!=0 && $h->Status!='N'){
						echo tr('list_genap').td($n,'center').
							 td('No. Transaksi : '.$h->NoTrans.'==> <b>Asal Gudang </b> : '.
								rdb('user_lokasi','lokasi','lokasi',"where ID='".$h->ID_Lokasi_asal."'").', <b>Tujuan Kirim </b> : '.
								rdb('user_lokasi','lokasi','lokasi',"where ID='".$h->ID_Lokasi_kirim."'"),
								'left\' colspan=\'5').
								td(($h->Status=='T')?'Diterima':img_aksi($h->NoTrans.'-'.tglfromSql($h->Tanggal),false,'pros'),'center').
							_tr();
								
						$data=$this->Admin_model->show_list('inv_mutasi_stock',"where Tanggal ='".$h->Tanggal."' and NoTrans='".$h->NoTrans."' order by ID");
						foreach($data as $r){
							$nn++;
								echo tr().td($nn.nbs(2),'right').
										  td(rdb('inv_barang','Kode','Kode',"where ID='".$r->ID_Barang."'"),'center').
										  td(rdb('inv_barang','Nama_Barang','Nama_Barang',"where ID='".$r->ID_Barang."'")).
										  td(rdb('inv_barang_satuan','Satuan','Satuan',"where ID='".$r->ID_Satuan."'")).
										  td(number_format($r->Jumlah,2),'right'),
										  td($r->Batch).
										  td(($r->Status=='Y')?img_aksi($r->ID,true,'check'):'Diterima','center').
										_tr();
						}
					}
				  }
				}else{
					echo tr().td(img_aksi('',true,'info').nbs(3).'Data not found','left\' colspan=\'8\'')._tr();
				}
			}else{
				$this->no_proses();
			}
			if($nn==0){
				echo tr().td(img_aksi('',true,'info').nbs(3).'Data not found','left\' colspan=\'8\'')._tr();				
			}
	}
	function update_terima_mutasi(){
		$data=array();$datax=array();
		$no_trans=empty($_POST['no_trans'])?'':$_POST['no_trans'];
		$id_trans=empty($_POST['id_trans'])?'':$_POST['id_trans'];
		$tanggal=empty($_POST['tanggal'])?'':tglToSql($_POST['tanggal']);
		($no_trans!='')?
		$this->Admin_model->upd_data('inv_mutasi_stock',"set Status='T'","where NoTrans='".$no_trans."' and Tanggal='".$tanggal."'"):
		$this->Admin_model->upd_data('inv_mutasi_stock',"set Status='T'","where ID='".$id_trans."'");
		//update stock
		$data=($no_trans!='')?
		$this->Admin_model->show_list('inv_mutasi_stock',"where NoTrans='".$no_trans."' and Tanggal='".$tanggal."'"):
		$this->Admin_model->show_list('inv_mutasi_stock',"where ID='".$id_trans."'");
		foreach($data as $r){
			unset($datax);$exist_stock=0;$hasil_konv=1;$harga_beli=0;
			$exist_stock=rdb('inv_material_stok','stock','stock',
							"where id_barang='".$r->ID_Barang."' and batch='".$r->Batch."' and id_lokasi='".$r->ID_Lokasi_kirim."'"); 
			$hasil_konv=rdb('inv_konversi','isi_konversi','isi_konversi',"where id_barang='".$r->ID_Barang."' and sat_beli='".
						rdb('inv_mutasi_stock','ID_Satuan','ID_Satuan',"where ID='".$r->ID_Satuan."'")."'");
			$hasil_konv=($hasil_konv=='')?1:$hasil_konv;
			$harga_beli=rdb('inv_material_stok','harga_beli','harga_beli',"where id_barang='".$r->ID_Barang."' and batch='".$r->Batch."'");
			$datax['id_lokasi']	=$r->ID_Lokasi_kirim;
			$datax['id_barang']	=$r->ID_Barang;
			$datax['batch']		=$r->Batch;
			$datax['nm_barang']	=rdb('inv_barang','Nama_Barang','Nama_Barang',"where ID='".$r->ID_Barang."'");
			$datax['stock']		=(($hasil_konv*$r->Jumlah)+$exist_stock);
			$datax['nm_satuan']	=$r->ID_Satuan;
			$datax['harga_beli']=($harga_beli=='')?'0':$harga_beli;
			$datax['created_by']=$this->session->userdata('userid');
			echo ($this->Admin_model->replace_data('inv_material_stok',$datax));
		}
	}
	//pemakaian lain
	function pemakaian(){
		$this->zetro_auth->menu_id(array('addpemakaian','listpemakaian'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('warehouse/material_mutasi');
	}
	
	function set_pemakaian(){
		$data=array();
		$data['ID_Jenis']=$_POST['id_jenis'];
		$data['Tanggal']=tglToSql($_POST['tanggal']);
		$data['Bulan']	=substr($_POST['tanggal'],3,2);
		$data['Tahun']	=substr($_POST['tanggal'],6,4);
		$data['ID_Barang']=$_POST['id_barang'];
		$data['Jumlah']	=$_POST['jumlah'];
		$data['Harga']	=$_POST['harga'];
		$data['Keterangan']=empty($_POST['keterangan'])?'':$_POST['keterangan'];
		$this->Admin_model->replace_data('z_inv_pemakaian',$data);
	}
	
	function get_pemakaian(){
		$data=array();$n=0;
		$dari=tglToSql($_POST['dari_tgl']);
		$sampai=empty($_POST['sampai_tgl'])?$dari:tglToSql($_POST['sampai_tgl']);
		$where=empty($_POST['sampai_tgl'])? "where Tanggal='".$dari."'":
			   "where Tanggal between '".$dari."' and '".$sampai."'";
		$where.=" and ID_Jenis='".$_POST['id_jenis']."'";
		$where.=" order by Tanggal";
		$data=$this->Admin_model->show_list('z_inv_pemakaian',$where);
		foreach($data as $r){
			$n++;
			echo tr().td($n,'center').td(tglfromSql($r->Tanggal),'center').
				 td(rdb('inv_barang','Kode','Kode',"where ID='".$r->ID_Barang."'")).	
				 td(rdb('inv_barang','Nama_Barang','Nama_Barang',"where ID='".$r->ID_Barang."'")).	
				 td(rdb('inv_barang_satuan','Satuan','Satuan',"where ID='".rdb('inv_barang','ID_Satuan','ID_Satuan',"where ID='".$r->ID_Barang."'")."'")).
				 td(number_format($r->Jumlah,2),'right').
				 td(number_format($r->Harga,2),'right').
				 td(number_format(($r->Jumlah*$r->Harga),2),'right').
				 td($r->Keterangan).
				 td(img_aksi($r->ID,true),'center').
				_tr();
		}
	}
	function print_pemakaian(){
		$data	=array();
		$dari	=$this->input->post('dari_tgl');
		$sampai	=($this->input->post('sampai_tgl')=='')?$dari:$this->input->post('sampai_tgl');
		$where	=($this->input->post('sampai_tgl')=='')? "where Tanggal='".tglToSql($dari)."'":
			  	 "where Tanggal between '".tglToSql($dari)."' and '".tglToSql($sampai)."'";
		$where.=" and ID_Jenis='1'";
		$where	.=" order by Tanggal";
		$data['dari']		=$dari;
		$data['sampai']		=$sampai;
		$data['temp_rec']	=$this->Admin_model->show_list('z_inv_pemakaian',$where);
			$this->zetro_auth->menu_id(array('trans_beli'));
			$this->list_data($data);
			$this->View("laporan/transaksi/lap_mutasi_print");

	}
	function print_rusak(){
		$data	=array();
		$dari	=$this->input->post('dari_tgl');
		$sampai	=($this->input->post('sampai_tgl')=='')?$dari:$this->input->post('sampai_tgl');
		$where	=($this->input->post('sampai_tgl')=='')? "where Tanggal='".tglToSql($dari)."'":
			  	 "where Tanggal between '".tglToSql($dari)."' and '".tglToSql($sampai)."'";
		$where.=" and ID_Jenis in('2','3')";
		$where	.=" order by Tanggal";
		$data['dari']		=$dari;
		$data['sampai']		=$sampai;
		$data['temp_rec']	=$this->Admin_model->show_list('z_inv_pemakaian',$where);
			$this->zetro_auth->menu_id(array('trans_beli'));
			$this->list_data($data);
			$this->View("laporan/transaksi/lap_mutasi_print");

	}
	function edit_pemakaian(){
		$data=array();
		$id=$_POST['id'];
		$data=$this->kasir_model->edit_pemakaian("where zp.ID='".$id."'");
		echo json_encode($data[0]);
	}
	function update_pemakaian(){
		$id		=$_POST['id'];
		$jml	=$_POST['jumlah'];
		$harga	=empty($_POST['harga'])?0:$_POST['harga'];
		$ketera	=empty($_POST['keterangan'])?'':$_POST['keterangan'];
		$jml_existing=rdb('z_inv_pemakaian','Jumlah','Jumlah',"where ID='".$id."'");
		$this->Admin_model->upd_data('z_inv_pemakaian',"set Jumlah='".$jml."', harga='".$harga."', Keterangan='".$ketera."'","where ID='".$id."'");	
		echo ((int)$jml-$jml_existing);
	}
	function delete_pemakaian(){
		$id=$_POST['id'];$jml=0;
		$jml=rdb('z_inv_pemakaian','Jumlah','Jumlah',"where ID='".$id."'");
		$this->Admin_model->hps_data('z_inv_pemakaian',"where ID='".$id."'");
		echo $jml;
	}
	//rusak atau hilang
	function rusak(){
		$this->zetro_auth->menu_id(array('addbarangrusak','listbarangrusak'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('warehouse/material_rusak');
	}
	//prosess auto suggest
	function update_stock(){
		$bt='';$existing_stock=0;$new_stock=0;
		$id=empty($_POST['id_barang'])?rdb('inv_barang','ID','ID',"where Nama_Barang='".addslashes($_POST['nm_barang'])."'"):$_POST['id_barang'];
		$jml=$_POST['jumlah'];
		$ret=empty($_POST['ret'])?'':$_POST['ret'];
		$bath=$this->inv_model->get_detail_stocked($id,'desc',$ret);
			foreach($bath as $w){
				$bt=$w->batch;
			}
		$bt=empty($_POST['batch'])?$bt:$_POST['batch'];
		$existing_stock=rdb('inv_material_stok','stock','stock',"where id_barang='".$id."' and batch='".$bt."'");
		$new_stock=($ret=='2')?($existing_stock+abs((int)$jml)):($existing_stock-abs((int)$jml));
		$new_stock=($new_stock<0)?0:$new_stock;
		$this->Admin_model->upd_data('inv_material_stok',"set stock='".$new_stock."'","where id_barang='".$id."' and batch='".$bt."'");
		echo ($ret=='2')?'':($existing_stock-(int)$jml);
	}
	function get_kategori(){
		$data=array();
		$str	=$_GET['str'];
		$limit	=$_GET['limit'];
		$data	=$this->inv_model->get_kategori($str,$limit);
		echo json_encode($data);
	}

	function get_jenis(){
		$data=array();
		$str	=$_GET['str'];
		$limit	=$_GET['limit'];
		$data	=$this->inv_model->get_jenis($str,$limit);
		echo json_encode($data);
	}
	function get_satuan(){
		$data=array();
		$str	=$_GET['str'];
		$limit	=$_GET['limit'];
		$data	=$this->inv_model->get_satuan($str,$limit);
		echo json_encode($data);
	}



}