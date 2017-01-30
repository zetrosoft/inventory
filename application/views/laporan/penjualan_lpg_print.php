<? ini_set('memory_limit','100000M');?>
<html>
<head>
    
<style>
body { margin-top:20px; font-size:xx-small; margin-left:0.1em;margin-right:0.2em; padding:0}
		.b_line { border-bottom:1px #AAAAAA solid; height:25px; font-size:114px}
		.border_t{border-top:1px inset #EEE;}
		.border_b{border-bottom:1px inset #EEE;}
		.border_l{border-left:1px inset #EEE;}
		.border_r{border-right:1px inset #EEE;}
		.xx:hover {cursor:pointer; background:#99FF99;}
		.xy:hover {cursor:pointer; background:#F30;}
		.box{border:1px solid #003}
		.kotak{border:0.5px solid #003}
		.box_lbr{border:1px solid #003; border-top:none}
		.box_ltr{border:1px solid #003; border-bottom:none}
		.box_lr{border-left:1px solid #003;border-right:1px solid #003;}
		.box_l{border-left:1px solid #003;}
		.box_r{border-right:1px solid #003;}
		.box_lb{border-left:1px solid #003;border-bottom:1px solid #003;}
		.box_rb{border-right:1px solid #003;border-bottom:1px solid #003;}
		.list_genap{cursor:pointer; background-color:#6699FF;}
		.list_ganjil{cursor:pointer; background-color:#9966FF;}
		.list_closed{cursor:pointer; background-color:#FF99FF;}

        .headere{background-color:#F90;
		 font-size:xx-small;
		 font-weight:bold;
        }
</style>
</head>
<body>
<b>&nbsp;&bull;&nbsp;REKAP RENJUALAN GAS</b><br>
&nbsp;&bull;&nbsp;Periode	: <b><?=$periode;?></b>
    <?=$hr;?>
<?
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
        $rkp.
        _tabel(true);
?>


</body></html>