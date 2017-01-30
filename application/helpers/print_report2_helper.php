<?php
//define('FPDF_FONTPATH', 'font/');
require('fpdf2.php');

class reportProduct2 extends FPDF2 
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
function Row($data,$rec=true,$color=false,$kolom='',$kolom1='',$kolom2='')
{
  $nb=0;
  for($i=0;$i<count($data);$i++)
    $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
  $h=(5.5*$nb);
  $this->CheckPageBreak($h);
  for($i=0;$i<count($data);$i++)
  {
   $w=$this->widths[$i];
   $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
   $x=$this->GetX();
   $y=$this->GetY();
   ($color==false)? $this->SetTextColor(0):
   ($kolom==$i || $kolom1==$i || $kolom2==$i)?
    $this->SetTextColor(255,0,0): $this->SetTextColor(0);
   ($rec==true)? $this->Rect($x,$y,$w,$h+2):'';
   $this->MultiCell($w,6,$data[$i],'',$a);
   $this->SetXY($x+$w,$y);
  }
  $this->Ln($h+2);
}

function CheckPageBreak($h)
{
  if($this->GetY()+$h>$this->PageBreakTrigger)
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
   if($c==' ')
    $sep=$i;
   $l+=$cw[$c];
   if($l>$wmax) {
    if($sep==-1) {
     if($i==$j)
      $i++;
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

}

function Footer(){
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