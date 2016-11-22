<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//include_once('Packages.php');
class Dashboard extends MY_Controller {

 function __construct()
 {
   parent::__construct();
 }

 function index()
 {
   if($this->session->userdata('logged_in'))
   {
     $session_data = $this->session->userdata('logged_in');
     $data['username'] = $session_data['username'];
     $data['container']['page_name'] = "dashboard";
     //$packages = new Packages;
     //$data['container']['menu_packages'] = $packages->getPackages();
     $this->template->load('templates/logged_in', 'containers/dashboard', 'containers/account', $data);
   }
   else
   {
     //If no session, redirect to login page
     //print_r($_SESSION);
     redirect('login', 'refresh');
   }
 }

 function logout()
 {
   $this->session->unset_userdata('logged_in');
   session_destroy();
   redirect('home', 'refresh');
 }

}

?>