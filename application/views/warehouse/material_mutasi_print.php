<?php
		  //$xx=new Kasir_Model;
		  $a=new reportProduct();
		  $zn=new zetro_manager();
		  $nfile='asset/bin/zetro_inv.frm';
		  //$a->Header();
		  $a->setKriteria("transkip");
		  $a->setNama("MUTASI BARANG");
		  $a->setSection('lapmutasi');
		  $darix=rdb('user_lokasi','lokasi','lokasi',"where ID='".$dari."'")." - ".
		  		 rdb('user_lokasi','alamat','alamat',"where ID='".$dari."'");
		  $kelokasix=rdb('user_lokasi','lokasi','lokasi',"where ID='".$kelokasi."'")." - ".
		  		 rdb('user_lokasi','alamat','alamat',"where ID='".$kelokasi."'");
		  $a->setFilter(array($notrans,$tanggal,$darix,$kelokasix,$ket));
		  $a->setReferer(array('No. Transaksi','Tanggal','Dari Lokasi','Ke Lokasi','Keterangan'));
		  $a->setFilename($nfile);
		  $a->AliasNbPages();
		  $a->AddPage("P","A4");
	
		  $a->SetFont('Arial','',10);
		  //echo $a->getColWidth();
		  // set lebar tiap kolom tabel transaksi
		  $a->SetWidths(array(10,30,80,15,25,25));
		  // set align tiap kolom tabel transaksi
		  $a->SetAligns(array("C","C","L","L","R","L"));
		  $a->SetFont('Arial','B',10);
		  $a->SetFont('Arial','',9);
		  $dataz=array();
		  $n=0;$harga=0;$hargaj=0;$jml=0;
		  foreach($temp_rec as $r)
		  {	
		  	 $n++; 
			  $a->Row(array($n,
			  				rdb('inv_barang','Kode','Kode',"where ID='".$r->ID_Barang."'"),
							rdb('inv_barang_kategori','Kategori','Kategori',"where ID='".
							rdb('inv_barang','ID_Kategori','ID_Kategori',"where ID='".$r->ID_Barang."'")."'").' '.
							rdb('inv_barang','Nama_Barang','Nama_Barang',"where ID='".$r->ID_Barang."'"),
							rdb('inv_barang_satuan','Satuan','Satuan',"where ID='".
							rdb('inv_barang','ID_Satuan','ID_Satuan',"where ID='".$r->ID_Barang."'")."'"),
							number_format($r->Jumlah,2),
							$r->Batch));
		  }
		  //buat kolom kosong jika tidak melebihi 10 barus
		  if($n<10){
			  for($i=1;$i<=(10-$n);$i++)
			  {
				$a->Row(array('','','','','',''));  
			  }
		  }
			  //grand total
		  $a->Ln(5);
		  $a->Cell(60,6,'Dibuat Oleh',0,0,'C');
		  $a->Cell(60,6,'Dikirim Oleh',0,0,'C');
		  $a->Cell(60,6,'Diterima Oleh',0,1,'C');
		  $a->Ln(20);
		  $a->Cell(60,6,'(_________________________)',0,0,'C');
		  $a->Cell(60,6,'(_________________________)',0,0,'C');
		  $a->Cell(60,6,'(_________________________)',0,1,'C');
		  $a->Output('application/logs/'.$this->session->userdata('userid').'_mutasi.pdf','F');

//show pdf output in frame

?>