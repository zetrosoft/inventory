<?php  
header('Access-Control-Allow-Origin: *');  
//if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//
//Class name: Penjualan controller
//version : 1.0
//Author : Iswan Putera

class Penjualan extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->model("Admin_model");
		$this->load->model("inv_model");
		$this->load->model("kasir_model");
		$this->load->library('zetro_auth');
		$this->load->library('zetro_slip');
		$this->load->model("report_model");
		$this->load->helper("print_report");
	}

	function Header(){ 
	// load header
		$this->load->view('admin/header');	
	}
	
	function Footer(){
	//load footer
		$this->load->view('admin/footer');	
	}
	function list_data($data){
	//membentuk data array untuk dikirim saat load view
		$this->data=$data;
	}
	function View($view){
		$this->Header();
		$this->zetro_auth->view($view);
		$this->load->view($view,$this->data);	
		$this->Footer();
	}
	//tampilan awal ketika menu input pembelian di klik
	function index(){
		$data=array();
		$data['datas']=$this->get_barang();
		$data['nasabah']=$this->get_pelanggan();
        $data['nomore']=$this->ListNotrans();
		$this->zetro_auth->menu_id(array('penjualan__index'));
		$this->list_data(array_merge($this->zetro_auth->auth(),$data));
		$this->hapus_resep_kosong();
		$this->View('penjualan/material_jual');
	}
	function get_barang()
	{
		$data=array();$akuns='';
		$data=$this->Admin_model->show_list("inv_barang","order by nama_barang");
		foreach($data as $r)
		{
			$akuns.="&quot;".str_replace("\""," ",strtoupper($r->Nama_Barang))."&quot;,";
		}
		//$akuns.=']';
		return substr($akuns,0,-1);
	}
	function get_pelanggan()
	{
		$data=array();$nasabah='';
		//$data=$this->Admin_model->show_list("mst_anggota","where id_jenis='1' and length(nama)>1 group by Nama,Alamat order by nama");
        $data=$this->Admin_model->show_list("mst_anggota_view","where  length(Nama)>1 group by Nama order by Nama");
		foreach($data as $r)
		{
	            $nasabah.="\"".str_replace("\\","-",str_replace("\""," ",strtoupper($r->Nama)))."\",";
		}
		return substr($nasabah,0,-1);
	}
	function get_detail_barang()
	{
		$data=array();
		$nm_br=empty($_POST['id'])?'':$_POST['id'];
		$data=$this->Admin_model->show_list("inv_barang_view","where nama_barang='".$nm_br."'");// or ID_Pemasok='".$nm_br."'");
			echo ($data)?json_encode($data[0]):'{"ID":"0"}';
		
	}
    function ListNotrans()
    {
     $data=array();$nomor='';
        $data=$this->Admin_model->show_list("inv_penjualan","where id_jenis='1' order by ID desc limit 100");
        foreach($data as $r)
        {
            $nomor.="\"".$r->NoUrut."\",";
        }
        return substr($nomor,0,-1);
    }
	// pejualan dengan resep untuk apotik
	function resep_std(){
		$data=array();
		$this->zetro_auth->menu_id(array('penjualan__resep_std'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('penjualan/material_jual_resep');
	}
	//generate nomor transaksi otomatis
	function nomor_transaksi(){
		$tipe=$_POST['tipe'];
		$level=empty($_POST['level'])?"1":$_POST['level'];
		$nomor=$this->Admin_model->penomoran($tipe,$level);
		echo $nomor;	
	}
	//generate nomor faktur penjualan
	function nomor_faktur(){
		$nom=0;
		$nom=rdb("inv_penjualan","bulan","count(bulan) as bulan","where bulan='".date('m')."' and tahun='".date('Y')."'");
		if($nom==0)
		{
			$n='0001';
		}else{
			if(strlen($nom)==1){$n='000'.($nom+1);
			}elseif(strlen($nom)==2){$n='00'.($nom+1);
			}elseif(strlen($nom)==3){$n='0'.($nom+1);
			}elseif(strlen($nom)==4){$n=($nom+1);
			}
		}
		echo $n;
	}
	
	function hapus_resep_kosong(){
	//hapus data resep yang stock nya kosong dan sudah expire 
	$this->inv_model->hapus_resep_kosong();
		
	}
	
	function get_satuan(){
		$data=array();
		$nm_barang=$_POST['nm_barang'];
		$rsp=$_POST['rsp'];
		$expired=$this->Admin_model->show_single_field('inv_material_stok','expired',"where nm_barang='$nm_barang' order by min(expired)");
		$harga=$this->Admin_model->show_single_field('inv_material_stok','harga_beli',"where nm_barang='$nm_barang' and expired='$expired'");
		$margin=1;//$this->Admin_model->show_single_field('inv_material','margin_jual',"where nm_barang='$nm_barang'");
		$uom=$this->Admin_model->show_single_field('inv_material','nm_satuan',"where nm_barang='$nm_barang'");
		($rsp=='n')?
			$satuan=$this->Admin_model->show_single_field('inv_material','nm_satuan',"where nm_barang='$nm_barang'"):
			$satuan=$this->Admin_model->show_single_field('inv_konversi','sat_beli',"where nm_barang='$nm_barang' order by min(isi_konversi)");
		($satuan=='')?
			$data['satuan']=$this->Admin_model->show_single_field('inv_material','nm_satuan',"where nm_barang='$nm_barang'"):
			$data['satuan']=$satuan;
			$isikonv=$this->Admin_model->show_single_field('inv_konversi','isi_konversi',"where nm_barang='$nm_barang' and sat_beli='$satuan'");
		($uom!=$satuan)?$harga=($harga*$isikonv):$harga=$harga;
		$data['expired']=tglfromSql($expired);
		$data['stock']=$this->Admin_model->show_single_field('inv_material_stok','stock',"where nm_barang='$nm_barang' and expired='$expired'");
		$data['harga_jual']=(($harga*$margin/100)+$harga);
		echo json_encode($data);
	}
	//simpan data header transaksi
	function set_header_trans(){
		$data=array();$datax=array();
		$cek_nourut	=rdb('inv_penjualan','NoUrut','NoUrut',"where NoUrut='".$_POST['no_trans']."'");
		$data['NoUrut']		=$_POST['no_trans'];
		$data['Tanggal']	=empty($_POST['tanggal'])?date('Ymd'):tgltoSql($_POST['tanggal']);
		$data['Nomor']		=empty($_POST['grp_nasabah'])?'Umum':$_POST['grp_nasabah'];;
		$data['ID_Anggota']	=empty($_POST['member'])?'0':$_POST['member'];
		$data['ID_Jenis']	=empty($_POST['cbayar'])?'1':$_POST['cbayar'];
		$data['Bulan']		=substr($_POST['tanggal'],3,2);
		$data['Tahun']		=substr($_POST['tanggal'],6,4);
		$data['Total']		=empty($_POST['total'])?'0':$_POST['total'];
		$data['cicilan']	=empty($_POST['cicilan'])?'0':$_POST['cicilan'];
		$data['ID_Lokasi']	=empty($_POST['lokasi'])?'1':$_POST['lokasi'];
		$data['Deskripsi']	=empty($_POST['deskripsi'])?'':$_POST['deskripsi'];
		$data["ID_Close"] =$this->session->userdata('gudang');
		if($cek_nourut==''){
		$this->Admin_model->replace_data('inv_penjualan',$data);
		}
		//update nomor transaksi	
		$datax['nomor']		=$_POST['no_trans'];
		$datax['jenis_transaksi']=$_POST['level'];
        $datax['created_by']=$this->session->userdata('username');
		$this->Admin_model->replace_data('nomor_transaksi',$datax);
	}
	function update_header_trans(){
		$data=array();
		$no_trans	=$_POST['no_trans'];
		$ID_Jenis	=empty($_POST['id_jenis'])?'1':$_POST['id_jenis'];
		$TotalHg	=empty($_POST['total'])?'0':$_POST['total'];
		$Tanggal	=$_POST['tanggal'];
		$id_anggota	=empty($_POST['id_anggota'])?'0':$_POST['id_anggota'];
		$cicilan	=empty($_POST['cicilan'])?'0'	:$_POST['cicilan'];
		$nogiro		=empty($_POST['nogiro'])?'0'	:strtoupper($_POST['nogiro']);
		$n_bank		=empty($_POST['n_bank'])?''		:strtoupper(addslashes($_POST['n_bank']));
		$tgl_giro	=empty($_POST['tgl_giro'])?'0000-00-00'	:tgltoSql($_POST['tgl_giro']);
		$lokasi		=empty($_POST['lokasi'])?'1':$_POST['lokasi'];
		$nasabah	=empty($_POST['deskripsi'])?'':$_POST['deskripsi'];
		$this->Admin_model->upd_data('inv_penjualan',"set ID_Jenis='".$ID_Jenis."', ID_Anggota='".$id_anggota."', Total='".$TotalHg."', Cicilan='".$cicilan."',ID_Post='".$nogiro."',Deskripsi='".$nasabah."' Bank='".$n_bank."', Tgl_Cicilan='".$tgl_giro."'",
									 "where NoUrut='".$no_trans."' and	Tanggal='".tgltoSql($Tanggal)."'");
		/**
		 added on 31-07-2010 for change trigger on database
		 */
		$this->Admin_model->upd_data('inv_penjualan_detail',"set ID_Jenis='".$ID_Jenis."'","where Keterangan='".$no_trans."' and	Tanggal='".tgltoSql($Tanggal)."'");
		$this->no_transaksi($no_trans);
		$this->tanggal($Tanggal);
		if($TotalHg!='0'){
			if(($ID_Jenis!='5' || $ID_Jenis!='5') && $id_anggota!=''){
				$this->process_to_jurnal($id_anggota,$TotalHg,'',$lokasi);
			}else if($this->id_jenis=='5'){
				$ket='Pembayaran Retur Barang tanggal $Tanggal';	
				$this->process_to_jurnal($id_anggota,$TotalHg,$ket,$lokasi);
			}
		}
	}

	function CekDetailTrans()
	{
		$data=array();$n=0;
		$where=" where id_barang=(select ID from inv_barang where nama_barang='".@$_POST['nm_barang']."')
				 and Keterangan='".@$_POST['no_trans']."' and Jumlah='".@$_POST['jml_trans']."' order by id desc limit 1";
		$data=$this->Admin_model->show_list('inv_penjualan_detail',$where);
		foreach($data as $r)
		{
			$n=$r->ID;
		}
		echo trim($n);
	}
	function set_detail_trans(){
		$data=array();
		$cekstatus=array();$countdata=0;
		//get ID from header trans
		$nm_barang=str_replace("/","\/",($_POST['nm_barang']));
		$countdata=rdb('inv_penjualan_detail','ID','ID',"where Bulan='".(@$_POST['no_id'])."' and/**/ Keterangan='".@$_POST['no_trans']."' and 
						ID_Barang='".rdb('inv_barang','ID','ID',"where Nama_Barang='".$nm_barang."'")."' and Jumlah='".@$_POST['jml_trans']."'");
		$data['Keterangan']	=empty($_POST['no_trans'])?"":$_POST['no_trans'];
		$data['ID_Jual']	=empty($_POST['no_trans'])?"":rdb('inv_penjualan','ID','ID',"where NoUrut='".$_POST['no_trans']."' and Tanggal='".tgltoSql($_POST['tanggal'])."'");
		$data['ID_Barang']	=empty($_POST['no_trans'])?"":rdb('inv_barang','ID','ID',"where Nama_Barang='".$nm_barang."'");
		$data['ID_Jenis']	=empty($_POST['no_trans'])?"":$_POST['cbayar'];
		$data['Jumlah']		=empty($_POST['no_trans'])?"":$_POST['jml_trans'];
		$data['Harga']		=empty($_POST['no_trans'])?"":$_POST['harga_jual'];
		$data['Tanggal']	=tgltoSql($_POST['tanggal']);
		$data['Bulan']		=empty($_POST['no_trans'])?"":$_POST['no_id'];
		$data['Batch']		=empty($_POST['expired'])?'0':$_POST['expired'];
		$data['ID_Satuan']	=empty($_POST['no_trans'])?"":rdb('inv_barang','ID_Satuan','ID_Satuan',"where Nama_Barang='".$_POST['nm_barang']."'");
		$data['ID']			=empty($_POST['ID'])?0:$_POST['ID'];
        $data['Tahun']      =$this->session->userdata('gudang');
		echo $this->Admin_model->replace_data('inv_penjualan_detail',$data);
        $ntran=empty($_POST['no_trans'])?"":$_POST['no_trans'];
		/**
		  kurangi stock masukan ke blok stock
          added on 29-10-2016
		*/
		$user=$this->session->userdata('userid');
		$gudang=$this->session->userdata('gudang');
        $jumlah=empty($_POST['no_trans'])?"0":$_POST['jml_trans'];
        $id_barang=empty($_POST['no_trans'])?"":rdb('inv_barang','ID','ID',"where Nama_Barang='".$nm_barang."'");
        $this->Admin_model->upd_data("inv_material_stok","set blokstok=(blokstok+".$jumlah."), stock=(stock-".$jumlah."),dariFlow='Penjualan-ntrans:".$ntran."-".@$_SERVER['REMOTE_ADDR']."',Created_By='".$user."-".$gudang."'","where ID_Barang=".$id_barang);
	}
	//simpan pembayaran	
	function simpan_bayar_old(){
		$data=array();$datax=array();
		$data['no_transaksi']	=$_POST['no_transaksi'];
		$data['total_belanja']	=empty($_POST['total_belanja'])?'0':$_POST['total_belanja'];
		$data['ppn']			=empty($_POST['ppn'])?'0':$_POST['ppn'];	
		$data['total_bayar']	=empty($_POST['total_bayar'])?'0':$_POST['total_bayar'];	
		$data['jml_dibayar']	=empty($_POST['dibayar'])?'0':$_POST['dibayar'];	
		$data['kembalian']		=empty($_POST['kembalian'])?'0':$_POST['kembalian'];
		$data['ID_Jenis']		=$_POST['cbayar'];
		$data['created_by']	    =$this->session->userdata('userid');
        $data['nota']           =empty($_POST['hutang'])?'0':abs($_POST['hutang']);
		
		//$this->Admin_model->hps_data('inv_material',"where nm_barang='".$_POST[
        //menambhakan data hutang pelanggan on 01-08-2015
		$nitip=empty($_POST['nitip'])?'0':$_POST['nitip'];
		$nbr=empty($_POST['nbarang'])?'0':$_POST['nbarang'];
        $datax['nm_pelanggan']=empty($_POST['nasabah'])?'':strtoupper($_POST['nasabah']);
        $notane=empty($_POST['nota'])?'0':($_POST['nota']);
		if((int)$nitip==0){
			$datax['hutang_pelanggan']=($notane <0)?abs($notane):0;
		}else{
			$datax['hutang_pelanggan']=((int)$nitip*-1);
		}
		$datax['telp_pelanggan']=$nbr.'-'.$_POST['no_transaksi'];
        $datax['created_by']	  =$this->session->userdata('userid');
        $datax['alm_pelanggan']   =$_POST['no_transaksi'];
        $this->Admin_model->replace_data('inv_pembayaran',$data);
        if(@$_POST['nasabah']!='-'){
            $this->Admin_model->replace_data('mst_pelanggan',$datax);
        }       
		echo $_POST['no_transaksi'];
	}
    function simpan_bayar(){
		$data=array();$datax=array();$datas=array();
		$data['no_transaksi']	=str_replace(' ','',$_POST['no_transaksi']);
		$data['total_belanja']	=empty($_POST['total_belanja'])?'0':$_POST['total_belanja'];
		$data['ppn']			=empty($_POST['ppn'])?'0':$_POST['ppn'];	
		$data['total_bayar']	=empty($_POST['total_bayar'])?'0':$_POST['total_bayar'];	
		$data['jml_dibayar']	=empty($_POST['dibayar'])?'0':$_POST['dibayar'];	
		$data['kembalian']		=empty($_POST['kembalian'])?'0':$_POST['kembalian'];
		$data['ID_Jenis']		=$_POST['cbayar'];
		$data['created_by']	    =$this->session->userdata('userid');
        $data['nota']           =empty($_POST['hutang'])?'0':abs($_POST['hutang']);
        $data['dinota']         =empty($_POST['notadibayar'])?"N":$_POST['notadibayar'];
        $data['nitipuang']      =empty($_POST['nitipuang'])?"N":$_POST['nitipuang'];
        $data['nitipbarang']    =empty($_POST['nitipbarang'])?"N":$_POST['nitipbarang'];
        //menambhakan data hutang pelanggan on 01-08-2015
		$nitip=empty($_POST['nitip'])?'0':$_POST['nitip'];
		$nbr=empty($_POST['nbarang'])?'0':$_POST['nbarang'];
        $datax['nm_pelanggan']=empty($_POST['nasabah'])?'':strtoupper($_POST['nasabah']);
        $notane=empty($_POST['nota'])?'0':($_POST['nota']);
        $hutang=empty($_POST['hutang'])?'0':abs($_POST['hutang']);
        $dinota=empty($_POST['notadibayar'])?"N":$_POST['notadibayar'];
        $notabaru   =empty($_POST['kembalian'])?'0':($_POST['kembalian']);
		if((int)$nitip==0){
            if($dinota=="N"){
                $datax['hutang_pelanggan']=($notabaru <0)?$hutang+abs($notabaru):$hutang;
            }else{
			    $datax['hutang_pelanggan']=($notabaru <0)?abs($notabaru):0;
            }
		}else{
			$datax['hutang_pelanggan']=((int)$nitip*-1);
		}
		$datax['telp_pelanggan']=$nbr.'-'.$_POST['no_transaksi'];
        $datax['created_by']	  =$this->session->userdata('userid');
        $datax['alm_pelanggan']   =$_POST['no_transaksi'];
        $this->Admin_model->replace_data('inv_pembayaran',$data);
        if(@$_POST['nasabah']!='-'){
            $this->Admin_model->replace_data('mst_pelanggan',$datax);
        }
        //menambahkan data transaksi hutang pelanggan on 28-11-2015
        $total_bayar	=empty($_POST['total_bayar'])?'0':$_POST['total_bayar'];	
		$jml_dibayar	=empty($_POST['dibayar'])?'0':$_POST['dibayar'];
        $datas['nm_pelanggan']=empty($_POST['nasabah'])?'-':strtoupper($_POST['nasabah']);
        $datas['nota_lama']=$hutang;
        $datas['nota_baru']=($dinota=="N")?(abs($notabaru)):($total_bayar - ($jml_dibayar + $hutang));
        $datas['nitipuang']=$nitip;
        $datas['nitipbarang']=$nbr;
        $datas['notrans']=$_POST['no_transaksi'];
        $datas['userid']=$this->session->userdata('userid');
        $datas['lokasi']=$this->session->userdata('gudang');
        if($notabaru<0){
            $this->Admin_model->replace_data("mst_pelanggan_hutang",$datas);
        }
		echo $_POST['no_transaksi'];
	}
	function update_stock_return(){
		$ntran	=$_POST['no_trans'];
		$tgl	=tglToSql($_POST['tanggal']);
		$lokasi =empty($_POST['lokasi'])?'1':$_POST['lokasi'];
		$this->return_stock($ntran,$tgl,$lokasi);
		echo '';
	}
	/**
	//update data stok material setelah di lakukan transaksi
	//transaksi penjualan
	*/
	function update_material_stock2($ntran='',$tgl=''){
		$data=array();$first_stock=0;$end_stock=0;$datax=array();$hgb=0;$bath=array();
		$ntran	=$_POST['no_trans'];
		$tgl	=tglToSql($_POST['tanggal']);
		$data=$this->Admin_model->show_list('inv_penjualan_detail',"where ID_Jual='".rdb('inv_penjualan','ID','ID',"where NoUrut='".$ntran."' and Tanggal='". $tgl."'")."'");
		$id_br=rdb('inv_penjualan_detail','ID_Barang','ID_Barang',"where ID_Jual='".rdb('inv_penjualan','ID','ID',"where NoUrut='".$ntran."' and Tanggal='". $tgl."'")."'");
		foreach($data as $r){
		$bt='';
		$bath=$this->inv_model->get_detail_stocked($id_br,'desc');
			foreach($bath as $w){
				$bt=$w->batch;
			}
			$jumlah=empty($_POST['jumlah'])?$r->Jumlah:$_POST['jumlah'];
			$hgb=rdb('inv_material_stok','harga_beli','harga_beli',"where id_barang='".$r->ID_Barang."' and batch='".$bt."'");
			$first_stock=rdb('inv_material_stok','stock','stock',"where id_barang='".$r->ID_Barang."' and batch='".$bt."'");
			$end_stock=($first_stock-abs($jumlah));
			$end_stock=($end_stock<0)? 0:$end_stock;
			$datax['id_barang']	=$r->ID_Barang;
			$datax['nm_barang']	=addslashes(rdb('inv_barang','Nama_Barang','Nama_Barang',"where ID='".$r->ID_Barang."'"));
			$datax['batch']		=$bt;
			$datax['stock']		=$end_stock;
			$datax['harga_beli']=empty($hgb)?'0':$hgb;
			$datax['nm_satuan'] =rdb('inv_barang','ID_Satuan','ID_Satuan',"where ID='".$r->ID_Barang."'");
			//$this->Admin_model->replace_data('inv_material_stok',$datax);
            //if($first_stock>0){
                $this->Admin_model->upd_data('inv_material_stok',"set blokstock=(blokstock-".abs($r->Jumlah).")"," where ID_Barang='".$r->ID_Barang."'");
            //}
            $this->Admin_model->upd_data('inv_material_stok',"set streset=(streset-".abs($r->Jumlah)."),dariFlow='update_material_stock2'"," where ID_Barang='".$r->ID_Barang."'");
		}
		 echo ($bt!='')?($first_stock-$r->Jumlah):'0';
	}
	
	//update data stok material setelah di lakukan transaksi
	//transaksi penjualan
	function update_material_stock($ntran='',$tgl=''){
		$data=array();$first_stock=0;$end_stock=0;$datax=array();$hgb=0;$bt='';
		$ntran	=$_POST['no_trans'];
		$tgl	=tglToSql($_POST['tanggal']);
		$lokasi	=empty($_POST['lokasi'])?'1':$_POST['lokasi'];
		$data=$this->Admin_model->show_list('inv_penjualan_detail',"where Keterangan='".$ntran."'");
		$id_br=rdb('inv_penjualan_detail','ID_Barang','ID_Barang',"where Keterangan='".$ntran."'");
		$kat=rdb('inv_barang','id_kategori','id_kategori',"where ID='".$id_br."'");
		$nitipbarang="N";
        $user=$this->session->userdata('userid');
        $gudang=$this->session->userdata("gudang");
        $nitipbarang=rdb('inv_pembayaran','nitipbarang','nitipbarang',"where no_transaksi='".$ntran."'");
        if($nitipbarang=='Y'){
          echo '0';  
        }else{
			foreach($data as $r){
			$bt=''; $bath=array();
			$bath=$this->inv_model->get_detail_stocked($id_br,'desc');
				foreach($bath as $w){
					$bt=$w->batch;
				}
				$jumlah=$r->Jumlah;
				$hgb=rdb('inv_material_stok','harga_beli','harga_beli',"where id_barang='".$r->ID_Barang."' /*and batch='".$bt."'*/ and id_lokasi='".$lokasi."'");
				$first_stock=rdb('inv_material_stok','stock','stock',"where id_barang='".$r->ID_Barang."' /*and batch='".$bt."'*/ and id_lokasi='".$lokasi."'");
				$end_stock=($first_stock-abs($jumlah));
				//$end_stock=($end_stock<0)? 0:$end_stock;
				$datax['id_lokasi'] =$lokasi;
				$datax['id_barang']	=$r->ID_Barang;
				$datax['nm_barang']	=rdb('inv_barang','Nama_Barang','Nama_Barang',"where ID='".$r->ID_Barang."'");
				//$datax['batch']		=$bt;
				$datax['stock']		=$end_stock;
				$datax['harga_beli']=empty($hgb)?'0':$hgb;
				$datax['nm_satuan'] =rdb('inv_barang','ID_Satuan','ID_Satuan',"where ID='".$r->ID_Barang."'");
				//$this->Admin_model->replace_data('inv_material_stok',$datax);
				/** updated on 10-10-2015*/
                //if($first_stock>0){
                //$this->Admin_model->upd_data('inv_material_stok',"set blokstok=0,Created_By='".$user."-".$gudang."'"," where ID_Barang='".$r->ID_Barang."'");
                //}
                $this->Admin_model->upd_data('inv_material_stok',"set blokstok=0,Created_By='".$user."-".$gudang."',streset=(streset-".abs($jumlah)."),dariFlow='update_material_stock:".$ntran."-".@$_SERVER['REMOTE_ADDR']."',Created_By='".$user."-".$gudang."'"," where ID_Barang='".$r->ID_Barang."'");
			}
        }
		 echo $end_stock;
		//}
	}	
	function return_stock($ntran,$tgl,$lokasi=''){
		$data=array();$first_stock=0;$end_stock=0;$datax=array();$hgb=0;
		$id_jual=rdb('inv_penjualan','ID','ID',"where NoUrut='".$ntran."' and Tanggal='". $tgl."'");
		$data=$this->Admin_model->show_list('inv_penjualan_detail',"where ID_Jual='".$id_jual."'");
		//print_r($data);
		foreach($data as $r){
			$hgb=rdb('inv_material_stok','harga_beli','harga_beli',"where id_barang='".$r->ID_Barang."' and batch='".$r->Batch."'");
			$first_stock=rdb('inv_material_stok','stock','sum(stock) as stock',"where id_barang='".$r->ID_Barang."' group by id_barang");
			$end_stock=($first_stock+(abs($r->Jumlah)));
			$datax['id_lokasi'] =$lokasi;
			$datax['id_barang']	=$r->ID_Barang;
			$datax['nm_barang']	=addslashes(rdb('inv_barang','Nama_Barang','Nama_Barang',"where ID='".$r->ID_Barang."'"));
			$datax['batch']		=$r->Batch;
			$datax['stock']		=$end_stock;
			$datax['harga_beli']=empty($hgb)?'0':$hgb;
			$datax['nm_satuan'] =rdb('inv_barang','ID_Satuan','ID_Satuan',"where ID='".$r->ID_Barang."'");
            $user=$this->session->userdata('userid');
			//$this->Admin_model->replace_data('inv_material_stok',$datax);
			/** Updated on 10-10-2015 
            if($first_stock>0)
            {*/
            //    $this->Admin_model->upd_data('inv_material_stok',"set stock=(stock+".abs($r->Jumlah)."),blokstok=(blokstok-".abs($r->Jumlah)."),dariFlow='Batal Transaksi',Created_By='".$user."'"," where ID_Barang='".$r->ID_Barang."'");
           /* }else{
                $this->Admin_model->upd_data('inv_material_stok',"set stock=(".abs($r->Jumlah)."),blokstok=(blokstok-".abs($r->Jumlah)."),Created_By='".$user."'"," where ID_Barang='".$r->ID_Barang."'");
            }
            $this->Admin_model->upd_data('inv_material_stok',"set streset=(streset+".abs($r->Jumlah)."),dariFlow='Retrun_stock',Created_By='".$user."'"," where ID_Barang='".$r->ID_Barang."'");
		*/}
		
	}
	function transaski_kas(){
		//pembayaran return material diambil dari uang kas toko
		$data=array();
		$data['id_trans']	=$_POST['no_trans'];
		$data['id_kas']		='KAS TOKO';	
	}
	function batal_transaksi(){
		echo $this->delete_trans($_POST['no_trans']);
		//echo $this->Admin_model->hps_data('inv_penjualan',"where NoUrut='".$_POST['no_trans']."' and Tanggal='".tgltoSql($_POST['tanggal'])."'");	
        //echo $this->Admin_model->hps_data('inv_penjualan_detail',"where Keterangan='".$_POST['no_trans']."' and Tanggal='".tgltoSql($_POST['tanggal'])."'");	
	}
	function get_total_trans(){
		$no_trans=@$_POST['no_trans'];
		$table=$_POST['table'];
		$where="where no_transaksi='$no_trans' and jenis_transaksi='GI'";
		
		$row_count=$this->inv_model->total_record($table,$where);
		echo $row_count;
	}
	//adjust stock barang di table inv_material
	function update_adjust(){
		$nm_barang=$_POST['nm_barang'];
		$stock=$_POST['stock'];
		$this->Admin_model->upd_data('inv_material','stock',"where nm_barang='".$nm_barang."'");
	}

	function _filename(){
		//configurasi data untuk generate form & list
		$this->zetro_buildlist->config_file('asset/bin/zetro_beli.frm');
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
	/**
	//proses print slip penjualan
	//create data text for print to dotmatrix
	*/
	function no_transaksi($no_trans=''){
		$this->no_trans=$no_trans;
	}
	function tanggal($tgl=''){
		$this->tgl=$tgl;
	}
	function redir(){
		redirect('penjualan/index');
	}
	//print slip besar format 1/2 kertas A4
	function print_slip(){
		$this->zetro_slip->path=$this->session->userdata('userid');
		$this->zetro_slip->modele('wb');
		$this->zetro_slip->newline();
		$this->no_transaksi($_POST['no_transaksi']);
		$this->tanggal(tgltoSql($_POST['tanggal']));
		$this->zetro_slip->content($this->struk_header());
		$this->zetro_slip->create_file();
		$this->re_print();
		//$this->index();
	}
	function re_print_slip(){
		$this->zetro_slip->path=$this->session->userdata('userid');
		$this->zetro_slip->modele('wb');
		$this->zetro_slip->newline();
		$notrans=$_POST['no_transaksi'];
		$tanggal=tglToSql($_POST['tanggal']);
		$this->no_transaksi($notrans);
		$this->tanggal($tanggal);
		$this->zetro_slip->content($this->struk_header());
		$this->zetro_slip->create_file();
		$this->re_print();
	}
	function struk_header(){
		$data=array();
		$slip="S L I P  P E N J U A L A N";
		$no_trans=$this->no_trans;
		$nfile	='asset/bin/zetro_config.dll';
		$coy	=$this->zetro_manager->rContent('InfoCo','Name',$nfile);	
		$address=$this->zetro_manager->rContent('InfoCo','Address',$nfile);
		$city	=$this->zetro_manager->rContent('InfoCo','Kota',$nfile);
		$phone	=$this->zetro_manager->rContent('InfoCo','Telp',$nfile);
		$fax	=$this->zetro_manager->rContent('InfoCo','Fax',$nfile);
		$tgl	=rdb('inv_penjualan','Tanggal','Tanggal',"where NoUrut='".$no_trans."' and Tanggal='".$this->tgl."'");
		$Jenis	=rdb('inv_penjualan','ID_Jenis','ID_Jenis',"where NoUrut='".$no_trans."' and Tanggal='".$this->tgl."'");
		$nJenis	=rdb('inv_penjualan_jenis','Jenis_Jual','Jenis_Jual',"where ID='".$Jenis."'");
		$no_faktur=rdb('inv_penjualan','Nomor','Nomor',"where NoUrut='".$no_trans."' and Tanggal='".$this->tgl."'");
		$isine	=array(
					sepasi(((80-(strlen($slip)+6))/2)).'** '.$slip.' **'.newline(),
					sepasi(80).newline(),
					$coy.sepasi((79-strlen($coy)-strlen('Tanggal :'.tglfromSql($tgl)))).'Tanggal :'.tglfromSql($tgl).newline(),
					$address.sepasi((79-strlen($address)-strlen('No. Faktur :'.$no_faktur))).'No. Faktur :'.$no_faktur.newline(),
					$phone.sepasi((79-strlen($phone)-strlen('Pembelian :'.$nJenis)-(10-strlen($nJenis)))).'Pembelian :'.$nJenis.newline(),
					str_repeat('-',79).newline(),
					'| No.|'.sepasi(((32-strlen('Nama Barang'))/2)).'Nama Barang'.sepasi(((32-strlen('Nama Barang'))/2)).
					'|'.sepasi(((10-strlen('Banyaknya'))/2)).'Banyaknya'.sepasi((((10-strlen('Banyaknya'))/2)-1)).'|'.
					sepasi(((14-strlen('Harga Satuan'))/2)).'Harga Satuan'.sepasi((((14-strlen('Harga Satuan'))/2)-1)).'|'.
					sepasi(((18-strlen('Total Harga'))/2)).'Total Harga'.sepasi((((17-strlen('Total Harga'))/2)-1)).'|'.newline(),
					str_repeat('-',79).newline(),
					$this->isi_slip(),
					($Jenis==1)?$this->struk_data_footer():$this->struk_data_footer_kredit()
					);
		return $isine;			
	}
	function isi_slip(){
		$data=array();$content="";$n=0;
		$this->inv_model->tabel('inv_penjualan_rekap');
		$data=$this->kasir_model->get_trans_jual($this->no_trans,$this->tgl);
		 foreach($data as $row){
			 $n++;
			 $satuan=rdb('inv_barang_satuan','Satuan','Satuan',"where ID='".
			 		 rdb('inv_barang','ID_Satuan','ID_Satuan',"where ID='".$row->ID_Barang."'")."'");
			$nama_barang=rdb('inv_barang','Nama_Barang','Nama_Barang',"where ID='".$row->ID_Barang."'");
			$content .=sepasi(((6-strlen($n))/2)).$n.sepasi(3).substr($nama_barang,0,31).sepasi((32-strlen($nama_barang))).
					 sepasi((8-strlen($row->Jumlah)-strlen($satuan))).round($row->Jumlah,0).sepasi(1).$satuan.
					 sepasi((13-strlen(number_format($row->Harga)))).number_format($row->Harga).
					 sepasi((16-strlen(number_format(($row->Jumlah *$row->Harga),2)))).number_format(($row->Jumlah *$row->Harga),2).newline();
		 }
		 if($n<8){
			 $content .=newline((8-$n));
		 }
		 return $content;
		 
	}
	function struk_data_footer(){
		$data=array();$bawah="";
		$nama=rdb('mst_anggota','Nama','Nama',"where ID='".rdb('inv_penjualan','ID_Anggota','ID_Anggota',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'")."'");
		$urut=rdb('mst_anggota','NoUrut','NoUrut',"where ID='".rdb('inv_penjualan','ID_Anggota','ID_Anggota',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'")."'");
		$alm =rdb('mst_departemen','Departemen','Departemen',"where ID='".
				rdb('mst_anggota','Nama','Nama',"where ID='".
				rdb('inv_penjualan','ID_Anggota','ID_Anggota',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'")."'")."'");
		$this->inv_model->tabel('inv_pembayaran');
		$data=$this->inv_model->show_list_1where('no_transaksi',$this->no_trans);
			foreach($data->result() as $row){
				$bawah=str_repeat('-',79).newline().
				sepasi((61-strlen('Sub Total'))).'Sub Total :'.sepasi((14-strlen(number_format($row->total_belanja,2)))).number_format($row->total_belanja,2).newline(2).
				sepasi((61-strlen('Diskon'))).'Diskon:'.sepasi((14-strlen(number_format($row->ppn,2)))).number_format($row->ppn,2).newline().
				sepasi(61).str_repeat('-',18).'+'.newline().
				sepasi((61-strlen('Total'))).'Total :'.sepasi((14-strlen(number_format($row->total_bayar,2)))).number_format($row->total_bayar,2).newline(2).
				sepasi((61-strlen('Cash'))).'Cash :'.sepasi((14-strlen(number_format($row->jml_dibayar,2)))).number_format($row->jml_dibayar,2).newline(2).
				sepasi(61).str_repeat('-',17).'-'.newline().
				sepasi((61-strlen('Kembali'))).'Kembali :'.sepasi((14-strlen(number_format($row->kembalian,2)))).number_format($row->kembalian,2).newline(2).
				str_repeat('-',79).newline().'Terima Kasih.'.newline();
			}
		return $bawah;
	}
    
	function struk_data_footer_kredit(){
		$data=array();$bawah="";
		$nama=rdb('mst_anggota','Nama','Nama',"where ID='".rdb('inv_penjualan','ID_Anggota','ID_Anggota',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'")."'");
		$urut=rdb('mst_anggota','No_Agt','No_Agt',"where ID='".rdb('inv_penjualan','ID_Anggota','ID_Anggota',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'")."'");
		$alm =rdb('mst_departemen','Departemen','Departemen',"where ID='".
				rdb('mst_anggota','ID_Dept','ID_Dept',"where ID='".
				rdb('inv_penjualan','ID_Anggota','ID_Anggota',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'")."'")."'");
		$ncby=rdb('inv_penjualan_jenis','Jenis_Jual','Jenis_Jual',"where ID='".rdb('inv_penjualan','ID_Jenis','ID_Jenis',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'")."'");
		$bank=rdb('inv_penjualan','Deskripsi','Deskripsi',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'");
		$rek =rdb('inv_penjualan','ID_Post','ID_Post',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'");
		$tgir=rdb('inv_penjualan','Tgl_Cicilan','Tgl_Cicilan',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'");
		$this->inv_model->tabel('inv_pembayaran');
		$data=$this->inv_model->show_list_1where('no_transaksi',$this->no_trans);
			foreach($data->result() as $row){
				$bawah=str_repeat('-',79).newline().
				sepasi((61-strlen('Sub Total'))).'Sub Total :'.sepasi((14-strlen(number_format($row->total_belanja,2)))).number_format($row->total_belanja,2).newline(2).
				sepasi((61-strlen('Diskon'))).'Diskon :'.sepasi((14-strlen(number_format($row->ppn,2)))).number_format($row->ppn,2).newline().
				sepasi(61).str_repeat('-',18).'+'.newline().
				sepasi((61-strlen('Total'))).'Total :'.sepasi((14-strlen(number_format($row->total_bayar,2)))).number_format($row->total_bayar,2).newline(2).
				sepasi((61-strlen('Uang Muka'))).'Uang Muka :'.sepasi((14-strlen(number_format($row->jml_dibayar,2)))).number_format($row->jml_dibayar,2).newline(2).
				sepasi(61).str_repeat('-',17).'-'.newline().
				sepasi((61-strlen('Sisa'))).'Sisa :'.sepasi((14-strlen(number_format($row->kembalian,2)))).number_format($row->kembalian,2).newline(2).
				str_repeat('-',79).newline().
				'No. Anggota'.sepasi((14-strlen('No.Anggota :'))).':'.$urut.newline().
				'Nama Anggota'.sepasi((14-strlen('Nama Anggota :'))).' :'.$nama.newline().
				'Departemen'.sepasi((14-strlen('Departemen :'))).' :'.$alm.newline(1);
			}
		return $bawah;
	}
	//print struk kecil
	function print_slip_kecil(){
		$this->zetro_slip->modele('wb');
		$this->zetro_slip->path=$this->session->userdata('userid');
		//$this->zetro_slip->newline();
		$this->no_transaksi(($_POST['no_transaksi']));
		$this->tanggal(tgltoSql($_POST['tanggal']));
		$this->zetro_slip->content($this->struk_header_kecil());
		$this->zetro_slip->create_file();
		//$this->re_print();
		//$this->index();
	}
	function struk_header_kecil(){
		$data=array();
		$no_trans=$this->no_trans;
		$nfile	='asset/bin/zetro_config.dll';
		$coy	=$this->zetro_manager->rContent('InfoCo','Name',$nfile);	
		$address=$this->zetro_manager->rContent('InfoCo','Address',$nfile);
		$city	=$this->zetro_manager->rContent('InfoCo','Kota',$nfile);
		$phone	=$this->zetro_manager->rContent('InfoCo','Telp',$nfile);
		$fax	=$this->zetro_manager->rContent('InfoCo','Fax',$nfile);
		$tgl	=rdb('inv_penjualan','Tanggal','Tanggal',"where NoUrut='".$no_trans."' and Tanggal='".$this->tgl."'");
		$Jenis	=rdb('inv_penjualan','ID_Jenis','ID_Jenis',"where NoUrut='".$no_trans."' and Tanggal='".$this->tgl."'");
		$nJenis	=rdb('inv_penjualan_jenis','Jenis_Jual','Jenis_Jual',"where ID='".$Jenis."'");
		$no_faktur=rdb('inv_penjualan','Nomor','Nomor',"where NoUrut='".$no_trans."' and Tanggal='".$this->tgl."'");
        $nasabah=rdb('inv_penjualan','Deskripsi','Deskripsi',"where NoUrut='".$no_trans."' and Tanggal='".$this->tgl."'");
		//$data	=$this->Admin_model->show_list('detail_transaksi',"where no_transaksi='$no_trans'");
		$JenisSLip=($Jenis=='5')?$nJenis." SLIP ":'';
		$isine	=array(
					strtoupper($JenisSLip).newline(),
					sepasi(((40-(strlen($coy)+6))/2)).'** '.$coy.' **'.newline(),
					sepasi(((40-((strlen($address))))/2)).$address.newline(),
					//sepasi(((40-((strlen($city))))/2)).$city.newline(),
					sepasi(((40-((strlen($phone))))/2)).$phone.newline(),
                    str_repeat('-',40).newline(),
                    'Pel: '.strtoupper(substr($nasabah,0,36)).newline(),
					str_repeat('-',40).newline(),
					$this->isi_slip_kecil(),
                    $this->struk_data_footer_kecil()
					//($Jenis==1)?$this->struk_data_footer_kecil():$this->struk_data_footer_kecil_kredit()
					);
		return $isine;			
	}
	function isi_slip_kecil(){
		$data=array();$content="";$n=0;
		$this->inv_model->tabel('inv_penjualan_rekap');
		$data=$this->kasir_model->get_trans_jual($this->no_trans,$this->tgl);
		 foreach($data as $row){
			 $n++;
			 $satuan=rdb('inv_barang_satuan','Satuan','Satuan',"where ID='".
			 		 rdb('inv_barang','ID_Satuan','ID_Satuan',"where ID='".$row->ID_Barang."'")."'");
			$nama_barang=rdb('inv_barang','Nama_Barang','Nama_Barang',"where ID='".$row->ID_Barang."'");
			$content .=$nama_barang.newline().
					 sepasi((5-strlen($row->Jumlah))).($row->Jumlah).
					 sepasi((8-strlen($satuan))).$satuan.
					 sepasi((12-strlen(number_format($row->Harga,0)))).number_format($row->Harga,0).
					 sepasi((15-strlen(number_format(($row->Jumlah *$row->Harga),0)))).number_format(($row->Jumlah *$row->Harga),0).newline();
		 }
		 if($n<2){
			 $content .=newline((2-$n));
		 }
		 return $content;
		 
	}
	function struk_data_footer_kecil(){
		$data=array();$bawah="";$x=0;
		$nama=rdb('mst_anggota','Nama','Nama',"where ID='".rdb('inv_penjualan','ID_Anggota','ID_Anggota',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'")."'");
		$urut=rdb('mst_anggota','NoUrut','NoUrut',"where ID='".rdb('inv_penjualan','ID_Anggota','ID_Anggota',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'")."'");
		$alm =rdb('mst_departemen','Departemen','Departemen',"where ID='".
				rdb('mst_anggota','Nama','Nama',"where ID='".
				rdb('inv_penjualan','ID_Anggota','ID_Anggota',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'")."'")."'");
		$nasabah=rdb('inv_penjualan','Deskripsi','Deskripsi',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'");
		$this->inv_model->tabel('inv_pembayaran');
		$data=$this->inv_model->show_list_1where('no_transaksi',$this->no_trans);
			foreach($data->result() as $row){
				$bawah=str_repeat('-',40).newline().
				'Sub Total'.sepasi((31-strlen(number_format($row->total_belanja,0)))).number_format($row->total_belanja,0).newline().
				'Diskon '.sepasi((33-strlen(number_format($row->ppn,0)))).number_format($row->ppn,0).newline().
                'Nota '.sepasi((35-strlen(number_format($row->nota,0)))).number_format($row->nota,0).newline().
				str_repeat('-',40).newline().
				'Total'.sepasi((35-strlen(number_format($row->total_bayar,0)))).number_format($row->total_bayar,0).newline(1).
				'Cash'.sepasi((36-strlen(number_format($row->jml_dibayar,0)))).number_format($row->jml_dibayar,0).newline().
				'Kembali'.sepasi((33-strlen(number_format($row->kembalian,0)))).number_format($row->kembalian,0).newline(1).
				str_repeat('-',40).newline().
				'Kasir :'.$row->created_by.' '.$row->doc_date.newline().
				'Doc No:'.$row->no_transaksi.sepasi(10);
				//$bawah.=($nama=='')?'Pelanggan:'.$nasabah.newline(2):'Pelanggan :'.$nama.'-'.$alm.newline(2);
				$bawah.='Terima Kasih '.newline(7);
				
				/*str_repeat('-',40).newline(2)."Info Promo :".newline();
				$data=$this->Admin_model->show_list('mst_promo',"where dari_tgl <='".$this->tgl."' and sampai_tgl >='".$this->tgl."' order by ID");
				foreach($data as $r){
					$bawah .=chunk_split($r->Keterangan,40,newline()).newline();
				}*/
			}
		return $bawah;
	}
	function struk_data_footer_kecil_kredit(){
		$data=array();$bawah="";$x=0;
		$nama=rdb('mst_anggota','Nama','Nama',"where ID='".rdb('inv_penjualan','ID_Anggota','ID_Anggota',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'")."'");
		$urut=rdb('mst_anggota','No_Agt','No_Agt',"where ID='".rdb('inv_penjualan','ID_Anggota','ID_Anggota',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'")."'");
		$alm =rdb('mst_departemen','Title','Title',"where ID='".
				rdb('mst_anggota','ID_Dept','ID_Dept',"where ID='".
				rdb('inv_penjualan','ID_Anggota','ID_Anggota',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'")."'")."'");
		$ncby=rdb('inv_penjualan_jenis','Jenis_Jual','Jenis_Jual',"where ID='".rdb('inv_penjualan','ID_Jenis','ID_Jenis',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'")."'");
		$bank=rdb('inv_penjualan','Deskripsi','Deskripsi',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'");
		$rek =rdb('inv_penjualan','ID_Post','ID_Post',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'");
		$tgir=rdb('inv_penjualan','Tgl_Cicilan','Tgl_Cicilan',"where NoUrut='".$this->no_trans."' and Tanggal='".$this->tgl."'");
		$this->inv_model->tabel('inv_pembayaran');
		$data=$this->inv_model->show_list_1where('no_transaksi',$this->no_trans);
			foreach($data->result() as $row){
				$bawah=str_repeat('-',40).newline().
				'Sub Total'.sepasi((31-strlen(number_format($row->total_belanja,0)))).number_format($row->total_belanja,0).newline(2).
				'Diskon '.sepasi((33-strlen(number_format($row->ppn,0)))).number_format($row->ppn,2).newline().
				str_repeat('-',40).newline().
				'Total'.sepasi((35-strlen(number_format($row->total_bayar,0)))).number_format($row->total_bayar,0).newline(2).
				'Uang Muka'.sepasi((31-strlen(number_format($row->jml_dibayar,0)))).number_format($row->jml_dibayar,0).newline().
				'Saldo'.sepasi((35-strlen(number_format($row->kembalian,2)))).number_format($row->kembalian,2).newline(2).
				str_repeat('-',40).newline().
				'Kasir :'.$row->created_by.' '.$row->doc_date.newline().
				'Doc No:'.$row->no_transaksi.newline(2).
				'Terima Kasih '.newline().
				str_repeat('-',40).newline().
				'No. Pelanggan'.sepasi((14-strlen('No.Pelanggan :'))).':'.$urut.newline().
				'Nama Pelanggan'.sepasi((14-strlen('Nama Pelanggan :'))).' :'.$nama.newline(3);
				/*'Departemen'.sepasi((14-strlen('Departemen :'))).' :'.$alm.newline(1).
				str_repeat('-',40).newline(2)."Info Promo :".newline();
				$data=$this->Admin_model->show_list('mst_promo',"where dari_tgl <='".$this->tgl."' and sampai_tgl >='".$this->tgl."' order by ID");
				foreach($data as $r){
					$bawah .=chunk_split($r->Keterangan,'40',newline()).newline();
				}*/
			}
		return $bawah;
	}
	function promo(){
		$data=array();$text='Info : ';
		$data=$this->Admin_model->show_list('mst_promo',"where dari_tgl <='".$this->tgl."' and sampai_tgl >='".$this->tgl."' order by ID");
		foreach($data as $r){
			$text .=$r->Keterangan.newline();
		}
		$cek=chunk_split($text,'36','<br>');
		return $cek;
	}
	function re_print(){
		
		//copyFile("\\192.168.1.100\\".$this->session->userdata('userid')."_slip_mj.txt","c:\app");
	}
	//simpan komposisi resep
	
	function stock_resep(){
		$data=array();
		$data['nm_barang']=strtoupper($_POST['nm_barang']);
		$data['batch']=str_replace('-','',tgltoSql($_POST['batch']));
		$data['expired']=tgltoSql($_POST['expired']);
		$data['stock']=$_POST['stock'];
		$data['blokstok']=$_POST['blokstok'];
		$data['nm_satuan']=$_POST['nm_satuan'];
		$data['harga_beli']=$_POST['harga_beli'];
		$data['created_by']	=$this->session->userdata('userid');
		//print_r($data);
		$this->Admin_model->replace_data('inv_material_stok',$data);
	}
	//return penjualan
	
	function return_jual(){
		$data=array();
		$data['datas']=$this->get_barang();
		$data['nasabah']=$this->get_pelanggan();
		$this->zetro_auth->menu_id(array('return_jual'));
		$this->list_data(array_merge($this->zetro_auth->auth(),$data));
		$this->hapus_resep_kosong();
		$this->View('penjualan/material_jual_return');
	}
	function get_transaksi(){
		$data=array();
		$no_transaksi=$_POST['no_transaksi'];
		$this->inv_model->tabel('detail_transaksi');
		$data['tgl_transaksi']=tglfromSql($this->Admin_model->show_single_field('detail_transaksi','tgl_transaksi',"where no_transaksi='$no_transaksi'"));
		$data['faktur_transaksi']=$this->Admin_model->show_single_field('detail_transaksi','faktur_transaksi',"where no_transaksi='$no_transaksi'");
		$data['nm_nasabah']=$this->Admin_model->show_single_field('detail_transaksi','nm_produsen',"where no_transaksi='$no_transaksi'");
			echo json_encode($data);
	}
	function get_detail_transaksi(){
		$datax=array();
		$nm_barang=$_POST['nm_barang'];
		$no_transaksi=$_POST['no_transaksi'];
		$this->inv_model->tabel('detail_transaksi');
		$datax=$this->inv_model->detail_transaksi($no_transaksi,$nm_barang);
		echo json_encode($datax[0]);
	}
	function process_to_jurnal($id_anggota,$total,$ket=''){
	/**
	 membuat data untuk diposting dalam jurnal
	  simpan penjualan barang secara kredit
	  inputnya adalah ID anggota yng melakukan pembelian secara kredit
	  dapatkan ID_Perkiraan dari table perkiraan based on ID_Anggota
	  extract data perkiraan menjadi klass, sub klass dan jenis simpanan dalam hal ini
	  jenis simpanan adalah barang [id:4]
	  data yang dihasilkan akan ditampung di table transaksi_temp
	*/ 
		$data=array();$akun='';
		//get ID_perkiraan
		$akun=rdb('perkiraan','ID','ID',"where ID_Agt='$id_anggota' and ID_Simpanan='4'");
		if($akun==''){
			//update database perkiraan
			$this->_update_perkiraan($id_anggota,'4');	
		}
		$data['ID_Perkiraan']	=rdb('perkiraan','ID','ID',"where ID_Agt='$id_anggota' and ID_Simpanan='4'");
		$data['ID_Unit']		=rdb('jenis_simpanan','ID_Unit','ID_Unit',"where ID='4'");
		$data['ID_Klas']		=rdb('jenis_simpanan','ID_Klasifikasi','ID_Klasifikasi',"where ID='4'");
		$data['ID_SubKlas']		=rdb('jenis_simpanan','ID_SubKlas','ID_SubKlas',"where ID='4'");
		$data['ID_Dept']		=($id_anggota=='0')?'0':rdb('mst_anggota','ID_Dept','ID_Dept',"where ID='".$id_anggota."'");
		if($ket==''){
			$data['Debet']		=$total;//rdb('inv_penjualan','Total','Total',"where ID_Anggota='".$id_anggota."' and NoUrut='".$this->no_trans."'");
		}else{
			$data['Kredit']		=$total;
		}
		$data['Keterangan']		=($ket=='')?'Penjualan barang toko no. Faktur: '.rdb('inv_penjualan','Nomor','Nomor',"where NoUrut='".$this->no_trans."'"):$ket;
		$data['tanggal']		=tgltoSql($this->tgl);
		$data['ID_Bulan']		=substr($this->tgl,3,2);
		$data['Tahun']			=substr($this->tgl,6,4);
		$data['created_by']		=$this->session->userdata('userid');
		//print_r($data);
		 $this->Admin_model->replace_data('transaksi_temp',$data);
	}
	
	function _update_perkiraan($ID_Agt,$ID_Simpanan){
		$datax=array();
		$datax['ID_Unit']		=rdb('jenis_simpanan','ID_Unit','ID_Unit',"where ID='4'");
		$datax['ID_Klas']		=rdb('jenis_simpanan','ID_Klasifikasi','ID_Klasifikasi',"where ID='4'");
		$datax['ID_SubKlas']	=rdb('jenis_simpanan','ID_SubKlas','ID_SubKlas',"where ID='4'");
		$datax['ID_Dept']		=rdb('mst_anggota','ID_Dept','ID_Dept',"where ID='".$ID_Agt."'");
		$datax['ID_Simpanan']	=$ID_Simpanan;
		$datax['ID_Agt']		=$ID_Agt;
		$datax['ID_Calc']		=rdb('jenis_simpanan','ID_Calc','ID_Calc',"where ID='4'");
		$datax['ID_Laporan']	=rdb('jenis_simpanan','ID_Laporan','ID_Laporan',"where ID='4'");
		$datax['ID_LapDetail']	=rdb('jenis_simpanan','ID_LapDetail','ID_LapDetail',"where ID='4'");
		echo $this->Admin_model->replace_data('perkiraan',$datax);
		//print_r($datax);
	}
	function get_bank(){
		$data=array();
		$str	=$_GET['str'];
		$limit	=$_GET['limit'];
		$fld	=rdb('mst_anggota','Nama','Nama',"where ID='".$_GET['fld']."'");
		$data=$this->inv_model->get_bank($str);
		echo json_encode($data);	
	}
	
	function get_no_transaction(){
		$data=array();
		$tgl=empty($_POST['tanggal'])?date('Ymd'):tglToSql($_POST['tanggal']);
		$data=$this->Admin_model->show_list('inv_penjualan',"where Tanggal ='".$tgl."' order by NoUrut");
		echo "<option value=''>--pilih no faktur--</option>";
		foreach($data as $r){
		 echo "<option value='".$r->NoUrut."'>".$r->NoUrut."-- ".$r->Deskripsi."</option>";
		}
	}
	/**
	List transaksi penjualan for editing data transaksi
	added on 21-07-2013
	*/
	
	function list_trans()
	{
		$data=array();
		$this->zetro_auth->menu_id(array('listtransaksipenjualan'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('penjualan/material_jual_list');
	}
	
	function get_list_trans()
	{
		$data=array();$n=0;
		$where=empty($_POST['to_tgl'])?"where p.tanggal='".tgltoSql($_POST['frm_tgl'])."'":
			   "where p.tanggal between '".tglToSql($_POST['frm_tgl'])."' and '".tglToSql($_POST['to_tgl'])."'";
		$where.=empty($_POST['st_trans'])?'':" and p.ID_Jenis='".$_POST['st_trans']."'";
	    $where.=empty($_POST['cari'])?'':" and p.Deskripsi like '".$_POST['cari']."%'";
		$data=$this->inv_model->show_transjual($where);
		foreach($data as $r)
		{
		 $n++;$cek=0;
		 $otok=$this->zetro_auth->cek_oto('e','listtransaksipenjualan');
		 $cek=RowCount("inv_penjualan_detail","where ID_Jual='".$r->ID_j."'","ID");
		 echo tr('xx list_genap tree_'.$r->ID_j).td($n,'center').
			td(tglFromSql($r->Tanggal),'center').
			td($r->NoUrut,'center').
			td($r->Nama).
			td(($r->ID_Post=='0')?$r->Jenis_Jual:$r->Jenis_Jual." - Posting").
			td().
			td(($r->CreatedTime),'center').
			td(($otok!='' && $cek!='0')?img_aksi($r->ID_j.':'.tglFromSql($r->Tanggal),false,'edit'):img_aksi($r->ID_j.':'.tglFromSql($r->Tanggal),true,'delete'),'center').
		   _tr();
		 	 $x=0;$total=0;$h_tot=0;$s_tot=0;
			  $datax=$this->Admin_model->show_list("inv_penjualan_detail","where id_jual='".$r->ID_j."' order by id");
			  foreach($datax as $d)
			  {
				  $x++;
				  //$r->ID_Post!='0' && 
				 echo tr().td($x.nbs(5),'right').
					  td(rdb("inv_barang","kode","kode","where ID='".$d->ID_Barang."'"),'center').
					  td(rdb("inv_barang","Nama_Barang","Nama_Barang","where ID='".$d->ID_Barang."'"),'left\' colspan=\'2').
					  td($d->Jumlah,'center').
					   td(number_format(($d->ID_Barang==6805)?$r->Jumlah:$d->Harga,2),'right').
					  td(number_format(($d->ID_Barang==6805)?($r->Jumlah*$d->Jumlah):($d->Jumlah*$d->Harga),2),'right').
					   td(($otok!='')?img_aksi($d->ID,true,'del'):'','center').
					 _tr();
					 $total +=$d->Jumlah;
					 $h_tot +=($d->ID_Barang==6805)?$r->Jumlah:$d->Harga;
					 $s_tot +=($d->ID_Barang==6805)?($r->Jumlah * $d->Jumlah):($d->Jumlah * $d->Harga);
			  }
			  //sub total per transaksi
				 echo tr('xx\' style=\'background:#999; font-weight:bold').td().
					  td("Total",'Right\' colspan=\'3').
					  td($total,'center').
					  td(number_format($h_tot,2),'right').
					  td(number_format(($s_tot),2),'right').
					  td().
					 _tr();
		}
		dataNotFound($data,8);
				
	}
	function get_detail_trans()
	{
		$data=array();
		$id=$_POST['id'];
		$data=$this->inv_model->get_detail_transak("where p.ID='".$id."'");
		echo json_encode($data[0]);	
	}
	function updatetgltrans()
	{
		$tgl=$_POST['tgl'];
		$id=$_POST['id'];
		echo ($this->Admin_model->upd_data('inv_penjualan',"set tanggal='".tglToSql($tgl)."'","where ID='".$id."'"));
			
	}
	
	function set_trans()
	{
		$data=array();
		$tanggal=tglToSql($_POST['tgl_trans']);
		$ID_Bar=$_POST['id_br'];
		$jml=$_POST['jumlah'];
		$harga=$_POST['harga'];
		$ID=$_POST['id_j'];
		$this->Admin_model->upd_data("inv_penjualan_detail","set tanggal='".$tanggal."', ID_Barang='".$ID_Bar."',Jumlah='".$jml."',Harga='".$harga."'",
		"where ID='".$ID."'");
	}
	
	function delete_trans($notrans='')
	{
		$data=array();
        $data=($notrans=='')?
              $this->Admin_model->show_list("inv_penjualan_detail","where ID='".$_POST['id']."'"):
              $this->Admin_model->show_list("inv_penjualan_detail","where keterangan='".$notrans."'");
        foreach($data as $r)
        {
          //update stock
            $this->Admin_model->upd_data('inv_material_stok',"set stock=(stock+".$r->Jumlah."),blokstok=0,dariFlow='Hapus Transaksi ID=".$r->ID."-".@$_SERVER['REMOTE_ADDR']."'"," where ID_Barang='".$r->ID_Barang."'");
        }
        if($notrans=='')
        {
		$this->Admin_model->hps_data("inv_penjualan_detail","where ID='".$_POST['id']."'");            
        }else{
         $this->Admin_model->hps_data("inv_penjualan_detail","where Keterangan='".$notrans."'");
		 $this->Admin_model->hps_data("inv_penjualan","where NoUrut='".$notrans."'");
   
        }
	}
	function delete_trans_head()
	{
		$this->Admin_model->hps_data("inv_penjualan","where ID='".$_POST['id']."'");
	}
	function get_barange()
	{
		$data=array();$akuns='';
		$data=$this->Admin_model->show_list("inv_barang","order by nama_barang");
		foreach($data as $r)
		{
			$akuns[]=str_replace("\""," ",strtoupper($r->Nama_Barang))."-:".$r->ID;
		}
		//$akuns.=']';
		echo json_encode($akuns);
	}
	function copyFile($url,$dirname){
        @$file = fopen ($url, "rb");
        if (!$file) {
            echo"<font color=red>Failed to copy $url!</font><br>";
            return false;
        }else {
            $filename = basename($url);
            $fc = fopen($dirname."$filename", "wb");
            while (!feof ($file)) {
               $line = fread ($file, 1028);
               fwrite($fc,$line);
            }
            fclose($fc);
            echo "<font color=blue>File $url saved to PC!</font><br>";
            return true;
        }
    }
    function hutangpelanggan(){
        $hutang=0;
        $namaplg=empty($_POST['nm_nasabah'])?'':$_POST['nm_nasabah'];
        $nama=explode('-',$namaplg);
        if(strlen($namaplg)>0){
            $hutang=$this->Admin_model->show_single_field('mst_pelanggan','hutang_pelanggan',"where nm_pelanggan='".$namaplg."'");          
        }
        echo($hutang=='')?"0.": $hutang;
    }
    /*
        Nota DO
        28-11-2015
        Pembuatan Nota pengiriman
    */
    function GetPelangganDO()
    {
        $data=array();
        $notrans=@$_POST['notrans'];
        $data=$this->Admin_model->show_list("inv_penjualan","where NoUrut='".$notrans."'");
        echo ($data)? json_encode($data[0]):json_encode("{'0'}");
    }
    function GetListTrans()
    {
        $data=array();
        $notrans=@$_POST['notrans'];
        $data=$this->Admin_model->show_list("inv_penjualan_detail","where Keterangan='".$notrans."'");
        foreach($data as $r)
        {
            $checked=rdb("inv_penjualan_do","id_jual","id_jual"," where id_jual='".$r->ID."'");
            $lokasi=rdb("inv_penjualan_do","lokasi","lokasi"," where id_jual='".$r->ID."'");
            $chk=($checked=='')?"":"checked='checked'";
            $dsb=($chk=="")?"disabled='disabled'":"";
            echo tr().td("<input type='checkbox' id='id_".$r->ID."' name='id_".$r->ID."' $dsb $chk onclick=\"chk_click('".$r->ID."');\"/>",'center').
                td("<input type='hidden' id='nn_".$r->ID."' name='nn_".$r->ID."' value='".$r->ID_Barang."'/>".rdb('inv_barang','Nama_Barang','Nama_Barang',"where ID='".$r->ID_Barang."'")).
                td("<input type='hidden' id='ss_".$r->ID."' name='ss_".$r->ID."' value='".$r->ID_Satuan."'/>".rdb('inv_barang_satuan','Satuan','Satuan',"where ID='".$r->ID_Satuan."'"),'center').
                td("<input type='text' style='width:95%; background-color:transparent; border:none' class='angka' id='nj_".$r->ID."' name='nj_".$r->ID."' value='".$r->Jumlah."' onkeypress=\"do_jml('".$r->ID."');\"/>",'center').
                td("<select id='ls_".$r->ID."' name='ls_".$r->ID."' class='S100 cls' onchange=\"do_select('".$r->ID."');\">".
                   Returndropdown('user_lokasi',"ID","Lokasi","where ID !='".$this->session->userdata('gudang')."'",$lokasi)."</select>").
                _tr();
        }
    }
    function dropdownlokasi()
    {
        Dropdown('user_lokasi',"ID","Lokasi","where ID!='".$this->session->userdata('gudang')."'");
    }
    function Set_ListDO()
    {
        $data=array();
        $data["notrans"]=@$_POST['notrans'];
        $data["id"]=empty($_POST['id'])?'0':$_POST['id'];
        $data["id_barang"]=empty($_POST['id_barang'])?"0":$_POST['id_barang'];
        $data["id_satuan"]=empty($_POST['id_satuan'])?"0":$_POST['id_satuan'];
        $data["id_jual"]=$_POST["di_jual"];
        $data["jumlah"]=$_POST['jumlah'];
        $data["harga"]=0;
        $data["nm_pelanggan"]=$_POST["nm_pelanggan"];
        $data["created_by"]=$this->session->userdata('userid');
        $data["lokasi"]=empty($_POST['lokasi'])?"0":$_POST['lokasi'];
        $chk=$_POST['chk'];
        $id_jual=$_POST['di_jual'];
        if($chk=='true')
        {
            echo $this->Admin_model->replace_data("inv_penjualan_do",$data);
        }else{
            echo $this->Admin_model->hps_data('inv_penjualan_do'," where id_jual='".$id_jual."'");
        }
        //
    }
    function printDO()
    {
        $notrans=$_POST['notransdo'];
        $this->Admin_model->upd_data("inv_penjualan_do","set printed='N'","where notrans='".$notrans."'");
        $nasabah=empty($_POST['nm_pelanggane'])?"":$_POST['nm_pelanggane'];
        //$nasabah=empty($_POST['nm_pelanggan'])?$nasab:$_POST['nm_pelanggan'];
 		$this->zetro_slip->modele('wb');
		$this->zetro_slip->path=$this->session->userdata('userid');
		//$this->zetro_slip->newline();
		$this->no_transaksi($notrans);
		$this->tanggal(date('Ymd'));
		$this->zetro_slip->content($this->struk_do_kecil($nasabah));
		$this->zetro_slip->create_file();

    }
    function struk_do_kecil($nasabah){
		$data=array();
		$no_trans=$this->no_trans;
		$nfile	='asset/bin/zetro_config.dll';
		$coy	=$this->zetro_manager->rContent('InfoCo','Name',$nfile);	
		$address=$this->zetro_manager->rContent('InfoCo','Address',$nfile);
		$city	=$this->zetro_manager->rContent('InfoCo','Kota',$nfile);
		$phone	=$this->zetro_manager->rContent('InfoCo','Telp',$nfile);
		$fax	=$this->zetro_manager->rContent('InfoCo','Fax',$nfile);
		$tgl	=rdb('inv_penjualan_do','Created_date','Created_date',"where notrans='".$no_trans."'");
		$no_faktur=rdb('inv_penjualan_do','notrans','notrans',"where notrans='".$no_trans."'");
		$JenisSLip="DO SLIP";
        $pel=($nasabah);//!='')?explode('-',$nasabah):'';
		$isine	=array(
					strtoupper($JenisSLip).newline(),
					sepasi(((40-(strlen($coy)+6))/2)).'** '.$coy.' **'.newline(),
					sepasi(((40-((strlen($address))))/2)).$address.newline(),
					//sepasi(((40-((strlen($city))))/2)).$city.newline(),
					sepasi(((40-((strlen($phone))))/2)).$phone.newline(),
                    str_repeat('-',40).newline(),
                    'Pel: '.strtoupper($pel).newline(),
					str_repeat('-',40).newline(),
					$this->isi_do_kecil(),
                    $this->footere($nasabah)
					);
		return $isine;			
	}
    function isi_do_kecil($where=""){
		$data=array();$content="";$n=0;
		$data=$this->Admin_model->show_list('inv_penjualan_do',"where notrans='".$this->no_trans."'".$where);
		 foreach($data as $row){
			 $n++;
			 $satuan=rdb('inv_barang_satuan','Satuan','Satuan',"where ID='".
			 		 rdb('inv_barang','ID_Satuan','ID_Satuan',"where ID='".$row->id_barang."'")."'");
			$nama_barang=rdb('inv_barang','Nama_Barang','Nama_Barang',"where ID='".$row->id_barang."'");
			$content .=$nama_barang.newline().
                     strtoupper(rdb('user_lokasi','lokasi','lokasi',"where ID='".$row->lokasi."'")).
					 sepasi((20-strlen($row->jumlah))).($row->jumlah).
					 sepasi((8-strlen($satuan))).$satuan.newline();
		 }
		 if($n<2){
			 $content .=newline((2-$n));
		 }
		 return $content;
		 
	}
    function footere($nasabah)
    {   
        $bawah="";
        $bawah.=str_repeat('-',40).newline().
				    'Kasir :'.$this->session->userdata("userid").' Tanggal :'.date("d-m-Y").newline().
				    'Doc No:'.$this->no_trans.sepasi(10);
		$bawah.='Terima Kasih '.newline();
        $bawah.=str_repeat('-',40).newline();
        $bawah.="Kirim Ke : ".newline();
        $bawah.=ucwords(substr($nasabah,0,40)).newline();
        $bawah.=(strlen($nasabah)>40)?ucwords(substr($nasabah,40,strlen($nasabah)-40)):"";
        $bawah.newline(5);
        return $bawah;
    }
    
    function printDO2($notran,$pelanggan,$user,$lokasi,$total=0,$ke=0)
    {
        $notrans=$notran;
        $nasabah=$pelanggan;
 		$this->zetro_slip->modele('wb');
		$this->zetro_slip->path=$user;
		//$this->zetro_slip->newline();
		$this->no_transaksi($notrans);
		$this->tanggal(date('Ymd'));
		$this->zetro_slip->content($this->struk_do_kecil($nasabah," and lokasi='".$lokasi."' "));
		if($total==$ke){
            $this->zetro_slip->create_file();
        }
        

    }
    function CheckOpenDO()
    {
        $data=array();$i=0;
        $where=" and lokasi='".$this->session->userdata('gudang')."' and harga=1";
        $data=$this->kasir_model->GetListDO($where);
        $row_count=$this->inv_model->total_record("inv_penjualan_do","where (Printed='N' OR Printed='I') and harga=1 ".$where);
        foreach($data as $r)
        {
            $i++;
            $this->printDO2($r->notrans,$r->nm_pelanggan,$r->Usr,$r->lokasi,$row_count,$i);
            $this->Admin_model->upd_data("inv_penjualan_do","set printed='Y'","where id='".$r->id."'");
        }
        print_r($data);
    }
    /*
    Menambahkan proses monitoring do
    06-02-2015
    */
    
    function GetListDO()
    {
        $data=array();$stat="";$ttl="";
        $status=empty($_POST['status'])?"":$_POST["status"];
        $cari=empty($_POST['cari'])?"":$_POST['cari'];
        $where=($status=="")?"":" where status_kirim !='Y' and id_barang>0 group by notrans order by id desc ";
        $data=$this->Admin_model->show_list('inv_penjualan_do',$where);
        foreach($data as $r)
        {
            switch($r->status_kirim)
            {
                case 'P': $stat='SK';$ttl='Siap di kirim';break;
                case 'N': $stat='BK';$ttl='Belum di kirim';break;
            }
            echo tr().
                 td($r->notrans.'->'.substr(ucwords($r->nm_pelanggan),0,15),"left' nowrap='nowrap' title='".strtoupper($r->nm_pelanggan)).
                 td($stat,"center' title='".$ttl).
                 td(img_aksi($r->notrans,false,'pros'),'center').
                _tr();
        }
    }
    
    
    function GetListDOTrans()
    {
        $data=array();$checked="";
		$chk="";$dsb="";
        $notrans=@$_POST['notrans'];
        $nomobil=@$_POST['nomobil'];
        $data=$this->Admin_model->show_list("inv_penjualan_do","where notrans='".$notrans."' Or (NoMobil='$nomobil' and status_kirim='P')");
        foreach($data as $r)
        {
            $checked=rdb("inv_penjualan_do","status_kirim","status_kirim"," where id_jual='".$r->id."'");
            //$lokasi=rdb("inv_penjualan_do","lokasi","lokasi"," where id_jual='".$r->ID."'");
            $chk=($r->status_kirim=='P')?"checked='true'":"";
            $dsb=($r->status_kirim=='Y')?"disabled='disabled'":"";
            $chk=($r->nomobil==$nomobil)?$chk:"";
            echo tr().td("<input type='checkbox' id='id_".$r->id."' name='id_".$r->id."' $dsb $chk onclick=\"chk_click2('".$r->id."');\"/>",'center').
                td("<input type='hidden' id='nn_".$r->id."' name='nn_".$r->id."' value='".$r->id_barang."'/>".rdb('inv_barang','Nama_Barang','Nama_Barang',"where ID='".$r->id_barang."'")).
                td("<input type='hidden' id='ss_".$r->id."' name='ss_".$r->id."' value='".$r->id_satuan."'/>".rdb('inv_barang_satuan','Satuan','Satuan',"where ID='".$r->id_satuan."'"),'center').
                td("<input type='hidden' id='nj_".$r->id."' name='nj_".$r->id."' value='".$r->jumlah."'/>".number_format($r->jumlah,2),'right').
                
                _tr();
        }
    }
    function UpdateDoKirim(){
        $id         =@$_POST['id'];
        $stat       =@$_POST['stat'];
        $mobil      =($stat=='N')?'':@$_POST['mobil'];
        $driver     =($stat=='N')?'':@$_POST['driver'];
        $kirimke    =($stat=='N')?'':@$_POST['tujuan'];
        $this->Admin_model->upd_data("inv_penjualan_do","set Status_kirim='".$stat."',nomobil='".$mobil."',
                                      Driver='".$driver."',TujuanKirim='".$kirimke."'","where id='".$id."'");
    }
    function PrintDOM(){
        $mobil=@$_POST['Mobil'];
        
        if($mobil!='DiAmbil'){
           $this->printSuratJalan($mobil);
        }else{
            $this->Admin_model->upd_data("inv_penjualan_do","set Status_kirim='Y'","where nomobil='".$nomobil."'");  
        }
    }
    /*
        Print Slip Surat Jalan Pengiriman Barang
        Added on : 21-08-2016
        Print per Pelanggan, tambahan di footer Sisa nota 
    */
    function Urutan($n)
    {
        $this->Nourut=$n;
    }
    function TotalTrans($n){ $this->Total_Trans=$n;}
    function printSuratJalan($nomobil)
    {
        $data=array();
        $isi=array();
        $n=0;$ttd=0;
        $data=$this->kasir_model->GetTransForSJ("and Nomobil='".$nomobil."'"," order by notrans");
        $this->TotalTrans(count($data));
        foreach($data as $r)
        {
            $n++;
            $this->zetro_slip->modele('wb');
            $this->zetro_slip->path=$this->session->userdata('userid');
            //$this->zetro_slip->newline();
            $this->no_transaksi($r->notrans);
            $this->tanggal(date('Ymd'));
            $this->Urutan($n);
            $isi=array_merge($this->StrukSuratJalan(),$isi);
            //$this->zetro_slip->create_file(false);
            
        }
        
        $this->zetro_slip->content($isi);
        $this->zetro_slip->create_file();
        $this->Admin_model->upd_data("inv_penjualan_do","set Status_kirim='Y'","where nomobil='".$nomobil."'");     
    }
    function StrukSuratJalan()
    {
        $noDO=$this->Nourut;
        $totalDO=$this->Total_Trans;
        $data=array();$isine=array();
		$no_trans=$this->no_trans;
		$nfile	='asset/bin/zetro_config.dll';
		$coy	=$this->zetro_manager->rContent('InfoCo','Name',$nfile);
        $tgl	=rdb('inv_penjualan_do','Created_date','Created_date',"where notrans='".$no_trans."'");
		$no_faktur=rdb('inv_penjualan_do','notrans','notrans',"where status_kirim='P' and notrans='".$no_trans."'");
        $no_mobil=rdb('inv_penjualan_do','nomobil','nomobil',"where status_kirim='P' and notrans='".$no_trans."'");
        $sopir=rdb('inv_penjualan_do','driver','driver',"where status_kirim='P' and notrans='".$no_trans."'");
        $kirimke=rdb('inv_penjualan_do','tujuankirim','tujuankirim',"where status_kirim='P' and notrans='".$no_trans."'");
        $nasabah=rdb('inv_penjualan_do','nm_pelanggan','nm_pelanggan',"where status_kirim='P' and notrans='".$no_trans."'");
		$JenisSLip="DO SLIP";
        $kirimken=ucwords(substr($kirimke,0,40));
        $kirimken.=(strlen($kirimke)>40)?newline().ucwords(substr($kirimke,40,strlen($kirimke)-40)):"";
        $judul=($noDO==$totalDO)?
					sepasi(((40-(strlen($coy)+6))/2)).'** '.$coy.' **'.newline().
                    str_repeat('-',40).newline().
                    'Mobil   : '.strtoupper($no_mobil).newline().
                    'Sopir   : '.strtoupper($sopir).newline(1)
                    :"";
		$isine	=array(
					$judul,strtoupper($JenisSLip." [".(($totalDO+1)-$noDO)."]").newline(),
                    'No.Order: '.strtoupper($no_faktur).newline(),
                    'Kirim Ke: '.newline(),
                    $kirimken.newline(),
					str_repeat('-',40).newline(),
					$this->isi_do_kecil(" and status_kirim='P'"),
                    $this->footereStruk($nasabah).newline(1),
                    ($noDO>1)?str_repeat("-",17).str_repeat("+",6).str_repeat("-",17).newline(1):str_repeat("-",40).newline()."Tanggal : ".date('Y-m-d H:i:s')
					);
		return $isine;	
    }
    function footereStruk($nasabah)
    {   
        $bawah="";
       // $this->Admin_model->filterby("where status_kirim='P' and notrans='".$this->no_trans."'","notrans");
        $totalitem=$this->Admin_model->field_exists("inv_penjualan_do","where status_kirim='P' and notrans='".$this->no_trans."'","count(notrans)");
        $hutang=rdb("mst_pelanggan_view","hutang_pelanggan","hutang_pelanggan","where nm_pelanggan='".$nasabah."'");
        $bawah.=str_repeat('-',40).newline().
				    'Total Barang :'.$totalitem." macam ".newline();
		$bawah.=($hutang>0)?'Catatan : '.newline():"";
        $bawah.=($hutang>0)?" ** Sisa Nota :".number_format($hutang,0).newline():"";
        
        $bawah.newline(5);
        return $bawah;
    }
}
?>