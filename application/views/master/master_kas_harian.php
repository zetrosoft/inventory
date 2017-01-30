<?php
$zfm=new zetro_frmBuilder('asset/bin/zetro_master.frm');
$section='Barang';
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_master.frm');
$path='application/views/master';
link_css('autosuggest.css','asset/css');
link_js('auto_sugest.js,jquery.fixedheader.js,master_kas_harian.js,jquery_terbilang.js','asset/js,asset/js,'.$path.'/js,asset/js');
tab_select('');
panel_begin('Kas Harian Toko');
panel_multi('setupsaldokas','none',false);
if($all_kas_harian!=''){
	$zfm->AddBarisKosong(false);
	$zfm->Start_form(true,'frm1');
	$zfm->BuildForm('kasharian',($c_kas_harian=='')? false:true,'50%');
	($c_kas_harian=='')?'': $zfm->BuildFormButton('Simpan','kas');
	echo "<hr>";
	//buildgrid('mst_kas_harian','tgl_kas','kasharian',true,'deleted');
		$zlb->section('kasharian');
		$zlb->aksi(false);
		$zlb->icon('deleted');
		$zlb->Header('100%');
		echo "</tbody></table>";
}else{
	no_auth();
}
panel_multi_end();
panel_multi('operasionaltoko','block',false);
if($all_kas_keluar!=''){
	$zfm->AddBarisKosong(false);
	$zfm->Start_form(true,'frm2');
	$zfm->BuildForm('kaskeluar',($c_kas_keluar=='')? false:true,'50%');
	($c_kas_keluar=='')?'': $zfm->BuildFormButton('Simpan','kaskeluar');
	echo "<hr>";
	//buildgrid("detail_transaksi where tgl_transaksi='".date('Y-m-d')."' and (jenis_transaksi='D' or jenis_transaksi='DR')",'no_transaksi','kaskeluar',true,'deleted');
		$zlb->section('kaskeluar');
		$zlb->aksi(false);
		$zlb->icon('deleted');
		$zlb->Header('100%');
		echo "</tbody></table>";
}else{
	no_auth();
}
panel_multi_end();
auto_sugest();
tab_select('prs');
panel_end();
terbilang();
function buildgrid($table,$field,$section,$aksi=true,$icon=''){
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_master.frm');
	$sql2="select * from $table order by $field";
	//echo $sql2;
		$zlb->section($section);
		$zlb->aksi($aksi);
		$zlb->icon($icon);
		$zlb->query($sql2);
		$zlb->sub_total(false);
		$zlb->sub_total_field('stock,blokstok');
		$zlb->Header('100%');
		$zlb->list_data($section);
		$zlb->BuildListData($field);
		echo "</tbody></table>";
}
$lokasi=$this->zetro_auth->cek_area();
?>
<input type='hidden' id='trans_new' value='D' />
<input type='hidden' id='aktif_user' value='<?=$this->session->userdata('idlevel');?>'/>
<script language="javascript">
	$(document).ready(function(e) {
		$('#id_lok').html("<? dropdown('user_lokasi','ID','lokasi',"where ID in(".$lokasi.") order by ID",'');?>");
   		$('#id_lok').val($('#lok').val()).select();
		$('#id_lokas').html("<? dropdown('user_lokasi','ID','lokasi',"where ID in(".$lokasi.") order by ID",'');?>");
   		$('#id_lokas').val($('#lok').val()).select();
    });
</script>
