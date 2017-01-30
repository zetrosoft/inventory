<?php
		  //$xx=new Kasir_Model;
		  $a=new reportProduct();
		  $zn=new zetro_manager();
		  $nfile='asset/bin/zetro_neraca.frm';
		  //$a->Header();
		  $a->setKriteria("transkip");
		  $a->setNama("LAPORAN PEMBELIAN BARANG");
		  $a->setSection(($detail=='')?'rekapbeli':'detailbeli');
		  $a->setFilter(array($dari ." s/d ".$sampai));
		  $a->setReferer(array('Periode'));
		  $a->setFilename($nfile);
		  $a->AliasNbPages();
		  $a->AddPage(($detail=='')?"P":"P","A4");
	
		  $a->SetFont('Arial','',10);
		  //echo $a->getColWidth();
		  // set lebar tiap kolom tabel transaksi
		  $a->SetWidths(($detail=='')?array(10,20,27,60,30,35):array(10,20,27,50,19,18,25,27));
		  // set align tiap kolom tabel transaksi
		  $a->SetAligns(($detail=='')?array("C","C","C","L","R","L"):array("R","C","C","L","R","L","R","R"));
		  $a->SetFont('Arial','B',10);
		  $a->SetFont('Arial','',9);
		  $dataz=array();
		  $n=0;$harga=0;$hargaj=0;$jml=0;
		  foreach($temp_rec as $r)
		  {
			$n++;$nn=0;$hgb=0;
			if($detail==''){
			$a->Row(array($n, tglfromSql($r->Tanggal),$r->NoUrut,
						  ucwords($r->Nama),
						  number_format(($r->Harga_Beli),2),
						  $r->Nomor
						  )) ;
                $harga=($harga+$r->Harga_Beli);
			}else if($detail=='detail'){
			$a->SetFont('Arial','B',9);
			$a->SetFillColor(210,210,010);	
			$a->Cell(10,8,$n,1,0,'C',true);
			$a->Cell(97,8,$r->Nama,1,0,'L',true);
			$a->Cell(62,8,$r->Catatan." ".$r->Alamat." ".$r->Kota,1,0,"L",true);
			$a->Cell(27,8,'',1,1,'R',true);
			$a->SetFont('Arial','',9);
			$ID_P=" and p.ID_Pemasok='".$r->ID_Pemasok."'";
			$dataz=$this->kasir_model->detail_trans_beli($where,$ID_P,$orderby);
				foreach($dataz as $r2){
					$nn++;
					$a->Row(array($nn,tglfromSql($r2->Tanggal),$r2->Nomor,
								  ucwords(strtolower($r2->Nama_Barang)),
								  number_format($r2->Jumlah,2),
								  $r2->Satuan,
								  number_format(($r2->Harga_Beli),2),
								  number_format(($r2->Harga_Beli*$r2->Jumlah),2)
								  )) ;
				$hgb=($hgb+($r2->Harga_Beli*$r2->Jumlah));
                $harga =($harga+($r2->Harga_Beli*$r2->Jumlah));
				}
			//sub tlot
			  $a->SetFont('Arial','B',9);
			  $a->SetFillColor(242,239,219);
			  $a->Cell(169,8,"Sub Total",1,0,'R',true);
			  $a->Cell(27,8,number_format($hgb,2),1,1,'R',true);
			}
			  //grand total
				
			
		  }
		  $a->SetFont('Arial','B',9);
		  $a->SetFillColor(225,225,225);
		  $a->Cell(($detail=='')?117:169,8,"GRAND TOTAL",1,0,'R',true);
		  $a->Cell(($detail=='')?30:27,8,number_format($harga,2),1,0,'R',true);
		  ($detail!='')?'':
		  $a->Cell(35,8,'',1,0,'R',true);
		  $a->Output('application/logs/'.$this->session->userdata('userid').'_rekap_pembelian.pdf','F');

//show pdf output in frame
$path='application/views/laporan';
$img=" <img src='".base_url()."asset/images/back.png' onclick='js:window.history.back();' style='cursor:pointer' title='click for select other filter data'>";
//link_js('auto_sugest.js,lap_beli.js,jquery.fixedheader.js','asset/js,'.$path.'/js,asset/js');
panel_begin('Print Preview','','Back'.$img);
?>
		  <iframe src="<?=base_url();?>application/logs/<?=$this->session->userdata('userid');?>_rekap_pembelian.pdf" height="100%" width="100%" frameborder="0" allowtransparency="1" style="z-index:100"></iframe>
<?
panel_end();

?>