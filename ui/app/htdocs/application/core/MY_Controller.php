<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Controller extends CI_Controller {

 function __construct($login_page = false)
 {
   parent::__construct();

   if($this->session->userdata('logged_in'))
   {
       if($this->session->userdata('logged_in') && $login_page){
        redirect('dashboard', 'refresh');
       }
       else if(!$login_page){
        $session_data = $this->session->userdata('logged_in');
        $token = $this->user->getToken();
        if(isset($token))
         $this->config->set_item('token', ($token) ? $token : "");
        else 
         redirect('login', 'refresh');
       }
     
   }
   else
   {
     //If no session, redirect to login page
     //print_r($_SESSION);
     if(!$login_page)
      redirect('login', 'refresh');
     else
      return;
   }
 }



}

?>