<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';
//require APPPATH.'/model/report_model.php';
class Zetroapi extends REST_Controller
{
    /*function  __construct() {
        parent::__construct();
        $this->load->model('Admin_model');
        $this->load->model('report_model.php');
		$this->load->library('zetro_auth');
		//$this->zc='asset/bin/zetro_config.dll';
		//$this->zm='asset/bin/zetro_menu.dll';
	}
    function index()
    {
        
    }*/
    function user_get()
    {
        if(!$this->get('id'))
        {
        	$this->response(NULL, 400);
        }

        // $user = $this->some_model->getSomething( $this->get('id') );
    	$users = array(
			1 => array('id' => 1, 'name' => 'Some Guy', 'email' => 'example1@example.com', 'fact' => 'Loves swimming'),
			2 => array('id' => 2, 'name' => 'Person Face', 'email' => 'example2@example.com', 'fact' => 'Has a huge face'),
			3 => array('id' => 3, 'name' => 'Scotty', 'email' => 'example3@example.com', 'fact' => 'Is a Scott!'),
		);
		
    	$user = @$users[$this->get('id')];
    	
        if($user)
        {
            $this->response($user, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'User could not be found'), 404);
        }
    }
    /*function zAuth()
    {
        $login_data = $this->Admin_model->cek_user_login('superuser','sepasi2x');
				if ($login_data->num_rows()==1){
					foreach($login_data->result_array() as $lgn){
						$session_data = array(
							'userid' 	=> $lgn['userid'],
							'username' 	=> $lgn['username'],
							'idlevel'	=> $lgn['idlevel'],
							'login'		=> TRUE,
							'version'	=>(addCopy()!=no_ser())?'Demo Version':addCopy(),
							'gudang'	=> $lgn['LokasiToko']
						);
                        $datax=array();
                        $datax['UserID']=$lgn['userid'];
                        $datax['Lokasi']=$lgn['LokasiToko'];
                        $datax['ipaddress']=$_SERVER['REMOTE_ADDR'];
                        $this->Admin_model->replace_data('user_logs',$datax);
					}
        $this->session->set_userdata($session_data);
        $this->index();
    }*/
}

?>