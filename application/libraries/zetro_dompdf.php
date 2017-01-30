<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
	Class name 	: zetro_dompdf
	Purpose		: generate pdf from dompdf library
	Author		: Iswan Putera S.Kom
	Date		: 17-05-2013
*/
require_once('dompdf/dompdf_config.inc.php');

class Zetro_dompdf extends dompdf {

        function createPDF($html, $filename='zetro_pdf',$stream=TRUE, $orientation='portrait',$papersize='A4'){  
            $this->load_html($html);
			$this->set_paper($papersize, $orientation);
            $this->render();
/*            if ($stream) {
                $this->stream($filename.".pdf");
            } else {
                return $this->output($ot);
            }
*/
		file_put_contents(substr(LOCAL,0,(strlen(LOCAL)-1)).$filename.".pdf",$this->output());
		}
}

//end of zetro_dompdf
?>