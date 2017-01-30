<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
		 
		  $a=new reportProduct();
		  $zn=new zetro_manager();
		  $nfile='asset/bin/zetro_neraca.frm';
		  $a->setKriteria("transkip");
		  $a->setNama("REKAP MUTASI BARANG");
		  $a->setSection("mutasi");
		  $a->setFilter(array(empty($sampai)?$dari:$dari ." s/d ".$sampai));
		  $a->setReferer(array('Periode Tanggal'));
		  $a->setFilename($nfile);
		  $a->AliasNbPages();
		  $a->AddPage("L","A4");
		  $a->SetFont('Arial','',10);
		  $a->SetWidths(array(10,25,35,35,70,15,30,60));
		  // set align tiap kolom tabel transaksi
		  $a->SetAligns(array("C","C","C","L","L","R","R","L"));
		  $a->SetFont('Arial','B',10);
		  $a->SetFont('Arial','',9);
		  //$rec = $temp_rec->result();
		  $n=0;$harga=0;$hgb=0;$hargaj=0;$jml=0;
		  foreach($temp_rec as $r)
		  {
			$n++;
			$a->Row(array($n,
					tglFromSql($r->Tanggal),
					rdb('user_lokasi','lokasi','lokasi',"where ID='".$r->ID_Lokasi_asal."'"),
					rdb('user_lokasi','lokasi','lokasi',"where ID='".$r->ID_Lokasi_kirim."'")." ".
					rdb('user_lokasi','alamat','alamat',"where ID='".$r->ID_Lokasi_kirim."'"),
					rdb('inv_barang_kategori','Kategori','Kategori',"where ID='".
					rdb('inv_barang','ID_Kategori','ID_Kategori',"where ID='".$r->ID_Barang."'")."'")." ".
					rdb('inv_barang','Nama_Barang','Nama_Barang',"where ID='".$r->ID_Barang."'"),
					rdb('inv_barang_satuan','satuan','satuan',"where ID='".$r->ID_Satuan."'"),
					number_format($r->Jumlah,2),
					$r->Keterangan));
					$jml=($jml+$r->Jumlah);
		  }
		  $a->SetFont('Arial','B',10);
		  $a->SetFillColor(225,225,225);
		  $a->Cell(190,8,"TOTAL",1,0,'R',true);
		  $a->Cell(30,8,number_format($jml,2),1,0,'R',true);
		  $a->Cell(60,8,'',1,0,'R',true);
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