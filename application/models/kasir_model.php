<?php
// Fronoffice model

class Kasir_model extends CI_Model {
	
	function __construct(){
		parent::__construct();
	}	
	function get_trans_jual($no_trans,$tanggal){
		$sql="select dt.*,b.*,bs.* from inv_penjualan as p
			 left join inv_penjualan_detail as dt
			 on dt.ID_Jual=p.ID
			 left join inv_barang as b
			 on b.ID=dt.ID_Barang
			 left join inv_barang_satuan as bs
			 on bs.ID=b.ID_Satuan
			 where p.NoUrut='$no_trans' /*and p.Tanggal='$tanggal'*/ and dt.ID_Barang<>'' order by dt.ID";
		//echo $sql;
		$data=$this->db->query($sql);
		return $data->result();
	}
	function get_tahun($bln=false,$tbl='pembelian'){
		$sql=($bln==false)?
		"select distinct(Tahun) as Tahun from inv_$tbl order by Tahun":
		"select distinct(month(Tanggal)) as Bulan from inv_$tbl order by month(Tanggal)";
		$data=$this->db->query($sql);
		return $data->result();
	}
	function rekap_trans_beli($where,$group='',$order='order by p.Tanggal'){
		$sql="select p.ID_Pemasok,p.Tanggal,sum(dt.Jumlah*dt.Harga_Beli) as Harga_Beli,p.Nomor,
			 a.Nama,pj.Jenis_Beli,a.Catatan,a.Alamat,a.Kota,a.NoUrut
			 from inv_pembelian as p
			 left join inv_pembelian_detail as dt
			 on dt.ID_Beli=p.ID
			 left join mst_anggota as a
			 on a.ID=p.ID_Pemasok 
			 left join inv_pembelian_jenis as pj
			 on pj.ID=p.ID_Jenis
			 $where $group $order";
			//echo $sql;//debug only
		$data=$this->db->query($sql);
		return $data->result();
	}
	function detail_trans_beli($where,$group='',$order='order by p.Tanggal'){
		$sql="select p.Tanggal,dt.ID_Barang,dt.Jumlah,dt.Harga_Beli,b.Nama_Barang,s.Satuan,
			 a.Nama,p.Nomor,j.Jenis_Beli,a.Catatan,a.Alamat,a.Kota,a.NoUrut
			 from inv_pembelian as p
		     left join inv_pembelian_detail as dt
			 on dt.ID_Beli=p.ID
			 right join inv_barang as b
			 on b.ID=dt.ID_Barang
			 left join inv_barang_satuan as s
			 on s.ID=b.ID_Satuan
			 left join mst_anggota as a
			 on a.ID=p.ID_Pemasok
			 left join inv_pembelian_jenis as j
			 on j.ID=p.ID_Jenis
			 $where $group $order";
		//echo $sql;
		$data=$this->db->query($sql);
		return $data->result();
	}
	function rekap_trans_jual($where,$group='',$order='order by p.Tanggal'){
		$sql2="select p.Tanggal,p.ID_Anggota,dt.ID_Barang,sum(Jumlah) as Jumlah,dt.Harga,p.Deskripsi as Nama,
			 a.Alamat,a.Kota,a.Catatan,p.ID_Jenis,p.NoUrut,p.Tahun
			 from inv_penjualan as p
		     left join inv_penjualan_detail as dt
			 on dt.ID_Jual=p.ID
			 right join inv_barang as b
			 on b.ID=dt.ID_Barang
			 left join mst_anggota as a
			 on a.ID=p.ID_Anggota";
        $sql="select p.Tanggal,p.ID_Anggota,dt.ID_Barang,
            case when p.ID_Jenis!=5 then sum(Jumlah) else 0 end as Jumlah,
            case when p.ID_Jenis=5 then sum(jumlah) else 0 end as Retur,
            case when p.ID_Jenis!=5 then sum(Jumlah)*harga else 0 end as HargaJual,
            case when p.ID_Jenis=5 then sum(jumlah) * harga else 0 end as HargaRetur,
            dt.Harga,p.Deskripsi as Nama,
            a.Alamat,a.Kota,a.Catatan,p.ID_Jenis,p.NoUrut,p.Tahun
            from inv_penjualan as p
            left join inv_penjualan_detail as dt on dt.ID_Jual=p.ID
            right join inv_barang as b on b.ID=dt.ID_Barang
            left join mst_anggota as a on a.ID=p.ID_Anggota
			 $where $group $order";
		//echo $sql;
		$data=$this->db->query($sql);
		return $data->result();
	}
	function detail_trans_jual($where,$group='',$order='order by p.Tanggal'){
		$sql="select dt.ID_Jual,p.Tanggal,dt.ID_Barang,b.Kode,dt.Jumlah,dt.Harga,b.Nama_Barang,s.Satuan,
			 p.Deskripsi as Nama,p.NoUrut as Nomor,j.Jenis_Jual,a.Catatan,a.Alamat,a.Kota,p.Tgl_Cicilan,p.ID_Post,
			 p.Deskripsi,p.ID_Anggota,p.ID_Jenis,p.NoUrut,p.Tahun
			 from inv_penjualan as p
		     left join inv_penjualan_detail as dt
			 on dt.ID_Jual=p.ID
			 right join inv_barang as b
			 on b.ID=dt.ID_Barang
			 left join inv_barang_satuan as s
			 on s.ID=b.ID_Satuan
			 left join mst_anggota as a
			 on a.ID=p.ID_Anggota
			 left join inv_penjualan_jenis as j
			 on j.ID=p.ID_Jenis
			 $where $group $order";
		//echo $sql."\n";
		$data=$this->db->query($sql);
		return $data->result();
	}
	function rekap_kreditur($where,$group='',$order=''){
		$sql="select a.Nama,a.ID_Dept,dt.ID_Barang,sum(Jumlah),dt.Harga,
			 sum(Jumlah*harga) as Total,p.Cicilan
			 from inv_penjualan as p
		     left join inv_penjualan_detail as dt
			 on dt.ID_Jual=p.ID
			 right join inv_barang as b
			 on b.ID=dt.ID_Barang
			 left join mst_anggota as a
			 on a.ID=p.ID_Anggota
			 $where $group $order";
		//echo $sql;
		$data=$this->db->query($sql);
		return $data->result();
	}
	function edit_pemakaian($where){
		$sql="select zp.*,b.Nama_Barang,s.Satuan,b.Kode
			  from z_inv_pemakaian as zp
			  left join inv_barang as b
			  on b.ID=zp.ID_Barang
			  left join inv_barang_satuan as s
			  on s.ID=b.ID_Satuan
			  $where";	
		$data=$this->db->query($sql);
		return $data->result();
	}
	function totaldata($where){
		$sql="select * from inv_penjualan  where ID_Jenis!=3 $where";
		$rs=mysql_query($sql) or die(mysql_error());
		return mysql_num_rows($rs);	
	}
    function totaldatax($where){
		$sql="select * from inv_penjualan as p  where p.ID_Jenis!=3 $where";
		$rs=mysql_query($sql) or die(mysql_error());
		return mysql_num_rows($rs);	
	}
	function kas_masuk($where,$orderby='order by p.ID_Jenis,p.Tanggal'){
		$sql="select p.ID_Jenis,sum(total) as tHarga,p.Tanggal,p.Nomor,p.NoUrut,
			p.Deskripsi,p.Tgl_Cicilan,p.ID_Post
			from inv_penjualan as p 
			$where
			group by p.id_jenis,p.Tanggal
			$orderby ";
		$data=$this->db->query($sql);
		return $data->result();
	}
	function laba_rugi($where,$orderby=''){
		$sql2="select dt.ID_Barang,sum(dt.Jumlah)as jml,sum(dt.jumlah*dt.harga) as Jual,
			sum(dt.Jumlah*dt.batch) as Harga_Beli,b.harga_beli,dt.Tanggal,dt.batch 
			from inv_penjualan_detail as dt
			left join inv_penjualan as p
			on p.ID=dt.ID_Jual
			left join inv_material_stok as b
			on b.id_barang=dt.ID_Barang
			$where
			group by concat(dt.Tanggal)
			$orderby";
		
        $sql="select sum(Margin) as Margin,Tanggal from(
              select *,(Jumlah*Harga)Jual,(Jumlah*Modal) as Beli,((Jumlah*Harga)-(Jumlah*Modal))as Margin,
              (select Satuan from inv_barang_satuan where ID=ID_Satuan)Satuan from(
              select Tanggal,ID_Barang,(select Nama_Barang from inv_barang where ID=pd.ID_Barang)NamaBarang, 
              sum(Jumlah)Jumlah,max(Cast(pd.Harga as decimal)) as Harga,Cast(batch as decimal) as Modal,ID_Satuan,
              (select ID_Kategori from inv_barang where ID=pd.ID_Barang)Kat
              from inv_penjualan_detail as pd
              where ID_Barang >0 ".$where. "
              group by Tanggal,Id_Barang,pd.harga,batch) as x where x.NamaBarang !='')
              as xx $orderby";
        //echo $sql;
		$data=$this->db->query($sql);
		return $data->result();
	}
	function get_kreditur($where,$orderby=''){
		$sql="select p.ID,p.ID_Anggota,b.Nama,b.NIP,c.Departemen,d.Keaktifan	
			  from inv_penjualan as p
			  left join mst_anggota as b
			  on b.ID=p.ID_Anggota
			  left join mst_departemen as c
			  on c.ID=b.ID_dept
			  left join keaktifan as d
			  on d.ID=b.ID_Aktif
			  $where
			  $orderby";
			echo $sql;
		$data=$this->db->query($sql);
		return $data->result();
	}
	
	function get_trans_jual_kreditur($ID){
		$sql="select p.Tanggal,p.Nomor,b.Nama_Barang,bs.Satuan,
			  pd.Jumlah,pd.Harga
			  from inv_penjualan as p
			  left join inv_penjualan_detail as pd
			  on pd.ID_jual=p.ID
			  left join inv_barang as b
			  on b.ID=pd.ID_Barang
			  left join inv_barang_satuan as bs
			  on bs.ID=b.ID_Satuan
			  where p.ID_Anggota='$ID' and p.ID_Jenis in('2','3') and pd.Jumlah <>'0'
			  order by p.Tanggal";	
			  echo $sql;
		$data=$this->db->query($sql);
		return $data->result();
	}
	function get_trans_jurnal($ID){
		$sql="select t.*,n.Nama,j.Nomor,j.Tanggal from perkiraan as p
				left join transaksi as t
				on t.ID_Perkiraan=p.ID
				left join jurnal as j
				on j.ID=t.ID_Jurnal
				left join mst_anggota as n
				on n.ID=p.ID_Agt
				where p.ID_Agt='$ID' and p.ID_Simpanan='4'
				order by j.Tanggal";
		$data=$this->db->query($sql);
		return $data->result();
	}
	function get_kas_awal($where){
		$sql="select * from mst_kas_harian $where order by tgl_kas";
		$data=$this->db->query($sql);
		return $data->result();
	
	}
	function operasional($where,$orderby,$groupby=''){
		$sql="select * from mst_kas_trans $where $groupby $orderby";
		$data=$this->db->query($sql);
		return $data->result();		
	}
	function get_trans_service($ntran,$tgl)
	{
		$data=array();
		$sql="select * from inv_penjualan_service where no_trans='".$ntran."' and tgl_service='".$tgl."'";
		$data=$this->db->query($sql);
		return $data->result();
	}
    /**
    Margain Penjualan
    added on 13-09-2015
    */
    function GetMatginJual($where,$orderby="")
    {
        $data=array();
        $sql="select *,(Jumlah*Harga)Jual,(Jumlah*Modal) as Beli,((Jumlah*Harga)-(Jumlah*Modal))as Margin,
              (select Satuan from inv_barang_satuan where ID=ID_Satuan)Satuan from(
              select Tanggal,ID_Barang,(select Nama_Barang from inv_barang where ID=pd.ID_Barang)NamaBarang, 
              sum(Jumlah)Jumlah,max(Cast(Harga as decimal)) as Harga,Cast(batch as decimal) as Modal,ID_Satuan,
              (select ID_Kategori from inv_barang where ID=pd.ID_Barang)Kat
              from inv_penjualan_detail as pd
              where ID_Barang >0 and ID_Jenis!='5' ".$where. "
              group by Tanggal,Id_Barang,harga,batch) as x where x.NamaBarang !='' $orderby";
        //echo $sql;
        $data=$this->db->query($sql);
        return $data->result();
    }
    function GetListDO($where="",$orderby="")
    {
        $data=array();
        $sql="Select do.id, (select userid from users where LokasiToko=do.lokasi limit 1) as Usr,do.notrans,do.nm_pelanggan,do.lokasi 
              from inv_penjualan_do as do where (printed='N' or printed='I')  $where";
        $data=$this->db->query($sql);
        return $data->result();
    }
    function GetTransForSJ($where="",$orderby="")
    {
        $data=array();
        $sql="Select distinct notrans from inv_penjualan_do where status_kirim='P' ".$where." ".$orderby;
        //echo $sql;//dbug only
        $data=$this->db->query($sql);
        return $data->result();
    }
}