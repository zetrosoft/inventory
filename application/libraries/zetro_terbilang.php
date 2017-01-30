<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Zetro_terbilang {
	function __construct(){
		
	}
	function kekata($x) {
		$x = abs($x);
		$angka = array("", "satu", "dua", "tiga", "empat", "lima",
		"enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$this->temp = "";
		if ($x <12) {
			$this->temp = " ". $angka[$x];
		} else if ($x <20) {
			$this->temp = $this->kekata($x - 10). " belas";
		} else if ($x <100) {
			$this->temp = $this->kekata($x/10)." puluh". $this->kekata($x % 10);
		} else if ($x <200) {
			$this->temp = " seratus" . $this->kekata($x - 100);
		} else if ($x <1000) {
			$this->temp = $this->kekata($x/100) . " ratus" . $this->kekata($x % 100);
		} else if ($x <2000) {
			$this->temp = " seribu" . $this->kekata($x - 1000);
		} else if ($x <1000000) {
			$this->temp = $this->kekata($x/1000) . " ribu" . $this->kekata($x % 1000);
		} else if ($x <1000000000) {
			$this->temp = $this->kekata($x/1000000) . " juta" . $this->kekata($x % 1000000);
		} else if ($x <1000000000000) {
			$this->temp = $this->kekata($x/1000000000) . " milyar" . $this->kekata(fmod($x,1000000000));
		} else if ($x <1000000000000000) {
			$this->temp = $this->kekata($x/1000000000000) . " trilyun" . $this->kekata(fmod($x,1000000000000));
		}
			return $this->temp;
	}
	function terbilang($x, $style=4) {
		if($x<0) {
			$this->hasil = "minus ". trim($this->kekata($x));
		} else {
			$this->poin  = trim($this->tkoma($x));
			$this->hasil = trim($this->kekata($x));
		}
		switch ($style) {
			case 1:
			    if($this->poin){$this->hasil = strtoupper($this->hasil).' KOMA '.strtoupper($this->poin);}else{
					$this->hasil = strtoupper($this->hasil);}
				break;
			case 2:
			    if($this->poin){$this->hasil = strtolower($this->hasil).' koma '.strtolower($this->poin);}else{
				$this->hasil = strtolower($this->hasil);}
				break;
			case 3:
			    if($this->poin){$this->hasil = ucwords($this->hasil).' Koma '.ucwords($this->poin);}else{
				$this->hasil = ucwords($this->hasil);}
				break;
			default:
			    if($this->poin){$this->hasil = ucfirst($this->hasil).' koma '.$this->poin;}else{
				$this->hasil = ucfirst($this->hasil);}
				break;
		}
		return $this->hasil;
	}
	function tkoma($x){
		$x=stristr($x,".");
		$angka=array('nol','satu','dua','tiga','empat','lima','enam','tujuh','delapan','sembilan');
		$this->tmp=" ";
		$pjg=strlen($x);
		$pos=1;
		while ($pos < $pjg){
			$char=substr($x,$pos,1);
			$pos++;
			$this->tmp .=" ".$angka[$char];
		}
		return $this->tmp;
	}
	function cekConn()
	{
		//Initiates a socket connection to www.itechroom.com at port 80
		$conn = @fsockopen("www.google.com", 80, $errno, $errstr, 30);
		if ($conn)
		{
			$status = 'Y';
			fclose($conn);
		}
		else
		{
			$status ='N';
			//$status .= "$errstr ($errno)";
		}
		return $status;
	}
}