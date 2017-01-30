<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_member.frm');
$zlb=new zetro_buildlist();
$zn=new zetro_manager();
$section='listanggota';
$zlb->config_file('asset/bin/zetro_member.frm');
$path='application/views/member';
$dept="<select id='dept' name='dept'></select>";
$stat="<select id='stat' name='stat'>
		<option value='all'>Semua</option>
		<option value='1'>Terjurnal</option>
		<option Value='0' selected='selected'>Belum Terjurnal</option>
		</select>";
link_css('style.css','asset/js');
link_js('jquery.fcbkcomplete.js','asset/js');
link_js('jquery.fixedheader.js,member_transaksi.js','asset/js,'.$path.'/js');
panel_begin('List Transaksi','','Department :,'.$dept.', &nbsp;&nbsp;Status :'.$stat);
panel_multi('listtransaksi','block');
addText(array('Departemen :','Status :'),array('<span id="ndept"></span>','<span id="nstat"></span>'));
if($all_listtransaksi!=''){
	$n=0;
		$zlb->section('listtransaksi');
		$zlb->aksi(true);
		$zlb->Header('100%');
		$zlb->icon('deleted');
		echo "</tbody></table>";
}else{
	no_auth();
}

panel_multi_end();
panel_end();

?>
<input type='hidden' id='ordby' value='' class='w100'/>
<input type='hidden' id='totdata' value='<?=(!empty($list))?count($list):0;?>' />
<script language="javascript">
	$(document).ready(function(e) {
		$('#dept').html("<option value='all'>Semua</option>");
        $('#dept').append("<? dropdown('mst_departemen','id','departemen',"order by Kode",'all');?>")
		//$('#dept').val('').select();
    });
</script>

