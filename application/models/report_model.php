<?php class Report_model extends CI_Model {
    function  __construct() 
	{
        parent::__construct();
    }

	function countsheet($where){
		$sql="select im.nm_jenis as nm_jenis,
					  im.nm_kategori as nm_kategori,
					  im.id_barang as id_barang,
					  im.nm_barang as nm_barang,
					  im.nm_satuan as nm_satuan,
					  ims.stock as stock,
					  ims.blokstok as blokstok 
			from inv_barang as im
			left join inv_material_stok as ims
			on im.nm_barang=ims.nm_barang
			$where order by im.nm_barang";
		return $this->db->query($sql);
	}
	function select_trans($where,$rpt='No'){
		$sql="select dt.*,dt.expired as expired,im.id_barang from detail_transaksi as dt 
			 	left join inv_material as im
				on im.nm_barang=dt.nm_barang
				$where";
		//echo $sql;
		
		return ($rpt=='No')?$sql:$this->db->query($sql);
	}
	function stock_list($where,$tipe,$orderby='order by im.Nama_Barang',$pos='left'){
		switch($tipe){
			case "stock":	
			$sql="select im.ID_Kategori,im.ID,im.ID_Satuan,im.Nama_Barang,im.Kode,
				  sum(ms.stock) as stock,im.Status,s.Satuan,k.Kategori,
				  sum(ms.harga_beli) as harga_beli,ms.batch,ms.id_lokasi
				  from inv_barang as im
				  $pos join inv_material_stok as ms
				  on ms.id_barang=im.ID
				  left join inv_barang_satuan as s
				  on s.ID=im.ID_Satuan
				  left join inv_barang_kategori as k
				  on k.ID=im.ID_Kategori
				  $where 
				  group by im.ID
				  $orderby";
			break;
			case "edit":
			$sql="select im.ID,im.Nama_Barang,im.Kode,im.Status,
				  sum(ms.stock) as stock,ms.harga_beli,ms.batch,
				  k.Kategori,s.Satuan,ms.id_lokasi
				  from inv_barang as im
				  left join inv_material_stok as ms
				  on ms.id_barang=im.ID
				  left join inv_barang_satuan as s
				  on s.ID=im.ID_Satuan
				  left join inv_barang_kategori as k
				  on k.ID=im.ID_Kategori
				  $where
				  group by im.ID,ms.batch
				  $orderby";
			break;
			case 'stocklimit':
			$sql="select im.Kode,im.Nama_Barang,im.ID,im.ID_Satuan,
					sum(ms.Stock) as stock,im.minstok
					from inv_material_stok as ms
					right join inv_barang  as im
					on ms.id_barang=im.ID
					left join inv_barang_satuan as s
				    on s.ID=im.ID_Satuan
				    left join inv_barang_kategori as k
				    on k.ID=im.ID_Kategori
					/*where (ms.stock) <= b.minstok*/ $where
					group by im.ID
					$orderby";
			break;
			case 'lap_kas':
			$sql="select * from ".$this->session->userdata('userid')."_tmp_lapkas $where $orderby";
			break;
		}
		//echo $sql;
		$data=$this->db->query($sql);	
		return $data->result();
	}
	
	function create_tmp_table(){
		$sql="CREATE TABLE IF NOT EXISTS `".$this->session->userdata('userid')."_tmp_lapkas` (
				`id` INT(10) NULL AUTO_INCREMENT,
				`tgl` DATE NULL,
				`uraian` VARCHAR(50) NULL,
				`no_trans` VARCHAR(50) NULL,
				`kredit` DOUBLE NULL,
				`debit` DOUBLE NULL,
				`saldoakhir` DOUBLE NULL,
				`doc_date` TIMESTAMP NULL,
				PRIMARY KEY (`id`)
				)
				COLLATE='latin1_swedish_ci'
				ENGINE=MyISAM;";
			mysql_query($sql) or die(mysql_error());
			mysql_query("truncate table ".$this->session->userdata('userid')."_tmp_lapkas") or die(mysql_error());
	}
	function copy_to_tmp_table($dari,$totgl){
		$sal="select * from mst_kas_harian where tgl_kas between '$dari' and '$totgl'";
		$rr=mysql_query($sal) or die(mysql_error());
		while($rw=mysql_fetch_object($rr)){
			$kas="insert into ".$this->session->userdata('userid')."_tmp_lapkas (tgl,uraian,kredit,doc_date) values('".$rw->tgl_kas."','Saldo awal kas','".$rw->sa_kas."','".$rw->doc_date."')";
				  mysql_query($kas);	
		}
		$sql="select * from detail_transaksi where tgl_transaksi between '$dari' and '$totgl' order by tgl_transaksi";
			  $rs=mysql_query($sql) or die(mysql_error());
			  while($row=mysql_fetch_object($rs)){
				  if($row->jenis_transaksi=='GI' || $row->jenis_transaksi=='K' ||$row->jenis_transaksi=='DR'){
					  $kredit=($row->jml_transaksi*$row->harga_beli);
					  ($row->jenis_transaksi=='K')?$uraian=$row->ket_transaksi:$uraian='Penjualan ';
						$kas1="insert into ".$this->session->userdata('userid')."_tmp_lapkas (tgl,uraian,no_trans,kredit,doc_date) values('".$row->tgl_transaksi."','".$uraian."','".$row->no_transaksi."','".$kredit."','".$row->doc_date."')";
							  mysql_query($kas1);	
				  }
				  //$row->jenis_transaksi=='GR' || $row->jenis_transaksi=='GRR' ||
				  if( $row->jenis_transaksi=='D' || $row->jenis_transaksi=='GIR'){
					  $debit=($row->jml_transaksi*$row->harga_beli);
					  ($row->jenis_transaksi=='D')?$uraian2=$row->ket_transaksi:$uraian2='Pembelian';//$row->nm_barang;
						$kas2="insert into ".$this->session->userdata('userid')."_tmp_lapkas (tgl,uraian,no_trans,debit,doc_date) values('".$row->tgl_transaksi."','".$uraian2."','".$row->no_transaksi."','".$debit."','".$row->doc_date."')";
							  mysql_query($kas2);	
				  }
			  }
	   //echo $kas2;
	}
	function laporan_kas($where='',$orderby='order by doc_date'){
	 $sql="select * from ".$this->session->userdata('userid')."_tmp_lapkas $where $orderby";	
		//echo $sql;
		return $this->db->query($sql);	
	}
	function get_no_trans($where){
			$sql="select p.NoUrut,a.Nama,a.Alamat,p.Tanggal from inv_penjualan as p
				INNER join mst_anggota as a
				on a.ID=p.ID_Anggota
			   $where order by p.ID desc";
			  // echo $sql;
			$data=$this->db->query($sql);	
			return $data->result();
			
	}
	function laporan_faktur($no_trans){
		$sql="select b.Nama_Barang,s.Satuan,pd.Jumlah,pd.Harga
			 from inv_penjualan as p
			 left join inv_penjualan_detail as pd
			 on pd.ID_Jual=p.ID
			 left join inv_barang as b
			 on b.ID=pd.ID_Barang
			 left join inv_barang_satuan as s
			 on s.ID=pd.ID_Satuan
			 where p.NoUrut='$no_trans' and p.Tahun='".date('Y')."' and pd.ID_Barang<>'0' order by pd.ID";
		return $this->db->query($sql);	
	}
	function lap_cash_flow(){
		$sql="select * from kas order by ID";
			$data=$this->db->query($sql);	
			return $data->result();
	}
	function lap_sub_cash($id){
		$sql="select * from kas_sub where ID_Kas='$id'";
			$data=$this->db->query($sql);	
			return $data->result();
	}
	function get_cash_flow($ID_CC,$dari,$sampai='',$lokasi=''){
		$sd=($sampai=='')?$dari:$sampai;
		$sql="select k.ID_Calc,t.ID_CC,sum(Jumlah) as Total 
			from transaksi_cashflow as t
			left join kas_sub as k
			on k.ID_CC=t.ID_CC
			where t.Tanggal between '".tglToSql($dari)."' and '".tglToSql($sd)."'
			and t.ID_CC='$ID_CC' $lokasi
			group by t.ID_CC";
			//echo $sql;
			$data=$this->db->query($sql);	
			return $data->result();
		
	}
    function LaporanLPG($where,$orderby='',$field="*")
    {
        $fld=($field=='*')?"*":$field;
        $sql="select $fld 
             from inv_pembelian_lpg as lpg
             $where $orderby";
        //echo $sql."<br>";// debug only
        $data=$this->db->query($sql);	
        return $data->result();
    }
	function SaldoAwalLPG($bulan,$Tahun,$ID_Barang)
	{
		$saldo=0;
		$sql="select ID_Barang,(SA+Beli-Jual)Saldo From(
			select ID_Barang,sum(beli)Beli,Sum(Jual)Jual,(sum(beli)-Sum(Jual))Saldo,(select Jumlah from SaldoAwalLPG 
			where id_barang=inv_transaksi_lpg_rekap.ID_Barang)SA 
			from inv_transaksi_lpg_rekap where month(Tanggal)<$bulan and Year(Tanggal)<=$Tahun 
			and id_barang in(select ID from inv_barang where status='LPG')
			group by ID_Barang
			) as x
			where ID_Barang='".$ID_Barang."' 
			order by id_barang";
		$data=$this->db->query($sql);	
        foreach($data->result() as $r)
		{
			$saldo= $r->Saldo;
		}
		return $saldo;
	}
    function CekDO($lokasi)
    {
        $sql="select * from inv_penjualan_DO where ID_Jual>0 and Lokasi='".$lokasi."' and (/*Printed='N' OR*/ Status_kirim='N') Order By ID";
        $data=$this->db->query($sql);
        return $data->result();
    }
    function CekBeli($lokasi)
    {
        $sql="Select id,id_barang,Jumlah,Harga_Beli,ID_Satuan from inv_pembelian_detail where posting=0 and lokasi='$lokasi'";
         $data=$this->db->query($sql);
        return $data->result();
    }
    function CheckHarga()
    {
        $data=array();
        $sql="select * from inv_barang where posting=0";
        $data=$this->db->query($sql);
        return $data->result();
    }
    function CheckDOForPrint($lokasi)
    {
        $sql="select notrans from inv_penjualan_DO where ID_Jual>0 and harga=0 and lokasi='".$lokasi."' group by notrans  Order By ID";
        $data=$this->db->query($sql);
        $notrans="";
        foreach($data->result() as $r)
        {
            $notrans .=$r->notrans."','";
        }
        return $notrans=substr($notrans,0,(strlen($notrans)-3));
    }
}