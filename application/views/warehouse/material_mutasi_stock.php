<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_inv.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_inv.frm');
$path='application/views/warehouse';
Calender();
link_css('jquery.coolautosuggest.css','asset/css');
link_js('jquery.coolautosuggest.js','asset/js');
link_js('jquery.fixedheader.js','asset/js');
link_js('material_mutasi_stock.js',$path.'/js');
link_js('jquery_terbilang.js','asset/js');
panel_begin('Mutasi Stock');
panel_multi('mutasistock','block',false);
if($all_mutasistock!=''){
	$zfm->AddBarisKosong(false);
	$zfm->Start_form(true,'frm1');
	$zfm->BuildForm('mutasi',false,'60%');
	echo "<hr><div align='right'>";
	addText(array('',''),
			array("<input type='button' value='Proses Mutasi' id='prs_mutasi'>",
				  "<input type='button' value='Cancel' id='prs_batal'>"));
	echo "</div><form id='frm2' name='frm2' method='post' action=''>";
		$zlb->section('mutasilist');
		$zlb->aksi(true);
		$zlb->Header('97%');
		echo "<tr><td class='kotak' colspan='8'>&nbsp;</td>";
		echo "</tbody><tfoot>";
		$zfm->section('mutasilist');
		$zfm->rowCount();
		$zfm->button('simpan');
		$zfm->BuildGrid();
		echo "</tfoot></table></form>";
}else{
	no_auth();
}
panel_multi_end();
panel_multi('listmutasi','none',false);
if($all_listmutasi!=''){
addText(array('Periode Dari','Sampai','Status',''),
		array("<input type='text' id='dari_tanggal' value='' class='w100'>",
			  "<input type='text' id='smp_tanggal' value='' class='w100'>",
			  "<select id='statuse'>
			  	<option value=''>Semua</option>
				<option value='N'>Pending</option>
				<option value='Y'>Terkirim</option>
				<option value='T'>Diterima</option>
				</select>",
			  "<input type='button' id='okelah' value='OK'>"));
		$zlb->section('lapmutasi');
		$zlb->aksi(true);
		$zlb->Header('100%','newTable');
		echo "</tbody></table>";
}else{
	no_auth();
}
panel_multi_end();
popup_start('preview','Print Preview',800,600);
popup_end();
panel_end();
auto_sugest();
tab_select('prs');
terbilang();
?>
<input type="hidden" id='id_sat' value='' />
<input type="hidden" id='id_brg' value='' />
<input type="hidden" id='total_beli' value='0' />
<input type="hidden" id='trans_new' value='' />
<input type="hidden" id='id_pemasoke' value='' />
<input type='hidden' id='aktif_user' value='<?=$this->session->userdata('idlevel');?>'/>
<script language="javascript">
$(document).ready(function(e) {
    	$('#prs_mutasi').click(function(){
		$.post('print_mutasi',{
			'notrans'	:$('#no_trans').val(),
			'tanggal'	:$('#tgl_trans').val()
			},function(result){
				$('#pp-preview').css({'left':'10%','top':'10%','max-height':'550px'})
				$('#tbl-preview').css({'height':'500px'});
				$('#tbl-preview').html('<iframe src="<?=base_url();?>application/logs/<?=$this->session->userdata('userid');?>_mutasi.pdf" height="100%" width="100%" frameborder="0" allowtransparency="1"></iframe>')
				$('#pp-preview').show();
				$('#lock-preview').show()
			})
		})
	$('#pp-preview img#preview').live('click',function(){
		//document.location.reload();
		_show_trans();
	})
});

function print_mutasi(n,t){
		$.post('print_mutasi',{
			'notrans'	:n,
			'tanggal'	:t
			},function(result){
				$('#pp-preview').css({'left':'10%','top':'10%','max-height':'550px'})
				$('#tbl-preview').css({'height':'500px'});
				$('#tbl-preview').html('<iframe src="<?=base_url();?>application/logs/<?=$this->session->userdata('userid');?>_mutasi.pdf" height="100%" width="100%" frameborder="0" allowtransparency="1"></iframe>')
				$('#pp-preview').show();
				$('#lock-preview').show()
			})
}
</script>