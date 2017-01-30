<?php
		  $a=new reportProduct();
		  $zn=new zetro_manager();
		  $nfile='asset/bin/zetro_neraca.frm';
		  //$a->Header();
		  $a->setKriteria("transkip");
		  $a->setNama("LAPORAN KAS MASUK");
		  $a->setSection("kasmasuk");
		  $a->setFilter(array(($sampai=='')?$dari:$dari .' s/d '. $sampai,$id_jenis));
		  $a->setReferer(array('Periode','Jenis Pembayaran'));
		  $a->setFilename($nfile);
		  $a->AliasNbPages();
		  $a->AddPage("P","A4");
		  $a->SetFont('Arial','',10);
		  // set lebar tiap kolom tabel transaksi
		  // set align tiap kolom tabel transaksi
		  $a->SetWidths(array(10,25,20,30,30,75));
		  $a->SetAligns(array("C","C","C","R","R","L"));
		  $a->SetFont('Arial','',9);
		  //$rec = $temp_rec->result();
		  $n=0;$harga=0;$hgb=0;$hargaj=0;$terima=0;
		  foreach($temp_rec as $r)
		  {
			$n++;$dibayar=0;$tobayar=0;
			$des=($r->Deskripsi!='')?" No.: ".$r->ID_Post." ".$r->Deskripsi." [ ". tglfromSql($r->Tgl_Cicilan)." ]":'';
			$dibayar=rdb('inv_pembayaran','jml_dibayar','sum(jml_dibayar) as jml_dibayar',"where /*no_transaksi='".$r->NoUrut."' and*/ date(doc_date)='".$r->Tanggal."' and ID_Jenis='".$r->ID_Jenis."' group by concat(ID_Jenis,date(doc_date))");
			$tobayar=rdb('inv_pembayaran','total_bayar','sum(total_bayar) as total_bayar',"where /*no_transaksi='".$r->NoUrut."' and*/ date(doc_date)='".$r->Tanggal."' and ID_Jenis='".$r->ID_Jenis."' group by concat(ID_Jenis,date(doc_date))");
			$terima=(($dibayar-$tobayar)>0)?(int)$tobayar:(int)$dibayar;
			$a->Row(array($n,$r->Nomor, tglfromSql($r->Tanggal),
				number_format($r->tHarga,2),
				number_format($terima,2),
				rdb('inv_penjualan_jenis','Jenis_Jual','Jenis_Jual',"where ID='".$r->ID_Jenis."'").$des,
				
				));
			//sub tlot
			$harga =($harga+($r->tHarga));
			$hargaj =($hargaj+$terima);
		  }
		  $a->SetFont('Arial','B',10);
		  $a->SetFillColor(225,225,225);
		  $a->Cell(55,8,"TOTAL",1,0,'R',true);
		  $a->Cell(30,8,number_format($harga,2),1,0,'R',true);
		  $a->Cell(30,8,number_format($hargaj,2),1,0,'R',true);
		  $a->Cell(75,8,'',1,1,'R',true);
/*		  $a->Cell(140,8,"SALDO",1,0,'R',true);
		  $a->Cell(30,8,number_format(($harga-$hargaj),2),1,0,'R',true);
*/		  $a->Output('application/logs/'.$this->session->userdata('userid').'_kas_masuk.pdf','F');

//show pdf output in frame
$path='application/views/laporan';
$img=" <img src='".base_url()."asset/images/back.png' onclick='js:window.history.back();' style='cursor:pointer' title='click for select other filter data'>";
//link_js('auto_sugest.js,lap_beli.js,jquery.fixedheader.js','asset/js,'.$path.'/js,asset/js');
panel_begin('Print Preview','','Back'.$img);
?>
		  <iframe src="<?=base_url();?>application/logs/<?=$this->session->userdata('userid');?>_kas_masuk.pdf" height="100%" width="100%" frameborder="0" allowtransparency="1"></iframe>
<?
panel_end();

?>