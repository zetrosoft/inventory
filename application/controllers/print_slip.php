<?
class Print_slip extends CI_Controller{
	
	function __construct()
	{
		parent::__construct();	
		$this->load->model("Admin_model");
		$this->load->model("inv_model");
		$this->load->model("kasir_model");
		$this->load->library('zetro_auth');
		$this->load->library('zetro_slip');
		$this->load->model("report_model");
		$this->load->helper("print_report");
	}
	function no_transaksi($no_trans){
		$this->no_trans=$no_trans;
	}
	function tanggal($tgl){
		$this->tgl=$tgl;
	}
	function lokasi_gudang($lokasi)
	{
		$this->lokasi=$lokasi;	
	}
	function print_slip_service(){
		$this->zetro_slip->path=$this->session->userdata('userid');
		$this->zetro_slip->modele('wb');
		$this->zetro_slip->newline();
		$this->no_transaksi($_POST['no_transaksi']);
		$this->tanggal(tgltoSql($_POST['tanggal']));
		$this->lokasi_gudang(empty($_POST['lokasi'])?'1':$_POST['lokasi']);
		$this->zetro_slip->content($this->struk_header());
		$this->zetro_slip->create_file();
		//send user id to ajax output
		echo $this->session->userdata('userid');
	}
	function struk_header(){
		$data=array();
		$slip="PENERIMAAN BARANG SERVICE";
		$no_trans=$this->no_trans;
		$nfile	='asset/bin/zetro_config.dll';
		$lokasi	=empty($_POST['lokasi'])?'1':$_POST['lokasi'];
		$gudang=rdb('user_lokasi','lokasi','lokasi',"where id='".$this->lokasi."'");
		$coy	=$this->zetro_manager->rContent($gudang,'Name',$nfile);	
		$address=$this->zetro_manager->rContent($gudang,'Address',$nfile);
		$city	=$this->zetro_manager->rContent($gudang,'Kota',$nfile);
		$phone	=$this->zetro_manager->rContent($gudang,'Telp',$nfile);
		$fax	=$this->zetro_manager->rContent($gudang,'Fax',$nfile);
		$tgl	=rdb('inv_penjualan_service','tgl_service','tgl_service',"where no_trans='".$no_trans."' and tgl_service='".$this->tgl."'");
		$no_faktur=rdb('inv_penjualan_service','no_trans','no_trans',"where no_trans='".$no_trans."' and tgl_service='".$this->tgl."'");
		$nama=rdb('inv_penjualan_service','nm_pelanggan','nm_pelanggan',"where no_trans='".$this->no_trans."' and tgl_service='".$this->tgl."'");
		$alm =rdb('inv_penjualan_service','alm_pelanggan','alm_pelanggan',"where no_trans='".$this->no_trans."' and tgl_service='".$this->tgl."'");
		$alm .=' '.rdb('inv_penjualan_service','tlp_pelanggan','tlp_pelanggan',"where no_trans='".$this->no_trans."' and tgl_service='".$this->tgl."'");
		$kasir=$this->session->userdata('username');
		$isine	=array(
					$coy.newline(),
					strtoupper($address).sepasi((79-strlen($address)-strlen($slip))).$slip.newline(),
					'Tanggal :'.date('d-m-Y H:i').sepasi((79-strlen('Tanggal :'.date('d-m-Y H:i'))-strlen('Kepada Yth,'))).'Kepada Yth,'.newline(),
					'Kasir : '.$kasir.sepasi((79-strlen($nama)-strlen('Kasir : '.$kasir))).$nama.newline(),
					'Nota  : '.$no_faktur.sepasi((79-strlen($alm)-strlen('Nota  : '.$no_faktur))).$alm.newline(),
					str_repeat('-',79).newline(),
					'| No.|'.sepasi(((32-strlen('Nama Barang'))/2)).'Nama Barang'.sepasi(((32-strlen('Nama Barang'))/2)).
					'|'.sepasi(((10-strlen('Banyaknya'))/2)).'Banyaknya'.sepasi((((10-strlen('Banyaknya'))/2)-1)).'|'.
					sepasi(((14-strlen('Harga Satuan'))/2)).'Harga Satuan'.sepasi((((14-strlen('Harga Satuan'))/2)-1)).'|'.
					sepasi(((18-strlen('Total Harga'))/2)).'Total Harga'.sepasi((((17-strlen('Total Harga'))/2)-1)).'|'.newline(),
					str_repeat('-',79).newline(),
					$this->isi_slip(),
					$this->struk_data_footer()
					);
		return $isine;			
	}
	function isi_slip(){
		$data=array();$content="";$n=0;
		$data=$this->kasir_model->get_trans_service($this->no_trans,$this->tgl);
		 foreach($data as $row){
			 $n++;
			$ket_service=$row->ket_service;
			$masalah=$row->masalah_service;
			$baris_masalah=(strlen($masalah)/31);
			$content .=sepasi(3).$n.sepasi(3).
					 ucwords(substr($row->nm_barang,0,31)).
					 sepasi((30-strlen(substr($row->nm_barang,0,31)))).
					 sepasi(7).'1'.sepasi(1).'Unit'.
					 sepasi(14).''.
					 sepasi(16).''.newline(3);
			if($masalah!=''){
			$content .='Masalah/Kerusakan :'.newline();
					$content .=sepasi(3).ucwords(substr($masalah,0,60)).newline();	
					$content .=sepasi(3).(strlen($masalah)>60)?ucwords(substr($masalah,60,60)).newline():'';	
					$content .=sepasi(3).(strlen($masalah)>120)?ucwords(substr($masalah,120,60)).newline(1):newline(1);	
			}
/**/		if($ket_service!=''){
				$content.='Kelengkapan :'.newline();
				$content.=sepasi(3).substr($ket_service,0,60).newline();
				$content.=sepasi(3).(strlen($ket_service)>60)?substr($ket_service,60,60).newline():'';
				$content.=sepasi(3).(strlen($ket_service)>120)?substr($ket_service,120,60).newline(1):newline(1);
			}
		 }
		 return $content;
		 
	}
	function struk_data_footer(){
		$bawah='';
		$tgl=$this->tgl;
		$nfile	='asset/bin/zetro_config.dll';
		$gudang	=rdb('user_lokasi','lokasi','lokasi',"where id='".$this->lokasi."'");
		$phone	=$this->zetro_manager->rContent($gudang,'Telp',$nfile);
		$bawah=str_repeat('-',79).newline().
				'*** Terima Kasih ***'.sepasi((61-strlen('*** Terima Kasih ***')-strlen('Jumlah RP. :'))).'Jumlah Rp. :'.newline().
				'INFORMASI '.$phone.sepasi((61-strlen('INFORMASI '.$phone)-strlen('Tunai (DP) :'))).'Tunai (DP) :'.newline().
				'Doc. No.'.$this->no_trans.' '.tglfromSql($tgl).sepasi((61-strlen('Doc. No.'.$this->no_trans.' '.tglfromSql($tgl))-strlen('Kembali Rp. :'))).'Kembali Rp. :'.newline().
				str_repeat('-',79).newline().
				'***Barang yang lebih dari 1 bulan tidak diambil dianggap hangus***'.newline().
				'Penerima                Gudang                Hormat Kami'.newline(1);
		return $bawah;
	}
//end of Print_slip class
}?>