<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*	class name	:akuntansi
	purpose		:akuntansi controller
	version		:1.0 [19/07/2012]
	author		:Zetrosoft
*/

class Akuntansi extends CI_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->model("Admin_model");
		$this->load->model("akun_model");
		$this->load->model('inv_model');	
		$this->load->helper("print_report");
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
		$this->load->view($view,$this->data);	
		$this->Footer();
	}
	//klasifikasi menu
	function klass_akun(){
		$this->zetro_auth->menu_id(array('klasifikasi'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('akuntansi/klass_akun');
	}
	function set_klass_akun(){
		$ID			=$_POST['ID'];
		$Kode		=$_POST['Kode'];
		$Klasifikasi=$_POST['Klas'];
		$data1	="ID,Kode,Klasifikasi";
		$data	="'$ID','$Kode','$Klasifikasi'";
		$this->akun_model->set_klas_akun($data1,$data);
	}
	
	function get_klass_akun(){
		$data=array();$n=0;
		$data=$this->Admin_model->show_list('Klasifikasi','order by ID,Kode');
		foreach($data as $row){
		$n++;
		echo "<tr class='xx' id='".$row->ID."' align='center'>
			 <td class='kotak'>$n</td>
			 <td class='kotak'>".$row->Kode."</td>
			 <td class='kotak' align='left'>".$row->Klasifikasi."</td>
			 <td class='kotak'>
			 	<img src='".base_url()."asset/images/editor.png' onClick=\"img_onClick('".$row->ID."','edit');\">
			 	<img src='".base_url()."asset/images/no.png' onClick=\"img_onClick('".$row->ID."','del');\">
			 </td></tr>\n";	
		}
	}
	function get_klas_ID(){
		$ID=$_POST['ID'];
		$data=$this->Admin_model->show_list('Klasifikasi',$ID);
		echo json_encode($data[0]);
	}
	//sub klasifikasi menu
	function subklass_akun(){
		$this->zetro_auth->menu_id(array('subklasifikasi'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('akuntansi/subklass_akun');
	}
	function set_subklass_akun(){
		$ID				=$_POST['ID'];
		$ID_Klasifikasi	=$_POST['ID_Klas'];
		$Kode			=$_POST['Kode'];
		$SubKlasifikasi	=$_POST['SubKlas'];
		$data1	="ID,ID_Klasifikasi,Kode,SubKlasifikasi";
		$data	="'$ID','$ID_Klasifikasi','$Kode','$SubKlasifikasi'";
		$this->akun_model->set_subklas_akun($data1,$data);
	}
	function get_subklas_ID(){
		$ID=$_POST['ID'];
		$data=$this->Admin_model->show_list('Sub_Klasifikasi',$ID);
		echo json_encode($data[0]);
	}
	//tampilkan data grid subklasifikasi
	function get_subklass_akun(){
		$data=array();$n=0;
		$where=empty($_POST['wh'])?'':$_POST['wh'];
		$data=$this->Admin_model->show_list('Sub_Klasifikasi',"$where order by ID_Klasifikasi,Kode");
		//print_r($data);
		foreach($data as $row){
		$n++;
		echo "<tr class='xx' id='".$row->ID."' align='center'>
			 <td class='kotak'>$n</td>
			 <td class='kotak' align='left'>".$row->ID_Klasifikasi."-".rdb('Klasifikasi','Klasifikasi','Klasifikasi',"where ID='".$row->ID_Klasifikasi."'")."</td>
			 <td class='kotak'>".$row->Kode."</td>
			 <td class='kotak' align='left'>".$row->SubKlasifikasi."</td>
			 <td class='kotak'>
			 	<img src='".base_url()."asset/images/editor.png' onClick=\"img_onClick('".$row->ID."','edit');\">
			 	<img src='".base_url()."asset/images/no.png' onClick=\"img_onClick('".$row->ID."','del');\">
			 </td></tr>\n";	
		}
	}
	// List Chart of Account atau Kode perkiraan
	function akun(){
		$this->zetro_auth->menu_id(array('addperkiraan','perkiraan'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('akuntansi/akun');
	}
	function set_akun(){
			$ID_Klas	=empty($_POST['ID_Klas'])?'0':$_POST['ID_Klas'];
			$ID_SubKlas	=empty($_POST['ID_SubKlas'])?'0':$_POST['ID_SubKlas'];
			$ID_Unit	=empty($_POST['ID_Unit'])?'0':$_POST['ID_Unit'];
			$ID_Laporan	=empty($_POST['ID_Laporan'])?'0':$_POST['ID_Laporan'];
			$ID_LapDetail=empty($_POST['ID_LapDetail'])?'0':$_POST['ID_LapDetail'];
			$NoUrut		=(int)substr($_POST['Kode'],6,4);
			$Kode		=substr($_POST['Kode'],6,4);
			$ID_Calc	=empty($_POST['ID_LapDetail'])?'0':$_POST['ID_Calc'];
			$Perkiraan	=$_POST['Perkiraan'];
			$SaldoAwal	=$_POST['SaldoAwal'];
			$ID_Dept	='1';
			$ID_Agt		='0';
			$ID_Simpanan='0';
			$field="ID_Klas,ID_SubKlas,ID_Unit,ID_Laporan,ID_LapDetail,NoUrut,Kode,ID_Calc,Perkiraan,SaldoAwal,ID_Dept,ID_Agt,ID_Simpanan";
			$data="'$ID_Klas','$ID_SubKlas','$ID_Unit','$ID_Laporan','$ID_LapDetail','$NoUrut','$Kode','$ID_Calc','$Perkiraan','$SaldoAwal','1','0','0'";
			$datax=$this->akun_model->set_akun($field,$data);
			echo $datax;
	}
	
	function get_akun(){
		$data	=array();$n=0;$filter='';$kode='';
		$filter	=empty($_POST['klas'])? '': "and ID_Klas='".$_POST['klas']."'";
		$filter	.=empty($_POST['subklas'])? '': " and ID_SubKlas='".$_POST['subklas']."'";
		$filter	.=empty($_POST['unit_j'])? '': " and ID_Unit='".$_POST['unit_j']."'";
		$data	=$this->Admin_model->show_list('perkiraan',"where ID_Agt='0' $filter order by ID_Klas,ID_SubKlas,ID_Unit,Kode");
		foreach($data as $row){
		$n++;
		$kode	 =rdb('Klasifikasi','Kode','Kode',"where ID='".$row->ID_Klas."'");
		$kode	.=rdb('Sub_Klasifikasi','Kode','Kode',"where ID='".$row->ID_SubKlas."'");
		$kode	.=rdb('unit_jurnal','Kode','Kode',"where ID='".$row->ID_Unit."'");
		echo "<tr class='xx' id='".$row->ID."' align='center'>
			 <td class='kotak'>$n</td>
			 <td class='kotak' align='left'>".$row->ID_Klas."-".rdb('Klasifikasi','Klasifikasi','Klasifikasi',"where ID='".$row->ID_Klas."'")."</td>
			 <td class='kotak' align='left'>".rdb('Sub_Klasifikasi','Kode','Kode',"where ID='".$row->ID_SubKlas."'")."-".rdb('Sub_Klasifikasi','SubKlasifikasi','SubKlasifikasi',"where ID='".$row->ID_SubKlas."'")."</td>
			 <td class='kotak'>".rdb('unit_jurnal','Unit','Unit',"where ID='".$row->ID_Unit."'")."</td>
			 <td class='kotak' align='left'>".$kode.'00'.$row->Kode."</td>
			 <td class='kotak' align='left'>".$row->Perkiraan."</td>
			 <td class='kotak'>
			 	<img src='".base_url()."asset/images/editor.png' onClick=\"img_onClick('".$row->ID."','edit');\">
			 	<img src='".base_url()."asset/images/no.png' onClick=\"img_onClick('".$row->ID."','del');\">
			 </td></tr>\n";	
		}
	}
	function get_akun_edit(){
		$data=array();
		$id		=$_POST['ID'];
		$data	=$this->akun_model->get_akun($id);
		echo json_encode($data[0]);
	}
	function get_nomor_akun(){
			$ID_Klas	=$_POST['ID_Klas'];
			$ID_SubKlas	=$_POST['ID_SubKlas'];
			$ID_Unit	=$_POST['ID_Unit'];
			$ID_Laporan	=$_POST['ID_Laporan'];
			$ID_LapDetail=$_POST['ID_LapDetail'];
			$field	 =empty($ID_Klas)?'':'ID_Klas';
			$field	.=empty($ID_SubKlas)?'':',ID_SubKlas';
			$field	.=empty($ID_Unit)?'':',ID_Unit';
			$field	.=empty($ID_Laporan)?'':',ID_Laporan';
			$field	.=empty($ID_LapDetail)?'':',ID_LapDetail';
			$concat="where concat($field)='".$ID_Klas.$ID_SubKlas.$ID_Unit.$ID_Laporan.$ID_LapDetail."'";
			$data=$this->akun_model->get_nomor_akun($concat);
			if(count($data)!=0){
			echo json_encode($data[0]);
			}else {
				$concat="where concat(ID_Klas,ID_SubKlas,ID_Unit,ID_Laporan)='".$ID_Klas.$ID_SubKlas.$ID_Unit.$ID_Laporan."'";
				$datax=$this->akun_model->get_nomor_akun($concat);
				if(count($datax)!=0){
					foreach($datax as $dt){
						$kode=$dt->NoUrut;
						if(strlen($kode)==0){
							$kode='0001';
							}else if(strlen($kode)==1){
								$kode='000'.($kode+1);
							}else if(strlen($kode)==2){
								$kode='00'.($kode+1);
							}else if(strlen($kode)==3){
								$kode='0'.($kode+1);
							}else if(strlen($kode)==4){
								$kode=($kode+1);
						}
					}
				}else{
					$kode='0001';
				}
					echo json_encode(array( 'Kode'=>$kode,
											'ID_Calc'=>'',
											'Perkiraan'=>'',
											'SaldoAwal'=>'0'));
			}
	}
	
	function hapus_akun(){
		$ID=$_POST['ID'];
		$tbl=$_POST['sumber'];
		echo $this->Admin_model->hps_data($tbl,"where ID='".$ID."'");	
		//echo $ID;
	}
 //addition 
   function fill_dropdown(){
	   $data=array();
	   $table	=$_POST['table'];
	   $id		=$_POST['id'];
	   $val		=$_POST['val'];
	   $where	=empty($_POST['where'])?'':$_POST['where'];
	   $pilih	=empty($_POST['pilih'])?'':$_POST['pilih'];
	   dropdown($table,$id,$val,$where,$pilih);
   }
   function check_status_akun(){
	$ID=$_POST['ID'];
	$data=$this->Admin_model->show_single_field('transaksi','ID_Perkiraan',"where ID_Perkiraan='$ID'");
	echo ($data=='')? "hapus":"dipakai";
   }
   function check_status_Klas(){
	$ID=$_POST['ID'];
	$data=$this->Admin_model->show_single_field('Perkiraan','ID_Klas',"where ID_Klas='$ID'");
	echo ($data=='')? "hapus":"dipakai";
   }
   function check_status_SubKlas(){
	$ID=$_POST['ID'];
	$data=$this->Admin_model->show_single_field('Perkiraan','ID_SubKlas',"where ID_SubKlas='$ID'");
	echo ($data=='')? "hapus":"dipakai";
   }
   function get_bulan(){
	dropdown('mst_bulan','ID','Bulan',"order by ID",date('m'));   
   }
   function get_tahun(){
	   dropdown("jurnal","distinct(Tahun) as Tahun","Tahun","where Tahun >0 order by Tahun",date('Y'));
   }
 //========================================================================================//
 //Jurnal Umum function
 function jurnal(){
		$this->zetro_auth->menu_id(array('listjurnalumum','addjurnal'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('akuntansi/jurnal_umum');
 
 }
 //create jurnal number
 function set_jurnal(){
	 $data=array();
	 $data['Tanggal']	=tgltoSql($_POST['Tgl']);//.'00:00:00';
	 $data['ID_Unit']	=$_POST['ID_Unit'];
	 $data['Nomor']		=$_POST['nomor'];
	 $data['Keterangan']=$_POST['Keterangan'];
	 $data['ID_Tipe']	='1';
	 $data['ID_Bulan']	=substr($_POST['Tgl'],3,2);
	 $data['Tahun']		=substr($_POST['Tgl'],6,4);
	 $data['ID_Mark']	='0';
	 $data['NoUrut']	=substr($_POST['nomor'],3,(strlen($_POST['nomor'])-3));
	 echo $this->Admin_model->replace_data('jurnal',$data);
	 
 }
 function get_no_jurnal(){
		$arr=array();
		$str=$_GET['str'];
		$limit=$_GET['limit'];
		$datax=$this->akun_model->get_no_jurnal($str,$limit);
		echo json_encode($datax);	
 }
 function  get_total_KD(){ //get total kredit and debet
		$ID_jurnal	=$_POST['ID_jurnal'];
		$Tanggal	=$_POST['Tanggal'];
		$NoJurnal	=$_POST['NoJurnal'];
		$ID_Unit	=$_POST['ID_Unit'];
		$Keterangan	=$_POST['Keterangan'];
		$data=$this->akun_model->get_total_KD($ID_jurnal);
		$debet=0;$kredit=0;
		foreach($data as $row){
			$debet	=number_format($row->Debet,2);
			$kredit	=number_format($row->Kredit,2);
		}
 echo "<form id='frm23' name='frm23' method='post' action=''>
	   <input type='hidden' name='ID_Jurnal' value='$ID_jurnal' id='ID_Jurnal'></form>
	   <table style='border-collapse:collapse' width='99%' id='j_det'>
		<tr><td width='10%'>Tanggal</td>
			<td width='10%'><input type='text' id='Tgl' readonly value='$Tanggal' class='w90 carix'></td>
			<td width='10%'>No. Jurnal</td>
			<td width='10%'><input type='text' id='NoJ' readonly value='$NoJurnal' class='w90 carix'></td>
			<td width='5%'>Unit</td>
			<td width='5%'><input type='text' id='unit' readonly value='$ID_Unit' class='w70 carix'></td>
			<td width='10%'class='border_l'>Total Debet</td>
			<td width='10%'><input type='text' id='debet' readonly value='$debet' class='w90 carix angka'></td>
		</tr>\n
		<tr><td >Keterangan</td>
			<td colspan='5'><input type='text' id='Ket' readonly value='$Keterangan' class='w100 carix'></td>
			<td class='border_l'>Total Kredit</td>
			<td ><input type='text' id='kredit' value='$kredit' readonly class='w90 carix angka'></td>
		</tr>\n
		</table>
		<hr>";
}
 function get_last_jurnal(){ //initailze last jurnal number
		$ID_Unit=$_POST['ID_Unit'];
		$data=$this->akun_model->get_last_jurnal($ID_Unit);
		echo $data;
 }
 function get_list_jurnal(){ //list jurnal
	 $data=array();$n=0;
	 $filter	=$_POST['filter'];
	 $daritgl	=empty($_POST['daritgl'])?'':tgltoSql($_POST['daritgl']);
	 $smptgl	=empty($_POST['smptgl'])?'':tgltoSql($_POST['smptgl']);
	 $bln		=$_POST['Bln'];
	 $thn		=$_POST['Thn'];
	 $unit		=$_POST['ID_Unit'];
	 switch($filter){ //pilih filter yang aktif
		case 'all':
		($unit=='all')? $where='':$where ="where ID_Unit='$unit'";
		break;
		case 'tgl':
		($unit=='all')? $where="where Tanggal between '$daritgl' and '$smptgl'":$where ="where Tanggal between '$daritgl' and '$smptgl' and ID_Unit='$unit'";
		break;
		case 'bln':
		($unit=='all')? $where="where ID_Bulan='$bln' and Tahun='$thn'":$where="where ID_Bulan='$bln' and Tahun='$thn' and ID_Unit='$unit'";
		break;
	 }
	 $auth=$this->zetro_auth->cek_oto('v','listjurnalumum');
	 $data=$this->akun_model->get_jurnal($where);
	 if(count($data)){
		 foreach($data as $row){
		 $n++;
	 	 ($auth!='')? $oto="onClick=\"show_jurnal_detail('".$row->ID_Jurnal."');\" title='Click for detail jurnal'":"";
		 (($row->Debet-$row->Kredit)!=0)? $class='list_genap':$class='';
			echo "<tr class='xx $class' id='".$row->ID_Jurnal."' align='center' $oto >
				  <td class='kotak'>$n</td>
				  <td class='kotak'>".tglfromSql($row->Tanggal)."</td>
				  <td class='kotak'>".rdb('unit_jurnal','unit','unit',"where ID='".$row->ID_Unit."'")."</td>
				  <td class='kotak'>".$row->Nomor."</td>
				  <td class='kotak' align='left'>".$row->Ket."</td>
				  <td class='kotak' align='right'>".number_format($row->Debet,2)."</td>
				  <td class='kotak' align='right'>".number_format($row->Kredit,2)."</td>
				  <td class='kotak' align='right'>". number_format(($row->Debet-$row->Kredit),2)."</td>
				  </tr>\n";
		 }
	 }else{
		 echo "<tr><td class='kotak' colspan='8'>Data not found....</td></tr>";
	 }
 }
 function get_detail_jurnal(){ 
 //tampilkan detail jurnal jika click pada list jurnal
	$data	=array();$t_Kredit=0;$t_Debet=0;
	$ID		=$_POST['ID'];
	$Tahun	=$_POST['Tahun'];
	$data	=$this->akun_model->detail_jurnal($ID);
	$n=0;$t_Debet=0;$t_Kredit=0;
	 foreach($data as $row){
		 $n++;
		 echo "<tr class='xx' id='r-".$row->IDT."'>
		 	  <td class='kotak' align='center'>$n</td>
		 	  <td class='kotak' align='center'>".$row->Kode."</td>
			  <td class='kotak' nowrap>".$row->Perkiraan."</td>
			  <td class='kotak' align='right'>".number_format($row->Debet,2)."</td>
			  <td class='kotak' align='right'>".number_format($row->Kredit,2)."</td>
			  <td class='kotak' nowrap align='left'>
			  <table style='border-collapse:collapse' width='100%'>
			  <tr valign='middle'><td width='95%'>".$row->Keterangan."</td>
			  <td align='right'>";
		echo ($Tahun == date('Y'))?
			 " <img src='".base_url()."asset/images/no.png' title='Hapus transaksi dari jurnal ini' onclick=\"hapus_content('".$row->IDT."');\">":"";
		echo "</td></tr></table></td>";
		echo "</tr>\n";
		$t_Debet	=($t_Debet+$row->Debet);
		$t_Kredit	=($t_Kredit+$row->Kredit);
	 }
	 echo "<tr class='list_genap'>
	 		<td class='kotak' colspan='3' align='right'><input type='hidden' id='nj' value='".$ID."'><b>Total</b></td>
			<td class='kotak' align='right'><b>".number_format($t_Debet,2)."</b></td>
			<td class='kotak' align='right'><b>".number_format($t_Kredit,2)."</b></td>
			<td class='kotak' align='right'><!--b>".number_format(($t_Debet-$t_Kredit),2)."</b--></td>
			<!--td class='kotak'>&nbsp</td-->
			</tr>\n";
 }
 //posting transaksi ke jurnal umum
 function add_jurnal_content(){ //tampilkan semua transaksi yang belum di jurnal
	$data	=array();
	$no_jurn=$_POST['ID'];
	$ID_Perkiraan=empty($_POST['ID_Akun'])?'1':$_POST['ID_Akun'];
	$data	=$this->akun_model->new_transaksi($ID_Perkiraan);
	$n=0;$t_Debet=0;$t_Kredit=0;
	 foreach($data as $row){
		 $n++;
		 echo "<tr class='xx' id='".$row->ID."'>
		 	  <td class='kotak' align='center'>
			  <input type='checkbox' id='r-".$row->ID."' onClick=\"addtojurnal('".$row->ID."','".$no_jurn."','".$ID_Perkiraan."');\"></td>
		 	  <td class='kotak' align='center'>".$row->Kode."</td>
			  <td class='kotak' nowrap>".$row->Perkiraan."</td>
			  <td class='kotak' align='right'>".number_format($row->Debet,2)."</td>
			  <td class='kotak' align='right'>".number_format($row->Kredit,2)."</td>
			  <td class='kotak' nowrap align='left'>".$row->Keterangan."</td>";
		echo "</tr>\n";
		$t_Debet	=($t_Debet+$row->Debet);
		$t_Kredit	=($t_Kredit+$row->Kredit);
	 }
 
 }
 function add_to_jurnal(){ //tambah transaki ke jurnal
	$data=array();
	$ID			=$_POST['id'];
	$ID_Jurnal	=$_POST['ID_Jurnal'];
	
	//collect data as values table transaksi
	$data['ID_Jurnal']		=$ID_Jurnal;
	$data['ID_Perkiraan']	=rdb('transaksi_temp','ID_Perkiraan','ID_Perkiraan',"where ID='$ID'");
	$data['Debet']			=rdb('transaksi_temp','Debet','Debet',"where ID='$ID'");
	$data['Kredit']			=rdb('transaksi_temp','Kredit','Kredit',"where ID='$ID'");
	$data['Keterangan']		=rdb('transaksi_temp','Keterangan','Keterangan',"where ID='$ID'");
	 echo $this->Admin_model->replace_data('transaksi',$data);
	 $this->Admin_model->upd_data('transaksi_temp',"set ID_Stat='1'","where ID='$ID'");
 }
 function add_balance_jurnal(){
	 $data='';
	$data['ID_Jurnal']		=$_POST['ID_Jurnal'];
	$data['ID_Perkiraan']	=$_POST['ID_Perkiraan'];
	$data['Debet']			=($_POST['ID_Jenis']=='1')? $_POST['Jml']:'0';
	$data['Kredit']			=($_POST['ID_Jenis']=='2')? $_POST['Jml']:'0';
	$data['Keterangan']		=$_POST['Keterangan'];
	$datax=$this->Admin_model->replace_data('transaksi',$data);
	echo ($datax=='1')? "Data berhasil di simpan":"Gagal mohon di check lagi data yang diinput";
 }
 function hapus_transaksi(){
	$ID=$_POST['ID'];
	$this->Admin_model->hps_data("transaksi","where ID='$ID'"); 
	//$this->Admin_model->upd_data('transaksi_temp',"set ID_Stat='0'","where ID='$ID'");
 }
 function header_perkiraan(){
	 $data=array();
	 $data=$this->akun_model->get_simpanan_name('order by id');
	 echo "<tr valign='middle'>
	 	   <td width='10px' align='right'>
		   <input style='cursor:pointer' type='radio' name='akun_pilihan' id='ID_KBR' checked onclick=\"process('ID_KBR');\"></td>
		   <td width='100px' >KBR</td>
	 	   <td width='10px' align='right'>
		   <input style='cursor:pointer' type='radio' name='akun_pilihan' id='ID_USP' onclick=\"process('ID_USP');\"></td>
		   <td width='100px' >USP</td>";
	 foreach($data->result() as $r){
		echo"<td width='10px' align='right'><input style='cursor:pointer' type='radio' name='akun_pilihan' id='p-".$r->ID."' onclick=\"process('".$r->ID."');\"></td>
		   <td width='100px' >".$r->Jenis."</td>";
	 }
	 echo "</tr>\n";
 }
 //print report to PDF
 function print_list_jurnal(){
	 $data=array();$n=0;$where='';
	 $filter	=$this->input->post('fby');
	 $daritgl	=($this->input->post('daritgl')=='')?'':tgltoSql($this->input->post('daritgl'));
	 $smptgl	=($this->input->post('smptgl')=='')? tgltoSql($this->input->post('daritgl')):tgltoSql($this->input->post('smptgl'));
	 $bln		=$this->input->post('Bln');
	 $thn		=$this->input->post('Thn');
	 $unit		=$this->input->post('ID_Unit');
	 switch($filter){
		case 'all':
		($unit=='all')? $where='':$where ="where ID_Unit='$unit'";
		break;
		case 'tgl':
		($unit=='all')? $where="where Tanggal between '$daritgl' and '$smptgl'":$where ="where Tanggal between '$daritgl' and '$smptgl' and ID_Unit='$unit'";
		break;
		case 'bln':
		($unit=='all')? $where="where ID_Bulan='$bln' and Tahun='$thn'":$where="where ID_Bulan='$bln' and Tahun='$thn' and ID_Unit='$unit'";
		break;
	 }
		$data['tanggal']=($this->input->post('daritgl')=='')? nBulan($this->input->post('Bln')).' '.$this->input->post('Thn'):$this->input->post('daritgl').' s/d '.$this->input->post('smptgl');
		$data['ID_Unit']=($this->input->post('ID_Unit')=='all')? "Gabungan":rdb('unit_jurnal','unit','unit',"where ID='".$this->input->post('ID_Unit')."'");
		$data['temp_rec']=$this->akun_model->get_jurnal($where);
		//proces generate pdf
		$this->zetro_auth->menu_id(array('trans_beli'));
		$this->list_data($data);
		$this->View("akuntansi/jurnal_umum_print");
 }
 //show header jurnal total debet dan kredit
 function header_popup(){
	 $data=array();
		$ID_jurnal	=$_POST['ID_jurnal'];
		$data=$this->akun_model->get_total_KD($ID_jurnal);
		$debet=0;$kredit=0;
		$Tanggal='';$NoJurnal='';$ID_Unit='';$Keterangan='';
		foreach($data as $row){
			$Tanggal	=tglfromSql(substr($row->Tanggal,0,10));
			$NoJurnal	=$row->nomor;
			$ID_Unit	=rdb("unit_jurnal",'unit','unit',"where ID='".$row->ID_Unit."'");
			$Keterangan	=$row->Ket;
			$debet		=number_format($row->Debet,2);
			$kredit		=number_format($row->Kredit,2);
		}
 echo "<form name='frm22' id='frm22' method='post' action=''>
		<input type='hidden' name='ID_Jurnal' id='ID_Jurnal' value='$ID_jurnal'>
	  </form>\n<hr>
	 	<table style='border-collapse:collapse' width='99%' id='j_dete'>
		<tr><td width='10%'>Tanggal</td>
			<td width='10%'><input type='text' id='Tgl' readonly value='$Tanggal' class='w90 carix'></td>
			<td width='10%'>No. Jurnal</td>
			<td width='10%'><input type='text' id='NoJ' readonly value='$NoJurnal' class='w90 carix'></td>
			<td width='5%'>Unit</td>
			<td width='5%'><input type='text' id='unit' readonly value='$ID_Unit' class='w70 carix'></td>
			<td width='10%'class='border_l'>Total Debet</td>
			<td width='10%'><input type='text' id='debet' readonly value='$debet' class='w90 carix angka'></td>
		</tr>\n
		<tr><td >Keterangan</td>
			<td colspan='5'><input type='text' id='Ket' readonly value='$Keterangan' class='w100 carix'></td>
			<td class='border_l'>Total Kredit</td>
			<td ><input type='text' id='kredit' value='$kredit' readonly class='w90 carix angka'></td>
		</tr>\n
		</table>
		<hr>";
 }
 function print_detail_jurnal(){
 		$data=array();
		$ID_jurnal	=$this->input->post('ID_Jurnal');
		$datax=$this->akun_model->get_total_KD($ID_jurnal);
		$debet=0;$kredit=0;
		$Tanggal='';$NoJurnal='';$ID_Unit='';$Keterangan='';
		foreach($datax as $row){
			$data['Tanggal']	=tglfromSql(substr($row->Tanggal,0,10));
			$data['NoJurnal']	=$row->nomor;
			$data['ID_Unit']	=rdb("unit_jurnal",'unit','unit',"where ID='".$row->ID_Unit."'");
			$data['Keterangan']	=$row->Ket;
			$data['debet']		=number_format($row->Debet,2);
			$data['kredit']		=number_format($row->Kredit,2);
		}
			$data['temp_rec']	=$this->akun_model->detail_jurnal($ID_jurnal);

		//proces generate pdf
		//print_r($data);
			$this->zetro_auth->menu_id(array('trans_beli'));
			$this->list_data($data);
			$this->View("akuntansi/jurnal_detail_print");

 }
 //=========================================================================================//
 //process popup add content jurnal
 
 	function get_SubJenis(){
		$data	=array();
		$ID		=$_POST['ID'];
		$data	=$this->akun_model->kode_akun($ID);
		echo "<option value=''>&nbsp;</option>";
		foreach($data as $rw){
			echo "<option value='".$rw->ID."'>".$rw->Kode ." - ". $rw->Perkiraan."</option>";	
		}
	}
	
	function total_perjurnal(){
		$datax=array();
		$ID_jurnal=$_POST['ID_jurnal'];
		$data=$this->akun_model->get_total_KD($ID_jurnal);
		$debet=0;$kredit=0;
		foreach($data as $row){
			$datax=array('debet'	=>number_format($row->Debet,2),
						'kredit'	=>number_format($row->Kredit,2),
						'balance'	=>number_format(abs(($row->Debet-$row->Kredit)),2),
						'ket'		=>$row->Keterangan);
		}
		echo json_encode($datax);
	}
	
// buku besar
	function buku_besar(){
		$this->zetro_auth->menu_id(array('bukubesar'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('akuntansi/buku_besar');
	
	}
	function get_buku_besar(){
		$datax	=array(); $saldo_awal=0;
		$data	=array(); $n=0;$saldo=0;
		$t_kredit=0;$t_debet=0;$ip_temp='';
		$id_Calc='';$saldo_akhir=0;
		$akun	=$_POST['Akun'];
		$ID_Simp=$_POST['ID_SubKlas'];
		$perkiraan=$_POST['ID_P'];
		$mulai	=tgltoSql($_POST['Start']);
		$sampai	=empty($_POST['Stop'])?tgltoSql($_POST['Start']):tgltoSql($_POST['Stop']);
		//konversi ke id perkiraan
		$toAkun	=($ID_Simp != '4' && 
				 $ID_Simp != '17' && 
				 $ID_Simp != '18' && 
				 $ID_Simp != '19' &&
				 $ID_Simp != '28')? $perkiraan:/**/
		$this->akun_model->get_nomor_akun("where ID_Agt='$perkiraan' and id_subklas='$ID_Simp'");
		//dapatkan data saldoawal
		if(is_array($toAkun)){
			foreach($toAkun as $t){
				$ip_temp=$t->ID;
				$id_Calc=$t->ID_Calc;
			}
		}else{
			$ip_temp=$toAkun;
			$id_Calc=$this->Admin_model->show_single_field('perkiraan','id_calc',"where ID='$ip_temp'");
		}
		$datax	=$this->akun_model->get_saldo_awal($ip_temp);
		foreach($datax as $sa){
			$saldo_awal=$sa->saldoawal;
		}
		echo "<tr class='xx list_genap'>
			  <td class='kotak' align='center'>&bull;&bull;&bull;</td>
			  <td class='kotak' colspan='3'>Saldo Awal</td>
			  <td class='kotak'>&nbsp;</td>
			  <td class='kotak'>&nbsp;</td>
			  <td class='kotak' align='right'><b>".number_format($saldo_awal,2)."</b></td>
			  </tr>";
		$saldo=$saldo_awal;
		$data	=$this->akun_model->buku_besar_byDate($ip_temp,$mulai,$sampai);
			foreach($data as $r){
				$n++;
				$saldo =($id_Calc==2)?($saldo+$r->Kredit)-($r->Debet):($saldo+$r->Debet)-($r->Kredit);
				echo "<tr class='xx'>
					  <td class='kotak' align='center'>$n</td>
					  <td class='kotak' align='center'>".tglfromSql($r->Tanggal)."</td>
					  <td class='kotak'>".$r->Nomor."</td>
					  <td class='kotak'>".$r->Keterangan."</td>
					  <td class='kotak' align='right'>".number_format($r->Debet,2)."</td>	
					  <td class='kotak' align='right'>".number_format($r->Kredit,2)."</td>	
					  <td class='kotak' align='right'>".number_format($saldo,2)."</td>
					  </tr>";
				$t_kredit	=($t_kredit+$r->Kredit);
				$t_debet	=($t_debet+$r->Debet);
				
			}
			$saldo_akhir=($id_Calc==2)? ($saldo_awal+$t_kredit-$t_debet):($saldo_awal+$t_debet-$t_kredit);
			if($n>=1){
				echo "<tr class='list_genap'>
					  <td class='kotak'>&bull;&bull;&bull;</td>
					  <td class='kotak' colspan='3'><b>Saldo Akhir</b>
					  <td class='kotak' align='right'><b>".number_format($t_debet,2)."</b></td>
					  <td class='kotak' align='right'><b>".number_format($t_kredit,2)."</b></td>
					  <td class='kotak' align='right'><b>".number_format($saldo_akhir,2)."</b></td>
					  </tr>";
			}
			if($n==0){
				echo "<tr class='xx'>
					<td colspan='7' class='kotak'><b>&bull;&nbsp; Belum ada data....</td>
					</tr>";
			}
		
	}
	function get_bukubesar_tahunan(){
		$data=array(); $n=0;$saldo=0;$toAkun='';
		$saldo_awal=0;
		$t_kredit=0;$t_debet=0;$ip_temp='';
		$akun	=$_POST['ID_P'];
		$ID_Simp=$_POST['ID_SubKlas'];
		$tahun	=$_POST['Tahun'];
		$toAkun	=($ID_Simp != '4' && 
				 $ID_Simp != '17' && 
				 $ID_Simp != '18' && 
				 $ID_Simp != '19' &&
				 $ID_Simp != '28')? $akun:/**/
				 $this->akun_model->get_nomor_akun("where ID_Agt='$akun' and id_subklas='$ID_Simp'");
			if(is_array($toAkun)){
				foreach($toAkun as $t){
					$ip_temp=$t->ID;
					$id_Calc=$t->ID_Calc;
				}
			}else{
				$ip_temp=$toAkun;
				$id_Calc=$this->Admin_model->show_single_field('perkiraan','id_calc',"where ID='$ip_temp'");
			}
		$datax	=($ID_Simp != '4' && 
				 $ID_Simp != '18' && 
				 $ID_Simp != '19' &&
				 $ID_Simp != '28')? 
				 $this->akun_model->get_saldo_awal($ip_temp):
				 $this->akun_model->get_saldo_simpanan($ip_temp,$tahun);
		foreach($datax as $sa){
			$saldo_awal=$sa->saldoawal;
		}
		echo "<tr class='xx list_genap'>
			  <td class='kotak' align='center'>&bull;&bull;&bull;</td>
			  <td class='kotak' colspan='3'>Saldo Awal</td>
			  <td class='kotak' align='right'><b>".number_format($saldo_awal,2)."</b></td>
			  </tr>";
		$saldo=$saldo_awal;
		$data=$this->akun_model->buku_besar_byTahun($ip_temp,$tahun);
			foreach($data as $r){
				$n++;
				$saldo =($id_Calc==2)?($saldo+$r->Kredit)-($r->Debet):($saldo+$r->Debet)-($r->Kredit);
				echo "<tr class='xx'>
					  <td class='kotak' align='center'>$n</td>
					  <td class='kotak'>".nBulan($r->ID_Bulan)."</td>
					  <td class='kotak' align='right'>".number_format($r->Debet,2)."</td>	
					  <td class='kotak' align='right'>".number_format($r->Kredit,2)."</td>	
					  <td class='kotak' align='right'>".number_format($saldo,2)."</td>
					  </tr>";	
				$t_kredit	=($t_kredit+$r->Kredit);
				$t_debet	=($t_debet+$r->Debet);
			}
		
			$saldo_akhir=($id_Calc==2)? ($saldo_awal+$t_kredit-$t_debet):($saldo_awal+$t_debet-$t_kredit);
			if($n>=1){
				echo "<tr class='list_genap'>
					  <td class='kotak'>&bull;&bull;&bull;</td>
					  <td class='kotak' colspan='3'><b>Saldo Akhir</b>
					  <td class='kotak' align='right'><b>".number_format($saldo_akhir,2)."</b></td>
					  </tr>";
			}
	}
	function dropdown_subklas(){
		$ID=$_POST['ID_Klas']	;
		dropdown('sub_klasifikasi','ID','SubKlasifikasi',"where ID_Klasifikasi='$ID'");
	}
	function dropdown_tahun(){
		dropdown('jurnal','distinct(Tahun) as Tahun','Tahun',"order by Tahun",date('Y'));	
	}
	function dropdown_agt(){
		$ID_Dept=$_POST['ID_Dept']	;
		$ID_Simp=$_POST['ID_SubKlas'];
		$ID_Klas=$_POST['ID_Klas'];
		echo $ID_Simp;
		$data=($ID_Simp != '4' && 
			   $ID_Simp != '17' && 
			   $ID_Simp != '18' && 
			   $ID_Simp != '19' &&
			   $ID_Simp != '28')?
					$this->akun_model->get_perkiraan($ID_Dept,$ID_Simp,$ID_Klas):
					$this->akun_model->get_agt_kode($ID_Dept,$ID_Simp);
		echo "<option value=''></option>";
		foreach($data as $rs){
			echo "<option value='".$rs->ID."'>".$rs->Kode."-".$rs->Nama."</option>";	
		}
	}
}