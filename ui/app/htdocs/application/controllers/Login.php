<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Controller {

 function __construct()
 {
   
 }

 function index()
 {
  parent::__construct(true);
   $this->load->helper(array('form'));
   $this->template->load('templates/logged_out', false, 'login_view', ['page_name' => "login"]);
 }
 
 function logout()
 {
  parent::__construct();
   $this->session->unset_userdata('logged_in');
   session_destroy();
   redirect('login', 'refresh');
 }

}

?>