<?php
		  $a=new reportProduct();
		  $zn=new zetro_manager();
		  $nfile='asset/bin/zetro_neraca.frm';
		  //$a->Header();
		  $pajake=$zn->rContent('Prefference','Pajak','asset/bin/zetro_config.dll');
		  $a->setKriteria("transkip");
		  $a->setNama("LAPORAN LABA RUGI");
		  $a->setSection("labarugi");
		  $a->setFilter(array(($sampai=='')?$dari:$dari .' s/d '. $sampai,$lokasi));
		  $a->setReferer(array('Periode','Lokasi'));
		  $a->setFilename($nfile);
		  $a->AliasNbPages();
		  $a->AddPage("P","A4");
		  $a->SetFont('Arial','',10);
		  // set lebar tiap kolom tabel transaksi
		  // set align tiap kolom tabel transaksi
		  $a->SetWidths(array(10,30,30,30,25,30));
		  $a->SetAligns(array("C","C","R","R","R","R"));
		  $a->SetFont('Arial','',9);
		  //$rec = $temp_rec->result();
		  $n=0;$harga=0;$hgbb=0;
		  $hargaj=0;$ppn=0;
		  $tppn=0;$opr=0;$lab=0;
		  $kotor=0;
		  foreach($temp_rec as $r)
		  {
			$n++;$hgb=0;$harga_beli=0;
			$opr	=rdb('mst_kas_trans','jumlah','sum(jumlah) as jumlah',"where tgl_trans='".$r->Tanggal."' $lokasine group by tgl_trans");
			$hgb	=rdb('inv_barang','harga_beli','harga_beli',"where id='".$r->ID_Barang."'");
			$harga_beli=($r->Harga_Beli==0)?($r->jml*$hgb):$r->Harga_Beli;
			$kotor	=($pajak=='ok')?(($r->Jual-$harga_beli)-(($r->Jual-$harga_beli)*$pajake)):($r->Jual-$harga_beli);
			$ppn	=($pajak=='ok')?(($kotor)*10/100):0;
			$lab	=($kotor-$opr-$ppn);
			$a->Row(array($n,tglfromSql($r->Tanggal),
				number_format(round($kotor,-2),2),
				number_format((int)$opr,2),
				number_format($ppn,2),
				number_format($lab,2)
				));
			//sub tlot
			$tppn	=($tppn+$ppn);
			$harga	=($harga+(round($kotor,-2)));//($r->Jual-$r->Harga_Beli));
			$hgbb	=($hgbb+$opr);
			$hargaj	=($hargaj+$lab);
			
		  }
		  $a->SetFont('Arial','B',10);
		  $a->SetFillColor(225,225,225);
		  $a->Cell(40,8,"TOTAL",1,0,'R',true);
		  $a->Cell(30,8,number_format($harga,2),1,0,'R',true);
		  $a->Cell(30,8,number_format($hgbb,2),1,0,'R',true);
		  $a->Cell(25,8,number_format($tppn,2),1,0,'R',true);
		  $a->Cell(30,8,number_format($hargaj,2),1,1,'R',true);
/*		  $a->Cell(140,8,"SALDO",1,0,'R',true);
		  $a->Cell(30,8,number_format(($harga-$hargaj),2),1,0,'R',true);
*/		  $a->Output('application/logs/'.$this->session->userdata('userid').'_laba_rugi.pdf','F');

//show pdf output in frame
$path='application/views/laporan';
$img=" <img src='".base_url()."asset/images/back.png' onclick='js:window.history.back();' style='cursor:pointer' title='click for select other filter data'>";
//link_js('auto_sugest.js,lap_beli.js,jquery.fixedheader.js','asset/js,'.$path.'/js,asset/js');
panel_begin('Print Preview','','Back'.$img);
?>
		  <iframe src="<?=base_url();?>application/logs/<?=$this->session->userdata('userid');?>_laba_rugi.pdf" height="100%" width="100%" frameborder="0" allowtransparency="1"></iframe>
<?
panel_end();

?>