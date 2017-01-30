<?php
/*
	class for execute query database mysql
*/
class zetro_buildlist extends zetro_listbuilder {
	public $zn;
	public $edit;
	public $statu;
	function __construct(){
		$this->zn= new zetro_manager();
	}
	function query($sql){
		$this->sql=$sql;
	}
	function rs($rst){
		$this->rst=mysql_query($this->sql) or die(mysql_error());
	}
	function config_file($filename='asset/bin/zetro_inv.frm'){
		$this->filename=$filename;	
	}
	function section($section){
		$this->section=$section;	
	}
	function list_data(){
		$data=array();
		$ii=$this->zn->Count($this->section,$this->filename);
		for ($n=1;$n<=($ii);$n++){
			$isi=explode(",",$this->zn->rContent($this->section,"$n",$this->filename));
			!empty($isi[2])?$jenis=explode(" ",$isi[2]):$jenis='';
			if(count($jenis)>1 && $isi[6]!=''){
				$tipe_col=$jenis[1];//:'';
				$data[]=$isi[3].".".$tipe_col;
			}
		}
		$this->data=$data;	
		//print_r($this->data);
	}
	public function inlineedit($edit='n'){
		$this->edit=$edit;
	}
	public function sub_total($stat=false){
		$this->statu=$stat;
	}
	function aksi($aksi=true){
		$this->aksi=$aksi;
	}
	function icon($jn=''){
		$this->jenis=$jn;
	}
	function linkmenu($mn=''){
		$this->menu=$mn;
	}
	function Header($width='100%',$IdTable='ListTable',$addfld=''){
		$this->path=$this->filename;
		$this->ListHeader($this->section,$width,$IdTable,$this->aksi,$addfld);
	}
	function deskripsi($tabel='',$field='',$kolom=''){
		$this->tabel=$tabel;
		$this->field=$field;
		$this->kolom=$kolom;
	}
	function BuildListData($pk='',$nuberInFirstColumn=true){
		$n=0;
		$flds=array();
		//echo $this->sql;
		$rs=mysql_query($this->sql) or die(mysql_error());
		if(mysql_num_rows($rs)){
			$fldx="";
			while($rw=mysql_fetch_array($rs)){
				$n++;
					if($this->statu==true){
						foreach($this->fields as $fld){
						empty($flds[$fld])?$flds[$fld]=$rw[$fld]:$flds[$fld]=($flds[$fld]+$rw[$fld]);
						}
					}
					
			($pk=='')? $id=$rw['id']:$id=$rw[$pk];
				echo "<tr class='xx' id='nm-".str_replace(' ','_',$id)."'>\n";
				echo ($nuberInFirstColumn==true)?
				 "<td class='kotak' align='center'>$n</td>\n": "";
					for ($i=0 ;$i<(count($this->data));$i++){
					   $jenis=explode('.',$this->data[$i]);
					   $this->nom(str_replace(' ','_',$id));
					   if($jenis[1]=='t'){
						echo "<td class='kotak' align='center'>".tglfromSql($rw[$jenis[0]])."</td>\n";
					   }else if($jenis[1]=='d'){
						echo "<td class='kotak' align='right'>".number_format($rw[$jenis[0]],2,'.',',')."</td>\n";
					   }else if($jenis[1]=='dn'){
					   echo ($this->edit=='y')?
						 "<td class='kotak' align='left' nowrap>".$this->field_inline(($i+1),number_format($rw[$jenis[0]],2))."</td>\n":
						 "<td class='kotak' align='right'>".number_format($rw[$jenis[0]],2,'.',',')."</td>\n";
					   }else{
						echo (!empty($this->kolom) && $this->kolom==$jenis[0])?
						"<td class='kotak' align='left'>".rdb($this->tabel,$this->field,'',"where ".$this->kolom."='".$rw[$jenis[0]]."'")."</td>\n":
						"<td class='kotak' align='left'>".$rw[$jenis[0]]."</td>\n";
					   }
					}
				//add icon action eq; edit icon, delete icon, process icon
				echo ($this->aksi==true)? "<td class='kotak' align='center' >".$this->event($this->section.'-'.str_replace(' ','_',$id),'',$this->jenis)."</td></tr>\n":"</tr>\n";
			}
		}else{
		//if data not found in database
		echo "<tr class='xx'><td class='kotak' align='center'><img src='".base_url()."asset/images/16/warning_16.png'></td>
				<td class='kotak' colspan='".(count($this->data)+1)."'>
				Data not found in database...(0)</td></tr>";
		}
		//add sub total	
		 if($this->statu==true){
			$clm=explode(",",$this->kol);
			echo "<tr class='xx j_info' align='right'>
				  <td colspan='".($clm[0]-1)."' class='kotak'><b>TOTAL</b></td>";
				 // for ($z=0;$z<count($clm);$z++){
				  foreach($flds as $z){
					echo (!empty($flds))?"<td class='kotak'>".number_format($z,2)."</td>":
							"<td class='kotak'>".$z."</td>";  
				  }
				  $jmlkolom=($clm[0]+count($clm)-1);
				  $totalkolom=(count($this->data)+1);
				  if(($totalkolom-$jmlkolom)>0) {
					  for ($n=1;$n<=($totalkolom-$jmlkolom);$n++){
						  echo "<td class='kotak'>&nbsp;</td>";
					  }
				  }
					  
			echo "</tr>";
		 }
	}
	function sub_total_kolom($kol){
		$this->kol=$kol;
	}
	function sub_total_field($fields){
		$this->fields=$fields;
	}
	
	function nom($nom='nm_barang'){
		$this->nomor=$nom;
	}
	function field_inline($n='',$isi=''){
		$this->tipe='';
		$this->isi=$isi;
		$ad='';
				$fld=explode(",",$this->zn->rContent($this->section,$n,$this->filename));
				  $tp=explode(" ",$fld[2]);
				  //if ($fld[1]!='')
				  switch($fld[1]){
					  case 'input':
				  		$this->tipe="<".$fld[1]." type='".$tp[0]."' id='".$this->nomor.'-'.$fld[3]."' name='".$this->nomor.'-'.$fld[3]."' value='".$this->isi."' class='".$fld[4]."'>".$ad
						.$this->event($this->nomor.'-'.$this->section.'-'.str_replace(' ','_',$fld[3]),'',$this->jenis);
					  break;
					  case 'select':
					  	$this->tipe= "<".$fld[1]." id='".$fld[3]."' name='".$fld[3]."' class='".$fld[4]."'>";
						if (count($fld)>=9){
							if($fld[7]=='RS'){
								$this->tipe.= "<option></option>";
									for ($x=1;$x<=$this->Count($fld[8],$this->path);$x++){
										$data=explode(",",$this->rContent($fld[8],$x,$this->path));
										$this->tipe.= "<option value='".$data[0]."'>".$data[1]."</option>\n\r";
									}
								}else if($fld[7]=='RD'){
									$data=explode("-",$fld[8]);
									dropdown($data[0],$data[1],$data[2],$data[3]);
							}
						}
						$this->tipe.= "</select>".$ad ;
					  break;
					  case 'textarea':
					  	$this->tipe= "<".$fld[1]." id='".$fld[3]."' name='".$fld[3]."' class='".$fld[4]."'></textarea>".$ad;
					  break;
					  case 'button':
				  		//echo "<".$fld[1]." type='".$fld[2]."' id='".$fld[3]."' value='".$fld[5]."' class='".$fld[4]."'></td>";
					  break;	   
			}
			return $this->tipe;	
	}
}