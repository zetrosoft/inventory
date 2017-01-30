<? ini_set('memory_limit','100000M');?>
<html><head>
<style>
body { margin-top:25px; font-size:12px; margin-left:0.3em;margin-right:0.3em; padding:0}
		.b_line { border-bottom:1px #AAAAAA solid; height:25px; font-size:14px}
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
</style>
</head><body>
<b>&nbsp;&bull;&nbsp;REKAP GAJI KARYAWAN MEKAR JAYA</b><br>
&nbsp;&bull;&nbsp;Periode	: <b><?=$periode;?></b>
<?=empty($nama)?'':'<br>&nbsp;&bull;&nbsp;Nama Karyawan :'.$nama;?>
<?=$hr;?>
<?=$rkp;?>  
</body></html>