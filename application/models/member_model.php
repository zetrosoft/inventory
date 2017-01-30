<?php
//class Name	:member_model

class Member_model extends CI_Model{
	
	function __construct(){
		parent::__construct();	
	}
	
	function tabel($table){
		$this->tabels=$table;	
	}
	function field($query){
		$this->fields=$query;
	}
	function userid($userid){
		$this->userid=$userid;	
	}
	
	function nomor_anggota(){
		$nom=0;$data=array();
		$sql="select ID from mst_anggota order by ID desc limit 1";
		$data=$this->db->query($sql);
		foreach ($data->result() as $rst){
			$nom=$rst->ID;
		}
		//print_r($data->result());
		($nom==0||trim($nom)=='')?$nom=1:$nom=((int)$nom+1);
		/*if(strlen($nom)==1){
			$nom='000'.$nom;
		}else if(strlen($nom)==2){
			$nom='00'.$nom;
		}else if(strlen($nom)==3){
			$nom='0'.$nom;
		}else if(strlen($nom)==4){
			$nom=$nom;
		}*/
        $nom=str_repeat('0',(6-strlen($nom))).$nom;
		return $nom;
	}
	
	function get_anggota($str,$limit='10',$id_dept=''){
		$data=array();
		($id_dept==1)?$copy='_copy':$copy='';
			$sql="select * from mst_anggota$copy where (Nama like '%".$str."%' or Telepon like '".$str."%') and ID_Jenis='1' order by Nama limit $limit";	
			//echo $sql;
			$rw= mysql_query($sql) or die(mysql_error());
			while($row=mysql_fetch_object($rw)){
				$photo=explode("_",str_replace("\\","_",$row->PhotoLink));
					$data[]=array('data'		=>$row->Nama,
								  'NIP'			=>$row->NIP,
								  'ID'			=>$row->ID,
								  'NoUrut'		=>$row->NoUrut,
								  'ID_Dept'		=>$row->ID_Dept,
								  'PhotoLink'	=>$row->PhotoLink,
								  'No_Agt'		=>$row->No_Agt,
								  'Alamat'		=>$row->Alamat." ".$row->Kota,
								  'Telepon'		=>$row->Telepon,
								  'Thumbnail'	=>'../uploads/member/'.$row->PhotoLink,
								  'description'	=>'Telp :'.$row->Telepon."<br>".$row->Alamat." ".$row->Kota
								  );
			}
			return $data;
	}
	function get_kota($str){
		$data=array();
			$sql="select * from mst_kota where kota_anggota like '%".$str."%' order by kota_anggota";	
			//echo $sql;
			$rw= mysql_query($sql) or die(mysql_error());
			while($row=mysql_fetch_object($rw)){
					$data[]=array('data'=>$row->kota_anggota,'doc_date'=>$row->doc_date);
			}
			return $data;
	}

	function get_propinsi($str){
		$data=array();
			$sql="select * from mst_propinsi where prop_anggota like '%".$str."%' order by prop_anggota";	
			//echo $sql;
			$rw= mysql_query($sql) or die(mysql_error());
			while($row=mysql_fetch_object($rw)){
					$data[]=array('data'=>$row->prop_anggota,'doc_date'=>$row->doc_date);
			}
			return $data;
	}
	function show_field($table,$where="where comment <>''"){
		$sql="show full columns from $table $where";
		$rs=mysql_query($sql) or die(mysql_error());
		while($row=mysql_fetch_array($rs)){
			$data[]=array('key'=>$row['Field'],'value'=>$row['Comment']);
		}
		return $data;
	}
	function jenis_simpanan($id){
		$sql="select Jenis from jenis_simpanan where ID='$id'";
		$q=$this->db->query($sql);
		foreach($q->result() as $row){
			return $row->Jenis;
		}
	}
	function summary_member_data($member){
	$sql="select 
			concat(k.Kode,sk.Kode,d.Kode,agt.No_Perkiraan) as Kode,
			p.ID as id_perkiraan,js.id as ID,js.Jenis,p.SaldoAwal,
			sum(tr.debet) as Debet,sum(tr.Kredit) as Kredit,
			((saldoawal+sum(tr.Kredit))-sum(tr.Debet)) as SaldoAkhir
			from perkiraan as p
			left join klasifikasi as k
			on k.ID=p.ID_Klas
			left join sub_klasifikasi as sk
			on sk.ID=p.ID_SubKlas
			left join mst_departemen as d
			on d.ID=p.ID_Dept
			left join mst_anggota as agt
			on agt.ID=p.ID_Agt
			left join jenis_simpanan as js 
			on p.ID_Simpanan=js.ID
			left join transaksi as tr
			on p.ID=tr.ID_Perkiraan
			where js.jenis<>'' and p.ID_Agt='$member'
			group by js.jenis
			order by js.id";
		return $this->db->query($sql);
	}
	function detail_member_data($ID_member,$ID_Simpanan){
		$sql="select p.ID,j.Tanggal,j.Nomor,j.Keterangan,
				t.Debet,t.Kredit,t.Keterangan
				from perkiraan as p
				left join transaksi as t
				on t.ID_Perkiraan=p.ID
				left join jurnal as j
				on j.ID=t.ID_Jurnal
				where ID_Agt='$ID_member' and p.ID_Simpanan='$ID_Simpanan'";
		return $this->db->query($sql);
	}
	function biodata_member($ID_Agt){
		$sql="select * from mst_anggota where ID='".$ID_Agt."'";
		return $this->db->query($sql);
	}
	function copy_agt($ID,$JD=''){
		mysql_query("truncate table mst_anggota_copy");
		($JD=='')?
		$sql="Insert into mst_anggota_copy select * from mst_anggota where ID_Dept='$ID' and ID_Aktif='1'":
		$sql="insert into mst_anggota_copy select a.* from mst_anggota as a
				right join pinjaman as p
				on a.ID=p.ID_Agt and p.ID_Dept='$ID'
				where a.ID_Dept='$ID' and ID_Aktif='1'";
		//echo $sql;
		return $this->db->query($sql);
	}
	function jml_simpanan($id){
		$data='0';
		$sql="select min_simpanan from setup_simpanan where id_simpanan='$id'";
		$rs=mysql_query($sql) or die(mysql_error());
		while($row=mysql_fetch_object($rs)){
			$data=$row->min_simpanan;
		}
		return $data;
	}
	function agt_blmbayar($ID_Dept,$ID_Simpanan,$bln,$thn){
		$akun='';$trans='';
		($ID_Simpanan=='1')?$wherex='':$wherex="where j.ID_Bulan='$bln' and j.Tahun='$thn'";
		$sql1="select t.* from transaksi as t 
			   left join jurnal as j
			   on t.ID_Jurnal=j.ID
			   $wherex
			   group by t.ID_Perkiraan
			   order by t.ID_Perkiraan";
		$rs1=mysql_query($sql1) or die(mysql_error());
			while($row1=mysql_fetch_object($rs1)){
				$trans .="'".$row1->ID_Perkiraan."',";
			}
		($trans=='')?$where='':$where="p.ID not in(".substr($trans,0,-1).") and";
			$sql="select ID_Agt from perkiraan as p where $where p.ID_Dept='$ID_Dept' and ID_Simpanan='$ID_Simpanan'";
			$rs=mysql_query($sql) or die(mysql_error());
				while($row=mysql_fetch_object($rs)){
					$akun .="'".$row->ID_Agt."',";
				}
			($akun=='')?$where="where ID_Aktif='1'":$where=" where ID_Aktif='1' and ID in(".substr($akun,0,-1).") ";
			$sql2="select * from mst_anggota $where order by Nama";
			$data=$this->db->query($sql2);
			return $data->result();
	}
	function total_pinjaman($ID){
		$sql="select p.*,count(pb.ID_Agt) as jml  from pinjaman as p
			 left join pinjaman_bayar as pb
			 on pb.ID_Pinjaman=p.ID
			 where p.ID_Agt='$ID'";
		$rs=mysql_query($sql) or die(mysql_error());
		while($row=mysql_fetch_object($rs)){
			$data=array('pinjaman'=>$row->pinjaman,'cicilan'=>$row->cicilan,'cicilanke'=>$row->jml);		
		}
		return $data;
	}
	function data_total_pinjm($ID_Agt){
		$sql="select sum(debet)as debet,sum(kredit)as kredit,(sum(debet)-sum(kredit))as saldo
				from pinjaman as p
				left join pinjaman_bayar as b
				on b.ID_Pinjaman=p.ID
				where p.ID_Agt='".$ID_Agt."' 
				group by p.ID_Agt";	

		$data=$this->db->query($sql);
		return $data->result();
	}
	function data_pinjaman($ID_Agt){
		$data=array();
		$sql2="select p.*,sum(Kredit) as Kredit,(sum(Debet)-sum(Kredit)) as Saldo
				from pinjaman_bayar as pb 
				left join pinjaman as p
				on p.ID=pb.ID_pinjaman
				where p.ID_Agt='$ID_Agt' and p.stat_pinjaman='0'
				group by pb.ID_pinjaman order by p.ID";
        $sql2="select * from mst_pelanggan_trans where nm_plg='$ID_Agt' and Jumlah>0 order by id";
        $sql="select ID,nm_plg,Tanggal,SUM(Nota)Hutang,SUM(Bayar)Bayar,Max(TglBayar)notrans from(
                select ID,nm_plg,
                CASE WHEN (right(left(notrans,15),3))='Not' Then Tanggal end  as Tanggal,
                CASE WHEN (right(left(notrans,15),3))='Not' Then jumlah else 0 end  as Nota,
                CASE WHEN (right(left(notrans,14),3))='dih' or (right(left(notrans,14),3))='dib' Then jumlah else 0 end  as Bayar,
                CASE WHEN (right(left(notrans,14),3))='dih' or (right(left(notrans,14),3))='dib' Then Tanggal end  as TglBayar,
                notrans
                from mst_pelanggan_trans where nm_plg='$ID_Agt'
                 and jumlah >0
                 ) as xx
                 group by LEFT(notrans,10)
                 order by ID desc";
       //echo $sql;//debug only
		$data=$this->db->query($sql);
		return $data->result();
	}
	function get_data_pinjaman($where,$orderby=''){
		$data=array();
		$sql2="select p.*,sum(Debet) as Debet,sum(Kredit) as Kredit,
			 (sum(Debet)-sum(Kredit)) as Saldo,
			  a.Nama,a.Alamat,a.Kota,a.Telepon as Catatan
				from pinjaman_bayar as pb 
				left join pinjaman as p
				on p.ID=pb.ID_pinjaman
				left join mst_anggota as a
				on a.ID=p.ID_Agt
				$where group by pb.ID_Agt
				$orderby";
		
        $sql="select *,DateDiff(Now(),doc_date)as Lama from mst_pelanggan $where $orderby";
       // echo $sql;
		$data=$this->db->query($sql);
		return $data->result();
	}
	function data_setoran($ID_Agt,$ID){
		$data=array();
		$sql="select * from pinjaman_bayar where ID_Pinjaman='$ID' and ID_Agt='$ID_Agt' 
			 and kredit!=0 order by ID_Pinjaman,Tanggal,Tahun";
		$data=$this->db->query($sql);
		return $data->result();
	}
	function get_transaksi($ID_Dept,$ID_Stat){
		if ($ID_Dept=='all' && $ID_Stat=='all'){
			$where ='';
		}else if ($ID_Dept!='all' && $ID_Stat=='all'){
			$where="where tp.ID_Dept='$ID_Dept'";
		}else if ($ID_Dept!='all' && $ID_Stat!='all'){
			$where="where tp.ID_Dept='$ID_Dept' and tp.ID_Stat='$ID_Stat'";
		}else if($ID_Dept=='all' && $ID_Stat!='all'){
			$where="where tp.ID_Stat='$ID_Stat'";
		}
		$sql="select
			concat(k.Kode,sk.Kode,uj.Kode,d.Kode,agt.No_Perkiraan) as Kode,
			concat(agt.Nama,\" ( \",d.Title, \") - \",js.Jenis,\" (\",uj.unit,\")\") as Perkiraan,
			tp.Debet,tp.Kredit,tp.Keterangan,tp.Tanggal,tp.ID,tp.ID_Stat,uj.unit
			from transaksi_temp as tp
				left join perkiraan as p
				on p.ID=tp.ID_Perkiraan
				left join klasifikasi as k
				on k.ID=p.ID_Klas
				left join sub_klasifikasi as sk
				on sk.ID=p.ID_SubKlas
				left join mst_departemen as d
				on d.ID=p.ID_Dept
				left join mst_anggota as agt
				on agt.ID=p.ID_Agt
				left join unit_jurnal as uj
				on uj.ID=p.ID_Unit
				left join jenis_simpanan as js
				on js.ID=p.ID_Simpanan
			$where";
			//echo $sql;
		$data=$this->db->query($sql);
		return $data->result();
	}
    function GetDetailAnggota($ID_Agt)
    {
        $sql="Select * from mst_anggota_view where Nama='".$ID_Agt."' group by Nama";
        $data=$this->db->query($sql);
        return $data->result();
    }
}