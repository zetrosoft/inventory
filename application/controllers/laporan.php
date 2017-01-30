<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan extends CI_Controller{
	
	 function __construct()
	  {
		parent::__construct();
		$this->load->model("inv_model");
		$this->load->model("report_model");
		$this->load->helper("print_report");
		$this->load->model("akun_model");
		$this->load->library("zetro_terbilang");
		$this->load->library("zetro_auth");
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
		//$this->zetro_auth->view($view);
		$this->load->view($view,$this->data);	
		$this->Footer();
	}
	function faktur(){
		$this->zetro_auth->menu_id(array('laporan__faktur'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('laporan/lap_faktur');
	}
	
	function rekap_simpanan(){
		$this->zetro_auth->menu_id(array('laporan__rekap_simpanan'));
		$data=$this->akun_model->get_simpanan_name();
		$this->list_data($this->zetro_auth->auth(array('simpanan'),array($data)));
		//print_r($this->zetro_auth->auth(array('simpanan'),array($data)));
		$this->View('laporan/rekap_simpanan');
	}
	
	function get_rekap_simpanan(){
	 $n		=0;
	 $dept	=array();
	 $ID_Dept=$_POST['dept'];
	 $dept	=$this->akun_model->get_departemen(" and ID_Dept='".$ID_Dept."'");
		foreach($dept->result() as $row){
			$n++;$hasil-array();
			echo "<tr class='xx' id='".$row->ID."'>
				 <td class='kotak' align='center'>$n</td>
				 <td class='kotak'>".$row->Departemen."</td>";
					$data=$this->akun_model->get_simpanan_name();
					$hasile=0;
					foreach($data->result() as $sim){
					$hasil=$this->akun_model->get_value_simpanan($row->ID,$sim->ID);
					 echo "<td class='kotak' align='right' id='".$sim->ID."'>".number_format($hasile,2)."</td>";
					$hasile =($hasile+(int)$hasile);
					}
					echo "<td class='kotak'>".number_format($hasile,2)."</td>
				 </tr>\n";
				 
		}
		//print_r($hasil->result());	
	}
	function get_data_simpanan(){
		$ID_Dept	=$this->akun_model->get_departemen();
		$data		=$this->akun_model->get_data_simpanan('17');
		$dataw		=$this->akun_model->get_data_simpanan('18');
		$datak		=$this->akun_model->get_data_simpanan('19');
		$i=0;
		$simp_khusus	=0;
		$simp_pokok		=0;
		$simp_wajib		=0;
		$total_dept		=0;
		$t_simp_khusus	=0;
		$t_simp_pokok	=0;
		$t_simp_wajib	=0;
		$grand_total	=0;
		foreach($data as $dept){
			$i++;
			$simp_pokok	=(($dept->KR+$dept->SA)-$dept->DB);
			$simp_wajib	=(($dataw[($i-1)]->KR+$dataw[($i-1)]->SA)-$dataw[($i-1)]->DB);
			$simp_khusus=(($datak[($i-1)]->KR+$datak[($i-1)]->SA)-$datak[($i-1)]->DB);
			$total_dept=($simp_pokok+$simp_wajib+$simp_khusus);
			
			echo "<tr class='xx' id='".$dept->ID_Dept."'>
				<td class='kotak' align='center'>".$i."</td>	
				<td class='kotak' align='left'>".rdb('mst_departemen','departemen','departemen',"where ID='".$dept->ID_Dept."'")."</td>	
				<td class='kotak' align='right'>".number_format($simp_pokok,2)."</td>	
				<td class='kotak' align='right'>".number_format($simp_wajib,2)."</td>	
				<td class='kotak' align='right'>".number_format($simp_khusus,2)."</td>	
				<td class='kotak' align='right'>".number_format($total_dept,2)."</td>	
				</tr>\n";
			$t_simp_pokok	=($t_simp_pokok+(($dept->KR+$dept->SA)-$dept->DB));
			$t_simp_wajib	=($t_simp_wajib+(($dataw[($i-1)]->KR+$dataw[($i-1)]->SA)-$dataw[($i-1)]->DB));
			$t_simp_khusus	=($t_simp_khusus+(($datak[($i-1)]->KR+$datak[($i-1)]->SA)-$datak[($i-1)]->DB));
			$grand_total	=($t_simp_pokok+$t_simp_wajib+$t_simp_khusus);
			
		}
		echo "<tr class='list_genap'>
			 <td colspan='2' class='kotak'>TOTAL</td>
				<td class='kotak' align='right'>".number_format($t_simp_pokok,2)."</td>	
				<td class='kotak' align='right'>".number_format($t_simp_wajib,2)."</td>	
				<td class='kotak' align='right'>".number_format($t_simp_khusus,2)."</td>	
				<td class='kotak' align='right'>".number_format($grand_total,2)."</td>
				</tr>";	
	}
	function print_lap_pdf(){
		$data['tanggal']=date('d F Y');
		$data['temp_rec']=$this->akun_model->get_data_simpanan('17');
		$data['tmp_sp']	=$this->akun_model->get_data_simpanan('18');
		$data['tmp_khs']=$this->akun_model->get_data_simpanan('19');
		$this->zetro_auth->menu_id(array('trans_beli'));
		$this->list_data($data);
		$this->View("laporan/rekap_simpanan_print");
	
	}
	function last_no_transaksi(){
		$data=array();
		$where=(empty($_POST['sampai_tgl']))? 
			"where Tanggal='".tglToSql($_POST['dari_tgl'])."'":
			"where Tanggal between '".tglToSql($_POST['dari_tgl'])."' and '".tglToSql($_POST['sampai_tgl'])."'";
		$where=empty($_POST['id_anggota'])?$where:" where (a.Nama like '%".$_POST['id_anggota']."%' or p.NoUrut='".$_POST['id_anggota']."')";
		$where.=empty($_POST['lokasi'])?"and id_lokasi='1'":"and id_lokasi='".$_POST['lokasi']."'";
		$data=$this->report_model->get_no_trans($where);
		foreach($data as $r){
			echo "<option value='".$r->NoUrut.":".tglfromSql($r->Tanggal)."'>".$r->NoUrut."-".$r->Nama." [".$r->Alamat."]</option>";
		}
			
	}
	function print_faktur(){
		$data=array();
		$no_trans=$this->input->post('no_transaksi');
		$data['nm_nasabah']	=rdb('mst_anggota','Nama','Nama',"where ID='".rdb("inv_penjualan",'ID_Anggota','ID_Anggota',"where NoUrut='$no_trans' and Tahun='".date('Y')."'")."'");
		$data['alamat']		=rdb('mst_anggota','Alamat','Alamat',"where ID='".rdb("inv_penjualan",'ID_Anggota','ID_Anggota',"where NoUrut='$no_trans' and Tahun='".date('Y')."'")."'");
		$data['telp']		=rdb('mst_anggota','Telepon','Telepon',"where ID='".rdb("inv_penjualan",'ID_Anggota','ID_Anggota',"where NoUrut='$no_trans' and Tahun='".date('Y')."'")."'");
		$data['Company']	=rdb('mst_anggota','Catatan','Catatan',"where ID='".rdb("inv_penjualan",'ID_Anggota','ID_Anggota',"where NoUrut='$no_trans' and Tahun='".date('Y')."'")."'");
		$data['faktur']		=rdb("inv_penjualan",'Nomor','Nomor',"where NoUrut='$no_trans' and Tahun='".date('Y')."'");
		$data['tanggal']	=tglfromSql(rdb("inv_penjualan",'Tanggal','Tanggal',"where NoUrut='$no_trans' and Tahun='".date('Y')."'"));
		//$data['terbilang']	=$this->zetro_terbilang->terbilang(rdb("inv_penjualan",'Total','Total',"where NoUrut='$no_trans' and Tahun='".date('Y')."'"));
		$data['temp_rec']	=$this->report_model->laporan_faktur($no_trans);
		$this->zetro_auth->menu_id(array('trans_beli'));
		$this->list_data($data);
		$this->View("laporan/lap_".$this->input->post('lap')."_print");
	}
  function rekapabsensi()
  {
		$this->zetro_auth->menu_id(array('laporan__rekapabsensi'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('laporan/lap_rekap_absensi');
  }
  
  function detailabsensi()
  {
		$this->zetro_auth->menu_id(array('detailabsensi'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('laporan/lap_absensi');
  }
  
  function get_rekap_list()
  {
	$data=array();$n=0;$data1=array();$data2=array();
	$bulan=empty($_POST['bulan'])?date('m'):$_POST['bulan'];
	$tahun=empty($_POST['tahun'])?date('Y'):$_POST['tahun'];
	$where=empty($_POST['id_lokasi'])?"":" where id='".$_POST['id_lokasi']."'";
	$jmlhari=($bulan==date('m'))?date('d'):cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
		$totaH=0;$totalA=0;
			$detail=empty($_POST['detail'])?false:$_POST['detail'];
			if($detail=='true'){
				for($i=1;$i<=$jmlhari;$i++)
				{
					$xx=(strlen($i)==1)?'0'.$i:$i;
					$tgl=$tahun.'-'.$bulan.'-'.$xx;
					$x=0; $totaK=0;
					$Hari=mktime(0,0,0,$bulan,$i,$tahun);
					$nHari=date('N',$Hari);
						echo tr(($nHari==7)?'xx list_ganjil':'xx list_genap').td(nbs(3).nHari($nHari).', '.$i.' '.nBulan($bulan).' '.$tahun,'left\'colspan=\'5')._tr();
						$data=$this->Admin_model->show_list('user_lokasi',$where.'order by id');
						foreach($data as $r)
						{
						 $x++; $hadir=0;$jmlKar=0;$setengah=0;$kehadiran=0;
						 $jmlKar=rdb('karyawan','jml','count(id) as jml',"where Lokasi='".$r->ID."'");
						 $hadir=rdb('karyawan_absensi','jml','count(on_absen) as jml',"where tgl_absen='".$tgl."' and on_absen='Y' and id_lokasi='".$r->ID."'");
						 $setengah=rdb('karyawan_absensi','jml','count(on_absen) as jml',"where tgl_absen='".$tgl."' and on_absen='C' and id_lokasi='".$r->ID."'");
						 $kehadiran=($hadir+($setengah/2));
						 echo tr().td($x.nbs(2),'right').
							  td(nbs(2).$r->lokasi).
							  td($jmlKar,'center').
							  td($kehadiran,'center').
							  td(($jmlKar-$kehadiran),'center').
							 _tr();
							 $totalK +=$jmlKar;
							 $totalH +=$kehadiran;
							 $totalA =($jmlhari-$totaH);
						}
				}
			}else{
				$x=0;
				$data=$this->Admin_model->show_list('user_lokasi',$where.'order by id');
						foreach($data as $r)
						{
						 $x++; $hadir=0;$jmlKar=0;$setengah=0;$kehadiran=0;
						 $jmlKar=rdb('karyawan','jml','count(id) as jml',"where Lokasi='".$r->ID."'");
						 $hadir=rdb('karyawan_absensi','jml','count(on_absen) as jml',"where month(tgl_absen)='".$bulan."' and year(tgl_absen)='".$tahun."' and on_absen='Y' and id_lokasi='".$r->ID."' 
						 group by concat(month(tgl_absen))");
						 $setengah=rdb('karyawan_absensi','jml','count(on_absen) as jml',"where month(tgl_absen)='".$bulan."' and year(tgl_absen)='".$tahun."' and on_absen='C' and 
						 id_lokasi='".$r->ID."' group by concat(month(tgl_absen))");
						 $kehadiran=($hadir+($setengah/2));
						 echo tr().td($x.nbs(2),'right').
							  td(nbs(2).$r->lokasi).
							  td($jmlhari,'center').
							  td($kehadiran,'center').
							  td(($jmlhari-$kehadiran),'center').
							 _tr();
							 $totalK +=$jmlhari;
							 $totalH +=$kehadiran;
							 $totalA =($jmlhari-$totaH);

						}
			}
		//echo tr('xx list_genap').td('Total',
  }
  
  
  function get_list_absen()
  {
	$data=array();$n=0;$data1=array();$data2=array();
	$bulan=empty($_POST['bulan'])?date('m'):$_POST['bulan'];
	$tahun=empty($_POST['tahun'])?date('Y'):$_POST['tahun'];
	$where=empty($_POST['id_lokasi'])?"":" where id='".$_POST['id_lokasi']."'";
	$data=$this->Admin_model->show_list('user_lokasi',$where.'order by id');
	$jmlhari=($bulan==date('m'))?date('d'):cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
	foreach($data as $r)
	{
		$n++;
		echo tr('xx list_genap').td('No.','center').
			 td(strtoupper($r->lokasi),'left\'colspan=\'3').
		    _tr();$x=0; 
/*		for($i=1;$i<=$jmlhari;$i++)
		{
			
			$Hari=mktime(0,0,0,$bulan,$i,$tahun);
			$nHari=date('N',$Hari);
			echo tr(($nHari==7)?'xx list_ganjil':'xx').td(nbs(3).nHari($nHari).', '.$i.' '.nBulan($bulan).' '.$tahun,'left\'colspan=\'4')._tr();
*/			$data1=$this->Admin_model->show_list('karyawan',"where lokasi='".$r->ID."' order by Nama");
			foreach($data1 as $row)
			{
				$x++;$absen=0;
				$absen=rdb('karyawan_absensi','on_absen','count(on_absen) as on_absen',"where id_karyawan='".$row->ID."'and on_absen='Y' and month(tgl_absen)='$bulan' and year(tgl_absen)='".$tahun."' group by month(tgl_absen)");
				$absen2=rdb('karyawan_absensi','on_absen','count(on_absen) as on_absen',"where id_karyawan='".$row->ID."'and on_absen='C' and month(tgl_absen)='$bulan' and year(tgl_absen)='".$tahun."' group by month(tgl_absen)");
				$absen12=($absen2==0)?0:($absen2/2);
				echo tr().td($x,'center').
					 td(nbs(5).$row->Nama).
					 td((($absen+$absen12)==0)?'0':($absen+$absen12),'center').
					 td(($jmlhari-($absen+$absen12)),'center').
					_tr();	
			}
		/*}*/
	}
  }
  	function get_tahun()
	{
		$data=array();
		$data=$this->control_model->get_bulan('karyawan_absensi','year','tgl_absen');
				echo "<option value=''></option>";	
		if($data){
			foreach($data as $r)
			{
				echo "<option value='".$r->year."'>".$r->year."</option>";	
			}
		}else{
				echo "<option value='".date('Y')."'>".date('Y')."</option>";	
		}
	}
	//end of class laporan
    
    function elpiji()
    {
        $this->zetro_auth->menu_id(array('listtransaksilpg'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('laporan/penjualan_lpg');
    }
    function tahunlpg()
    {
      dropdown("inv_penjualan","distinct(Tahun) as Tahun","Tahun","where Tahun >0 order by Tahun",date('Y'));
    }
    function pangkalan()
    {
      dropdown("inv_pembelian_pkln","id_barang","Deskripsi","Group By ID_Pemasok order by Deskripsi");   
    }
    function pemasokLpg()
    {
        dropdown("mst_anggota","id","nama","where NIP='LPG' order by nama");
    }
    function ListFromPemasok()
    {
        $data=array();$n=0;$sisa=0;
        $wherex =empty($_POST['bulan'])?"  where Month(Tanggal)<'".date("m")."'":
                ($_POST['bulan']=="1")?"where Month(Tanggal)='12'":" where Month(Tanggal)<'".$_POST['bulan']."'";
        $wherex .=empty($_POST['tahun'])?" and Year(Tanggal)<='".date("Y")."'":
                ($_POST['bulan']=="1")?" and Year(Tanggal)<'".$_POST['tahun']."'":" and Year(Tanggal)<='".$_POST['tahun']."'";
        $wherex .=(empty($_POST['pangkalan']))?"":" and ID_Barang='".$_POST['pangkalan']."'";
        $lastbeli=(empty($_POST['pangkalan']))?"":rdb("inv_pembelian_lpg","Tanggal","Tanggal","$wherex  order by ID Desc Limit 1");       
        $lastbeli=($lastbeli!="")?" Tanggal >'".$lastbeli."' or ":"";
        $where =empty($_POST['bulan'])?" where ( $lastbeli (Month(Tanggal)='".date("m")."'":" where ( $lastbeli (Month(Tanggal)='".$_POST['bulan']."'";
        $where .=empty($_POST['tahun'])?" and Year(Tanggal)='".date("Y")."'))":" and Year(Tanggal)='".$_POST['tahun']."'))";
        $where .=(empty($_POST['pangkalan']))?"":" and ID_Barang='".$_POST['pangkalan']."'";
        $orderby=" group by Tanggal,ID_Barang order by ID , ID_Barang,Tanggal ";
        $field="*,SUM(Jumlah)Jumlah,(select Tanggal from inv_pembelian_lpg as l where l.Tanggal > lpg.Tanggal and l.ID_Pemasok=lpg.ID_Pemasok order by l.Tanggal limit 1)NextT";
        $field.=",(select Tanggal from inv_pembelian_lpg as l where l.Tanggal < lpg.Tanggal and l.ID_Pemasok=lpg.ID_Pemasok order by l.Tanggal desc limit 1)NextP";
        $data=$this->report_model->LaporanLPG($where,$orderby,$field);
        //print_r($data);exit;
        foreach($data as $r)
        {
            $n++;$datax=array();$i=0;$jml=0;$sd="";$jmlDatang=0;
            $jmlDatang=($sisa>0)?$r->Jumlah+$sisa:$r->Jumlah;
            echo tr('xx list_genap').
                 td($n,'center').
                 td(($r->Tanggal),'center').
                 td($r->Deskripsi).
                 td(number_format($r->Jumlah,0),'right').
                 td(($sisa>0)?"Sisa kedatangan tanggal $r->NextP = ".$sisa ."&nbsp;&nbsp; [ Total Stock = $jmlDatang ]":'','left\' colspan=\'5').
                 td($r->NextT).
                 td('&nbsp','left\' colspan=\'4').
                _tr();
            $sd=($r->NextT=='NULL'|| $r->NextT=='')?date('Y-m-d'):$r->NextT;
            $datax=$this->Admin_model->show_list('inv_penjualan_view',"where Tanggal >= '".$r->Tanggal."' and Tanggal <= '".$sd."' 
                and id_barang ='".$r->ID_Barang."' and Jumlah > 0 order by Tanggal,id_barang,Deskripsi");
            foreach($datax as $row)
            {
                $jml+=$row->Jumlah;$i++;
                $posisi=strtoupper(rdb('mst_anggota','Catatan','Catatan',"where Nama='".$row->Deskripsi."'"));
                echo tr().
                 td('','center').
                 td($i.". | ". tglFromSql($row->Tanggal)."&nbsp;&nbsp;",'right\' colspan=\'3').
                 
                 td(strtoupper($row->Deskripsi)).
                    td(($posisi=='RUMAH TANGGA')?"V":"",'center').
                    td(($posisi=='USAHA MIKRO')?"V":"",'center').
                    td(($posisi=='PENGECER')?"V":"",'center').
                 td(ucwords(rdb('mst_anggota','keterangan','keterangan',"where Nama='".$row->Deskripsi."'"))).
                 td(number_format($row->Jumlah,0),'right').td().
                 td(number_format(($jmlDatang-$jml),0),'right').
                 td(number_format($jml,0),'right').td().
                _tr();
                
            }/**/
            $sisa=$jmlDatang-$jml;
            echo tr('xx list_ganjil').td('<b>JUMLAH</b>','Right\' colspan=\'9').td("<b>".number_format($jml,0)."</b>",'right').
                 td('Stock Akhir ','right\' colspan=\'2').
                 td(number_format($sisa,0),'right').td().
                _tr();
        }
    }
    function PrintLaporanLpg()
    {
        $data=array();$n=0;$sisa=0;$html="";$datax=array();
       //$data=array();$n=0;$sisa=0;
        $wherex =empty($_POST['bulan'])?"  where Month(Tanggal)<'".date("m")."'":
                ($_POST['bulan']=="1")?"where Month(Tanggal)='12'":" where Month(Tanggal)<'".$_POST['bulan']."'";
        $wherex .=empty($_POST['tahun'])?" and Year(Tanggal)<='".date("Y")."'":
                ($_POST['bulan']=="1")?" and Year(Tanggal)<'".$_POST['tahun']."'":" and Year(Tanggal)<='".$_POST['tahun']."'";
        $wherex .=(empty($_POST['pangkalan']))?"":" and ID_Barang='".$_POST['pangkalan']."'";
        $lastbeli=(empty($_POST['pangkalan']))?"":rdb("inv_pembelian_lpg","Tanggal","Tanggal","$wherex  order by Tanggal Desc Limit 1");       
        $lastbeli=($lastbeli!="")?" Tanggal >='".$lastbeli."' or ":"";
        $where =empty($_POST['bulan'])?" where ( $lastbeli (Month(Tanggal)='".date("m")."'":" where ( $lastbeli (Month(Tanggal)='".$_POST['bulan']."'";
        $where .=empty($_POST['tahun'])?" and Year(Tanggal)='".date("Y")."'))":" and Year(Tanggal)='".$_POST['tahun']."'))";
        $where .=(empty($_POST['pangkalan']))?"":" and ID_Barang='".$_POST['pangkalan']."'";
        $orderby=" group by Tanggal,ID_Barang order by ID, ID_Barang,Tanggal ";
        $field="*,SUM(Jumlah)Jumlah,(select Tanggal from inv_pembelian_lpg as l where l.Tanggal > lpg.Tanggal and l.ID_Pemasok=lpg.ID_Pemasok order by l.Tanggal limit 1)NextT";
        $field.=",(select Tanggal from inv_pembelian_lpg as l where l.Tanggal < lpg.Tanggal and l.ID_Pemasok=lpg.ID_Pemasok order by l.Tanggal desc limit 1)NextP";
        $data=$this->report_model->LaporanLPG($where,$orderby,$field);
        foreach($data as $r)
        {
            $n++;$datax=array();$i=0;$jml=0;$sd="";$jmlDatang=0;
            $jmlDatang=($sisa>0)?$r->Jumlah+$sisa:$r->Jumlah;
            $html.= tr('xx list_genap').
                 td($n,'center').
                 td(($r->Tanggal),'center').
                 td($r->Deskripsi).
                 td(number_format($r->Jumlah,0),'right').
                 td(($sisa>0)?"Sisa kedatangan tanggal $r->NextP = ".$sisa ."&nbsp;&nbsp; [ Total Stock = $jmlDatang ]":'','left\' colspan=\'5').
                 td().
                 td('','left\' colspan=\'4').
                _tr();
            $sd=($r->NextT=='NULL'|| $r->NextT=='')?date('Y-m-d'):$r->NextT;
            $datax=$this->Admin_model->show_list('inv_penjualan_view',"where Tanggal >= '".$r->Tanggal."' and Tanggal <= '".$sd."' 
                and id_barang ='".$r->ID_Barang."' and Jumlah > 0 order by Tanggal,id_barang,Deskripsi");
            $jm=0;
            foreach($datax as $row)
            {
                $jml+=$row->Jumlah;$i++;
                $posisi=strtoupper(rdb('mst_anggota','Catatan','Catatan',"where Nama='".$row->Deskripsi."'"));
                $html.= tr().
                 td('','center').
                 td($i.". | ". tglFromSql($row->Tanggal)."&nbsp;&nbsp;",'right\' colspan=\'3').
                 
                 td(strtoupper($row->Deskripsi)).
                    td(($posisi=='RUMAH TANGGA')?"V":"",'center').
                    td(($posisi=='USAHA MIKRO')?"V":"",'center').
                    td(($posisi=='PENGECER')?"V":"",'center').
                 td(ucwords(rdb('mst_anggota','keterangan','keterangan',"where Nama='".$row->Deskripsi."'"))).
                 td(number_format($row->Jumlah,0),'right').td().
                 td(number_format(($jmlDatang-$jml),0),'right').
                 td(number_format($jml,0),'right').td().
                _tr();
                $jm++;
            }
            /**/
            $sisa=$jmlDatang-$jml;
            $html .= tr('xx list_ganjil').td('<b>JUMLAH</b>','Right\' colspan=\'9').td("<b>".number_format($jml,0)."</b>",'right').
                 td('Stock Akhir ','right\' colspan=\'2').
                 td(number_format($sisa,0),'right').td().
                _tr();
        }
        $bulan=empty($_POST['bulan'])?date('m'):$_POST['bulan'];
		$tahun=empty($_POST['tahun'])?date('Y'):$_POST['tahun'];
        $datax['periode']=nBulan($bulan).' '.$tahun;
        $datax['rkp']=$html;
        $datax['hr']="<br>";
        $this->load->library('zetro_dompdf');
        $htmlx = $this->load->view('Laporan/penjualan_lpg_print', $datax, true);
        //echo $html;
        
        //echo base_url().$this->session->userdata('userid').'_rekaplpg.pdf';
        //echo 'C://reportpdf/'.$this->session->userdata('userid').'_rekaplpg.pdf';
        $this->zetro_dompdf->createPDF($htmlx, $this->session->userdata('userid').'_rekaplpg',false);
    }
	function PrintLaporanLpgExcel()
    {
        $data=array();$n=0;$sisa=0;$html="";$datax=array();
       //$data=array();$n=0;$sisa=0;
        $wherex =empty($_POST['bulan'])?"  where Month(Tanggal)<'".date("m")."'":
                ($_POST['bulan']=="1")?"where Month(Tanggal)='12'":" where Month(Tanggal)<'".$_POST['bulan']."'";
        $wherex .=empty($_POST['tahun'])?" and Year(Tanggal)<='".date("Y")."'":
                ($_POST['bulan']=="1")?" and Year(Tanggal)<'".$_POST['tahun']."'":" and Year(Tanggal)<='".$_POST['tahun']."'";
        $wherex .=(empty($_POST['pangkalan']))?"":" and ID_Barang='".$_POST['pangkalan']."'";
        $lastbeli=(empty($_POST['pangkalan']))?"":rdb("inv_pembelian_lpg","Tanggal","Tanggal","$wherex  order by Tanggal Desc Limit 1");       
        $lastbeli=($lastbeli!="")?" Tanggal >='".$lastbeli."' or ":"";
        $where =empty($_POST['bulan'])?" where ( $lastbeli (Month(Tanggal)='".date("m")."'":" where ( $lastbeli (Month(Tanggal)='".$_POST['bulan']."'";
        $where .=empty($_POST['tahun'])?" and Year(Tanggal)='".date("Y")."'))":" and Year(Tanggal)='".$_POST['tahun']."'))";
        $where .=(empty($_POST['pangkalan']))?"":" and ID_Barang='".$_POST['pangkalan']."'";
        $orderby=" group by Tanggal,ID_Barang order by ID, ID_Barang,Tanggal ";
        $field="*,SUM(Jumlah)Jumlah,(select Tanggal from inv_pembelian_lpg as l where l.Tanggal > lpg.Tanggal and l.ID_Pemasok=lpg.ID_Pemasok order by l.Tanggal limit 1)NextT";
        $field.=",(select Tanggal from inv_pembelian_lpg as l where l.Tanggal < lpg.Tanggal and l.ID_Pemasok=lpg.ID_Pemasok order by l.Tanggal desc limit 1)NextP";
        $data=$this->report_model->LaporanLPG($where,$orderby,$field);
        foreach($data as $r)
        {
            $n++;$datax=array();$i=0;$jml=0;$sd="";$jmlDatang=0;
            $jmlDatang=($sisa>0)?$r->Jumlah+$sisa:$r->Jumlah;
            $html.= tr('xx list_genap').
                 td($n,'center').
                 td(($r->Tanggal),'center').
                 td($r->Deskripsi).
                 td(number_format($r->Jumlah,0),'right').
                 td(($sisa>0)?"Sisa kedatangan tanggal $r->NextP = ".$sisa ."&nbsp;&nbsp; [ Total Stock = $jmlDatang ]":'','left\' colspan=\'5').
                 td().
                 td('','left\' colspan=\'4').
                _tr();
            $sd=($r->NextT=='NULL'|| $r->NextT=='')?date('Y-m-d'):$r->NextT;
            $datax=$this->Admin_model->show_list('inv_penjualan_view',"where Tanggal >= '".$r->Tanggal."' and Tanggal <= '".$sd."' 
                and id_barang ='".$r->ID_Barang."' and Jumlah > 0 order by Tanggal,id_barang,Deskripsi");
            foreach($datax as $row)
            {
                $jml+=$row->Jumlah;$i++;
                $posisi=strtoupper(rdb('mst_anggota','Catatan','Catatan',"where Nama='".$row->Deskripsi."'"));
                $html.= tr().
                 td('','center').
                 td($i.". | ". tglFromSql($row->Tanggal)."&nbsp;&nbsp;",'right\' colspan=\'3').
                 
                 td(strtoupper($row->Deskripsi)).
                    td(($posisi=='RUMAH TANGGA')?"V":"",'center').
                    td(($posisi=='USAHA MIKRO')?"V":"",'center').
                    td(($posisi=='PENGECER')?"V":"",'center').
                 td(ucwords(rdb('mst_anggota','keterangan','keterangan',"where Nama='".$row->Deskripsi."'"))).
                 td(number_format($row->Jumlah,0),'right').td().
                 td(number_format(($jmlDatang-$jml),0),'right').
                 td(number_format($jml,0),'right').td().
                _tr();
                
            }/**/
            $sisa=$jmlDatang-$jml;
            $html .= tr('xx list_ganjil').td('<b>JUMLAH</b>','Right\' colspan=\'9').td("<b>".number_format($jml,0)."</b>",'right').
                 td('Stock Akhir ','right\' colspan=\'2').
                 td(number_format($sisa,0),'right').td().
                _tr();
        }
        $bulan=empty($_POST['bulan'])?date('m'):$_POST['bulan'];
		$tahun=empty($_POST['tahun'])?date('Y'):$_POST['tahun'];
        $datax['periode']=nBulan($bulan).' '.$tahun;
        $datax['rkp']=$html;
        $datax['hr']="<br>";
        $this->load->library('zetro_dompdf');
        $htmlx =  $this->load->view('Laporan/penjualan_lpg_print_excel', $datax, true);
        $this->exportToExcel($htmlx);
        //created file
		
    }
    function view_pdf()
    {
         echo base_url().$this->session->userdata('userid').'_rekaplpg.pdf';
    }
	function ListFromPemasok2()
	{
		$jmlDatang=0;$datax=array();
		$jml=0;$i=0;
		$wherex =empty($_POST['bulan'])?"  where Month(Tanggal)='".date("m")."'":
                ($_POST['bulan']=="1")?"where Month(Tanggal)='12'":" where Month(Tanggal)='".$_POST['bulan']."'";
        $wherex .=empty($_POST['tahun'])?" and Year(Tanggal)='".date("Y")."'":
                ($_POST['bulan']=="1")?" and Year(Tanggal)='".$_POST['tahun']."'":" and Year(Tanggal)='".$_POST['tahun']."'";
		$wherex .=(empty($_POST['pangkalan']))?"":" and ID_Barang='".$_POST['pangkalan']."'";
		$orderby=" order by Tanggal,Num";
		$Bulan=(empty($_POST['bulan']))? date("m"):$_POST['bulan'];
		$Bulanx=(empty($_POST['tahun']))? date("m"):$_POST['tahun'];
		$datax=$this->Admin_model->show_list('inv_transaksi_lpg_rekap',$wherex.$orderby);
		$saldoAwal=$this->report_model->SaldoAwalLPG($Bulan,$Bulanx,@$_POST['pangkalan']);
		echo tr('xx list_ganjil').td('<b>Sado Awal</b>','Right\' colspan=\'9').td("<b>".number_format($saldoAwal,0)."</b>",'right').
                 td('Stock Awal ','right\' colspan=\'2').
                 td(number_format($saldoAwal,0),'right').td().
                _tr();
		$jmlDatang=$saldoAwal;
            foreach($datax as $row)
            {
                $jml+=$row->Jual;$i++;
				$jmlDatang +=$row->Beli;
                $posisi=strtoupper($row->Catatan);//strtoupper(rdb('mst_anggota','Catatan','Catatan',"where Nama='".$row->Deskripsi."'"));
                echo tr().
                 td($i,'center').
                 td(tglFromSql($row->Tanggal)."&nbsp;&nbsp;",'right\' colspan=\'0').
				 td(($row->Beli>0)?strtoupper($row->Konsumen):"").	
                 td(($row->Beli>0)?number_format($row->Beli,0):"","right").
                 td(($row->Beli>0)?"":strtoupper($row->Konsumen)).
                 td(($posisi=='RUMAH TANGGA')?"V":"",'center').
                 td(($posisi=='USAHA MIKRO')?"V":"",'center').
                 td(($posisi=='PENGECER')?"V":"",'center').
                 td(($row->Beli<=0)?"":ucwords($row->Konsumen)).//rdb('mst_anggota','keterangan','keterangan',"where Nama='".$row->Deskripsi."'"))).
                 td(number_format($row->Jual,0),'right').td().
                 td(number_format(($jmlDatang-$jml),0),'right').
                 td(number_format($jml,0),'right').td().
                _tr();
                
            }/**/
            $sisa=$jmlDatang-$jml;
            echo tr('xx list_ganjil').td('<b>JUMLAH</b>','Right\' colspan=\'9').td("<b>".number_format($jml,0)."</b>",'right').
                 td('Stock Akhir ','right\' colspan=\'2').
                 td(number_format($sisa,0),'right').td().
                _tr();
	}
	
	function exportToExcel($html){
	// filename for download $filename = "website_data_" . date('Ymd') . ".xls"; 
	header("Content-Disposition: attachment; filename=RekapPenjualanLpg.xls"); 
	header("Content-Type: application/vnd.ms-excel"); 
	$flag = false; 
    $newfile=fopen('c:\\app\\lpgexcel.php',"wb");
		fwrite($newfile,$html);
		fclose($newfile);
		echo 'c:\\app\\lpgexcel.php';
	exit;
    }
}


?>
