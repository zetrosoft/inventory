<?php
class zetro_manager{
	var $CRLF;
	var $bi_a;
	var $filePATH;
	function __construct (){ //zetro_manager(){
          $info = strtolower( $_SERVER['HTTP_USER_AGENT'] );
      
          $this->crlf = ( strpos( strtolower( $info ), "windows", 0 ) === false ) ? "\n" : "\r\n" ;
          
          unset( $this->bi_a );
	}
	
   function parse_ini_file($filename){
	     unset( $this->bi_a );
	 
		// Allocate the result array
		$res = array();
		// Does the file exists and can we read it?
		if(file_exists($filename) && is_readable($filename))
		{
			// In the beggining we are not in a section
			$section = "";
			// Open the file
			$fd = @fopen($filename,"r");
			
			// Read each line
			while(!feof($fd))
			{
				// Read the line and trim it
				$line = trim(@fgets($fd, 4096 ));
				
				$len = strlen($line);
				// Only process non-blank lines
				if($len != 0)
				{
					// Only process non-comment lines
					if($line[0] != ';')
					{
						// Found a section?
						if( ( $line[0] == '[') && ($line[$len-1] == ']' ) )
						{
							// Get section name
							$section = substr($line,1,$len-2);
							// Check if the section is already included in result array						
							if(!isset($res[$section]))
							{
								// If not included create it
								$res[ $section ] = array();
							}
						}
						// Check for entries
						$pos = strpos($line,'|'); //'original =
						// Found an entry
						if( $pos != false )
						{
							// get name of entry and [Joao Borges] delete any blank spaces (begin and end)
							$name = trim( substr( $line, 0, $pos ) );

							// get value of entry and [Joao Borges] delete blank spaces again
							$value = trim( substr( $line, $pos+1, $len - $pos - 1 ) );

              $value = stripslashes( $value );
              
              // follows some sort of inizialization for entries not including text
              if ( empty( $value ) ) $value = "" ;              
              // syntax must be strictly followed !
						  if ( strlen( $name ) > 0 )  {
											// Store entry if we are inside a section
								( strlen( $section ) > 0 )? $res[$section][$name] = $value:$res[$name] = $value;
						  }
						}
					}
				}				
			}
			// Close the file
			@fclose($fd);
		}
		return $res;
	}
      function show_content( $path )
      {
            $this->filePATH = $path ;
            $INIarray = $this->parse_ini_file( $path );
            
            $fileCONTENTS = "" ;
            
            $c1 = 0 ;
            if ( is_array( $INIarray ) )
            {
                foreach ( $INIarray as $i => $a )
                { 
                    $c2 = 0 ;
                    if ( is_array( $a ) )
                    {
                        foreach ( $a as $n => $value )
                        { 
                            if ( $c2 == 0 ) $fileCONTENTS .= "[".($i)."]<br/>".("$this->CRLF$n")."=".($value)."<br/>$this->CRLF";
                            else if ( strlen( $value ) != 0 ) $fileCONTENTS .= ($n)."=".($value)."<br/>$this->CRLF";
                            $c2++;
                        }
                        
                        $fileCONTENTS .= "<br/>$this->CRLF" ;
                    }
                }
                
                $fileCONTENTS = substr( $fileCONTENTS, 0, strlen( $fileCONTENTS ) - ( 5 + strlen( "<br/>" ) ) );
            }
            
            echo "<code>$fileCONTENTS</code>" ;
      }
      function save_content( $path )
      {
            $fileCONTENTS = "" ;
            
            $c1 = 0 ;
            
            if ( is_array( $this->bi_a ) )
            {
                foreach ( $this->bi_a as $i => $a)
                { 
                    $c2 = 0 ;
                    
                    if ( is_array( $a ) )
                    {
                        foreach ( $a as $n => $value )
                        { 
                            if ( $c2 == 0 ) $fileCONTENTS .= "[$i]$this->crlf$n|$value$this->crlf";
                            else if ( strlen( $value ) != 0 ) $fileCONTENTS .= "$n|$value$this->crlf";
                            $c2++;
                        }
                        
                        $fileCONTENTS .= $this->crlf ;
                    }
                }
                
                $hFile = @fopen( $path, "w+" );
                @fwrite( $hFile, $fileCONTENTS );
                @fclose( $hFile );
                
                unset( $this->bi_a );
            }
      }
      function get_content_array( $path )
      {
            $this->filePATH = $path ;
            $INIarray = $this->parse_ini_file( $path );

            return $INIarray ;
      }

      //////////////////////////////////////////////////////////
      function find_content( $path, $keyNAME, $entryNAME )
      {
            $this->filePATH = $path ;
            $INIarray = $this->parse_ini_file( $path );
            
            if ( is_array( $INIarray ) )
            {
                foreach ( $INIarray as $i => $a )
                {
                    if ( is_array( $a ) )
                    {
                        foreach ( $a as $n => $value )
                        { 
                            if ( strcmp( $i, $keyNAME ) == 0 && strcmp( $n, $entryNAME ) == 0 )
                            { 
                                return true ;
                            }
                        }
                    }
                }
            }

            return false ;
      }
      //////////////////////////////////////////////////////////
      function get_content( $path, $keyNAME, $entryNAME )
      {
            $this->filePATH = $path ;
            $INIarray = $this->parse_ini_file( $path );
      
            if ( is_array( $INIarray ) )
            {
                foreach ( $INIarray as $i => $a )
                { 
                    if ( is_array( $a ) )
                    {
                        foreach ( $a as $n => $value )
                        { 
                            if ( strcmp( $i, $keyNAME ) == 0 && strcmp( $n, $entryNAME ) == 0 )
                            { 
                                // [Ulrich Zdebel] Bugfix: doublequotes were not correctly managed
                                return (stripslashes( $value )) ;
                            }
                        }
                    }
                }
            }

            return "" ;
      }
      //////////////////////////////////////////////////////////
      function add_content( $path, $keyNAME, $entryNAME='', $entryVALUE='' )
      {
            if ( $this->find_content( $path, $keyNAME, $entryNAME ) )
            {
                $this->edit_content( $path, $keyNAME, $entryNAME, $entryVALUE ) ;
                return ;
            }
            
            $this->filePATH = $path ;
            $INIarray = $this->parse_ini_file( $path );
      
            $this->bi_a = array();
            
            $bKEYfound = false ;
            $bKEYadded = false ;
            
            if ( is_array( $INIarray ) )
            {
                foreach ($INIarray as $i => $a)
                { 
                    if ( is_array( $a ) )
                    {
                          foreach ($a as $n => $value)
                          { 
                              if ( strcmp( $i, $keyNAME ) == 0 ) $bKEYfound = true ;
                              
                              $this->bi_a[$i][$n] = $a[$n] ;
                          }
                      
                          if ( $bKEYfound )
                          { 
                              $this->bi_a[$i][$entryNAME] = $entryVALUE ;
                              $bKEYfound = false ;
                              $bKEYadded = true ;
                          }
                    }
                }
            }
            

          if ( !$bKEYadded ) $this->bi_a[$keyNAME][$entryNAME] = $entryVALUE ;

          $this->save_content( $path );
      }


      //////////////////////////////////////////////////////////
      function edit_content( $path, $keyNAME, $entryNAME, $entryVALUE )
      {
            $this->filePATH = $path ;
            $INIarray = $this->parse_ini_file( $path );
      
            $this->bi_a = array();

            if ( is_array( $INIarray ) )
            {
                foreach ($INIarray as $i => $a)
                { 
                    if ( is_array( $a ) )
                    {
                        foreach ($a as $n => $value)
                        { 
                            if ( strcmp( $i, $keyNAME ) == 0 && strcmp( $n, $entryNAME ) == 0 )
                            { 
                                $this->bi_a[$i][$n] = $entryVALUE ;
                            }
                            else $this->bi_a[$i][$n] = $a[$n] ;
                        }
                    }
                }
            }

          $this->save_content( $path );
      }

      //////////////////////////////////////////////////////////
      function del_content($keyNAME,$entryNAME,$path )
      {
            $this->filePATH = $path ;
            $INIarray = $this->parse_ini_file( $path );
      
            $this->bi_a = array();
            
            if ( is_array( $INIarray ) )
            {
                foreach ($INIarray as $i => $a)
                { 
                    if ( is_array( $a ) )
                    {
                        foreach ($a as $n => $value)
                        { 
                            if ( strcmp( $i, ($keyNAME) ) == 0 && 
							   strcmp( $n, ($entryNAME) ) == 0 )
                            { 
                                // don't do anything !
							} else $this->bi_a[$i][$n] = $a[$n] ;
							echo strcmp( $i, ($keyNAME) );//echo"akan di hapus";
                        }
                    }
                }
            }

          $this->save_content( $path );
      }
      //////////////////////////////////////////////////////////
      function del_field( $path, $keyNAME )
      {
            $fileCONTENTS = "" ;

            $this->filePATH = $path ;
            $INIarray = $this->parse_ini_file( $path );
           
            if ( is_array( $INIarray ) )
            {
                foreach ($INIarray as $i => $a)
                { 
                    $c2 = 0 ;
                    
                    if ( is_array( $a ) )
                    {
                        foreach ($a as $n => $value)
                        { 
                            if (strcmp( $i, $keyNAME ) != 0 ) 
                            {
                                if ( $c2 == 0 ) $fileCONTENTS .= "[$i]$this->crlf$n|$value$this->crlf";
                                else if ( strlen( $value ) != 0 ) $fileCONTENTS .= "$n|$value$this->crlf";
                                $c2++;
                            }    
                        }
                        
                        $fileCONTENTS .= $this->crlf ;
                    }
                }
            }
            
            $hFile = @fopen( $path, "w+" );
            @fwrite( $hFile, $fileCONTENTS );
            @fclose( $hFile );
     }

      //////////////////////////////////////////////////////////
      function del_all_field( $path )
      {
            $fileCONTENTS = "" ;

            $this->filePATH = $path ;
            $INIarray = $this->parse_ini_file( $path );
            
            if ( is_array( $INIarray ) )
            {
                foreach ($INIarray as $i => $a)
                { 
                    $c2 = 0 ;
                    
                    if ( is_array( $a ) )
                    {
                      foreach ($a as $n => $value)
                      { 
                          if ( $c2 == 0 ) $fileCONTENTS .= "[$i]$this->crlf";
                          $c2++ ;
                      }
                      
                      $fileCONTENTS .= $this->crlf ;
                    }
                }
            }
            
            $hFile = @fopen( $path, "w+" );
            @fwrite( $hFile, $fileCONTENTS );
            @fclose( $hFile );
     }
      //////////////////////////////////////////////////////////
	function Count($keyName,$path){
            $this->filePATH = $path ;
            $INIarray = $this->parse_ini_file( $path );
            
            if ( is_array( $INIarray ) )
            {
				$c2 = 0;
                foreach ($INIarray as $i => $a)
                { if ( is_array( $a ) )
                    {foreach ($a as $n => $value){ 
					if($i==($keyName)){$c2++;}}}	
           		}
			}
		return $c2;

	}
		//if (is_array($a)){$c2++;}		
      //////////////////////////////////////////////////////////
     function rContent($keyName,$entryName,$path)
	 {
		 $entry_val=$this->get_content($path,($keyName),($entryName));
		 return $entry_val;
	 }
	 /////////////////////////////////////////////////////////
	 function field( $keyNAME,$path ){
            $this->filePATH = $path ;
            $INIarray = $this->parse_ini_file( $path );
            
            $fileCONTENTS = "" ;
            
            $c1 = 0 ;
            if ( is_array( $INIarray ) )
            {
                foreach ( $INIarray as $i => $a )
                { 
                    $c2 = 0 ;
                    if ( is_array( $a ) )
                    {
                        foreach ( $a as $n => $value )
                        { 
                          if(($i)==$keyNAME){
						    if ( $c2 == 0 ) $fileCONTENTS .= "[".($i)."]\n".("$this->CRLF$n")."=".($value)."\n$this->CRLF";
                            else if ( strlen( $value ) != 0 ) $fileCONTENTS .= ($n)."=".($value)."$this->CRLF";
                            $c2++;
						  }
                        }
                        
                        $fileCONTENTS .= "$this->CRLF" ;
                    }
                }
                
                $fileCONTENTS = substr( $fileCONTENTS, 0, strlen( $fileCONTENTS ) - ( 5 + strlen( "<br/>" ) ) );
            }
            
            echo $fileCONTENTS;
	  }
      function show_field( $path )
      {
            $this->filePATH = $path ;
            $INIarray = $this->parse_ini_file( $path );
            
            $fileCONTENTS = "" ;
            
            $c1 = 0 ;
            if ( is_array( $INIarray ) )
            {
                foreach ( $INIarray as $i => $a )
                { 
                    $c2 = 0 ;
                    if ( is_array( $a ) )
                    {
                        foreach ( $a as $n => $value )
                        { 
                            if ( $c2 == 0 ) $fileCONTENTS .= ($i).","; 
                               $c2++;
                        }
                        
                        $fileCONTENTS .= "$this->CRLF" ;
                    }
                }
                
                $fileCONTENTS = substr( $fileCONTENTS, 0, strlen( $fileCONTENTS )-1/* - ( 5 + strlen( "<br/>" ) )*/ );
            }
            
            echo $fileCONTENTS ;
      }
	  
	function ReadSec($keyNAME,$path,$cari='S',$loop='',$select='',$separator=','){
	   $readSec='';
	   $data=explode($separator,$this->field($keyNAME,$path));
	   $nfile=$path;
	   if ($cari=='S'){
	   		//for ($i=0;$i<=count($data)-1;$i++){
				$xx=trim($data[$loop]);
				$readSec="<option value='$xx'";
				if($xx==$select){$readSec .=" selected";}
				$readSec .=">".$this->rContent($keyNAME,$xx,$nfile)."</option>\n";
		      // }
	   }else if ($cari=='L'){
	   		//for ($i=0;$i<=count($data)-1;$i++){
				$xx=trim($data[$loop]);
				if($xx==$select){$readSec .=" selected";}
				$readSec=$this->rContent($keyNAME,"$xx",$nfile)."\n";
		      // }
	   }else if($cari!='S'){
		   while($pos=current($data)){
			   if($pos==$cari){
				   $xx=$data[key($data)];
		   		$readSec=$this->rContent($keyNAME,"$xx",$nfile);
			   }
			   next($data);
		   }
	   }
	   return $readSec;
	}
	  
}//end class
