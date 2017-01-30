<?php
/*  class name 	= zetro_table_creator
	Author		= Iswan Putera
	Version		= 1.2
	Function	+ Generate auto table from zetro_table.cfg
*/
class zetro_table_creator extends zetro_manager{
	
	function db_form($path='asset/bin/zetro_table.cfg'){
		$this->path=$path;
	}
	function table_name($name){
		$this->name=strtolower(str_replace(" ","",$name));
	}
	function section($sec){
		$this->sec=$sec;	
	}
	function table_created(){
		$sql='';$pri=1;
		$jml_field=$this->Count($this->sec,$this->path);
		$sql="CREATE TABLE IF NOT EXISTS `".$this->name."` (\n";
			for ($i=1;$i<=$jml_field;$i++){
				$fld=explode(",",$this->rContent($this->sec,$i,$this->path));
					if($fld[1]!='N'){
					$sql .="`".$fld[2]."` ".$fld[3].",\n";
					}else{$sql=$sql;}
			}
			for ($z=1;$z<=$jml_field;$z++){
				$fld2=explode(",",$this->rContent($this->sec,$z,$this->path));
					if($fld2[1]=='P'){
					$sql .="PRIMARY KEY (".$fld2[2].")\n";
					$pri=1;
					}else{
					$pri=0;
					}
			}
			$sql .=" )\nCOLLATE='latin1_swedish_ci'\nENGINE=MyISAM;";
			return mysql_query($sql) or die("<br>".$sql."<br/>".mysql_error());
	}
	
	function generate_table($name,$sec){
		$this->db_form();
		$this->table_name($name);
		$this->section($sec);
		$this->table_created();	
	}
}
?>








