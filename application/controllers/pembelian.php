<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//
//Class name: Pembelian controller
//version : 1.0
//Author : Iswan Putera


class Pembelian extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->model("Admin_model");
		$this->load->model("inv_model");
		$this->load->model("purch_model");
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
		//$data['nasabah']=$this->get_pelanggan();
		$this->zetro_auth->menu_id(array('inputpembelian','listpenerimaan'));
		$this->list_data(array_merge($this->zetro_auth->auth(),$data));
		$this->View('pembelian/material_income');
	}
	function return_beli(){
		$data=array();
		$this->zetro_auth->menu_id(array('returnpembelian'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('pembelian/material_income_return');
	}
	function nomor_transaksi(){
		$tipe=$_POST['tipe'];
		$nomor=$this->Admin_model->penomoran($tipe,2);
		echo $nomor;	
	}
	//menampilkan data - data sesuai dengan variable yang dikirim via ajx post
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
		$data=$this->Admin_model->show_list("mst_anggota","where id_jenis!='1' and length(nama)>1 group by Nama,Alamat order by nama");
		foreach($data as $r)
		{
			//$nasabah.="\"".str_replace("\""," ",strtoupper($r->Nama)).' - '.ucwords($r->Alamat)."\",";
            $nasabah.="\"".str_replace("\\","-",str_replace("\""," ",strtoupper($r->Nama))).' - '.ucwords($r->Alamat)."\",";
		}
		return substr($nasabah,0,-1);
	}
	function get_detail_barang()
	{
		$data=array();
		$nm_br=empty($_POST['id'])?'':$_POST['id'];
		$data=$this->Admin_model->show_list("inv_barang_view","where nama_barang='".$nm_br."' or ID_Pemasok='".$nm_br."'");
		//print_r($data);
		echo ($data)?json_encode($data[0]):'{"ID":"0"}';
		
	}
	function harga($harga){
		// insert harga jual pada data array
		$this->harga=$harga;
	}	
	function jenistran($jtran){
		//insert jenis transaksi penjualan (GI) atau pembelian(GR)
		$this->jtran=$jtran;
	}
	/*simpan data header transaksi*/
	function set_header_pembelian(){
		$data=array();$cek='';$datax=array();$total=0;
		$cek=$this->Admin_model->field_exists('inv_pembelian',"where NoUrut='".$_POST['no_trans']."' and Tanggal='".tgltoSql($_POST['tanggal'])."'",'ID');
		if($_POST['cbayar']==1){
			$jenis='BT';
		}else if($_POST['cbayar']==2){
			$jenis='KY';
		}else if($_POST['cbayar']==3){
			$jenis='RK';
		}
		$data['ID']			=empty($_POST['id_beli'])?'0':$_POST['id_beli'];
		$data['NoUrut']		=$_POST['no_trans'];
		$data['Tanggal']	=tgltoSql($_POST['tanggal']);
		$data['ID_Jenis']	=empty($_POST['cbayar'])?'1':$_POST['cbayar'];	
		$data['ID_Pemasok']	=empty($_POST['id_pemasok'])?0:$_POST['id_pemasok'];	
		$data['Nomor']		=empty($_POST['faktur'])? $jenis.'-'.date('ymd').'-'.substr($_POST['no_trans'],6,4):$_POST['faktur'];	
		$data['Bulan']		=substr($_POST['tanggal'],3,2);	
		$data['Tahun']		=substr($_POST['tanggal'],6,4);	
		$data['Deskripsi']	=addslashes($_POST['pemasok']);	
        $data['JatuhTempo'] =empty($_POST['jtempo'])?0:$_POST['jtempo'];
		//$total=empty($_POST['total'])? 0:$_POST['total'];
		($cek=='')?
		$this->Admin_model->replace_data('inv_pembelian',$data):'';
		//$this->Admin_model->upd_data('inv_pembelian',"set ID_Bayar='".$total."'","where ID='$cek'");
		
		$datax['nomor']		=$_POST['no_trans'];
		$datax['jenis_transaksi']='GR2';
		$this->Admin_model->replace_data('nomor_transaksi',$datax);
	}
	/*simpan data detail transaksi*/
	function set_detail_pembelian(){
		$data=array();$rcord=0;$tot_bel=0;$find_batch='';
		$id_beli=rdb('inv_pembelian','ID','ID',"where NoUrut='".$_POST['no_trans']."' and Tanggal='".tgltoSql($_POST['tanggal'])."'");
		$id_barang=rdb('inv_barang','ID','ID',"where Nama_Barang='".addslashes($_POST['nm_barang'])."'");
		$find_batch=rdb('inv_material_stok','batch','batch',"where id_barang='".$id_barang."' and harga_beli='".$_POST['harga_beli']."'");
		$tot_bel=rdb('inv_pembelian','ID_Bayar','ID_Bayar',"where NoUrut='".$_POST['no_trans']."' and Tanggal='".tgltoSql($_POST['tanggal'])."'");
		$id_anggota=rdb('inv_pembelian','ID_Pemasok','ID_Pemasok',"where NoUrut='".$_POST['no_trans']."' and Tanggal='".tgltoSql($_POST['tanggal'])."'");
		$data['tanggal']	=tgltoSql($_POST['tanggal']);
		$data['id_beli']	=$id_beli;
		$data['id_barang']	=$_POST['kode'];//rdb('inv_barang','ID','ID',"where Nama_Barang='".addslashes($_POST['nm_barang'])."'");
		$data['jml_faktur']	=$_POST['jumlah'];
		$data['Jumlah']		=$_POST['jumlah'];
		$data['Harga_Beli']	=$_POST['harga_beli'];
		$data['ID_Satuan']	=$_POST['id_satuan'];
		$data['batch']		=($find_batch=='' || $find_batch==NULL)?date('ymdz').'-'.date('i'):$find_batch;
		$data['Keterangan']	=$_POST['keterangan'];
		$data['Bulan']		=substr($_POST['tanggal'],3,2);	
		$data['Tahun']		=substr($_POST['tanggal'],6,4);	
        $data['lokasi']     =@$_POST['lokasi'];
		$this->Admin_model->replace_data('inv_pembelian_detail',$data);
		$this->Admin_model->upd_data('inv_pembelian',"set ID_Bayar='".($tot_bel+$_POST['keterangan'])."',JatuhTempo='".@$_POST['jtempo']."'",
									 "where NoUrut='".$_POST['no_trans']."' and Tanggal='".tgltoSql($_POST['tanggal'])."'");
		echo rdb('inv_pembelian_detail','ID','ID',"order by ID desc limit 1");
		//process to jurnal temp
		/*$ID_Jenis=empty($_POST['id_jenis'])?'1':$_POST['id_jenis'];
		$TotalHg=$_POST['harga_beli'];
		$this->no_transaksi($_POST['no_trans']);
		$this->tanggal(($_POST['tanggal']));
		$this->JenisBayar('6');
		if($TotalHg!='0'){
			if(($ID_Jenis!='4' || $ID_Jenis!='5') && $id_anggota!=''){
				$this->process_to_jurnal($id_anggota,$TotalHg);
			}else if($ID_Jenis=='6' && $id_anggota!=''){
				$ket='';	
				$this->process_to_jurnal($id_anggota,$TotalHg,$ket);
			}
		}*/
	}
	function JenisBayar($id_jenis){
		$this->id_jenis=$id_jenis;	
	}
	function no_transaksi($no_trans){
		$this->no_trans=$no_trans;
	}
	function tanggal($tgl){
		$this->tgl=$tgl;
	}

	function hapus_transaksi(){
	  //jika transaksi di batalkan
	  $data=array();
	  $ID=$_POST['ID'];
	  $this->Admin_model->hps_data('inv_pembelian_detail',"where ID='$ID'");	
	}
	function get_detail_trans(){
		$data=array();
		$ID=$_POST['id'];
		$data=$this->inv_model->get_detail_trans($ID);
		echo json_encode($data[0]);
	}
	function update_stock(){
		$data=array();$hasil_konv=1;
		$stock_awal=0;$stock_akhir=0;$rcord=0;
		$id_barang=rdb('inv_barang','ID','ID',"where Nama_Barang='".addslashes($_POST['nm_barang'])."'");
		$find_batch=empty($_POST['batch'])?rdb('inv_pembelian_detail','batch','batch',"where id_barang='".$id_barang."' and harga_beli='".$_POST['harga_beli']."'"):$_POST['batch'];
		$stock_awal=rdb('inv_material_stok','stock','stock',"where id_barang='".$id_barang."' /*and batch='".$find_batch."'*/");
		$hasil_konv=rdb('inv_konversi','isi_konversi','isi_konversi',"where id_barang='".$id_barang."' and sat_beli='".$_POST['id_satuan']."'");
		$hasil_konv=($hasil_konv>0)?$hasil_konv:1;
        $stock_akhir=($_POST['aksi']=='del')?((int)$stock_awal-($hasil_konv*$_POST['jumlah'])):
        ((int)$stock_awal+($hasil_konv*$_POST['jumlah']));
		$data['id_lokasi']	='3';//toko 3
		$data['id_barang']	=empty($_POST['id_barang'])?'0':$_POST['id_barang'];
		$data['nm_satuan']	=empty($_POST['id_satuan'])?'0':$_POST['id_satuan'];
		$data['nm_barang']	=addslashes($_POST['nm_barang']);
		$data['stock']		=$stock_akhir;
		$data['harga_beli']	=empty($_POST['harga_beli'])?'0':$_POST['harga_beli'];
		$data['created_by']	=$this->session->userdata('userid');
        $data['batch']  =@$_POST['batch'];
        $data['dariFlow']='Pembelian -'.@$_SERVER['REMOTE_ADDR'];
        $data['blokstok']=($hasil_kov*@$_POST['jumlah']);
		echo $this->Admin_model->replace_data('inv_material_stok',$data);
	}
    
	function show_list(){
		$data=array();$n=0;$id_beli='';
		$jtran=$_POST['no_transaksi'];
		$tanggal=tgltoSql($_POST['tanggal']);
		$id_beli=rdb('inv_pembelian','ID','ID',"where NoUrut='".$_POST['no_transaksi']."' and Tanggal='".$tanggal."'");
		if($id_beli){//!=''|| $id_beli!=0){
			$data=$this->Admin_model->show_list('inv_pembelian_detail',"where ID_Beli='$id_beli' order by ID");
			foreach ($data as $r){
				$n++; $lokasine='';
                switch($r->Lokasi)
                {
                    case 1:$lokasine='PUSAT';break;
                    case 4:$lokasine='MGM';break;
                    case 3:$lokasine='GUDANG';break;
                }
				echo tr().td($n,'center').
						  //td(rdb('inv_barang','Kode','Kode',"where ID='".$r->ID_Barang."'")).
						  td(rdb('inv_barang','Nama_Barang','Nama_Barang',"where ID='".$r->ID_Barang."'")).
						  td(rdb('inv_barang_satuan','Satuan','Satuan',"where ID='".$r->ID_Satuan."'")).
						  td(number_format($r->Jml_Faktur,2),'right').
						  td(number_format($r->Harga_Beli,2),'right').
						  td(number_format(($r->Jml_Faktur*$r->Harga_Beli),2),'right').
                          td($lokasine,'center').
						  td("<img src='".base_url()."asset/images/no.png' title='Hapus transaksi' onclick=\"image_click('".$r->ID."','del');\">","center").
					 _tr();
			}
			if($n=='0'){echo tr().td('&nbsp;','left\' colspan=\'8\'')._tr();}
		}else{	echo tr().td('&nbsp;','left\' colspan=\'8\'')._tr();}
	}
	
	function get_pemasok(){
		$data=array();
		$str	=$_GET['str'];
		$limit	=$_GET['limit'];
		$data=$this->purch_model->get_pemasok($str,$limit);
		echo json_encode($data);
	}

//-------List Pembelian----------------
	function laporan_pembelian(){
	//process filter
	$cek=$this->zetro_auth->cek_oto('e','listpenerimaan');
	$data=array();$where='';$datax=array();$n=0;
	empty($_POST['smp_tanggal'])?
		$where="where Tanggal='".tgltoSql($_POST['dari_tanggal'])."'":
		$where="where (Tanggal between '".tgltoSql($_POST['dari_tanggal'])."' and '".tgltoSql($_POST['smp_tanggal'])."')"; 
		//echo $where;
		$data=$this->Admin_model->show_list('inv_pembelian',$where." order by NoUrut");
		foreach($data as $r){
			$n++;$x=0;
			echo tr('xx list_genap').td($n.nbs(3),'center').td($r->Nomor,'center').td(tglfromSql($r->Tanggal),'center').
					  td(strtoupper(rdb('inv_pemasok','Pemasok','Pemasok',"where ID='".$r->ID_Pemasok."'")),'left\' colspan=\'3\'').
					  td(rdb('inv_pembelian_jenis','Jenis_Beli','Jenis_Beli',"where ID='".$r->ID_Jenis."'")).
					  td('<b>'.number_format($r->ID_Bayar,2).'</b>','right').td('').
				 _tr();	
			$datax=$this->Admin_model->show_list('inv_pembelian_detail',"where ID_Beli='".$r->ID."'");
			foreach($datax as $row){
				$x++;$cek_stock=0;
				$cek_stock=rdb('inv_material_stok','stock','sum(stock) as stock',"where id_barang='".
							$row->ID_Barang."' and Batch='".$row->Batch."'");
                $lokasine='';
                switch($row->Lokasi)
                {
                    case 1:$lokasine='PUSAT';break;
                    case 4:$lokasine='MGM';break;
                    case 3:$lokasine='GUDANG';break;
                }
				echo tr().td(nbs(2).$x,'center').
						  td(rdb('inv_barang','Kode','Kode',"where ID='".$row->ID_Barang."'").nbs(5),'right\' colspan=\'2\'').
						  td(nbs(2).rdb('inv_barang','Nama_Barang','Nama_Barang',"where ID='".$row->ID_Barang."'")).
						  td(rdb('inv_barang_satuan','Satuan','Satuan',"where ID='".$row->ID_Satuan."'")).
						  td(number_format($row->Jml_Faktur,2),'right').
						  td(number_format($row->Harga_Beli,2),'right').
						  td(number_format(($row->Jml_Faktur*$row->Harga_Beli),2),'right').
						  td(($cek!='' && $cek_stock==$row->Jml_Faktur)?img_aksi($row->ID,true,'del'):$lokasine,'center');
					 _tr();	
			}
		}
		if(!$data){
			echo tr().td('Data not found...','left\'colspan=\'9')._tr();
		}
	}
	//return pembelian
	function set_retur_beli(){
			
	}
	
	//----------------------------support function----------------------------------
	//get data material by kode
	function get_material_kode(){
		$data=array();
		$kode=$_POST['kode'];
		$data=$this->purch_model->get_material_kode($kode);
		echo (count($data)>0)?json_encode($data[0]):'{"Nama_Barang":"","ID_Satuan":""}';
	}
	
	function get_satuan_konversi(){
		$data=array();$opt='';
		$kode=addslashes($_POST['nm_barang']);
		$data=$this->purch_model->get_satuan_konv($kode);
		foreach($data as $r){
			$opt.= "<option value='".$r->sat_beli."'>".$r->Satuan."</option>";	
		}
		$id_s=rdb('inv_barang','id_satuan','id_satuan',"where nama_barang='".$kode."'");
		$n_sat=rdb('inv_barang_satuan','satuan','satuan',"where id='".$id_s."'");
		echo (!$data)?"<option value='".$id_s."'>".$n_sat."</option>":$opt;
	}
	function get_total_belanja(){
		$data		=array();$tt_harga=0;
		$no_trans	=$_POST['no_trans'];
		$tanggal	=$_POST['tanggal'];
		$data		=$this->purch_model->get_total_belanja($no_trans,$tanggal);
		foreach($data as $r){
			$tt_harga=$r->total;
		}
		echo $tt_harga;
	}
	function process_to_jurnal($id_anggota,$total,$ket=''){
	/* membuat data untuk diposting dalam jurnal
	  simpan penjualan barang secara kredit
	  inputnya adalah ID anggota yng melakukan pembelian secara kredit
	  dapatkan ID_Perkiraan dari table perkiraan based on ID_Anggota
	  extract data perkiraan menjadi klass, sub klass dan jenis simpanan dalam hal ini
	  jenis simpanan adalah barang [id:4]
	  data yang dihasilkan akan ditampung di table transaksi_temp
	*/ 
		$data=array();$akun='';
		//get ID_perkiraan
		$akun=rdb('perkiraan','ID','ID',"where ID_Agt='$id_anggota' and ID_Simpanan='".$this->id_jenis."'");
		//echo $akun;
		if($akun==''){
			//update database perkiraan
			$this->_update_perkiraan($id_anggota,$this->id_jenis);	
		}
		$data['ID_Perkiraan']	=rdb('perkiraan','ID','ID',"where ID_Agt='$id_anggota' and ID_Simpanan='".$this->id_jenis."'");
		$data['ID_Unit']		='1';//rdb('jenis_simpanan','ID_Unit','ID_Unit',"where ID='".$this->id_jenis."'");
		$data['ID_Klas']		='1';//rdb('jenis_simpanan','ID_Klasifikasi','ID_Klasifikasi',"where ID='".$this->id_jenis."'");
		$data['ID_SubKlas']		='1';//rdb('jenis_simpanan','ID_SubKlas','ID_SubKlas',"where ID='".$this->id_jenis."'");
		$data['ID_Dept']		='0';rdb('mst_anggota','ID_Dept','ID_Dept',"where ID='".$id_anggota."'");
		if($ket==''){
			$data['Debet']		=$total;//rdb('inv_penjualan','Total','Total',"where ID_Anggota='".$id_anggota."' and NoUrut='".$this->no_trans."'");
		}else{
			$data['Kredit']		=$total;
		}
		$data['ID_CC']			='4';
		$data['Keterangan']		=($ket=='')?'Pembelian  no. Faktur: '.rdb('inv_pembelian','Nomor','Nomor',"where NoUrut='".$this->no_trans."' and Tanggal='".tgltoSql($this->tgl)."'"):$ket;
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
		$datax['ID_Dept']		='0';//rdb('mst_anggota','ID_Dept','ID_Dept',"where ID='".$ID_Agt."'");
		$datax['ID_Simpanan']	=$ID_Simpanan;
		$datax['ID_Agt']		=$ID_Agt;
		$datax['ID_Calc']		=rdb('jenis_simpanan','ID_Calc','ID_Calc',"where ID='".$this->id_jenis."'");
		$datax['ID_Laporan']	=rdb('jenis_simpanan','ID_Laporan','ID_Laporan',"where ID='".$this->id_jenis."'");
		$datax['ID_LapDetail']	=rdb('jenis_simpanan','ID_LapDetail','ID_LapDetail',"where ID='".$this->id_jenis."'");
		echo $this->Admin_model->replace_data('perkiraan',$datax);
		//print_r($datax);
	}
    
    /*
     Rencana Belanja
     added on 10-01-2016
    */
    function nomor_transaksi2(){
		$tipe=$_POST['tipe'];
		$nomor=$this->Admin_model->penomoran($tipe,1);
		echo $nomor;	
	}
	function belanja()
    {
        $data=array();
		$data['datas']=$this->get_barang();
		$data['nasabah']=$this->get_pelanggan();
		$this->zetro_auth->menu_id(array('rencanabelanja'));
		$this->list_data(array_merge($this->zetro_auth->auth(),$data));
		$this->View('pembelian/rencana_belanja');
    }
    function simpan_spp()
    {
        $data=array();
        $data["id"]=empty($_POST['id'])?"0":$_POST['id'];
        $data["notrans"]=empty($_POST['no_transaksi'])?"":$_POST['no_transaksi'];
        $data["tanggal"]=empty($_POST['tgl_transaksi'])?date('Ymd'):tglToSql($_POST['tgl_transaksi']);
        $data["id_barang"]=empty($_POST['1__id_barang'])?"0":$_POST['1__id_barang'];
        $data["satuan"]=empty($_POST['1__nm_satuan'])?"0":$_POST['1__nm_satuan'];
        $data["jumlah"]=empty($_POST['1__jml_transaksi'])?"":$_POST['1__jml_transaksi'];
        $data["harga"]=empty($_POST['1__harga_jual'])?"":$_POST['1__harga_jual'];
        $data["nm_barang"]=empty($_POST['1__nm_barang'])?"":$_POST['1__nm_barang'];
        $data["createdby"]=$this->session->userdata('userid');
        echo $this->Admin_model->replace_data('inv_belanja',$data);
    }
    function get_total_spp()
    {
        $data=array();
        $notrans=$_POST['no_trans'];
        $data=$this->purch_model->get_total_spp($notrans);
		foreach($data as $r){
			$tt_harga=$r->totalharga;
		}
		echo $tt_harga;
    }
    function show_list_spp()
    {
        $data=array(); $n=0;
        $notrans=$_POST['no_transaksi'];
        $data=$this->Admin_model->show_list('inv_belanja',"where notrans='$notrans' order by id desc");
        foreach($data as $r)
        {
            $n++;
            echo tr().
                 td($n,'center').
                 td($r->nm_barang).
                 td(rdb('inv_barang_satuan','Satuan','Satuan',"where ID='".$r->satuan."'")).
                 td(number_format($r->jumlah,2),'right').
				 td(number_format($r->harga,2),'right').
				 td(number_format(($r->jumlah*$r->harga),2),'right').
				 td("<img src='".base_url()."asset/images/no.png' title='Hapus transaksi' onclick=\"images_click('".$r->id."','del');\">","center").
                _tr();
        }
    }
    function printSPP()
    {
       $datax=array();
        $notrans=$_POST['no_transaksi'];
        $nasabah="";//empty($_POST['nm_pelanggan'])?"":$_POST['nm_pelanggan'];
 		$this->zetro_slip->modele('wb');
		$this->zetro_slip->path='admin';//$this->session->userdata('userid');
		//$this->zetro_slip->newline();
		$this->no_transaksi($notrans);
		$this->tanggal(date('Ymd'));
		$this->zetro_slip->content($this->struk_do_kecil($nasabah));
		$this->zetro_slip->create_file();
        //update nomor
        //update nomor transaksi	
		$datax['nomor']		=$notrans;
		$datax['jenis_transaksi']="SPP1";
        $datax['created_by']=$this->session->userdata('username');
		$this->Admin_model->replace_data('nomor_transaksi',$datax);

    }
    function struk_do_kecil($nasabah,$lokasine=""){
		$data=array();
		$no_trans=$this->no_trans;
		$nfile	='asset/bin/zetro_config.dll';
		$coy	=$this->zetro_manager->rContent('InfoCo','Name',$nfile);	
		$address=$this->zetro_manager->rContent('InfoCo','Address',$nfile);
		$city	=$this->zetro_manager->rContent('InfoCo','Kota',$nfile);
		$phone	=$this->zetro_manager->rContent('InfoCo','Telp',$nfile);
		$fax	=$this->zetro_manager->rContent('InfoCo','Fax',$nfile);
		$tgl	=rdb('inv_belanja','Createdtime','Createdtime',"where notrans='".$no_trans."'");
		//$no_faktur=rdb('inv_penjualan_do','notrans','notrans',"where notrans='".$no_trans."'");
		$JenisSLip="SLIP RENCANA BELANJA";
        $isine	=array(
					sepasi(((40-(strlen($coy)+6))/2)).strtoupper($JenisSLip).newline(),
                    str_repeat('-',40).newline(),
					sepasi(((40-(strlen($coy)+6))/2)).'** '.$coy.' **'.newline(),
					sepasi(((40-((strlen($address))))/2)).$address.newline(),
					//sepasi(((40-((strlen($city))))/2)).$city.newline(),
					sepasi(((40-((strlen($phone))))/2)).$phone.newline(),
                    str_repeat('-',40).newline(),
					$this->isi_do_kecil($lokasine),
                    $this->footere($no_trans)
					);
		return $isine;			
	}
    function isi_do_kecil($lokasi=""){
		$data=array();$content="";$n=0;
		$data=$this->Admin_model->show_list('inv_belanja',"where notrans='".$this->no_trans."'$lokasi");
		 foreach($data as $row){
			 $n++;
			 $satuan=rdb('inv_barang_satuan','Satuan','Satuan',"where ID='".
			 		 rdb('inv_barang','ID_Satuan','ID_Satuan',"where ID='".$row->id_barang."'")."'");
			$nama_barang=rdb('inv_barang','Nama_Barang','Nama_Barang',"where ID='".$row->id_barang."'");
			$content .=$nama_barang.newline().
					 sepasi((5-strlen($row->jumlah))).($row->jumlah).
					 sepasi((8-strlen($satuan))).$satuan.
					 sepasi((12-strlen(number_format($row->harga,0)))).number_format($row->harga,0).
					 sepasi((15-strlen(number_format(($row->jumlah *$row->harga),0)))).number_format(($row->jumlah *$row->harga),0).newline();
		 }
		 if($n<2){
			 $content .=newline((2-$n));
		 }
		 return $content;
		 
	}
    function footere($notrans)
    {   
        $bawah="";
        $tanggal=rdb('inv_belanja','tanggal','tanggal',"where notrans='$notrans'");
        $bawah.=str_repeat('-',40).newline().
		'Total'.sepasi((35-strlen(number_format($this->get_total_blnj($notrans),0)))).number_format($this->get_total_blnj($notrans),0).newline();
        $bawah.='Doc No  :'.$this->no_trans.sepasi(10).newline().'Tanggal :'.tglFromSql($tanggal).newline();
        $bawah.newline(5);
        return $bawah;
    }
    
    function get_total_blnj($notrans)
    {
        $data=array();
        //$notrans=$_POST['no_trans'];
        $data=$this->purch_model->get_total_spp($notrans);
		foreach($data as $r){
			$tt_harga=$r->totalharga;
		}
		return $tt_harga;
    }

}