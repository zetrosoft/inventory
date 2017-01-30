<?php
		  $a=new reportProduct();
		  $zn=new zetro_manager();
		  $nfile='asset/bin/zetro_neraca.frm';
		  //$a->Header();
		  $a->setLokasi($lokasi);
		  $a->setKriteria("transkip");
		  $a->setNama("REKAP PENJUALAN ".strtoupper($judul));
		  $a->setSection(($orient=='P')?"rekapjualtunai":"detailjual");
		  $a->setFilter(array($dari ." s/d ".$sampai,$Kategori));
		  $a->setReferer(array('Periode','Kategori'));
		  $a->setFilename($nfile);
		  $a->AliasNbPages();
		  $a->AddPage($orient,"A4");
	
		  $a->SetFont('Arial','',10);
		  //echo $a->getColWidth();
		  // set lebar tiap kolom tabel transaksi
		  		$a->SetWidths(array(10,30,60,20,18,25,25));
				//$a->SetWidths();
		  // set align tiap kolom tabel transaksi
		  		$a->SetAligns(array("C","L","L","R","L","R","R"));
		  $a->SetFont('Arial','B',10);
		  $a->SetFont('Arial','',9);
		  //$rec = $temp_rec->result();
		  $n=0;$harga=0;$hgb=0;$hargaj=0;$jml=0;$corting=0;
		  foreach($temp_rec as $r)
		  {
			$n++;
		  ($orient=='P')?
			$a->Row(array($n,
						  rdb('inv_barang','Kode','Kode',"where ID='".$r->ID_Barang."'"),
						  ucwords(strtolower(rdb('inv_barang','Nama_Barang','Nama_Barang',"where ID='".$r->ID_Barang."'"))),
						  number_format($r->Jumlah,2),
						  rdb('inv_barang_satuan','Satuan','Satuan',"where ID='".rdb('inv_barang','ID_Satuan','ID_Satuan',"where ID='".$r->ID_Barang."'")."'"),
						  number_format($r->Harga,2),
						  number_format(($r->Jumlah*$r->Harga),2)
						  )):
			$a->Row(array($n,ucwords(strtolower($r->Nama_Barang)),number_format($r->Jumlah,2),
						  $r->Satuan,number_format($r->Harga,2),
						  number_format(($r->Jumlah*$r->Harga),2),
						  $r->Nama,
						  $r->Jenis_Jual." - ".$r->Nomor));
			//sub tlot
			$corting=rdb('inv_pembayaran','ppn','sum(ppn) as ppn',"where no_transaksi='".$r->NoUrut."' and year(doc_date)='".$r->Tahun."'");
			$jml	=($jml+$r->Jumlah);
			$hargaj	=($hargaj+$r->Harga);
			$harga	=($harga+(($r->Jumlah*$r->Harga)-$corting));
			
		  }
		  if($corting!=0){
			  $a->SetFont('Arial','I',10);
			  $a->SetFillColor(225,225,225);
			  $a->Cell(100,8,"Potongan",1,0,'R',true);
			  $a->Cell(20,8,'',1,0,'R',true);
			  $a->Cell(18,8,'',1,0,'C',true);
			  $a->Cell(25,8,'',1,0,'R',true);
			  $a->Cell(25,8,number_format($corting,2),1,1,'R',true);
		  }
		  $a->SetFont('Arial','B',10);
		  $a->SetFillColor(225,225,225);
		  $a->Cell(100,8,"TOTAL",1,0,'R',true);
		  $a->Cell(20,8,number_format($jml,2),1,0,'R',true);
		  $a->Cell(18,8,'',1,0,'C',true);
		  $a->Cell(25,8,number_format($hargaj,2),1,0,'R',true);
		  $a->Cell(25,8,number_format($harga,2),1,1,'R',true);
		  $a->Output('application/logs/'.$this->session->userdata('userid').'_rekap_penjualan.pdf','F');

//show pdf output in frame
$path='application/views/laporan';
$img=" <img src='".base_url()."asset/images/back.png' onclick='js:window.history.back();' style='cursor:pointer' title='click for select other filter data'>";
//link_js('auto_sugest.js,lap_beli.js,jquery.fixedheader.js','asset/js,'.$path.'/js,asset/js');
panel_begin('Print Preview','','Back'.$img);
?>
		  <iframe src="<?=base_url();?>application/logs/<?=$this->session->userdata('userid');?>_rekap_penjualan.pdf" height="100%" width="100%" frameborder="0" allowtransparency="1"></iframe>
<?
panel_end();

?>