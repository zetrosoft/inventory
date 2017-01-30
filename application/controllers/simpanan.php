<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//class name	: Simpanan
//version		: 1.0
//Author		: Iswan Putera

class Simpanan extends CI_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->model("Admin_model");
		$this->load->model("member_model");
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
	//process transaksi simpanan anggota kredit maupun debit
	function t_simpanan(){
		$this->zetro_auth->menu_id(array('transaksisimpanan'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('member/member_simpanan');
	}
	//teknik membercepat loading database mst_anggota dengan memasukan data yang di pilih
	//table copy
	function set_copy_agt(){
		$ID_Dept=$_POST['ID_Dept'];
		$ID_pinj=empty($_POST['ID_Pinj'])?'':$_POST['ID_Pinj'];
		echo $this->member_model->copy_agt($ID_Dept,$ID_pinj);	
	}
	//baca data setup simpanan wajib dan pokok anggota
	function get_jml_simpanan(){
		$ID=$_POST['ID'];
		$data=$this->member_model->jml_simpanan($ID);
		echo $data;
	}
	//simpan transaksi simpanan anggota
	function set_simpanan(){
		$data=array();
		$ID_Jenis		=$_POST['ID_Jenis'];
		$ID_Unit		=empty($_POST['ID_Unit'])?$this->session->userdata('gudang'):$_POST['ID_Unit'];
		$ID_Simpanan	=$_POST['ID_Simpanan'];
		$ID_Dept		=$_POST['ID_Dept'];
		$ID_Perkiraan	=$_POST['ID_Perkiraan'];
		$jumlah			=$_POST['jumlah'];
		$data['ID_Unit']	=$ID_Unit;
		$data['ID_Dept']	=$ID_Dept;
		$data['ID_Klas']	=rdb("jenis_simpanan",'ID_Klasifikasi','ID_Klasifikasi',"where ID='$ID_Simpanan'");
		$data['ID_SubKlas']	=rdb("jenis_simpanan",'ID_Klasifikasi','ID_Klasifikasi',"where ID='$ID_Simpanan'");
		$data['ID_Perkiraan']=rdb('perkiraan','ID','ID',"where ID_Agt='$ID_Perkiraan' and ID_Simpanan='$ID_Simpanan'");
		$data['Debet']		=($ID_Jenis==1)?$jumlah:0;
		$data['Kredit']		=($ID_Jenis==2)?$jumlah:0;
		$data['Keterangan']	=$_POST['keterangan'];
		$data['ID_Stat']	='0';
		$data['ID_Bulan']	=$_POST['ID_Bulan'];
		$data['Tahun']		=$_POST['Tahun'];
		$data['created_by']	=$this->session->userdata('userid');
		echo $this->Admin_model->replace_data('transaksi_temp',$data);
	}
	//simpan transaksi untuk pembayaran dengan cara potong gaji
	function set_potonggaji(){
		$ID_Jenis	=$_POST['ID_Jenis'];
		$ID_Agt		=$_POST['ID_Agt'];
		$ID_Simpanan=$_POST['ID_Simpanan'];
		$jumlah		=$_POST['jumlah'];
		//collect data to array
		$data['ID_Unit']	=rdb('perkiraan','ID_Unit','ID_Unit',"where ID_Agt='$ID_Agt'");
		$data['ID_Dept']	=rdb('perkiraan','ID_Dept','ID_Dept',"where ID_Agt='$ID_Agt'");
		$data['ID_Klas']	=rdb("jenis_simpanan",'ID_Klasifikasi','ID_Klasifikasi',"where ID='$ID_Simpanan'");
		$data['ID_SubKlas']	=rdb("jenis_simpanan",'ID_Klasifikasi','ID_Klasifikasi',"where ID='$ID_Simpanan'");
		$data['ID_Perkiraan']=rdb('perkiraan','ID','ID',"where ID_Agt='$ID_Agt' and ID_Simpanan='$ID_Simpanan'");
		$data['Debet']		=($ID_Jenis==1)?$jumlah:0;
		$data['Kredit']		=($ID_Jenis==2)?$jumlah:0;
		$data['Keterangan']	=$_POST['keterangan'];
		$data['ID_Stat']	='0';
		$data['ID_Bulan']	=$_POST['ID_Bulan'];
		$data['Tahun']		=$_POST['Tahun'];
		$data['created_by']	=$this->session->userdata('userid');
		echo $this->Admin_model->replace_data('transaksi_temp',$data);
			
	}
	//populate daftar anggota yang belum melakukan pembayaran di bulan yang dipilih
	function get_agt_blmbayar(){
		$data=array();$n=0;
		$ID_Dept	=$_POST['ID_Dept'];
		$ID_Simpanan=$_POST['ID_Simpanan'];
		$ID_Bulan	=$_POST['ID_Bulan'];
		$Tahun		=$_POST['Tahun'];		
		$data=$this->member_model->agt_blmbayar($ID_Dept,$ID_Simpanan,$ID_Bulan,$Tahun);
			foreach($data as $r){
				$n++;
				$kode='';
				$kode.=rdb('Klasifikasi','Kode','Kode',"where ID='".
					   rdb('perkiraan','ID_Klas','ID_Klas',"where ID_Agt='".$r->ID."' and ID_Simpanan='$ID_Simpanan'")."' ");
				$kode.=rdb('Sub_Klasifikasi','Kode','Kode',"where ID='".
					   rdb('perkiraan','ID_SubKlas','ID_SubKlas',"where ID_Agt='".$r->ID."' and ID_Simpanan='$ID_Simpanan'")."'");
				$kode.=rdb('unit_jurnal','Kode','Kode',"where ID='".
					   rdb('perkiraan','ID_Unit','ID_Unit',"where ID_Agt='".$r->ID."' and ID_Simpanan='$ID_Simpanan'")."'");
				$kode.=rdb('mst_departemen','Kode','Kode',"where ID='".$r->ID_Dept."'");
				$kode.=$r->No_Perkiraan;
				$jumlah=rdb('setup_simpanan','min_simpanan','min_simpanan',"where id_simpanan='$ID_Simpanan'");
				empty($jumlah)?$jumlah='0':$jumlah=$jumlah;
				($ID_Simpanan=='3')?
				$simp_khusus=rdb('transaksi_temp','Kredit','Kredit',"where ID_Perkiraan='".
							 rdb('perkiraan','ID','ID',"where ID_Agt='".$r->ID."'
							     and ID_Simpanan='$ID_Simpanan'")."' and concat(ID_Bulan,Tahun)='".$ID_Bulan.$Tahun."'"):
				$simp_khusus='0';
				echo "<tr class='xx' align='center'>
					  <td class='kotak'>$n</td>
					  <td class='kotak'>".$kode."</td>
					  <td class='kotak' align='left'>".$r->Nama."</td>
					  <td class='kotak' align='right'>";
					  ($ID_Simpanan!='3')?$enabl='':$enabl="disabled='disabled'";
					  echo ($ID_Simpanan!='3')?number_format($jumlah,2):
					  		"<input type='text' id='t-".$r->ID."' class='angka' value='$simp_khusus' onkeyup=\"simkh('".$r->ID."');\" onmouseout=\"lostfocus('".$r->ID."');\">";
					  echo "</td>
					  <td class='kotak'><input type='checkbox' id='n-".$r->ID."' onClick=\"bayar('".$r->ID."','".$jumlah."','".$ID_Simpanan."');\" $enabl></td>
					  </tr>";
			}
	}
	//transaksi pinjaman uang dan barang
	//pinjaman barang dari toko masuk pada class penjualan toko
	function t_pinjaman(){
        $data["nasabah"]=$this->get_pelanggan();
		$this->zetro_auth->menu_id(array('pembayarantagihan','setoranpinjaman'));
		$this->list_data(array_merge($this->zetro_auth->auth(),$data));
		$this->View('member/member_pinjaman');
	}
    function get_pelanggan()
	{
		$data=array();$nasabah='';
		//$data=$this->Admin_model->show_list("mst_anggota","where id_jenis='1' and length(nama)>1 group by Nama,Alamat order by nama");
        $data=$this->Admin_model->show_list("mst_anggota_view","where  length(Nama)>1 group by Nama order by Nama");
		foreach($data as $r)
		{
			//$nasabah.="\"".str_replace("\""," ",strtoupper($r->Nama)).' - '.ucwords($r->Alamat)."\",";
            //$nasabah.="\"".str_replace("\\","-",str_replace("\""," ",strtoupper($r->Nama))).' - '.preg_replace('/\r\n?/', "\n",ucwords($r->Alamat))."\",";
            $nasabah.="\"".str_replace("\\","-",str_replace("\""," ",strtoupper($r->Nama)))."\",";
		}
		return substr($nasabah,0,-1);
	}
	function set_pinjaman(){
		$data=array();$datax=array();
		//simpan ke table pinjaman
			$data['ID_Agt']		=$_POST['ID_Agt'];
			$data['ID_Bulan']	=$_POST['ID_Bulan'];
			$data['Tahun']		=$_POST['Tahun'];
			$data['ID_Unit']	=$_POST['ID_Unit'];
			$data['ID_Dept']	=$_POST['ID_Dept'];
			$data['pinjaman	']	=$_POST['Pinjaman'];
			$data['cicilan']	=$_POST['cicilan'];
			$data['cicilan_end']=$_POST['cicilan_end'];
			$data['lama_cicilan']=$_POST['lama_cicilan'];
			$data['cara_bayar']	=$_POST['cara_bayar'];
			$data['mulai_bayar']=$_POST['mulai_bayar'];
			$data['keterangan']	=$_POST['keterangan'];
				$data['created_by']	=$this->session->userdata('userid');
				echo $this->Admin_model->replace_data('pinjaman',$data);
			//simpan kredit to table transaksi_temp / jurnal temporary
			$datax['ID_Unit']	=$_POST['ID_Unit'];
			$datax['ID_Dept']	=$_POST['ID_Dept'];
			$datax['ID_Klas']	=rdb('perkiraan','ID_Klas','ID_Klas',"where ID_Agt='".$_POST['ID_Agt']."' and ID_Simpanan='".$_POST['ID_Pinjaman']."'");
			$datax['ID_SubKlas']=rdb('perkiraan','ID_SubKlas','ID_SubKlas',"where ID_Agt='".$_POST['ID_Agt']."' and ID_Simpanan='".$_POST['ID_Pinjaman']."'");
			$datax['ID_Perkiraan']=rdb('perkiraan','ID','ID',"where ID_Agt='".$_POST['ID_Agt']."' and ID_Simpanan='".$_POST['ID_Pinjaman']."'");
			$datax['Debet']		=$_POST['Pinjaman'];
			$datax['keterangan']=$_POST['keterangan'];
			$datax['ID_Bulan']	=$_POST['ID_Bulan'];
			$datax['Tahun']		=$_POST['Tahun'];
			$datax['created_by']	=$this->session->userdata('userid');
				echo $this->Admin_model->replace_data('transaksi_temp',$datax);
	}
	function get_total_pinjaman(){
		$data=array();
		$ID_Agt	=$_POST['ID_Agt'];
		$data=$this->member_model->total_pinjaman($ID_Agt);
		echo json_encode($data);
	}
	function get_tot_pinjm(){
		$data=array();
		$ID_Agt=$_POST['ID_Agt'];	
		$data=$this->member_model->data_total_pinjm($ID_Agt);
		//echo count($data);
		echo(count($data)>0)?json_encode($data[0]):json_encode("{'debet':'0','saldo':'0'}");	
	}
	function set_bayar_pinjaman(){
		$data=array();
		$id_jenis=rdb('inv_penjualan','ID_Jenis','ID_Jenis',"where NoUrut='".$_POST['ID_Pinj']."' and Tahun='".$_POST['ThnAsal']."'");
			$debet=rdb("pinjaman_bayar","Saldo","(sum(Debet)-sum(Kredit)) as Saldo","where ID_Pinjaman='".$_POST['ID_Pinj']."' and Tahun='".$_POST['ThnAsal']."' group by ID_Pinjaman");
			//echo $debet."<br>";
			$data['ID_Pinjaman']=$_POST['ID_Pinj'];
			$data['ID_Agt']		=$_POST['ID_Agt'];
			$data['Tanggal']	=tglToSql($_POST['Tanggal']);
			$data['Tahun']		=$_POST['Tahun'];
			$data['Kredit']		=$_POST['Kredit'];
			$data['Keterangan']	=$_POST['Keterangan'];
			$data['saldo']		=((int)$debet-(int)$_POST['Kredit']);
			$data['created_by']	=$this->session->userdata('userid');
				 $this->Admin_model->replace_data('pinjaman_bayar',$data);
			//update lama cicilan jika masih ada sisa hutang
			$cekSaldo=rdb("pinjaman_bayar","Saldo","(sum(Debet)-sum(Kredit)) as Saldo","where ID_Pinjaman='".$_POST['ID_Pinj']."' and Tahun='".$_POST['ThnAsal']."'");
			$lama=rdb("pinjaman","lama_cicilan","lama_cicilan","where ID='".$_POST['ID_Pinj']."' and Tahun='".$_POST['ThnAsal']."'");
			($cekSaldo==0)?
			$this->Admin_model->upd_data('pinjaman',"set stat_pinjaman='1'","where ID='".$_POST['ID_Pinj']."' and Tahun='".$_POST['ThnAsal']."'"):
			$this->Admin_model->upd_data('pinjaman',"set lama_cicilan='".($lama+1)."'","where ID='".$_POST['ID_Pinj']."' and Tahun='".$_POST['ThnAsal']."'");
			//simpan kredit to table transaksi_temp / jurnal temporary
			$datax['ID_Unit']	=rdb('jenis_simpanan','ID_Unit','ID_Unit',"where ID='".$id_jenis."'");
			$datax['ID_Dept']	=$this->session->userdata('gudang');//rdb('jenis_simpanan','ID_Dept','ID_Dept',"where ID='".$id_jenis."'");
			$datax['ID_Klas']	=rdb('jenis_simpanan','ID_Klasifikasi','ID_Klasifikasi',"where ID='".$id_jenis."'");
			$datax['ID_SubKlas']=rdb('jenis_simpanan','ID_SubKlas','ID_SubKlas',"where ID='".$id_jenis."'");
			$datax['ID_Perkiraan']=rdb('perkiraan','ID','ID',"where ID_Agt='".$_POST['ID_Agt']."' and ID_Simpanan='".$id_jenis."'");
			$datax['Kredit']	=$_POST['Kredit'];
			$datax['keterangan']=$_POST['Keterangan']." a/n ".rdb('mst_anggota','Nama','Nama',"where ID='".$_POST['ID_Agt']."'");
			$datax['ID_Bulan']	=substr($_POST['Tanggal'],3,2);
			$datax['Tahun']		=$_POST['Tahun'];
			$datax['Tanggal']	=tglToSql($_POST['Tanggal']);
			$datax['ID_CC']		='7';
				$datax['created_by']	=$this->session->userdata('userid');
				$this->Admin_model->replace_data('transaksi_temp',$datax);
	}
	function update_keterangan(){
	 $id	=$_POST['id'];
	 $ket	=$_POST['Ket'];
	 	$this->Admin_model->upd_data('pinjaman_bayar',"set keterangan='$ket'","where id='".$id."'");
	}
	function hapus_setoran(){
		$id=$_POST['id'];
		$no_pinjaman=rdb('pinjaman_bayar','ID_Pinjaman','ID_Pinjaman',"where ID='".$id."'");
		$this->Admin_model->hps_data('pinjaman_bayar',"where ID='".$id."'");
		$lama_cicilan=rdb('pinjaman','lama_cicilan','lama_cicilan',"where ID='".$no_pinjaman."'");
		$lama_cicilan=($lama_cicilan > 1)?($lama_cicilan-1):1;
		echo $lama_cicilan."--".$no_pinjaman;
		$this->Admin_model->upd_data('pinjaman',"set lama_cicilan='".$lama_cicilan."'","where ID='".$no_pinjaman."'");
	}
	function hapus_tagihan(){
		$id=$_POST['id'];
		$th=$_POST['thn'];
		$this->Admin_model->hps_data('mst_pelanggan',"where nm_pelanggan='".$id."'");
	}
    function data_pinjaman(){
        $n=0;
        $ID_Agt=$_POST['ID_Agt'];
		$data=$this->member_model->data_pinjaman($ID_Agt);
        if(count($data)>0)
        {
            foreach($data as $r)
            {
                $n++;$nota=0;$bayar=0;
                $bayar=($r->Hutang)-($r->Bayar);
                    echo tr().td($n,'center').
                     td(tglfromSql($r->Tanggal),'center').
                     td(number_format($r->Hutang,2),'right').
                     td(number_format($r->Bayar,2),'right').
                     td(number_format($bayar,2),'right').
                     td('Tgl Bayar : '.tglfromSql($r->notrans)).
                    _tr();

            }
        }else{
            echo tr().td('Tidak ada nota pelanggan','kotak\' colspan=\'6')._tr();
        }
    }
	function data_pinjaman1(){
		$data=array();$detail=array();$n=0;$x=0;$stat='';$saldo=0;
		$ID_Agt=$_POST['ID_Agt'];
		$data=$this->member_model->data_pinjaman($ID_Agt);
		if($_POST['cBayar']=='par'){
			if(count($data)>0){
				foreach ($data as $r){
					$n++;$x=0;
					echo "<tr class='list_genap' id='n-".$r->ID."'>
						<td class='kotak' colspan='2' nowrap ><b>$n.&nbsp;&nbsp; Tanggal : ".tglfromSql($r->Tanggal)." - No. Faktur :".rdb('inv_penjualan','Nomor','Nomor',"where NoUrut='".$r->ID."' and Tanggal='".$r->Tanggal."'")."</b></td>
						<td class='kotak'  align='right'><b>".number_format($r->jml_pinjaman,2)."</b></td>
						<td class='kotak'></td>
						<td class='kotak'></td>
						<td class='kotak' colspan='1' nowrap >".$r->keterangan."
						<input type='hidden' id='ID_Pinj' value='".$r->ID."'></td>
						</tr>";
						$detail=$this->member_model->data_setoran($ID_Agt,$r->ID);
						$stat='';
						foreach($detail as $d){
							$x++;	
							$saldo=($r->jml_pinjaman-($saldo+$d->Kredit));
						echo "<tr class='xx'>
							<td class='kotak' align='center'>$x</td>
							<td class='kotak' >&nbsp;&nbsp;&bull;".tglfromSql($d->Tanggal)."</td>
							<td class='kotak'></td>
							<td class='kotak' align='right'>".number_format($d->Kredit,2)."<input type='hidden' class='w70 angka' value='".$d->Kredit."'></td>
							<td class='kotak' align='right'>".number_format($d->saldo,2)."</td>
							<td class='kotak' >
								<table width='100%' style='border-collapse:collapse'>
								<tr><td width='75%' id='r-".$d->ID."'>".$d->keterangan."</td>
								<td width='25%' align='right'>".img_aksi($d->ID,true)."</td>
								</tr></table>
							</td>
							</tr>";
							$stat=($d->saldo ==0)?number_format($d->Kredit,2) :'';
						}
						for($i=1;$i<=($r->lama_cicilan-$x);$i++){
							($i==$r->lama_cicilan)? $cil=$r->cicilan_end:$cil=$r->cicilan;
							//($stat=='')?$stat="<img src='".base_url()."asset/img/checkout.gif' id='g-".$i."' onclick=\"bayar($i);\"":$stat=$stat;
						echo "<tr class='xx' id='r-".$r->ID."'>
							<td class='kotak' align='center'>&nbsp;</td>
							<td class='kotak' >Pembayaran Ke ".$r->lama_cicilan."</td>
							<td class='kotak' align='right'>&nbsp;</td>
							<td class='kotak' align='right'>".number_format($r->Saldo,2)."</td>
							<td class='kotak'  align='right' nowrap >";
							echo ($stat=='')?"<img src='".base_url()."asset/img/checkout.gif' id='g-".$r->ID."' onclick=\"bayar('".$r->ID."','".$r->Tahun."');\">":$stat;
							echo "</td>
							<td class='kotak' align='right'>&nbsp;</td>
							</tr>";
						}
		
				}
			}else{
				echo tr().td('Tidak ada tagihan','kotak\' colspan=\'6')._tr();
			}
		}else{
			$jml=0;$kred=0;$sal=0;
			foreach($data as $r){
				$n++;
				echo tr('xx').
					 td("<b>$n. Tanggal : ".tglfromSql($r->Tanggal)." - No. Faktur :".rdb('inv_penjualan','Nomor','Nomor',"where NoUrut='".$r->ID."' and Tanggal='".$r->Tanggal."'"),'left\' nowrap colspan=\'2').
					 td(number_format($r->jml_pinjaman,2),'right').
					 td(number_format($r->Kredit,2),'right').
					 td(number_format($r->Saldo,2),'right').
					 td($r->keterangan,'left\' nowrap=\'nowrap').
					 _tr();
				$jml	=($jml+$r->jml_pinjaman);
				$kred	=($kred+$r->Kredit);
				$sal	=($sal+$r->Saldo);
			}
			echo tr('xx  list_genap',"r-".$r->ID_Agt).
				 td('<b>Total Tagihan</b>','right\' colspan=\'2').
				 td('<b>'.number_format($jml,2).'</b>','right').
				 td('<b>'.number_format($kred,2).'</b>','right').
				 td('<b>'.number_format($sal,2).'</b>','right').
				 td(($sal!=0)?"<img src='".base_url()."asset/img/checkout.gif' id='g-".$r->ID_Agt."' onclick=\"bayarAll('".$r->ID_Agt."','".$r->Tahun."');\">":'<b>LUNAS</b>','center').
				_tr();
		}
	}
	//list transaksi simpanan pinjaman dan setoran pinjaman
	
	function list_transaksi(){
		$this->zetro_auth->menu_id(array('listtransaksi'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('member/member_transaksi');
	}
	
	function get_transaksi(){
		$data=array();
		$ID_Dept	=empty($_POST['ID_Dept'])?''	:$_POST['ID_Dept'];
		$ID_Aktif	=empty($_POST['ID_Aktif'])?''	:$_POST['ID_Aktif'];
		$data=$this->member_model->get_transaksi($ID_Dept,$ID_Aktif);
		$n=0;$t_debet=0;$t_kredit=0;
		foreach($data as $r){
			$n++;
			echo "<tr class='xx' align='center'>
				  <td class='kotak'>$n</td>
				  <td class='kotak'>".tglfromSql(substr($r->Tanggal,0,10))."</td>
				  <td class='kotak'>".$r->Kode."</td>
				  <td class='kotak' align='left' nowrap>".$r->Perkiraan."</td>
				  <td class='kotak' align='right'>".number_format($r->Debet,2)."</td>
				  <td class='kotak' align='right'>".number_format($r->Kredit,2)."</td>
				  <td class='kotak' align='left' nowrap>".$r->Keterangan."</td>
				  <td class='kotak'>";
				  echo ($r->ID_Stat=='0')?
				  "<img title='hapus transaksi' src='".base_url()."asset/images/no.png' onclick=\"hapus('".$r->ID."');\">":
				  "<img title='sudah terjurnal' src='".base_url()."asset/images/5.png'>";
			echo "</td>
				  </tr>";
				$t_debet	=($t_debet+$r->Debet);
				$t_kredit	=($t_kredit+$r->Kredit);  
		}
		echo "<tr class='list_genap'>
			 <td class='kotak' colspan='4' align='right'>TOTAL</td>
			 <td class='kotak' align='right'><b>".number_format($t_debet,2)."</td>
			 <td class='kotak' align='right'><b>".number_format($t_kredit,2)."</td>
			 <td class='kotak' align='right'></td>
			 <td class='kotak' align='right'></td>
			 </tr>";
	}
	function hapus_transaksi(){
		$ID	=$_POST['ID'];
		$this->Admin_model->hps_data('transaksi_temp',"where ID='$ID'");	
	}
}
?>