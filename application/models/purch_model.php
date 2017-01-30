<?php
// Inventori model

class Purch_model extends CI_Model {
	
	public $user='';
	function __construct(){
		parent::__construct();
		$this->user=$this->session->userdata('userid');
	}

	function  get_pemasok($str,$limit){
		$data=array();
		$sql="select * from mst_anggota where Nama like '".$str."%' and ID_Jenis='2' order by Nama limit $limit";
		$rs=mysql_query($sql) or die(mysql_error());
		while($row=mysql_fetch_object($rs)){
				$data[]=array('data'		=>$row->Nama,
							  'description' =>$row->Alamat." ".$row->Kota." ".$row->Propinsi,
							  'id_pemasok'	=>$row->ID
							  );
		}
		return $data;
	}
	
	function get_material_kode($kode){
		$data=array();
		$sql="select * from inv_barang where Kode='$kode' or ID_Pemasok='$kode'";
		$data=$this->db->query($sql);
		return $data->result();
	}
	
	function get_satuan_konv($nm_barang){
		$data=array();
		$sql="select ik.sat_beli,ik.isi_konversi,n.Satuan,b.ID from inv_konversi as ik 
			  left join inv_barang_satuan as n
			  on n.ID=ik.sat_beli
			  left join inv_barang as b
			  on b.nama_barang=ik.nm_barang
			  where nm_barang='$nm_barang'";//ACER AOD 257E-HAPPY2 1GB
		$data=$this->db->query($sql);
		return $data->result();
	}
	function get_total_belanja($notrans,$tanggal){
		$data=array();
		$sql="select sum(Jml_faktur*Harga_Beli) as total from inv_pembelian as p
			 left join inv_pembelian_detail as pd
			 on pd.ID_Beli=p.ID
			 where p.NoUrut='$notrans' and p.Tanggal='".tgltoSql($tanggal)."'";
		$data=$this->db->query($sql);
		return $data->result();
	}
    function get_total_spp($notrans)
    {
        $data=array();
        $sql="select Sum(jumlah*harga) as totalharga from inv_belanja where notrans='".$notrans."'";
        $data=$this->db->query($sql);
		return $data->result();
    }
	function detail_trans_vendor($where){
		$sql="select b.Nama_Barang,bs.Satuan,b.Kode,pd.Jumlah,pd.Harga_Beli,p.Tanggal,p.Nomor from
			  inv_pembelian as p
			  left join inv_pembelian_detail as pd
			  on pd.ID_Beli=p.ID
			  left join inv_barang as b
			  on b.ID=pd.ID_Barang
			  left join inv_barang_satuan as bs
			  on bs.ID=b.ID_Satuan
			  $where";
			  
		$data=$this->db->query($sql);
		return $data->result();
	}
	
	function pembelian_graph($thn){
		$xml=fopen($this->user.'_graph_p.xml','w+');
		fwrite($xml,"<graph caption='Grafik Pembelian' subcaption='Tahun : ".$thn."' xAxisName='Bulan' yAxisName='Value' showValues= '1' showLabels='1' showValues='1'>\r\n");
		$trr=mysql_query("select distinct(month(Tanggal)) as Bulan from inv_pembelian where year(Tanggal)='".$thn."' order by month(Tanggal)");
		while($th=mysql_fetch_object($trr)){
		$sql="select(sum(Jumlah*Harga_Beli)) as Total from inv_pembelian_detail where month(Tanggal)='".$th->Bulan."' and year(Tanggal)='$thn' group by month(Tanggal) order by month(Tanggal)";
			$rs=mysql_query($sql) or die($sql.mysql_error());
			while($rw=mysql_fetch_object($rs)){
				fwrite($xml,"<set name='".nBulan($th->Bulan)."' value='".$rw->Total."'/>\r\n");
			}
		}
		fwrite($xml,"</graph>\r\n");
	}
	
	function penjualan_graph($thn,$bln){
		$t_days=cal_days_in_month(CAL_GREGORIAN, $bln, $thn);
		$xml=fopen($this->user.'_graph_j.xml','w+');
		fwrite($xml,"<graph caption='Grafik Penjualan' subcaption='Periode :".nBulan($bln)." ".$thn."' xAxisName='Bulan' yAxisName='Value' showValues= '0' showLabels='1' showValues='2'>\r\n");
		//create category by jenis penjuallan ( tunai,giro,cheque,Kredit,return
		
		$cat="select Jenis_Jual,ID from inv_penjualan_jenis order by ID";
		$rcat=mysql_query($cat) or die(mysql_error());
		fwrite($xml,"<categories>\r\n");
		//while($rw=mysql_fetch_object($rcat)){
		for($i=1;$i<=$t_days;$i++){
			fwrite($xml,"<category name='".$i."'/>\r\n");
		}
		fwrite($xml,"</categories>\r\n");
		$color=array('','1D8BD1','F1683C','2AD62A','DBDC25','D2DCDD');
		$lok="select * from user_lokasi order by ID";
		$r_lok=mysql_query($lok) or die(mysql_error());$ul=0;
		while($w_lok=mysql_fetch_object($r_lok)){
			$ul++;
			fwrite($xml,"<dataset seriesName='".$w_lok->lokasi."' color='".$color[$ul]."'>\r\n");
			for($x=1;$x<=$t_days;$x++){
				$ii=(strlen($x)==1)?"0".$x:$x;
				$val=rdb('inv_penjualan_view','Total',"sum(jumlah*harga) as Total","where ID_Lokasi='".$w_lok->ID."' and Tanggal='".$thn."-".$bln."-".$ii."' order by Tanggal");	
				$val=($val=='')?'0':$val;
				fwrite($xml,"<set name='".$ii."'  value='".$val."'/>\r\n");
			$ii='';
			}
		   fwrite($xml,"</dataset>\r\n");
		   
		}
		fwrite($xml,"</graph>\r\n");

	}
	
	function cash_graph($thn,$bln){
		$t_days=cal_days_in_month(CAL_GREGORIAN, $bln, $thn);
		$xml=fopen($this->user.'_graph_cashflow.xml','w');
		fwrite($xml,"<graph caption='Grafik Aliran Kas' subcaption='Periode :".nBulan($bln)." ".$thn."' xAxisName='Bulan' yAxisName='Value' showValues= '1' showLabels='1' showValues='2'>\r\n");
		//create category by jenis penjuallan ( tunai,giro,cheque,Kredit,return
		fwrite($xml,"<categories>\r\n");
		//while($rw=mysql_fetch_object($rcat)){
		for($i=1;$i<=$t_days;$i++){
			fwrite($xml,"<category name='".$i."'/>\r\n");
		}
		fwrite($xml,"</categories>\r\n");
		$color=array('','1D8BD1','F1683C','2AD62A','DBDC25','D2DCDD');
		$lok="select distinct ID_Unit,lokasi from transaksi_cashflow as t
			  left join user_lokasi as ul
			  on ul.ID=t.ID_Unit order by ID_Unit";
		$r_lok=mysql_query($lok) or die(mysql_error());$ul=0;
		while($w_lok=mysql_fetch_object($r_lok)){
			$ul++;
			fwrite($xml,"<dataset seriesName='".$w_lok->lokasi."' color='".$color[$ul]."'>\r\n");
			for($x=1;$x<=$t_days;$x++){
				$valu='';
				$ii=(strlen($x)==1)?'0'.$x:$x;$val=0;
				
				$cat1="select t.Tanggal,k.ID_Calc,t.ID_CC,sum(Jumlah) as Total 
					from transaksi_cashflow as t
					left join kas_sub as k
					on k.ID_CC=t.ID_CC
					where t.Tanggal='".$thn."-".$bln."-".$ii."' and ID_Unit='".$w_lok->ID_Unit."'
					group by Tanggal";
				echo $cat1;
				$val=rdb('mst_kas_harian','sa_kas',"sa_kas","where tgl_kas='".$thn."-".$bln."-".$ii."' and id_lokasi='".$w_lok->ID_Unit."' order by tgl_kas");	
				$rcat1=mysql_query($cat1) or die(mysql_error());
				while($rw1=mysql_fetch_object($rcat1)){
						$valu=($val=='' && $rw1->Total=='')? '':($rw1->Total+$val);
					}
						fwrite($xml,"<set name='".$ii."'  value='".$valu."'/>\r\n");
			//   
			}
		fwrite($xml,"</dataset>\r\n");
		}
		fwrite($xml,"</graph>\r\n");

	}
	
	function laba_graph($thn,$bln){
		$t_days=cal_days_in_month(CAL_GREGORIAN, $bln, $thn);
		$xml=fopen($this->user.'_graph_labarugi.xml','w');
		fwrite($xml,"<graph caption='Grafik Laba Rugi' subcaption='Periode :".nBulan($bln)." ".$thn."' xAxisName='Bulan' yAxisName='Value' showValues= '1' showLabels='1' showValues='2'>\r\n");
		//create category by jenis penjuallan ( tunai,giro,cheque,Kredit,return
		fwrite($xml,"<categories>\r\n");
		//while($rw=mysql_fetch_object($rcat)){
		for($i=1;$i<=$t_days;$i++){
			fwrite($xml,"<category name='".$i."'/>\r\n");
		}
		fwrite($xml,"</categories>\r\n");
		$color=array('','1D8BD1','F1683C','2AD62A','DBDC25','D2DCDD');
		$lok="select distinct ID_Unit,lokasi from transaksi_cashflow as t
			  left join user_lokasi as ul
			  on ul.ID=t.ID_Unit order by ID_Unit";
		$r_lok=mysql_query($lok) or die(mysql_error());$ul=0;
		while($w_lok=mysql_fetch_object($r_lok)){
			$ul++;
			fwrite($xml,"<dataset seriesName='".$w_lok->lokasi."' color='".$color[$ul]."'>\r\n");
			for($x=1;$x<=$t_days;$x++){
				$ii=(strlen($x)==1)?'0'.$x:$x;
				$val=0;$laba=0;
				
				$cat1="select sum(dt.jumlah*dt.harga) as Jual,sum(dt.Jumlah*dt.Batch) as Harga_Beli
						from inv_penjualan_detail as dt
						left join inv_penjualan as p
                        on p.id=dt.ID_Jual
						left join inv_material_stok as b
						on b.id_barang=dt.ID_Barang 
						where dt.Tanggal ='".$thn."-".$bln."-".$ii."' and dt.ID_Jenis!='5'
						and p.ID_Close='".$w_lok->ID_Unit."'
						group by concat(dt.Tanggal)
						order by dt.Tanggal";
                //echo $cat1;
				$val=rdb('mst_kas_trans','jumlah',"sum(jumlah) as jumlah","where tgl_trans='".$thn."-".$bln."-".$ii."' and id_lokasi='".$w_lok->ID_Unit."' group by tgl_trans order by tgl_trans");	
				$rcat1=mysql_query($cat1) or die(mysql_error());
				while($rw1=mysql_fetch_object($rcat1)){
					$laba=($rw1->Jual-$rw1->Harga_Beli);
					}
						$val=($val=='' && $laba=='')? '':($laba-$val);
						fwrite($xml,"<set name='".$ii."'  value='".$val."'/>\r\n");
			}
			fwrite($xml,"</dataset>\r\n");
		}
		fwrite($xml,"</graph>\r\n");
	}
}