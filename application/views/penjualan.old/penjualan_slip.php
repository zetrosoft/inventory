<?php
	$a= new reportProduct();
	
	$a->setLokasi($lokasi);
	$a->setKriteria('slip');
	$a->setReferer(date('d/m/Y'));
	$a->setFilter($no_faktur);
	$a->setNama('Slip Penjualan');
		  $a->AliasNbPages();
		  $a->AddPage('P');
		   $a->SetFont('Arial','',8);
	   	   $a->SetWidths(array('10','65','18','25','25'));
		   $a->SetAligns(array('C','L','R','R','R'));
		   $n=0;$total=0;
		  foreach($temp_rec as $r)
		  {
			$n++;
			$a->Row(array($n,$r->Nama_Barang,number_format($r->Jumlah,2).' '.$r->Satuan,
						 number_format($r->Harga,2),number_format(($r->Jumlah*$r->Harga),2)));
		  $total=($total+($r->Jumlah*$r->Harga));
		  }
		  if($n<10){
			  $h=(6*(10-$n));
		  $a->MultiCell(133,$h,'',1,0,'L');  
		  }
		  $a->Cell(108,6,'Sub Total :',1,0,'R');
		  $a->Cell(25,6,number_format($total,2),1,1,'R');
		  $a->Cell(108,6,'PPN 10% :',1,0,'R');
		  $a->Cell(25,6,'0.00',1,1,'R');
		  $a->Cell(108,6,'Total :',1,0,'R');
		  $a->Cell(25,6,number_format($total,2),1,1,'R');
		  $a->Cell(108,6,'Cash :',1,0,'R');
		  $a->Cell(25,6,number_format($cash,2),1,1,'R');
		  $a->Cell(108,6,'Kembali :',1,0,'R');
		  $a->Cell(25,6,number_format(($cash-$total),2),1,1,'R');
		  $a->Output('application/logs/'.$this->session->userdata('userid').'_slip_penjualan.pdf','F');
//show in browser
/*$path='application/views/penjualan';
$img=" <img src='".base_url()."asset/images/back.png' onclick='js:window.history.back();' style='cursor:pointer' title='click for select other filter data'>";
//link_js('auto_sugest.js,lap_beli.js,jquery.fixedheader.js','asset/js,'.$path.'/js,asset/js');
panel_begin('Print Preview','','Back'.$img);
?>
		  <iframe src="<?=base_url();?>application/logs/<?=$this->session->userdata('userid');?>_slip_penjualan.pdf" height="100%" width="100%" frameborder="0" allowtransparency="1"></iframe>
<?
panel_end();
*/
?>