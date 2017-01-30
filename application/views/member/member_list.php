<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_member.frm');
$zlb=new zetro_buildlist();
$zn=new zetro_manager();
$section='listanggota';
$zlb->config_file('asset/bin/zetro_member.frm');
$path='application/views/member';
$dept="<select id='dept' name='dept'></select>";
$stat="<select id='stat' name='stat'>
		<option value='all' selected>Semua</option>
		<option value='1'>Aktif</option>
		<option Value='2'>Non Aktif</option>
		<option Value='3'>Keluar</option>
		</select>";
link_css('style.css','asset/js');
link_js('jquery.fixedheader.js,member_list.js','asset/js,'.$path.'/js');
panel_begin('Pelanggan');//,'','Department :,'.$dept.', &nbsp;&nbsp;Keaktifan :'.$stat,",Total Data : <span id='td'></span>");
panel_multi('daftaranggota','block');
if($all_member__member_list!=''){
addText(array('','Cari Berdasarkan Nama','>350','','Group'),
		array('','',"<input type='text' id='carix' name='carix' class='w100' data-provide='typeahead' data-source=\"[".$datax."]\" value='' />",
			  "<input type='button' value='OK' id='cari'>",
              "<select id='groupby' name='groupby'>
                <option value='All'>All</option>
                <option value='umum'>Umum</option>
                <option value='lpg'>LPG</option>
                </select>"),true,'frm3');
	$n=0;
		$zlb->section($section);
		$zlb->aksi(true);
		$zlb->Header('100%');
		$zlb->icon();
		echo "</tbody></table>";
}else{
	no_auth();
}
panel_multi_end();
popup_full();
terbilang();
?>
<input type='hidden' id='ordby' value='' class='w100'/>
<input type='hidden' id='totdata' value='<?=(!empty($list))?count($list):0;?>' />
<input type='hidden' id='otor' value='<?=$all_member__member_list;?>' />
<script language="javascript">
	$(document).ready(function(e) {
		//$('#dept').html("<option value='all'>Semua</option>");
        //$('#dept').append("<? dropdown('mst_departemen','id','departemen',"order by Kode");?>")
		//$('#dept').val('').select();
    });
</script>
