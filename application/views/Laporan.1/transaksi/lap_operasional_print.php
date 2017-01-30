<?php
		  $a=new reportProduct();
		  $zn=new zetro_manager();
		  $nfile='asset/bin/zetro_master.frm';
		  //$a->Header();
		  $a->setKriteria("transkip");
		  $a->setNama("LAPORAN ALIRAN KAS");
		  $a->setSection("lapkas");
		  $a->setFilter(array($dari.' s/d '.$sampai,$lokasi));
		  $a->setReferer(array('Tanggal','Lokasi'));
		  $a->setFilename('asset/bin/zetro_master.frm');
		  $a->AliasNbPages();
		  $a->AddPage("P","A4");
		  $a->SetFont('Arial','',10);
		  // set lebar tiap kolom tabel transaksi
		  // set align tiap kolom tabel transaksi
		  $a->SetWidths(array(10,22,78,30,30));
		  $a->SetAligns(array("C","C","L","R","R"));
		  $a->SetFont('Arial','',9);
		  //$rec = $temp_rec->result();
		  $n=0;$harga=0;$hargaj=0;$datax=array();
		  foreach($temp_rec as $r)
		  {
			$hgb=0;  
			$n++;$x=0;
		 	$a->SetFont('Arial','BI',9);
			$a->Cell(10,8,$n,1,0,'C');
			$a->Cell(22,8,tglfromSql($r->tgl_kas),1,0,'C');
			$a->Cell(108,8,'Saldo Awal '. tglfromSql($r->tgl_kas),1,0,'L');
			$a->Cell(30,8,number_format($r->sa_kas,2),1,1,'R');
			$a->SetFont('Arial','',9);
			$where="where tgl_trans='".$r->tgl_kas."' and jumlah!='0'";
			$datax=$this->kasir_model->operasional($where,$orderby);
				foreach($datax as $row){
				$x++;
				$a->Row(array('',$x,$row->uraian_trans,number_format($row->jumlah,2),''));	
				$hgb=($hgb+$row->jumlah);
				}
		 	$a->SetFont('Arial','I',9);
			$a->Cell(32,8,'',1,0,'C');
			$a->Cell(108,8,'Saldo Akhir '. tglfromSql($r->tgl_kas),1,0,'L');
			$a->Cell(30,8,number_format(($r->sa_kas-$hgb),2),1,1,'R');
		  }
/*		  $a->SetFont('Arial','B',10);
		  $a->SetFillColor(225,225,225);
		  $a->Cell(110,8,"TOTAL",1,0,'R',true);
		  $a->Cell(30,8,number_format($harga,2),1,0,'R',true);
		  $a->Cell(30,8,number_format($hargaj,2),1,1,'R',true);
		  $a->Cell(140,8,"SALDO",1,0,'R',true);
		  $a->Cell(30,8,number_format(($harga-$hargaj),2),1,0,'R',true);
*/		  $a->Output('application/logs/'.$this->session->userdata('userid').'_kas_keluar.pdf','F');

//show pdf output in frame
$path='application/views/laporan';
$img=" <img src='".base_url()."asset/images/back.png' onclick='js:window.history.back();' style='cursor:pointer' title='click for select other filter data'>";
//link_js('auto_sugest.js,lap_beli.js,jquery.fixedheader.js','asset/js,'.$path.'/js,asset/js');
panel_begin('Print Preview','','Back'.$img);
?>
		  <iframe src="<?=base_url();?>application/logs/<?=$this->session->userdata('userid');?>_kas_keluar.pdf" height="100%" width="100%" frameborder="0" allowtransparency="1"></iframe>
<?
panel_end();

?>