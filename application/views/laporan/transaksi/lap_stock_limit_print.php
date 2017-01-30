<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
		 
		  $a=new reportProduct();
		  $zn=new zetro_manager();
		  $nfile='asset/bin/zetro_neraca.frm';
		  //$a->Header();
		  $a->setKriteria("transkip");
		  $a->setNama("LAPORAN BARANG YANG HARUS DI BELI");
		  $a->setSection("stocklimit");
		  $a->setFilter(array($kategori));
		  $a->setReferer(array('Kategori'));
		  $a->setFilename($nfile);
		  $a->AliasNbPages();
		  $a->AddPage("P","A4");
	
		  $a->SetFont('Arial','',10);
		  //echo $a->getColWidth();
		  // set lebar tiap kolom tabel transaksi
		 // $a->SetWidths(array(10,25,60,20,15,25));
		  $a->SetWidths(array(10,30,60,25,25,30));
		  // set align tiap kolom tabel transaksi
		  $a->SetAligns(array("C","L","L","R","R","L"));
		  $a->SetFont('Arial','B',10);
		  $a->SetFont('Arial','',9);
		  //$rec = $temp_rec->result();
		  $n=0;$harga=0;$hgb=0;$hargaj=0;$jml=0;
		  foreach($temp_rec as $r)
		  {
			$n++;
			((int)$r->stock <=$r->minstok)?
			$a->Row(array($n, strtoupper($r->Kode),
						 $r->Nama_Barang,
						 number_format($r->minstok,0),
						 number_format($r->stock,0),
						 rdb('inv_barang_satuan','Satuan','Satuan',"where ID='".$r->ID_Satuan."'"),
						 
						  )):'';
		  }
		  $a->Output('application/logs/'.$this->session->userdata('userid').'_mutasi.pdf','F');

//show pdf output in frame
$path='application/views/laporan';
$img=" <img src='".base_url()."asset/images/back.png' onclick='js:window.history.back();' style='cursor:pointer' title='click for select other filter data'>";
//link_js('auto_sugest.js,lap_beli.js,jquery.fixedheader.js','asset/js,'.$path.'/js,asset/js');
panel_begin('Print Preview','','Back'.$img);
?>
		  <iframe src="<?=base_url();?>application/logs/<?=$this->session->userdata('userid');?>_mutasi.pdf" height="100%" width="100%" frameborder="0" allowtransparency="1" style="z-index:100"></iframe>
<?
panel_end();

?>