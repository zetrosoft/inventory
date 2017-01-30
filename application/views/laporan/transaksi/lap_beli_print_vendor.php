<?php
		  $a=new reportProduct();
		  $zn=new zetro_manager();
		  $nfile='asset/bin/zetro_neraca.frm';
		  //$a->Header();
		  $a->setKriteria("transkip");
		  $a->setNama("PEMBELIAN PER VENDOR");
		  $a->setSection("belibyvendor");
		  $a->setFilter(array($dari ." s/d ".$sampai,$vendor));
		  $a->setReferer(array('Periode','Vendor Name'));
		  $a->setFilename($nfile);
		  $a->AliasNbPages();
		  $a->AddPage('L',"A4");
	
		  $a->SetFont('Arial','',10);
		  //echo $a->getColWidth();
		  // set lebar tiap kolom tabel transaksi
		  		$a->SetWidths(array(10,20,20,50,25,18,30,30,69));
				//$a->SetWidths();
		  // set align tiap kolom tabel transaksi
		  		$a->SetAligns(array("C","C","C","L","R","L","R","R","L"));
		  $a->SetFont('Arial','B',10);
		  $a->SetFont('Arial','',9);
		  //$rec = $temp_rec->result();
		  $n=0;$harga=0;$hgb=0;$hargaj=0;$jml=0;
		  foreach($temp_rec as $r)
		  {
			$n++;
			$a->Row(array($n,tglfromSql($r->Tanggal),$r->NoUrut,$r->Nama_Barang,
						  number_format($r->Jumlah,2),
						  $r->Satuan,number_format($r->Harga_Beli,2),
						  number_format(($r->Jumlah*$r->Harga_Beli),2),
						  "No. Reff : ".$r->Nomor));
			//sub tlot
			$jml	=($jml+$r->Jumlah);
			$hargaj	=($hargaj+$r->Harga_Beli);
			$harga	=($harga+($r->Jumlah*$r->Harga_Beli));
		  }
		  $a->SetFont('Arial','B',10);
		  $a->SetFillColor(225,225,225);
		  $a->Cell(143,8,"TOTAL",1,0,'R',true);
/*		  $a->Cell(20,8,number_format($jml,2),1,0,'R',true);
		  $a->Cell(18,8,'',1,0,'C',true);
*/		  $a->Cell(30,8,number_format($hargaj,2),1,0,'R',true);
		  $a->Cell(30,8,number_format($harga,2),1,0,'R',true);
		  $a->Cell(69,8,'',1,0,'C',true);
		  $a->Output('application/logs/'.$this->session->userdata('userid').'_detail_penjualan.pdf','F');

//show pdf output in frame
$path='application/views/laporan';
$img=" <img src='".base_url()."asset/images/back.png' onclick='js:window.history.back();' style='cursor:pointer' title='click for select other filter data'>";
link_js('auto_sugest.js,lap_beli.js,jquery.fixedheader.js','asset/js,'.$path.'/js,asset/js');
panel_begin('Print Preview','','Back'.$img);
?>
		  <iframe src="<?=base_url();?>application/logs/<?=$this->session->userdata('userid');?>_detail_penjualan.pdf" height="100%" width="100%" frameborder="0" allowtransparency="1"></iframe>
<?
panel_end();

?>