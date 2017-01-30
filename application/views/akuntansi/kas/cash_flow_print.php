<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

		  $a=new reportProduct();
		  $TglLong=LongTgl($dari);
		  $TglSamp=LongTgl($sampai);
		  $a->setKriteria("neraca");
		  $a->setNama("LAPORAN ALIRAN KAS");
		  $a->setSection("");
		  $a->setFilter(array(($sampai=='')?$TglLong:$TglLong." s/d ".$TglSamp,$lokasi));
		  $a->setReferer(array('Periode','Lokasi'));
		  $a->setFilename('asset/bin/zetro_akun.frm');
		  $a->AliasNbPages();
		  //$a->AddPage("P","A4");
		  $a->AddPage('P','A4');
		  $a->SetFont('Arial','',10);
		  $a->SetWidths(array(100,30,30));// set lebar tiap kolom tabel transaksi
		  $a->SetAligns(array("L","R","R"));// set align tiap kolom tabel transaksi
		  $a->SetFont('Arial','B',10);
		  $data=array();$n=0;
		  $ax=0;$k=0;$saldo=0;
		  $where=($sampai=='')?
		  			"where tgl_kas='".tgltoSql($dari)."'":
					"where tgl_kas between '".tgltoSql($dari)."' and '".tgltoSql($sampai)."'";
		  $where.=($lokasi=='All')?'':" and id_lokasi='".$area."'";
		  foreach($temp_rec as $r){
			$n++;
			$tot=0;
			($r->ID==5)?'':
			$a->Row(array($r->Kode.'.'.$r->Nama_Kas),6,false); 
			$data=$this->report_model->lap_sub_cash($r->ID);
			$a->SetFont('Arial','',10);
				foreach($data as $row){
					$valued=0;
					if($row->ID_CC==0){
						$valued=rdb('mst_kas_harian','saldo','sum(sa_kas) as saldo',$where ." group by tgl_kas");
						$tot=rdb('mst_kas_harian','saldo','sum(sa_kas) as saldo',$where ." group by tgl_kas");
					}else /*if($row->ID_CC!=4)*/{
						$datax=$this->report_model->get_cash_flow($row->ID_CC,$dari,$sampai,$lokasine);
						foreach($datax as $d){
							$valued=$d->Total;
							$tot=($tot+$d->Total);
						}
						if($row->ID_Calc==1){
							$ax=($ax+$valued);
						}else if($row->ID_Calc==2){
							$ax=($ax-$valued);
						}
					}
					$a->Row(array(sepasi(5).$row->Nama_SubKas,number_format((int)$valued,2),''),6,false);	
				}
			 $a->SetFont('Arial','B',10);
			($r->ID==5)?'':
			 $a->Row(array(sepasi(15).'Total '. ucwords($r->Nama_Kas),($r->ID==3)?number_format((int)$ax,2):number_format((int)$tot,2)),6,false); 
			 $k=rdb('mst_kas_harian','saldo','sum(sa_kas) as saldo',$where ." group by tgl_kas");
			 $saldo=($k+$ax);
			($r->ID==5)?$a->Row(array($r->Kode.'.'.$r->Nama_Kas,number_format((int)$saldo,2)),6,false):''; 
		  }
		  
		  $a->Output('application/logs/'.$this->session->userdata('userid').'_cash_flow.pdf','F');

//show pdf output in frame
$path='application/views/_laporan';
$img=" <img src='".base_url()."asset/images/back.png' onclick='js:window.history.back();' style='cursor:pointer' title='click for select other filter data'>";
//link_js('auto_sugest.js,lap_beli.js,jquery.fixedheader.js','asset/js,'.$path.'/js,asset/js');
panel_begin('Print Preview','','Back'.$img);
?>
		  <iframe src="<?=base_url();?>application/logs/<?=$this->session->userdata('userid');?>_cash_flow.pdf" height="100%" width="100%" frameborder="0" allowtransparency="1"></iframe>
<?
panel_end();

?>