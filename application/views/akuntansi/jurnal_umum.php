<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_akun.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_akun.frm');
$path='application/views/akuntansi';
calender();
($p_listjurnalumum!='')?$oto_p='':$oto_p='none';
link_css('jquery.coolautosuggest.css','asset/css');
link_js('jquery.fixedheader.js,jquery.coolautosuggest.js','asset/js,asset/js');
link_js('jurnal_umum.js,jquery_terbilang.js',$path.'/js,asset/js');
panel_begin('Jurnal Umum','',',',",,,,<input type='button' class='print' style='display:$oto_p' value='Cetak' id='cetak' title='Klik untuk print'>");
panel_multi('listjurnalumum','block',false);
if($all_listjurnalumum!=''){
echo "<form name='frm_j' id='frm_j' method='post' action=''>";
addText(array('Filter By',"<span id='fltby'></span>",'Unit','',''),
		array("<select id='fby' name='fby'>
			   <option value=''>&nbsp;</option>
			   <!--option value='all'>Semua Data</option-->
			   <option value='tgl'>Tanggal</option>
			   <option value='bln'>Bulan/Tahun</option>
			   </select>",
			   "<span id='bytgl' style='display:none'>
			   	<input type='text' id='daritgl' name='daritgl' value=''> s/d
				<input type='text' id='smptgl' name='smptgl' value=''></span>
				<span id='bybln' style='display:none'>
				<select id='Bln' name='Bln'></select>&nbsp;&nbsp;<select id='Thn' name='Thn'></select>
				</span>",
			   "<select id='ID_Unit' name='ID_Unit'>
			   <option value='all'>Semua</option>
			   <option value='1'>KBR</option>
			   <option value='2'>USP</option>
			   </select>",
			   "<input type='button' id='process' value='OK'>",'<span id="ttd"></span>'));
echo "</form>";			   
		$zlb->section('jurnal');
		$zlb->aksi(false);
		$zlb->Header('100%');
		$zlb->icon();
		echo "</tbody></table>";
}else{
	no_auth();
}
panel_multi_end();
panel_multi('addjurnal','none',false);
if($all_addjurnal!=''){
addText(array('New Jurnal','Add Jurnal Content','No Jurnal'),
		array('<input type="radio" name="pilih" id="new" checked="checked">',
				'<input type="radio" name="pilih" id="addcontent">',
				'<input type="text" id="NoJurnal" value="" class="cari">'));
echo "<div id='addnew' style='display:'>";
	$zfm->AddBarisKosong(true,'bottom');
	$zfm->Start_form(true,'frm1');
	$zfm->BuildForm('newjurnal',true,'70%');
	$zfm->BuildFormButton('Simpan','newjurnal');
echo "</div>\n<div id='addcontent' style='dispaly:none'>\n";
echo "<div id='jdet'>\n
	</div>";
		$zlb->section('addcontent');
		$zlb->aksi(false);
		$zlb->Header('100%','j_content');
		$zlb->icon('deleted');
		echo "\n</tbody></table>\n<hr>
		<div>\n<table width='100%' id='bwh' style='border-collapse:collapse'>
			<tr><td width='60%'>&nbsp;</td>
			<td align='right' style='padding-right:20px'>
			<!--input type='button' id='reklawan_adc' onclick=\"balance_show();\" value='Create Balance' title='tambah perkiraan pembanding'>&nbsp;
			--><input type='button' id='addtrans' value='Add Transaksi'>&nbsp;
			<input type='button' id='pdf' value='Cetak'>
			<input type='hidden' id='thn' value='".date('Y')."'></td></tr>\n
			</table></div>" ;
echo "</div>\n";
}else{
	no_auth();
}
panel_multi_end();
popup_start('j_detail',"Jurnal Detail Transaksi");
echo "<div id='jdete'>\n
	</div>";
		$zlb->section('addcontent');
		$zlb->aksi(false);
		$zlb->Header('100%','sj_content');
		$zlb->icon('deleted');
		echo "\n</tbody></table>\n<hr>
		<div>\n<table width='100%' id='bwhe' style='border-collapse:collapse'>
			<tr><td width='60%'>&nbsp;</td>
			<td align='right' style='padding-right:20px'>
			<!--input type='button' id='reklawan' value='Create Balance' title='tambah perkiraan pembanding'>&nbsp;
			--><input type='button' id='pdf' value='Cetak' title='cetak laporan'>&nbsp;
			<input type='button' id='batal' value='Keluar' title='tutup popup'>
			</td></tr>\n
			</table></div>\n" ;
popup_end();
popup_start('ad_content',"Add Transaksi to Jurnal Umum",500,500);
//
echo "<font color='#0000CC' style='font-size:normal'>Pilih Perkiraan :</font>
	 <hr>
		<table style='border-collapse:collapse' id='pilihan'>
		</table>
	  <hr>
	<div style='height:330px'>  
	  <div id='simp' style='display:none'>";
		$zlb->section('addcontent');
		$zlb->aksi(false);
		$zlb->Header('100%','add_trans');
		$zlb->icon('deleted');
		echo "\n</tbody></table>\n
			</div>\n
	 <div id='unt' style='display:'>" ;
	$zfm->AddBarisKosong(true,'bottom');
	$zfm->Start_form(true,'frm3');
	$zfm->BuildForm('balance',false,'80%');
	//$zfm->BuildFormButton('Simpan','lawan');
	echo "</div>\n</div><hr>
		<div>\n<table width='100%' id='bwht' style='border-collapse:collapse'>
			<tr><td width='60%'><span id='stato'></span></td>
			<td align='right' style='padding-right:20px'>
			<input type='button' id='simpan_x' name='simpan_x' value='Simpan' onclick=\"simpan_ad_content();\" title='simpan'>
			<input type='button' id='batal' value='Keluar' onclick=\"keluar_ad_content();\" title='tutup popup'>
			<input type='hidden' id='thn' value='".date('Y')."'></td></tr>\n
			</table></div>\n";
popup_end();
panel_end();
loading_ajax();
terbilang();
?>