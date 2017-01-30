<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_member.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_member.frm');
$path='application/views/member';
$sesi=$this->session->userdata('menus');
calender();
link_css('jquery.coolautosuggest.css','asset/css');
link_css('jquery.alerts.css','asset/css');
link_js('jquery.alerts.js','asset/js');
link_js('jquery.coolautosuggest.js','asset/js');
link_js('jquery.fixedheader.js,jquery_terbilang.js,member_tagihan.js','asset/js,asset/js,'.$path.'/js');
panel_begin('Tagihan Pelanggan');
panel_multi('listtagihan','block',false);
if($all_listtagihan!=''){
	echo "<form id='frm1' name='frm1' method='post' action=''>";
	addText(array('Order by ','Urutan','',''),
			array("<select id='orderby' name='orderby'>
				   <option value='nm_pelanggan'>Nama Pelanggan</option>
				   <option value='hutang_pelanggan'>Jumlah Hutang</option></select>",
				  "<select id='urutan' name='urutan'>".selectTxt('Urutan',true)."</select>",
				  "<input type='button' value='OK' id='okelah'/>",
				  "<input type='button' value='Print' id='prt'/>"));
	addText(array('Cari by Nama Pelangan',''),
			array("<input type='text' class='cari w100' id='cariya' data-provide='typeahead' value=''>",
				  "<input type='button' value='Cari' id='carilah'/>"),true,'',"width='300px'");
	echo "</form>";
		$zlb->section('TagihanKredit');
		$zlb->aksi(true);
		$zlb->icon();
		$zlb->Header('100%');
	echo "</tbody></table>";

}else{
	no_auth();
}
panel_multi_end();
panel_end();

?>
<script language='javascript'>
$(document).ready(function(e){
   $('#frm1 input#cariya').attr({'data-source':'[<?=$nasabah;?>]'}); 
   $('#frm1 #urutan').val('desc').select();
   $('#frm1 #orderby').val('hutang_pelanggan').select();
});
</script>