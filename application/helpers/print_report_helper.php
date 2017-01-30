<?php
//define('FPDF_FONTPATH', 'font/');
require('fpdf.php');

class reportProduct extends FPDF 
{
  var $widths;
  var $aligns;

function SetWidths($w)
{
  $this->widths=$w;
}

function SetAligns($a)
{
  $this->aligns=$a;
}
function Row($data,$spacing=6,$rec=true,$color=false,$hg=3.5)
	{
	  $nb=0;
	  for($i=0;$i<count($data);$i++)
		$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
	  $h=($hg*$nb);
	  $this->CheckPageBreak($h);
	  for($i=0;$i<count($data);$i++)
	  {
	   $w=$this->widths[$i];
	   $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
	   $x=$this->GetX();
	   $y=$this->GetY();
	   ($color==true)?$this->SetFillColor(225,225,225):$this->SetFillColor(225,0,0);
	   ($rec==true)? $this->Rect($x,$y,$w,$h+2,($color==true)?'DF':''):'';
	   $this->MultiCell($w,$spacing,$data[$i],'',$a);
	   $this->SetXY($x+$w,$y);
	  }
	  $this->Ln($h+2);
	}
	

function CheckPageBreak($h)
	{
	  if($this->GetY()+($h+3)>$this->PageBreakTrigger)
	  $this->AddPage($this->CurOrientation);
	}
	
	function NbLines($w,$txt)
	{
	  $cw=&$this->CurrentFont['cw'];
	  if($w==0)
		$w		=$this->w-$this->rMargin-$this->x;
		$wmax	=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s		=str_replace("\r",'',$txt);
		$nb		=strlen($s);
	  if($nb>0 and $s[$nb-1]=="\n")
		  $nb--;
		  $sep=-1;
		  $i=0;
		  $j=0;
		  $l=0;
		  $nl=1;
	  while($i<$nb){
	   $c=$s[$i];
	   if($c=="\n") {
			$i++;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			continue;
	   }
	   if($c==' ') $sep=$i;
	   $l+=$cw[$c];
	   if($l>$wmax) {
		if($sep==-1) {
		 if($i==$j) $i++;
		}
		else
		 $i=$sep+1;
		$sep=-1;
		$j=$i;
		$l=0;
		$nl++;
	   }
	   else
	   $i++;
	 }
	 return $nl;
	}
function Header()
{
	$zn=new zetro_manager();
	$nfile='asset/bin/zetro_config.dll';
	$lokasi=empty($this->lokasi)?'InfoCo':$this->lokasi;
	$this->SetTitle($this->nama);
  if($this->kriteria=="transkip"){
	$co		=$zn->rContent($lokasi,'subtitle',$nfile);
	$address=$zn->rContent($lokasi,'Address',$nfile);
	$kota	=$zn->rContent($lokasi,'Kota',$nfile);
	$prop	=$zn->rContent($lokasi,'Propinsi',$nfile);
	$telp	=$zn->rContent($lokasi,'Telp',$nfile);
	$fax	=$zn->rContent($lokasi,'Fax',$nfile);
	$BH		=$zn->rContent($lokasi,'BH',$nfile);
	   $this->Ln(2);
	   $this->Image('asset/img/about.jpg',10,6,70,12);
	   $this->Ln(2);
	   //$this->SetFont('Arial','B',15);
	  // $this->Cell(5);
	  // $this->MultiCell(120,5,$co,0,1,'L');
	   //$this->Cell(5);
	   $this->SetFont('Arial','B',11);
	   //$this->MultiCell(100,5,$BH,0,1,'C');
	   $this->SetFont('Arial','',10);
	   //$this->Cell(3);
	   $this->MultiCell(0,6,"",0,1,'C');
	   $this->SetFont('Arial','',10);
	   //$this->Cell(5);
	   $this->MultiCell(0,6,$address." ". $kota." ". $prop,0,1,'C');
	   $this->MultiCell(0,4,$telp." ". $fax,0,1,'C');
	   $this->Ln(5);
	   $this->SetFont('Arial','B',10);
	   ($this->CurOrientation=='P')?
	   $this->MultiCell(0,4,str_repeat("_",95),0,1,'C'):
	   $this->MultiCell(0,4,str_repeat("_",140),0,1,'C');
	   $this->SetFont('Arial','B',14);
	   $this->Cell(0,10,$this->nama,2,1,'C');
	   $this->SetFont('Arial','B',10);
			  $this->Ln(2); // spasi enter
			  $this->SetFont('Arial','B',11); // set font,size,dan properti (B=Bold)
			  $n=0;
			  foreach($this->refer as $rr){
				  $this->Cell(40,6,$rr,0,0,'L');
				  $this->Cell(130,6,': '.$this->filter[$n],0,1,'L');
				$n++;
			  }
			  $this->Ln(2);
			  // set nama header tabel transaksi
			  $this->SetFillColor(225,225,225);
			  $kol=$zn->Count($this->section,$this->nfilex);
			  $this->Cell(10,8,'No.',1,0,'C',true);
			  for ($i=1;$i<=$kol;$i++){
				  $d=explode(',',$zn->rContent($this->section,$i,$this->nfilex));
				  $this->Cell($d[9],8,$d[0],1,0,'C',true);
			  }
			  $this->Ln();
	
  }
  if($this->kriteria=='faktur'){
		$co		=$zn->rContent($lokasi,'Name',$nfile);
		$address=$zn->rContent($lokasi,'Address',$nfile);
		$kota	=$zn->rContent($lokasi,'Kota',$nfile);
		$telp	=$zn->rContent($lokasi,'Telp',$nfile);
		$fax	=$zn->rContent($lokasi,'Fax',$nfile);
		$npwp	=$zn->rContent($lokasi,'NPWP',$nfile);
		$nppkp	=$zn->rContent($lokasi,'NPPKP',$nfile);
		
		   $this->SetFont('Arial','B',14);
		   $this->Cell(110,4,$co,0,0,'L');
		   $this->SetFont('Arial','',10);
		   $this->Cell(120,6,$this->nama,0,1,'L');
		   $this->Cell(110,6,$address." ". $kota,0,0,'L');
		   $this->Cell(120,6,'Customer :',0,1,'L');
		   $this->Cell(110,6,$telp." ". $fax,0,0,'L');
		   $this->SetFont('Arial','IB',10);
		   $this->Cell(120,6,'   '.$this->refer,0,1,'L');
		   $this->SetFont('Arial','',10);
		   $this->Cell(110,6,$npwp,0,0,'L');
		   $this->SetFont('Arial','IB',10);
		   $this->Cell(125,6,'   '.$this->filter,0,1,'L');
		   $this->SetFont('Arial','',10);
		   $this->Cell(110,6,$nppkp,0,0,'L');
		   $this->Cell(120,6,'NPWP :',0,1,'L');
		   $this->Cell(110,4,'Nomor Seri faktur: '.$this->nofaktur,0,0,'L');
		   $this->Cell(120,6,'Tanggal : '.$this->dataset,0,1,'L');
		   $this->SetFont('Arial','B',10);
		   $this->SetLineWidth(0.4);
		   $this->Line(10,50,197,50);
		   $this->Ln();
		   $this->SetLineWidth(0.2);
		   $this->SetFont('Arial','B',12);
			//header table
			// set nama header tabel transaksi
			  $this->Ln(2);
			  $this->SetFillColor(225,225,225);
			  $kol=$zn->Count($this->section,$this->nfilex);
			  $this->Cell(10,8,'No.',1,0,'C',true);
			  for ($i=1;$i<=$kol;$i++){
				  $d=explode(',',$zn->rContent($this->section,$i,$this->nfilex));
				  $this->Cell($d[9],8,$d[0],1,0,'C',true);
			  }
			  $this->Ln();
 }
 if($this->kriteria=='slip')
 {
	$co		=$zn->rContent($lokasi,'subtitle',$nfile);
	$address=$zn->rContent($lokasi,'Address',$nfile);
	$kota	=$zn->rContent($lokasi,'Kota',$nfile);
	$prop	=$zn->rContent($lokasi,'Propinsi',$nfile);
	$telp	=$zn->rContent($lokasi,'Telp',$nfile);
	$fax	=$zn->rContent($lokasi,'Fax',$nfile);
	$BH		=$zn->rContent($lokasi,'BH',$nfile);
	   $this->Ln(2);
	   $this->Image(base_url().'asset/img/about.jpg',11,8,35,5);
	   $this->Ln(1);
	   $this->SetFont('Arial','',9);
	   $this->Cell(100,5,$address." ". $kota." ". $prop,0,0,'L');
	   $this->Cell(50,5,'Tanggal :',0,0,'R');
	   $this->Cell(30,5,$this->refer,0,1,'L');
	   $this->Cell(100,5,$telp." ". $fax,0,0,'L');
	   $this->Cell(50,5,'No. Faktur :',0,0,'R');
	   $this->Cell(30,5,$this->filter,0,1,'L');
	   $this->SetFont('Arial','',11);
	  // $this->Cell(180,0,'',1,1,'C');
	   $this->Ln();
	   $this->SetFont('Arial','',9);
	   $this->SetWidths(array('12','90','20','30','30'));
	   $this->SetAligns(array('C','C','C','C','C'));
	   $this->Row(array('No','Nama Barang','Jumlah','Harga Satuan','Total Harga'));
	   
 }
 if($this->kriteria=='neraca'){
	$co		=$zn->rContent('InfoCo','subtitle',$nfile);
	$address=$zn->rContent('InfoCo','Address',$nfile);
	$kota	=$zn->rContent('InfoCo','Kota',$nfile);
	$prop	=$zn->rContent('InfoCo','Propinsi',$nfile);
	$telp	=$zn->rContent('InfoCo','Telp',$nfile);
	$fax	=$zn->rContent('InfoCo','Fax',$nfile);
	$BH		=$zn->rContent('InfoCo','BH',$nfile);
	   $this->Ln(2);
	   $this->Image(base_url().'asset/img/about.jpg',10,6,50,15);
	   $this->Ln(2);
	   //$this->SetFont('Arial','B',15);
	  // $this->Cell(5);
	  // $this->MultiCell(120,5,$co,0,1,'L');
	   //$this->Cell(5);
	   $this->SetFont('Arial','B',11);
	   //$this->MultiCell(100,5,$BH,0,1,'C');
	   $this->SetFont('Arial','',10);
	   //$this->Cell(3);
	   $this->MultiCell(0,6,"",0,1,'C');
	   $this->SetFont('Arial','',10);
	   //$this->Cell(5);
	   $this->MultiCell(0,6,$address." ". $kota." ". $prop,0,1,'C');
	   $this->MultiCell(0,4,$telp." ". $fax,0,1,'C');
	   $this->Ln(5);
	   $this->SetFont('Arial','B',10);
	   ($this->CurOrientation=='P')?
	   $this->MultiCell(0,4,str_repeat("_",95),0,1,'C'):
	   $this->MultiCell(0,4,str_repeat("_",140),0,1,'C');
	   $this->SetFont('Arial','B',14);
	   $this->Cell(0,10,$this->nama,2,1,'L');
	   $this->SetFont('Arial','B',10);
			  //$this->Ln(1); // spasi enter
			  $this->SetFont('Arial','B',11); // set font,size,dan properti (B=Bold)
			  $n=0;
			  if(!empty($this->refer)){
				  foreach($this->refer as $rr){
					  $this->Cell(30,6,$rr,0,0,'L');
					  $this->Cell(100,6,': '.$this->filter[$n],0,1,'L');
					$n++;
				  }
			  }
			  $this->Ln(1);
	   $this->SetFont('Arial','B',10);
		   ($this->CurOrientation=='P')?
		   $this->MultiCell(0,4,str_repeat("_",95),0,1,'C'):
		   $this->MultiCell(0,4,str_repeat("_",140),0,1,'C');
 }
 
}

function Footer(){
 // Position at 1.5 cm from bottom
  ($this->kriteria=='neraca')?$this->SetY(-10):$this->SetY(-15);
  //Arial italic 8
  $this->SetFont('Arial','i',7);
  if($this->kriteria=='faktur'){
  $this->Cell(150,10,'Berlaku sebagai faktur pajak sesuai Peraturan Menkeu No. 38/PMK.03/2010',0,0,'L');
  }else{
  $this->Cell(0,10,'Print Date :'.date('d F Y'),0,0,'C');
  }
  $this->Cell(0,10,'Page '.$this->PageNo().' of {nb}',0,0,'R');
}

public function setKriteria($i){
  $this->kriteria=$i;
}

public function getKriteria(){
  return $this->kriteria;
}

public function setNoFaktur($n){
 $this->nofaktur=$n;	
}

public function getNoFaktur(){
  return $this->nofaktur;
}
public function setNama($n){
  $this->nama=$n;
}
public function getNama(){
  return $this->nama;
}
public function setReferer($n){
  	$this->refer=$n;
 
}
public function getReferer(){
  $this->refer;
}

public function setSection($n){
  $this->section=$n;
}

public function getSection(){
  return $this->section;
}
public function setFilter($n){
	$this->filter=$n;
}
public function getFilter(){
	return $this->filter;
}
public function setDataset($n){
  $this->dataset=$n;
}

public function setFilename($n){
	$this->nfilex=$n;
}
public function getFilename(){
	return $this->nfilex;
}
public function getDataset(){
  return $this->dataset;
}
public function setLokasi($n)
{
	$this->lokasi=$n;
}
public function getLokasi()
{
	return $this->lokasi;
}
}

?>