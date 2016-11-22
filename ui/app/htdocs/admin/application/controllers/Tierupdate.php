<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tierupdate extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function __contsruct()
    {
        parent::__construct();
        $this->load->module('Templates');
    }
	
	public function index()
	{
		$data = [];
		if(isset($_GET['message']))
			$data = ['message' => $_GET['message']];
		$this->template->load("template/index", false, "containers/tier_update", $data);
	}
	
	public function submit(){
		if((isset($_POST['team'])) && isset($_POST['Sport']) && isset($_POST['year']) && isset($_POST['cell'])){
			$curl = curl_init();
			unset($_POST['sport']);
			$_POST['zones'] = $_POST['cell'];
			unset($_POST['cell']);
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "http://100.2.150.31/api/v3/team/".$_POST['team']."/tiers",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => json_encode($_POST),
			  CURLOPT_HTTPHEADER => array(
				"cache-control: no-cache",
				"content-type: application/json",
				"token: login_2@0d942f0a5ee7616fb46796fe9e0bc496"
			  ),
			));
			
			$response = curl_exec($curl);
			$err = curl_error($curl);
			
			curl_close($curl);
			
			if ($err) {
			  echo "cURL Error #:" . $err;
			} else {
			  ($response) ? redirect($this->config->item("base_url")."/tierupdate?message=".urlencode($response)) : redirect($this->config->item("base_url")."/tierupdate?message=Successfully Uploaded");
			}
		}
	}
	
	public function link(){
		$this->template->load("template/index", false, "containers/tier_link", null);
	}
	
	public function update(){
		$this->template->load("template/index", false, "containers/tier_change", null);
	}
}
