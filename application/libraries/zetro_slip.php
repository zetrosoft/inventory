<?

class zetro_slip{
	public $path;
	public $sessi;
	function __construct($path=''){
		$this->path=$path;
	}
	
	function namafile($filename){
		$this->filename=$filename;
	}
	function colom($kolom){
		$this->colom=$colom;
	}
	
	function newline($jmlbaris=1){
		//$this->jmlbaris=1;
		for ($i=1;$i<=$jmlbaris;$i++){
			$this->jmlbaris .="\r\n";		
		}
		return $this->jmlbaris;
	}
	function modele($model="wb"){
		$this->model=$model;	
	}
	
/*	function create_file($nm=true,$printer_name=''){
		$dir=sys_get_temp_dir();
		$file=tempnam($dir,$this->sessi.'_slip');
		$newfile=fopen($file,$this->model);
		if ($nm==true){ fwrite($newfile,$this->newline());}
		foreach($this->isifile as $data){
		fwrite($newfile,$data);
		}
		if ($nm==true){ fwrite($newfile,$this->newline());}
		fclose($newfile);
		copy($file,$printer_name);
		unlink($file);
		echo $file."=".$printer_name;
	
	}
*/
	function create_file($nm=true){
		$name='c:/app';
		createDir($name);
		$newfile=fopen('c:\\app\\'.$this->path.'_slip_mj.txt',$this->model);
		if ($nm==true){ fwrite($newfile,$this->newline());}
		foreach($this->isifile as $data){
		fwrite($newfile,$data);
		}
		if ($nm==true){ fwrite($newfile,$this->newline());}
		fclose($newfile);
	}
	function content($isifile=array()){
		$this->isifile=$isifile;
	}
}