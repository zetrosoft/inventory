<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$path='application/views/laporan';
//calender();
link_js('jquery.fixedheader.js','asset/js');
link_js('penjualan_lpg.js',$path.'/js');
panel_begin("List Transaksi LPG");
panel_multi('listtransaksilpg','block',false);
if($all_listtransaksilpg!='')
{
    $bulan='\n';
    for($i=1;$i<=12;$i++)
	{
		$bulan.=($i==(int)date('m'))?"<option value='".$i."' selected='selected'>".nBulan($i)."</option>\n":
                "<option value='".$i."'>".nBulan($i)."</option>\n";
	}
    echo "<form id='frm1' name='frm1' method='post' action=''>";
    addText(array('Periode Bulan','Tahun','Pangkalan','',''),
			array("<select id='bulan' name='bulan' >".$bulan."</select>",
				  "<select id='tahun' name='tahun'></select>",
                  "<select id='pangkalan' name='pangkalan'></select>",
				  "<input type='button' id='ok' value='Preview'><input type='button' id='prnt' value='Print'/>",
				  "<input type='hidden' id='exp' value='Export to Excel'>"),true);
    echo "</form>";
    echo "<div id='xxd' style='overflow-y:scroll; width:100%'>";
    echo tabel()._thead().
         tr()
            .th('No','3%',"headere","rowspan='2'")
            .th('Tanggal','8%','headere',"rowspan='2'")
            .th('Penerimaan','12%','headere',"colspan='2'")
            .th('Penyaluran','','headere',"colspan='7'")
            .th('Sisa Stock Akhir','','headere',"colspan='3'").
         _tr().
         tr()
            .th('Pangkalan','8%','headere')
            .th('Jumlah','4%','headere')
            .th('Nama','20%','headere')
            .th('Rumah Tangga','5%','headere')
            .th('Usaha Mikro','5%','headere')
            .th('Pengecer','5%','headere')
            //.th('Alamat','15%','headere')
            .th('Keterangan','20%','headere')
            .th('Jumlah','5%','headere')
            .th('Tanda Tangan','8%','headere')
            .th('Isi','5%','headere')
            .th('Kosong','5%','headere')
            .th('Bocor','5%','headere').
         _tr().
        _thead(false,true).
        _tabel(true);
    echo "</div>";  
}else{
   no_auth(); 
}
panel_multi_end();
popup_start('print_prev','Print Preview ',800,750);
	echo "<iframe id='prv' src='' height='100%' width='100%' frameborder='0' allowtransparency='1' style='z-index:100'></iframe>";
popup_end();
panel_end();
?>