<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$zfm=new zetro_frmBuilder('asset/bin/zetro_akun.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_akun.frm');
$path='application/views/akuntansi';
calender();
($p_bukubesar!='')?$oto_p='':$oto_p='none';
link_css('jquery.coolautosuggest.css','asset/css');
link_js('jquery.fixedheader.js,jquery.coolautosuggest.js','asset/js,asset/js');
link_js('buku_besar.js,jquery_terbilang.js',$path.'/js,asset/js');
panel_begin('Buku Besar','',',',",,,,<input type='button' class='print' style='display:$oto_p' value='Cetak' id='cetak' title='Klik untuk print'>");
panel_multi('bukubesar','block',false);
if($all_bukubesar!=''){
echo "<form name='frm_j' id='frm_j' method='post' action=''>";
echo "
<table id='period' style='border-collapse:collapse'>
	<tr><td width='70px'>Periode :</td>
    	<td width='20px'><input type='radio' name='periode' id='pertgl' /></td>
        <td width='70px'>Per Tanggal</td>
        <td width='100px'><input type='text' id='tgl_start' class='w100' value='' /></td>
        <td width='20px'>s/d</td>
        <td width='100px'><input type='text' id='tgl_stop' class='w100' value='' /></td>
        <td style='border-right:2px dotted #FFF' width='15px'></td>
        <td width='10px'>&nbsp;</td>
        <td width='80px'>Klasifikasi</td>
        <td width='170px'><select id='ID_Klas' class='S100'>";
		dropdown("klasifikasi",'ID','Klasifikasi');
	echo "</select></td>
        <td width='5px'>&nbsp;</td>
        <td width='90px'>Sub Klasifikasi</td>
        <td width='250px'><select id='ID_SubKlas' class='S70'></select></td>
    </tr>
	<tr><td>&nbsp;</td>
    	<td><input type='radio' name='periode' id='pertahun' /></td>
        <td>Per Tahun</td>
        <td><select id='tahun'></select></td>
        <td>&nbsp;</td>
        <td><input type='button' id='oke' value='OK'></td>
        <td style='border-right:2px dotted #FFF' width='15px'></td>
        <td >&nbsp;</td>
        <td >Departemen</td>
        <td ><select id='ID_Dept' class='S100'>";
		dropdown("mst_departemen",'ID','Departemen');
	echo "</select></td>
        <td >&nbsp;</td>
        <td >Perkiraan</td>
        <td ><select id='ID_Agt' class='S100' style='float:left'></select></td>
    </tr>
</table>
<hr />";

echo "</form>";	
echo "<div id='tgl' style='display:none'>";		   
		$zlb->section('bukubesar');
		$zlb->aksi(false);
		$zlb->Header('100%');
		$zlb->icon();
		echo "</tbody></table>\n
		</div>
		<div id='thn' style='display:none'>";
		$zlb->section('bukubesartahunan');
		$zlb->aksi(false);
		$zlb->Header('70%','bbTahunan');
		$zlb->icon();
		echo "</tbody></table></div>";
}else{
	no_auth();
}
panel_multi_end();
panel_end();
loading_ajax();
terbilang();
?>
<input type='hidden' id='filper' value='' />