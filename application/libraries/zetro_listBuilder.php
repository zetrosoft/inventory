<?php
class zetro_listBuilder extends zetro_manager{
	public $path;
	var $sql;
	var $con;
	public $data;
	public $title;
	
	function zetro_listBuilder($path=''){
		$this->path=$path;
	}
	function db_query($sql,$con=''){
		$this->sql=$sql;
		$this->con=$con;
	}
    function AddField($txt='')
    {
        $this->addfield=$txt;
        return $this->addfield;
    }
	function ListHeader($section,$width='100%',$id='',$tp=true,$addfld=''){
		//$zm= new zetro_manager;
		$this->tp=$tp;
		$jml=$this->Count($section,$this->path);
		($id=='')?$jud='listTable':$jud=$id;
		echo "<table id='$id' border='0' style='border-collapse:collapse' width='$width'>\n<thead>
			  <tr align='center' class='headere'>\n\r
			  <th id='c0' width='4%' class='kotak'>No.</th>\n\r";
			  for($i=1;$i<=$jml;$i++){
				  $fld=explode(',',$this->rContent($section,$i,$this->path));
				  (count($fld)>6)? $wd="width='".$fld[6]."'":$wd='';
				  ($fld[6]=='')? $st="style='display:none'":$st='';
				  echo "<th id='c$i' $wd class='kotak' $st>".$fld[0]."</th>\n\r";
			  }
		//echo $this->data; 
		echo ($this->tp==true)? "<th id='c".($jml+1)."' width='8%' class='kotak'></th>\n\r</tr>\n\r":"</tr>";
        echo $addfld;
		echo "</thead><tbody>";
	}
	function event($id,$menu='',$stat=''){
		if($stat==''){
		$data= "<img src='".base_url()."asset/images/editor.png' id='$id' class='edit' style='cursor:pointer' title='click for update this line' onclick=\"image_click('".$id."','edit');\">
				  <img src='".base_url()."asset/images/no.png' id='$id' class='del' style='cursor:pointer' title='Click for delete this line' onclick=\"image_click('".$id."','del');\">";
		}elseif ($stat=='process'){
		$data= "<img src='".base_url()."asset/images/16/diagram_16.png' id='$id' style='cursor:pointer' class='process' title='Click for process this data' onclick=\"image_click('".$id."','process');\">";
		}elseif($stat=='deleted'){
		$data="<img src='".base_url()."asset/images/no.png' id='$id' class='del' style='cursor:pointer' title='Click for delete this line' onclick=\"image_click('".$id."','del');\">";
		}else if($stat=='inline'){
		$data= "<img src='".base_url()."asset/images/save.png' id='$id' class='edit' style='cursor:pointer' title='click for update this line' onclick=\"image_click('".$id."','edit');\">
				<img src='".base_url()."asset/images/no.png' id='$id' class='del' style='cursor:pointer' title='Click for delete this line' onclick=\"image_click('".$id."','del');\">";
		}else if($stat=='simpan'){
		$data= "<img src='".base_url()."asset/images/save.png' id='$id' class='edit' style='cursor:pointer' title='click for saved data' onclick=\"image_click('".$id."','simpan');\">";
		}
		return $data;
	}
	function ListBuilder($section,$total=false,$tp='list'){
		$n=0;
		//$zm= new zetro_manager;
		$jml=$this->Count($section,$this->path);
		$this->rs=mysql_query($this->sql,$this->con) or die(mysql_error());
			while($row=mysql_fetch_array($this->rs)){
				$n++;
				echo "<tr align='center' class='xx'>\n
				<td id='c00' class='kotak'>No.</td>";
				  for($i=1;$i<=$jml;$i++){
					  $fld=explode(',',$this->rContent($section,$i,$this->path));
					  echo "<td id='c$i$n' class='kotak'>".$row[$fld[3]]."</td>";
				  }
			echo ($this->tp=='list')? "<td id='c".($jml+1)."' class='kotak' >".$this->event($n)."</td></tr>":"</tr>";
			}
		echo "</table>";
		
	}
	function AddColom($n=1,$confirm=false,$title=false){
		$this->data='';
		$tt=explode(',',$this->title);
		if($confirm==true){
			for ($i=1;$i<=$n;$i++){
			 ($title==true)?
				$this->data="<td class='kotak'>".($title==false)? $i:$tt[$i]."</td>":
				$this->data=$this->data."<th class='kotak'>$i</th>";
			}
			return $this->data;
		}
	}
	function AddTitle($title=''){
		$this->title=$title;
		return $this->title;
	}
	
}
?>