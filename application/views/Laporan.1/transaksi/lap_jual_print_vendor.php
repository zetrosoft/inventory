<?php
		  $a=new reportProduct();
		  $zn=new zetro_manager();
		  $nfile='asset/bin/zetro_neraca.frm';
		  //$a->Header();
		  $a->setKriteria("transkip");
		  $a->setNama("PENJUALAN PER PERLANGGAN");
		  $a->setSection("detailbeli");
		  $a->setFilter(array($dari ." s/d ".$sampai,$vendor));
		  $a->setReferer(array('Periode','Nama Pelanggan'));
		  $a->setFilename($nfile);
		  $a->AliasNbPages();
		  $a->AddPage('P',"A4");
	
		  $a->SetFont('Arial','',10);
		  //echo $a->getColWidth();
		  // set lebar tiap kolom tabel transaksi
		  		$a->SetWidths(array(10,20,20,50,25,18,25,25));
				//$a->SetWidths();
		  // set align tiap kolom tabel transaksi
		  		$a->SetAligns(array("C","C","C","L","R","L","R","R"));
		  $a->SetFont('Arial','',9);
		  //$rec = $temp_rec->result();
		  $n=0;$hgb=0;$hargaj=0;$jml=0;
		  foreach($temp_rec as $r)
		  {
			$n++;$nn=0;$harga=0;$corting=0;
			$a->SetFont('Arial','I',9);
			$a->SetFillColor(210,210,010);	
			$a->Cell(10,8,$n,1,0,'C',true);
			$a->Cell(20,8,tglfromSql($r->Tanggal),1,0,'L',true);
			$a->Cell(20,8,$r->Nomor,1,0,'L',true);
			$a->Cell(75,8,$r->Jenis_Jual." ".$r->ID_Post." - ".$r->Deskripsi,1,0,"L",true);
			$a->Cell(68,8,"Tanggal Jatuh Tempo : ".tglfromSql($r->Tgl_Cicilan),1,1,"L",true);
			$a->SetFont('Arial','',9);
			$ID=" and ID_Jual='".$r->ID_Jual."'";
			$dataz=$this->kasir_model->detail_trans_jual($where,$ID,$orderby);
			foreach($dataz as $rr){
				$nn++;
				$a->Row(array('',$nn,$rr->Kode,$rr->Nama_Barang,
							  number_format($rr->Jumlah,2),
							  $r->Satuan,number_format($rr->Harga,2),
							  number_format(($rr->Jumlah*$rr->Harga),2)));
				//sub tlot
				$jml	=($jml+$rr->Jumlah);
				$hargaj	=($hargaj+$rr->Harga);
				$harga	=($harga+($rr->Jumlah*$rr->Harga));
			}
			  //potongan
			$corting=rdb('inv_pembayaran','ppn','sum(ppn) as ppn',"where no_transaksi='".$r->NoUrut."' and year(doc_date)='".$r->Tahun."'");
			 if($corting!=0){
			  $a->SetFont('Arial','I',10);
			  $a->SetFillColor(242,239,219);
			  $a->Cell(168,8,"Potongan",1,0,'R',true);
			  $a->Cell(25,8,number_format($corting,2),1,1,'R',true);
			 }
			  //subtotal
			  $a->SetFont('Arial','B',10);
			  $a->SetFillColor(242,239,219);
			  $a->Cell(168,8,"Sub Total",1,0,'R',true);
			  $a->Cell(25,8,number_format(($harga-$corting),2),1,1,'R',true);
			  $hgb=($hgb+($harga-$corting));
		  }
		  $a->SetFont('Arial','B',10);
		  $a->SetFillColor(225,225,225);
		  $a->Cell(167,8,"TOTAL",1,0,'R',true);
		  $a->Cell(25,8,number_format($hgb,2),1,0,'R',true);
		  $a->Output('application/logs/'.$this->session->userdata('userid').'_detail_penjualan.pdf','F');

//show pdf output in frame
$path='application/views/laporan';
$img=" <img src='".base_url()."asset/images/back.png' onclick='js:window.history.back();' style='cursor:pointer' title='click for select other filter data'>";
//link_js('auto_sugest.js,lap_beli.js,jquery.fixedheader.js','asset/js,'.$path.'/js,asset/js');
panel_begin('Print Preview','','Back'.$img);
?>
		  <iframe src="<?=base_url();?>application/logs/<?=$this->session->userdata('userid');?>_detail_penjualan.pdf" height="100%" width="100%" frameborder="0" allowtransparency="1"></iframe>
<?
panel_end();

?>