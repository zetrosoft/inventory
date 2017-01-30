<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
	controller laporan kas
*/
class Laptransaksi extends CI_Controller{
	
	 function __construct()
	  {
		parent::__construct();
		$this->load->model("report_model");
		$this->load->model("kasir_model");
		$this->load->helper("print_report");
		$this->load->library("zetro_auth");
		$this->userid=$this->session->userdata('idlevel');
	  }
	
	function Header(){
		$this->load->view('admin/header');	
	}
	
	function Footer(){
		$this->load->view('admin/footer');	
	}
	function list_data($data){
		$this->data=$data;
	}
	function View($view){
		$this->Header();
		//$this->zetro_auth->view($view);
		$this->load->view($view,$this->data);	
		$this->Footer();
	}
	
	function cash_flow(){
		$this->zetro_auth->menu_id(array('alirankas'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('akuntansi/kas/cash_flow');
	}
	function laba_rugi(){
		$this->zetro_auth->menu_id(array('labarugi'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('akuntansi/kas/laba_rugi');
	}
	function kas_masuk(){
		$this->zetro_auth->menu_id(array('laporankasmasuk'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('akuntansi/kas/kas_masuk');
	}
	function ops_harian(){
		$this->zetro_auth->menu_id(array('operasionalharian'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('akuntansi/kas/operasional');
	}
	
	function get_cash_flow(){
		$data=array();
		$where="where p.Tanggal='".$this->input->post('dari_tgl')."'";
		$where=($this->input->post('sampai_tgl')=='')? $where:
			   "where p.Tanggal between '".$this->input->post('dari_tgl')."' and '".$this->input->post('sampai_tgl')."'"; 
 		$where.=($this->input->post('id_lokasi')=='')?'':"and p.ID_Close='".$this->input->post('id_lokasi')."'";
		
		$data['lokasi'] =($this->input->post('id_lokasi')=='')?'All':rdb('user_lokasi','lokasi','lokasi',"where ID='".$this->input->post('id_lokasi')."'"); 
 		$data['lokasine']=($this->input->post('id_lokasi')=='')?'':"and t.ID_Unit='".$this->input->post('id_lokasi')."'";
		$data['where']	=$where;
		$data['area']	=($this->input->post('id_lokasi')=='')?'':$this->input->post('id_lokasi');
		$data['dari']	=$this->input->post('dari_tgl');
		$data['sampai']	=$this->input->post('sampai_tgl');	
		$data['temp_rec']=$this->report_model->lap_cash_flow();
		
		$this->zetro_auth->menu_id(array('trans_beli'));
		$this->list_data($data);
		$this->View("akuntansi/kas/cash_flow_print");
	}
	
	function get_laba_rugi(){
		$data=array();
		$where=" and Tanggal='".tglToSql($this->input->post('dari_tgl'))."'";
		$where=($this->input->post('sampai_tgl')=='')? $where:
			   " and Tanggal between '".tglToSql($this->input->post('dari_tgl'))."' and '".tglToSql($this->input->post('sampai_tgl'))."'"; 
		//$where.="and p.ID_Jenis!='5'";
 		$where.=($this->input->post('id_lokasi')=='')?'':"and p.ID_Close='".$this->input->post('id_lokasi')."'";
		$jumlah=$this->kasir_model->totaldata($where);
		$data['jumlah']=($jumlah>4)? round(($jumlah/4),0):$jumlah;
		$orderby=" group by Tanggal order by Tanggal";
 		$data['lokasi']=($this->input->post('id_lokasi')=='')?'All':rdb('user_lokasi','lokasi','lokasi',"where ID='".$this->input->post('id_lokasi')."'");
 		$data['lokasine']=($this->input->post('id_lokasi')=='')?'':"and id_lokasi='".$this->input->post('id_lokasi')."'";
		$data['pajak']	=$this->input->post('pajak');
 		$data['dari']	=$this->input->post('dari_tgl');
		$data['sampai']	=$this->input->post('sampai_tgl');	
		$data['temp_rec']=$this->kasir_model->laba_rugi($where,$orderby);
		$this->zetro_auth->menu_id(array('trans_beli'));
		$this->list_data($data);
		$this->View("laporan/transaksi/lap_laba_rugi_print");
		
	}
	
	function get_kas_masuk(){
		$data=array();
		$where="where p.Tanggal='".tglToSql($this->input->post('dari_tgl'))."'";
		$where=($this->input->post('sampai_tgl')=='')? $where:
			   "where p.Tanggal between '".tglToSql($this->input->post('dari_tgl'))."' and '".tglToSql($this->input->post('sampai_tgl'))."'"; 
		$where.=($this->input->post('id_jenis')=='')?'':" and ID_Jenis='".$this->input->post('id_jenis')."'";	   
 		$where.=($this->input->post('id_lokasi')=='')?'':"and p.ID_lokasi='".$this->input->post('id_lokasi')."'";
		$jumlah=$this->kasir_model->totaldatax(str_replace('where',' and ',$where));
		$jumlah=($jumlah>4)? round(($jumlah/4),0):$jumlah;
		$group =" group by concat(p.ID_Jenis,dt.Tanggal)";
		$orderby=($this->input->post('pajak')=='ok')?"order by rand() limit $jumlah":"order by p.Tanggal";
 		$data['dari']	=$this->input->post('dari_tgl');
		$data['sampai']	=$this->input->post('sampai_tgl');	
		$data['id_jenis']=($this->input->post('id_jenis'))?'Semua':rdb('inv_penjualan_jenis','Jenis_Jual','Jenis_Jual',"where ID='".$this->input->post('id_jenis')."'");
		$data['temp_rec']=$this->kasir_model->kas_masuk($where,$orderby);
		$this->zetro_auth->menu_id(array('trans_beli'));
		$this->list_data($data);
		$this->View("laporan/transaksi/lap_kas_print");
	}
	
	function get_operasional(){
		$data=array();
		$where="where tgl_kas='".tglToSql($this->input->post('dari_tgl'))."'";
		$where=($this->input->post('sampai_tgl')=='')? $where:
			   "where tgl_kas between '".tglToSql($this->input->post('dari_tgl'))."' and '".tglToSql($this->input->post('sampai_tgl'))."'"; 
		$where.=($this->input->post('id_lok')=='')?'':" and id_lokasi='".$_POST['id_lok']."'";
		$orderby="order by tgl_trans";
		//$data['where']	=str_replace('tgl_kas','tgl_trans',$where)." and jumlah!='0'";
		$data['lokasi']		=($this->input->post('id_lok')=='')?'All':rdb('user_lokasi','Lokasi','Lokasi',"where ID='".$this->input->post('id_lok')."'");
		$data['orderby']=$orderby;
 		$data['dari']	=$this->input->post('dari_tgl');
		$data['sampai']	=$this->input->post('sampai_tgl');	
		$data['temp_rec']=$this->kasir_model->get_kas_awal($where);
		$this->zetro_auth->menu_id(array('trans_beli'));
		$this->list_data($data);
		$this->View("laporan/transaksi/lap_operasional_print");
	}
    function margin()
    {
        $this->zetro_auth->menu_id(array('marginpenjualan'));
		$this->list_data($this->zetro_auth->auth());
		$this->View('laporan/penjualan_margin');
    }
    function get_margin_jual(){
        $data=array();$n=0;$t_jual=0;$t_beli=0;$t_margin=0;$corting=0;
        $dTgl=empty($_POST['frm_tgl'])?date('Ymd'):tglToSql($_POST['frm_tgl']);
        $tTgl=empty($_POST['to_tgl'])?$dTgl:tglToSql($_POST['to_tgl']);
        $where=" and Tanggal between '".$dTgl."' and '".$tTgl."'";
        $period="and doc_date between '".$dTgl."' and '".$tTgl."'";
        $orderby=empty($_POST['kat'])?"":" and Kat='".$_POST['kat']."' ";
        $orderby.=empty($_POST['cari'])?" order by ".$_POST['orderby']:" and NamaBarang like '%".$_POST['cari']."%' order by ". $_POST['orderby'];
        $data=$this->kasir_model->GetMatginJual($where,$orderby);
        foreach($data as $r)
        {
            $n++;
            echo tr().td($n,'center').
                 td(TglFromSql($r->Tanggal),'center').
                 td($r->NamaBarang).
                 td($r->Satuan).
                 td(number_format($r->Jumlah,2),'right').
                 td(number_format($r->Harga,2),'right').
                 td(number_format($r->Modal,2),'right').
                 td(number_format($r->Jual,2),'right').
                 td(number_format($r->Beli,2),'right').
                 td(number_format($r->Margin,2),'right').
                 td(($r->Beli>0)?number_format(($r->Margin/$r->Beli)*100,2):number_format(($r->Margin/$r->Jual)*100,2),'right').
                _tr();
            $t_jual+=$r->Jual;
            $t_beli+=$r->Beli;
            
        }
        //$nota=rdb()
        $corting=rdb('inv_pembayaran','ppn','sum(ppn) as ppn',"where ID_Jenis='1' $period");
        if($corting>0)
        {
        echo tr('list_genap').td('<b>Corting Penjualan</b> &nbsp;','right\' colspan=\'7').
             td(number_format($corting,2),'right').
             td('','right').
             td('','right').
             td('','right').
            _tr();
        }
        echo tr('list_genap').td('<b>Total</b> &nbsp;','right\' colspan=\'7').
             td(number_format(($t_jual-$corting),2),'right').
             td(number_format($t_beli,2),'right').
             td(number_format((($t_jual-$corting)-$t_beli),2),'right').
             td(($t_jual>0)?number_format((($t_jual-$corting)-$t_beli)/$t_beli*100,2):'0','right').
            _tr();
        dataNotFound($data,12);
    }
    function cleanData(&$str) { 
	$str = preg_replace("/\t/", "\\t", $str); 
	$str = preg_replace("/\r?\n/", "\\n", $str); 
	if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
	} 
    function exportToExcel(){
	// filename for download $filename = "website_data_" . date('Ymd') . ".xls"; 
	header("Content-Disposition: attachment; filename=RekapMarginPenjualan.xls"); 
	header("Content-Type: application/vnd.ms-excel"); 
	$flag = false; 
        $data=array();$n=0;$t_jual=0;$t_beli=0;$t_margin=0;
        $dTgl=empty($_POST['frm_tgl'])?date('Ymd'):tglToSql($_POST['frm_tgl']);
        $tTgl=empty($_POST['to_tgl'])?$dTgl:tglToSql($_POST['to_tgl']);
        $where=" and Tanggal between '".$dTgl."' and '".$tTgl."'";
        $orderby=empty($_POST['kat'])?"":" and Kat='".$_POST['kat']."' ";
        $orderby.=empty($_POST['cari'])?" order by ".$_POST['orderby']:" and NamaBarang like '%".$_POST['cari']."%' order by ". $_POST['orderby'];
        $data=$this->kasir_model->GetMatginJual($where,$orderby);
	//$result = pg_query("SELECT * FROM table ORDER BY field") or die('Query failed!'); 
    //    while(false !== ($row = pg_fetch_assoc($result)))
        foreach($data as $row)
        { 
            if(!$flag) { 
            // display field/column names as first row 
            echo implode("\t", array_keys($row)) . "\r\n"; $flag = true; 
            } 
            array_walk($row, 'cleanData'); 
            echo implode("\t", array_values($row)) . "\r\n"; 
        } 
	exit;
    }
}