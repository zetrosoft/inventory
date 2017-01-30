<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Neraca_model extends CI_Model {
	public $data=array();
	public $user='';
	function __construct(){
		parent::__construct();
		$this->user=$this->session->userdata('userid');
	}
	function get_users($user=''){
		 //$this->user=$user;	
	}
	function get_rekap_data($periode){
		$this->build_data($periode);
		$this->drop_data();
		mysql_query("truncate table v_".$this->user."_neraca_lajur");
		$sql="select t.ID_Dept,d.Departemen,js.ID,js.Jenis,sum(debet) as debet, sum(kredit) as kredit,t.ID_Calc
			  from tmp_".$this->user."_transaksi_rekap as t
			  left join mst_departemen as d
			  on d.ID=t.ID_Dept
			  left join jenis_simpanan as js
				on js.ID=t.ID_Simpanan
				where t.ID_Dept not in('0','1')
				group by concat(t.ID_Simpanan,t.ID_Dept)
				order by t.ID_Dept,t.ID_Simpanan";
		$rs=mysql_query($sql) or die(mysql_error());
		$hasil=0;$saldoawal=0;
		while($row=mysql_fetch_object($rs)){
			$saldoawal=rdb("perkiraan",'SaldoAwal','sum(SaldoAwal) as SaldoAwal',"where ID_Dept='".$row->ID_Dept."' and ID_Simpanan='".$row->ID."'");
			$cek=rdb("v_".$this->user."_neraca_lajur","ID_Dept","ID_Dept","where ID_Dept='".$row->Departemen."'");
			$hasil=($row->ID_Calc=='2')?(($row->kredit)-($row->debet)):(($row->debet)-($row->kredit));
			($cek=='')?
			$drop="insert into v_".$this->user."_neraca_lajur(ID_Dept,".str_replace('. ','_',$row->Jenis).") 
				   values ('".$row->Departemen."','".($hasil+$saldoawal)."')":
			$drop="update v_".$this->user."_neraca_lajur set ".str_replace('. ','_',$row->Jenis)."='".($hasil+$saldoawal)."'
				  where ID_Dept='".$row->Departemen."'" ;
			mysql_query($drop) or die($drop.mysql_error());
		}
		$sdata="select * from v_".$this->user."_neraca_lajur";	
		$data=$this->db->query($sdata);
		return $data->result();
	}
	function get_rekap_dept($periode,$where){
		$this->build_data($periode);
		$sql="select n.Bulan,Sum(Debet) as Debet, Sum(Kredit) as Kredit 
			from tmp_".$this->user."_transaksi_rekap as p
			left join mst_bulan as n
			on n.ID=month(p.Tanggal)
			$where
			group by p.ID_Dept";
		echo $sql;
		$data=$this->db->query($sql);
		return $data->result();
	}
	function get_nc_lajure($periode,$where=''){
		$this->build_data($periode);
		$sql="select ID_P,ID_Agt,ID_Klas,ID_SubKlas,ID_Unit,ID_Dept,ID_Simpanan,ID_Calc,sum(kredit) as Kredit,sum(debet) as Debet
			  from tmp_".$this->user."_transaksi_rekap 
			  where id_agt not in('0') $where
			  group by concat(id_simpanan,id_agt)
			  order by concat(id_agt,ID_Klas,ID_SubKlas,ID_Unit,ID_Dept)";
			// echo $sql;
				$data=$this->db->query($sql);
				return $data->result();
		
	}
	
	 function build_data($periode){
		$sql="CREATE TABLE IF NOT EXISTS `tmp_".$this->user."_transaksi_rekap` (
			`ID` INT(11) NOT NULL AUTO_INCREMENT,
			`ID_Jurnal` INT(11) NULL DEFAULT NULL,
			`ID_Perkiraan` INT(11) NULL DEFAULT NULL,
			`ID_Dept_T` INT(11) NULL DEFAULT NULL,
			`Debet` DOUBLE NULL DEFAULT NULL,
			`Kredit` DOUBLE NULL DEFAULT NULL,
			`Keterangan` VARCHAR(100) NULL DEFAULT NULL,
			`ID_P` INT(11) NULL DEFAULT NULL,
			`ID_Klas` INT(11) NULL DEFAULT NULL,
			`ID_SubKlas` INT(11) NULL DEFAULT NULL,
			`ID_Dept` INT(11) NULL DEFAULT NULL,
			`ID_Unit` INT(11) NULL DEFAULT NULL,
			`ID_Laporan` INT(11) NULL DEFAULT NULL,
			`ID_LapDetail` INT(11) NULL DEFAULT NULL,
			`ID_Agt` INT(11) NULL DEFAULT NULL,
			`ID_Calc` INT(11) NULL DEFAULT NULL,
			`ID_Simpanan` INT(11) NULL DEFAULT NULL,
			`NoUrut_P` INT(11) NULL DEFAULT NULL,
			`Kode` VARCHAR(4) NULL DEFAULT NULL,
			`Perkiraan` VARCHAR(50) NULL DEFAULT NULL,
			`SaldoAwal` DOUBLE NULL DEFAULT NULL,
			`ID_J` INT(11) NULL DEFAULT NULL,
			`ID_Unit_J` INT(11) NULL DEFAULT NULL,
			`ID_Tipe` INT(11) NULL DEFAULT NULL,
			`ID_Dept_J` INT(11) NULL DEFAULT NULL,
			`Tanggal` DATE NULL DEFAULT NULL,
			`ID_Bulan` SMALLINT(6) NULL DEFAULT NULL,
			`Tahun` SMALLINT(6) NULL DEFAULT NULL,
			`NoUrut` INT(11) NULL DEFAULT NULL,
			`Nomor` VARCHAR(10) NULL DEFAULT NULL,
			`Keterangan_J` VARCHAR(60) NULL DEFAULT NULL,
			`ID_Mark` TINYINT(4) NULL DEFAULT NULL,
			PRIMARY KEY (`ID`)
		)
		COLLATE='latin1_swedish_ci'
		ENGINE=MyISAM";
		$sql1="truncate table tmp_".$this->user."_transaksi_rekap";
		$sql3="select min(tanggal) as tgl from jurnal";
		$rs=mysql_query($sql3);
		while($row=mysql_fetch_object($rs)){
		$fist_periode=$row->tgl;
		}
		$sql2="replace into tmp_".$this->user."_transaksi_rekap 
				select t.*,p.*,j.*  from jurnal as j
				left join transaksi as t
				on t.ID_Jurnal=j.ID
				right join perkiraan as p
				on p.ID=t.ID_Perkiraan
				where j.Tanggal between '$fist_periode' and '$periode' order by j.ID";
		mysql_query($sql) or die($sql."</br>".mysql_error()."</br>");
		mysql_query($sql1) or die($sql1."</br>".mysql_error()."</br>");
		mysql_query($sql2) or die($sql2."</br>".mysql_error()."</br>");
	}
	
	function drop_data(){
		$sql="CREATE TABLE IF NOT EXISTS `v_".$this->user."_neraca_lajur` (
			`ID_Dept` VARCHAR(250) NOT NULL DEFAULT '',
			`SaldoAwal` DOUBLE NULL DEFAULT '0',";
		$rs=mysql_query("select Jenis from jenis_simpanan");
		while($r=mysql_fetch_object($rs)){
			$sql.="`".str_replace('. ','_',$r->Jenis)."` DOUBLE NULL DEFAULT '0',";
		}
		$sql.="`doc_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY (`ID_Dept`))
				COLLATE='latin1_swedish_ci'
				ENGINE=MyISAM;";
		mysql_query($sql) or die(mysql_error());
	}
	function tmp_balance(){
		$sql="CREATE TABLE IF NOT EXISTS `tmp_".$this->user."_neraca_balance` (
			`unit` INT(10) NULL DEFAULT NULL,
			`periode` DATE NULL DEFAULT NULL,";
		$rs=mysql_query("select * from lap_head");
		while($r=mysql_fetch_object($rs)){
			$sql.="`".str_replace('. ','_',$r->Header2)."` DOUBLE NULL DEFAULT '0',";
			$sql.="`".str_replace('. ','_',$r->Header2)."2` DOUBLE NULL DEFAULT '0',";
		}
		$sql.="PRIMARY KEY (`periode`, `unit`)
			)
			COLLATE='latin1_swedish_ci'
			ENGINE=MyISAM;";
		mysql_query($sql) or die(mysql_error());
		mysql_query("truncate table tmp_".$this->user."_neraca_balance") or die(mysql_error());
	}
	function neraca_kalkulasi($periode,$unite){
		($unite=='')?$where='':$where="where n.ID_$unite='1'";
		$this->build_data($periode);
		$sql="select h.Header1 as ID_Head,j.Jenis as ID_Jenis,n.SubJenis
				from v_".$this->user."_neraca as n
				left join lap_head as h
				on h.ID=n.ID_Head
				left join lap_jenis as j
				on j.ID=n.ID_Jenis
				$where
				order by n.ID_Head";	
				$data=$this->db->query($sql);
				return $data->result();
	}
	
	function generate_shu($tglAwal,$tglAkhir,$Unit){
		//define variable
		//echo $Unit;
		$saldoA=0; $Saldo=0; $idCalc='';$saldoNc=0;$saldoNc1=0;
		//create temporary table for store saldo shu
		$tmp="CREATE TABLE IF NOT EXISTS `tmp_".$this->user."_total_shu` (
					`tglAwal` DATE NULL,
					`tglAkhir` DATE NULL,
					`saldo` DOUBLE NULL DEFAULT '0',
					`saldo2` DOUBLE NULL DEFAULT '0',
					`unit` INT NULL,
					PRIMARY KEY (`unit`)
				)
				COLLATE='latin1_swedish_ci'
				ENGINE=MyISAM;";
		mysql_query($tmp) or die(mysql_error());
		mysql_query("truncate table tmp_".$this->user."_total_shu");
	// proces data jika Unit bukan gabungan
	if($Unit!='3'){ 
		$nm_unit=rdb("unit_jurnal","Unit","Unit","where ID='$Unit'");
		//read lap_jenis
		$tglAwal2=getPrevDays($tglAwal,366);
		$tglAkhir2=getPrevDays($tglAkhir,366);
		$sql="select * from lap_jenis where ID_Head='0' order by ID";
		$rs	=mysql_query($sql) or die($sql.mysql_error());
			while($row=mysql_fetch_object($rs)){
				$saldo=0;$saldo1=0;
				$sql2	="select * from lap_subjenis where ID_Jenis='".$row->ID."' and ID_$nm_unit='1' order by NoUrut";
				$rs2	=mysql_query($sql2) or die($sql2.mysql_error());
				while($row2=mysql_fetch_object($rs2)){
					$saldoA=rdb("perkiraan",'SaldoAwal','sum(SaldoAwal) as SaldoAwal',"where ID_Laporan='1' and ID_Unit='$Unit' and ID_LapDetail='".$row2->ID."'");
					$idCalc=rdb("perkiraan",'ID_Calc','ID_Calc',"where ID_Laporan='1' and ID_Unit='$Unit' and ID_LapDetail='".$row2->ID."'");
					//process total shu akhir periode
					$sql3="select id_calc,sum(debet) as debet,sum(kredit) as kredit from tmp_".$this->user."_transaksi_rekap where (Tanggal between '".$tglAwal."' and '".$tglAkhir."') and ID_Laporan='1' and ID_Unit='$Unit' and id_lapdetail='".$row2->ID."'";
					$rs3=mysql_query($sql3) or die($sql3.mysql_error());
						while($row3=mysql_fetch_object($rs3)){
							$Saldo=($idCalc==1)?($saldoA+($row3->debet-$row3->kredit)):($saldoA+($row3->kredit-$row3->debet));
							$saldoNc=($row2->ID_Calc==1)?($saldoNc-$Saldo):($saldoNc+$Saldo);
						}
					//process total shu tahun sebelumnya
					$sql31="select id_calc,sum(debet) as debet,sum(kredit) as kredit from tmp_".$this->user."_transaksi_rekap where (Tanggal between '".$tglAwal2."' and '".$tglAkhir2."') and ID_Laporan='1' and ID_Unit='$Unit' and id_lapdetail='".$row2->ID."'";
					//echo $sql31;
					$rs31=mysql_query($sql31) or die($sql31.mysql_error());
						while($row31=mysql_fetch_object($rs31)){
							$Saldo1=($idCalc==1)?($saldoA+($row31->debet-$row31->kredit)):($saldoA+($row31->kredit-$row31->debet));
							$saldoNc1=($row2->ID_Calc==1)?($saldoNc1-$Saldo1):($saldoNc1+$Saldo1);
						}
				}
			}
		$simpan="replace into tmp_".$this->user."_total_shu values('$tglAwal','$tglAkhir','".$saldoNc."','".$saldoNc1."','".$Unit."')";
		mysql_query($simpan) or die(mysql_error());
	}else if($Unit=='3'){
	//jika data adalah gabungan
	$nm_unit='';
		$rsg=mysql_query("select ID from unit_jurnal order by ID");
		while($rwg=mysql_fetch_object($rsg)){
		$saldoNc=0;$saldoNc1=0;
		$nm_unit=rdb("unit_jurnal","Unit","Unit","where ID='".$rwg->ID."'");
		//read lap_jenis
		$tglAwal2=getPrevDays($tglAwal,366);
		$tglAkhir2=getPrevDays($tglAkhir,366);
		$sql="select * from lap_jenis where ID_Head='0' order by ID";
		$rs	=mysql_query($sql) or die($sql.mysql_error());
			while($row=mysql_fetch_object($rs)){
				$saldo=0;$saldo1=0;
				$sql2	="select * from lap_subjenis where ID_Jenis='".$row->ID."' and ID_$nm_unit='1' order by NoUrut";
				$rs2	=mysql_query($sql2) or die($sql2.mysql_error());
				while($row2=mysql_fetch_object($rs2)){
					$saldoA=rdb("perkiraan",'SaldoAwal','sum(SaldoAwal) as SaldoAwal',"where ID_Laporan='1' and ID_Unit='".$rwg->ID."' and ID_LapDetail='".$row2->ID."'");
					$idCalc=rdb("perkiraan",'ID_Calc','ID_Calc',"where ID_Laporan='1' and ID_Unit='".$rwg->ID."' and ID_LapDetail='".$row2->ID."'");
					//process total shu akhir periode
					$sql3="select id_calc,sum(debet) as debet,sum(kredit) as kredit from tmp_".$this->user."_transaksi_rekap where (Tanggal between '".$tglAwal."' and '".$tglAkhir."') and ID_Laporan='1' and ID_Unit='".$rwg->ID."' and id_lapdetail='".$row2->ID."'";
					$rs3=mysql_query($sql3) or die($sql3.mysql_error());
						while($row3=mysql_fetch_object($rs3)){
							$Saldo=($idCalc==1)?($saldoA+($row3->debet-$row3->kredit)):($saldoA+($row3->kredit-$row3->debet));
							$saldoNc=($row2->ID_Calc==1)?($saldoNc-$Saldo):($saldoNc+$Saldo);
						}
					//process total shu tahun sebelumnya
					$sql31="select id_calc,sum(debet) as debet,sum(kredit) as kredit from tmp_".$this->user."_transaksi_rekap where (Tanggal between '".$tglAwal2."' and '".$tglAkhir2."') and ID_Laporan='1' and ID_Unit='".$rwg->ID."' and id_lapdetail='".$row2->ID."'";
					//echo $sql31;
					$rs31=mysql_query($sql31) or die($sql31.mysql_error());
						while($row31=mysql_fetch_object($rs31)){
							$Saldo1=($idCalc==1)?($saldoA+($row31->debet-$row31->kredit)):($saldoA+($row31->kredit-$row31->debet));
							$saldoNc1=($row2->ID_Calc==1)?($saldoNc1-$Saldo1):($saldoNc1+$Saldo1);
						}
				}
			}
		$simpan="replace into tmp_".$this->user."_total_shu values('$tglAwal','$tglAkhir','".$saldoNc."','".$saldoNc1."','".$rwg->ID."')";
		mysql_query($simpan) or die(mysql_error());
		}
	}
		//echo $sql31;
	}
	//generate data shu for grafik
	
	function data_grap_shu(){
		
		$endofdata=rdb("jurnal","Tanggal","Max(Tanggal) as Tanggal");
		$cek_data=date('dmY',filemtime($this->user.'_graph.xml'));
	if($cek_data!=date('dmY')){
		 $this->build_data($endofdata);
		
		$xml=fopen($this->user.'_graph.xml','w+');
		fwrite($xml,"<graph caption='Grafik Sisa Hasil Usaha' subcaption='YTD : ".date('d/m/Y')."' xAxisName='Tahun' yAxisName='SHU' showValues= '1' showLabels='1' showValues='1'>\r\n");
		//data tahun sebagai categori 			
     	fwrite($xml,"<categories>\r\n");
		$trr=mysql_query("select distinct(tahun) as Tahun from tmp_".$this->user."_transaksi_rekap order by Tahun");
		while($thr=mysql_fetch_object($trr)){
			fwrite($xml,"<category name='".$thr->Tahun."'/>\r\n");
		}
		fwrite($xml,"</categories>\r\n");
		//end of categories
		
		$nm_unit='';
			$saldoNc=0;$Saldo=0;$saldo=0;
			$rsg=mysql_query("select ID from unit_jurnal order by ID");
			while($rwg=mysql_fetch_object($rsg)){
				$nm_unit=rdb("unit_jurnal","Unit","Unit","where ID='".$rwg->ID."'");
				//read lap_jenis
				//create data set
				$warna=($nm_unit=='KBR')?'DBDC25':'2AD62A';
				fwrite($xml,"<dataset  seriesName='".$nm_unit."'  color='".$warna."'>\r\n");
		$rr=mysql_query("select distinct(tahun) as Tahun from tmp_".$this->user."_transaksi_rekap order by Tahun");
		while($th=mysql_fetch_object($rr)){
				$saldoNc=0;$saldoNc1=0;
					$sql="select * from lap_jenis where ID_Head='0' and ID_$nm_unit='1' order by ID_$nm_unit";
					$rs	=mysql_query($sql) or die($sql.mysql_error());
						while($row=mysql_fetch_object($rs)){
							$saldo=0;$saldo1=0;
							$sql2	="select * from lap_subjenis where ID_Jenis='".$row->ID."' and ID_$nm_unit='1' order by ID_$nm_unit";
							$rs2	=mysql_query($sql2) or die($sql2.mysql_error());
							while($row2=mysql_fetch_object($rs2)){
								$saldoA=rdb("perkiraan",'SaldoAwal','sum(SaldoAwal) as SaldoAwal',"where ID_Laporan='1' and ID_Unit='".$rwg->ID."' and ID_LapDetail='".$row2->ID."'");
								$idCalc=rdb("perkiraan",'ID_Calc','ID_Calc',"where ID_Laporan='1' and ID_Unit='".$rwg->ID."' and ID_LapDetail='".$row2->ID."'");
								//process total shu akhir periode
									$sql3="select id_calc,sum(debet) as debet,sum(kredit) as kredit from tmp_".$this->user."_transaksi_rekap where Tahun='".$th->Tahun."' and ID_Laporan='1' and ID_Unit='".$rwg->ID."' and id_lapdetail='".$row2->ID."'";
									$rs3=mysql_query($sql3) or die($sql3.mysql_error());
									while($row3=mysql_fetch_object($rs3)){
										$Saldo=($idCalc==1)?($saldoA+($row3->debet-$row3->kredit)):($saldoA+($row3->kredit-$row3->debet));
										$saldoNc=($idCalc==1)?($saldoNc-$Saldo):($saldoNc+$Saldo);
									}
							} //end of $row2
						}// end if $row
						fwrite($xml,"<set label='".$th->Tahun."' value='".$saldoNc."'/>\r\n");
				}//end of $rwg
				//generate XML content file
				fwrite($xml,"</dataset>\r\n");	
			
					//fwrite($xml,"<set name='".$th->Tahun.'\' value=\''.$SaldoNc."'/>\r\n");
					//$n++;
		} //end of $th
		/**/
		fwrite($xml,"</graph>\r\n");
	}
	}
	function data_XML(){
		$n=0;$x=0;
		$user=$this->session->userdata('userid');
		$xml=fopen(base_url()."application/log/".$user.'_graph.xml','wb');
		fwrite($xml,"<graph caption='".$this->judul."' xAxisName='".$this->xAxis."' yAxisName='".$this->yAxis."' numberPrefix='' showvalues='1'  numDivLines='4' formatNumberScale='0' decimalPrecision='0' anchorSides='10' anchorRadius='3' anchorBorderColor='00990'>\r\n");
		foreach($this->datasec as $sec=>$par_tip){
			fwrite($xml,"<set name='".$sec.'\' value=\''.$par_tip."'/>\r\n");
			$n++;
		}
		fwrite($xml,"</graph>\r\n");
	}
	
}
?>