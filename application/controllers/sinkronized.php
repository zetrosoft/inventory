<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class sinkronized extends CI_Controller {
  public $tc; public $zn;public $zc;public $zm;
    function  __construct() {
        parent::__construct();
        $this->load->model('Admin_model');
        $this->load->model('report_model');
		$this->load->library('zetro_auth');
		$this->zc='asset/bin/zetro_config.dll';
	}
    function CheckOpenDO()
    {
        $data=array();
        $lokasi=@$_POST['l'];
        $data=( $this->report_model->CekDO($lokasi));
        header('Content-Type: application/json');
        echo json_encode($data );
    }
    function simpando()
    {
        $datas=array();
        $datas=json_decode(@$_POST['data'],true);
        $data=array();
        
                $data["notrans"]=$datas['notrans'];
                $data["id"]=$datas['id'];
                $data["id_barang"]=$datas['id_barang'];
                $data["id_satuan"]=$datas['id_satuan'];
                $data["id_jual"]=$datas['id_jual'];
                $data["jumlah"]=$datas['jumlah'];
                $data["nm_pelanggan"]=$datas['nm_pelanggan'];
                $data["created_by"]=$this->session->userdata('userid');
                $data["lokasi"]=$datas['lokasi'];
                $data["harga"]='0';
                if($datas["id_jual"]=="0")
                {
                    echo "0";
                }else{
                    if($this->Admin_model->replace_data("inv_penjualan_do",$data))
                    {
                       echo $datas["id"];
                    } else {
                        echo "0";
                    }
                }
    }
    function CheckDONotPrint()
    {
        $data='';
        $lokasi=@$_POST['l'];
        $data=( $this->report_model->CheckDOForPrint($lokasi));
        echo $data;
    }
    //update do siap di print
    function updatedoPrint()
    {
        $id=@$_POST['id'];
        $this->Admin_model->upd_data("inv_penjualan_do","set harga='1'","where notrans in('".trim($id)."')");
        echo $id; 
    }
    //updare status do dari server lain sudah dikirim
    function updatedo()
    {
        $id=@$_POST['id'];
        echo $this->Admin_model->upd_data("inv_penjualan_do","set status_kirim='Y', printed='Y'","where id='$id'");
        
    }
    //update stock setleah do di proses
	function updatestock()
	{
		$id=@$_POST['id'];
		$jml=rdb("inv_penjualan_do","Jumlah","Jumlah","where ID='".$id."'");
        $idBarang=rdb("inv_penjualan_do","id_barang","id_barang","where ID='".$id."'");
        $notrans=rdb("inv_penjualan_do","notrans","notrans","where ID='".$id."'");
        $stok=rdb("inv_material_stok","stock","stock","where id_barang='".$idBarang."'");
        $stok=($stok-$jml);
        $lokasi=$this->session->userdata('server');
        echo $stok;
        echo $this->Admin_model->upd_data("inv_material_stok","set stock='$stok',dariFlow='update_material_stock_do:$notrans'",
                                     "where id_barang='".$idBarang."'");
	}
    /**
    * Update inventory baru
    */
    function checkinv()
    {
        
        
    }
    
    //check apakah ada penerimaan baru untuk gudang sesuai lokasi
    function CheklBelanja()
    {
        $data=array();
        $lokasi=@$_POST['l'];
        $data=$this->report_model->CekBeli($lokasi);
        header('Content-Type: application/json');
        echo json_encode($data );
    }
    
    //update stock jika ada pernerimaan baru
    function UpdateInventory()
    {
        $datas=array();
        $datas=json_decode(@$_POST['data'],true);
        $id=$datas['id'];
        $id_barang=$datas['id_barang'];
        $jumlah=$datas["Jumlah"];
        $harga=$datas["Harga_Beli"];
        $satuan=$datas["ID_Satuan"];
        $lokasi=$this->session->userdata('server');
        $stok=rdb("inv_material_stok","stock","stock","where id_barang='".$id_barang."'");
        $stok=($stok+$jumlah);
        //update stok
        $this->Admin_model->upd_data("inv_material_stok",
                                     "set stock='$stok',harga_beli='$harga',dariFlow='pembelian-$id',nm_satuan='$satuan'",
                                     "where id_barang='".$id_barang."'");
        echo $id;
    }
    //tandai transaksi pembelian jika sudah di copy stock nya
    function posting()
    {
        $id=@$_POST['id'];
        echo $this->Admin_model->upd_data("inv_pembelian_detail","set posting=1","where id=$id");
    }
    
    //check jika ada perubahan harga di server pusat
    //juga jika ada material baru di server pusat
    function CheklHargaJual()
    {
        $data=array();
        $data=$this->report_model->CheckHarga();
        header('Content-Type: application/json');
        echo json_encode($data );
    }
    
    //update harga atau create material baru
    function UpdateHarga()
    {
        $datas=array();$data=array();
        $datax=@$_POST['data'];
        $datax=(substr($datax,(strlen($datax)-2),2)=="}}")?substr($datax,0,(strlen($datax)-1)):$datax;
        //echo $datax;
        $datas=json_decode($datax,true);
        $id=$datas['ID'];
        $id_barang=$datas['ID'];
        $harga_jual=$datas["Harga_Jual"];
        $harga_partai=$datas["Harga_Partai"];
        $harga_cabang=$datas["Harga_Cabang"];
        $posting="1";
         $cek=rdb("inv_barang","id","id","where ID='".$id."'");
        if($cek=='')
        {
            $data['ID']			=$datas["ID"];
            $data['ID_Jenis']	  =$datas["ID_Jenis"];
            $data['Kode']		  =$datas["Kode"];
            $data['ID_Pemasok'] =$datas["ID_Pemasok"];
            $data['ID_Kategori']   =$datas["ID_Kategori"];
            $data['Nama_Barang']   =$datas["Nama_Barang"];
            $data['ID_Satuan']	 =$datas["ID_Satuan"];
            $data['Status']		=$datas["Status"];
            $data['Harga_Beli']	=$datas["Harga_Beli"];
            $data['Harga_Jual']	=$datas["Harga_Jual"];
            $data['Harga_Partai']  =$datas["Harga_Partai"];
            $data['Harga_Cabang']  =$datas["Harga_Cabang"];
            $data['minstok']	   =$datas["minstok"];
            $data["posting"]="1";
		    $this->Admin_model->replace_data('inv_barang',$data);
        }
        //update stok
        $this->Admin_model->upd_data("inv_barang",
                                     "set harga_jual='$harga_jual',harga_partai='$harga_partai',
                                     harga_cabang=$harga_cabang",
                                     "where ID='".$id."'");
       
        echo $id;
    }
    
    //tandai data material klo sudah terupdate
    function PostingStatus()
    {
        $id=@$_POST['id'];
        echo $this->Admin_model->upd_data("inv_barang","set posting=1","where id=$id");
    }
    //check stocks
    function GetStock()
    {
        $id_barang=@$_POST['id_barang'];
        $data=array();
        $data=$this->report_model->CheckStock($id_barang);
        header('Content-Type: application/json');
        echo json_encode($data );
    }
}
?>