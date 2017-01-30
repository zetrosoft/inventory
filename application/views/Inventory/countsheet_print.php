<?php
		  $a=new reportProduct();
		  $zn=new zetro_manager();
		  $nfile='asset/bin/zetro_inv.frm';
		  //$a->Header();
		  $a->setKriteria("transkip");
		  $a->setNama("INVENTORY COUNT SHEET");
		  $a->setSection("stokopname");
		  $a->setFilter(array($bln." ". $thn));
		  $a->setReferer(array('Periode '));
		  $a->setFilename('asset/bin/zetro_inv.frm');
		  $a->AliasNbPages();
		  $a->AddPage("P","A4");
		  $a->SetFont('Arial','',10);
		  // set lebar tiap kolom tabel transaksi
		  // set align tiap kolom tabel transaksi
		  $a->SetFont('Arial','B',10);
		  //$a->Ln(2);
		  // set nama header tabel transaksi
		  $a->SetWidths(array(10,80,20,30,30));
		  $a->SetAligns(array("C","L","C","R","R"));
		  $a->SetFillColor(225,225,225);
		  /*
		  $kol=$zn->Count('stokopname',$nfile);
		  $a->Cell(10,8,'No.',1,0,'C',true);
		  for ($i=1;$i<=$kol;$i++){
			  $d=explode(',',$zn->rContent('stokopname',"$i",$nfile));
			  $a->Cell($d[9],8,$d[0],1,0,'C',true);
		  }
		  $a->Ln();*/
		  $a->SetFont('Arial','',10);
		 // $rec = $temp_rec->result();
		  $n=0;
		  foreach($temp_rec as $r)
		  {
			$n++;
			$a->Row(array($n, $r->Nama_Barang, $r->Satuan, '',''));
			//
		  }
		  $a->Ln(5);
		  $a->Cell(50,8,'Counting by',0,0,'C');
		  $a->Ln(20);
		  $a->Cell(50,8,'(______________________)',0,0,'C');
		  $a->Output('application/logs/'.$this->session->userdata('userid').'_countsheet.pdf','F');
		  
		$path='application/views/_laporan';
		$img=" <img src='".base_url()."asset/images/back.png' onclick='js:window.history.back();' style='cursor:pointer' title='click for select other filter data'>";
		link_js('lap_beli.js,jquery.fixedheader.js',$path.'/js,asset/js');
		panel_begin('Print Preview','','Back'.$img);
		?>
				  <iframe src="<?=base_url();?>application/logs/<?=$this->session->userdata('userid');?>_countsheet.pdf" height="100%" width="100%" frameborder="0" allowtransparency="1"></iframe>
		<?
		panel_end();
?>
