<?php
		  $a=new reportProduct();
		  $zn=new zetro_manager();
		  $nfile='asset/bin/zetro_member.frm';
		  //$a->Header();
		  $a->setKriteria("transkip");
		  $a->setNama("TAGIHAN PELANGGAN");
		  $a->setSection("TagihanKreditPdf");
		  $a->setFilter(array($status));
		  $a->setReferer(array('Status Tagihan'));
		  $a->setFilename($nfile);
		  $a->AliasNbPages();
		  $a->AddPage("P","A4");
	
		  $a->SetFont('Arial','',10);
		  //echo $a->getColWidth();
		  // set lebar tiap kolom tabel transaksi
		  $a->SetWidths(array(10,90,25,25,25,18));
		  // set align tiap kolom tabel transaksi
		  $a->SetAligns(array("C","L","R","R","R","C"));
		  $a->SetFont('Arial','B',10);
		  $a->SetFont('Arial','',9);
		  //$rec = $temp_rec->result();
		  $n=0;$harga=0;$hgb=0;$hargaj=0;$jml=0;
		  foreach($temp_rec as $r)
		  {
			$n++;
			$a->Row(array($n,$r->Nama.' [ '.$r->Catatan.' ]',
							number_format($r->Debet,2),
							number_format($r->Kredit,2),
							number_format($r->Saldo,2),
							tglfromSql($r->mulai_bayar)
						  ));
			//sub tlot
			$jml	=($jml+$r->Debet);
			$harga	=($harga+($r->Kredit));
			$hargaj	=($hargaj+$r->Saldo);
		  }
		  $a->SetFont('Arial','B',10);
		  $a->SetFillColor(225,225,225);
		  $a->Cell(100,8,"TOTAL",1,0,'R',true);
		  $a->Cell(25,8,number_format($jml,2),1,0,'R',true);
		  $a->Cell(25,8,number_format($harga,2),1,0,'R',true);
		  $a->Cell(25,8,number_format($hargaj,2),1,0,'R',true);
		  $a->Cell(18,8,'',1,0,'R',true);
		  $a->Output('application/logs/'.$this->session->userdata('userid').'_rekap_tagihan.pdf','F');

//show pdf output in frame
$path='application/views/laporan';
$img=" <img src='".base_url()."asset/images/back.png' onclick='js:window.history.back();' style='cursor:pointer' title='click for select other filter data'>";
//link_js('auto_sugest.js,lap_beli.js,jquery.fixedheader.js','asset/js,'.$path.'/js,asset/js');
panel_begin('Print Preview','','Back'.$img);
?>
		  <iframe src="<?=base_url();?>application/logs/<?=$this->session->userdata('userid');?>_rekap_tagihan.pdf" height="100%" width="100%" frameborder="0" allowtransparency="1"></iframe>
<?
panel_end();

?>