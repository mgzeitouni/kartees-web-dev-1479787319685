<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('calendar.php');
date_default_timezone_set('America/New_York');


class Packages extends MY_Controller {

 function __construct()
 {
   parent::__construct();
 }
 
 function getPackages(){
   $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "http://".$this->config->item('api_host')."/api/".$this->config->item('api_version')."/package/id",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "token: ".$this->config->item('token')
      ),
    ));
    
    $curl_response = curl_exec($curl);
    $teams = array();
    $curl_response = json_decode($curl_response, true);
    foreach($curl_response as $team){
     if(!in_array($team['Team'], $teams)){
      $teams[] = $team['Team'];
     }
    }
    $err = curl_error($curl);
    if ($err) {
      die( "cURL Error #:" . $err );
    }
    curl_close($curl);
    
    
    
    
    
    
    $curl2 = curl_init();

    curl_setopt_array($curl2, array(
      CURLOPT_URL => "http://".$this->config->item('api_host')."/api/".$this->config->item('api_version')."/teamNames/id",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode($teams),
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/json",
        "token: ".$this->config->item('token')
      ),
    ));
    //echo json_encode($teams);
    $teamNames = curl_exec($curl2);
    //echo $teamNames;
    $err = curl_error($curl2);
    
    curl_close($curl2);
    
    if ($err) {
      die( "cURL Error #:" . $err );
    }
    
    $teamNames = json_decode($teamNames, true);
       //print_r($teamNames); 

    foreach($curl_response as &$team){
      $team['Team_Name'] = $teamNames[$team['Team']];
    }
    
    
    
    
    //print_r($curl_response);
    return $curl_response;
 }

 function index()
 {
  $curl_response = $this->getPackages();
   $response['response'] = $curl_response;
   $response['container']['menu_packages'] = $curl_response;
    $response['container']['page_name'] = "packages";
   $response['container']['flash_message'] = $this->session->flashdata('message');
    
      if(count($response['response']) > 0){
        $this->template->load("templates/logged_in","containers/dashboard", "containers/packages", $response);
      } else {
        $this->noPackages();
      }
    
    
    
 }
 
 function noPackages(){
    
 }
 
 function delete(){
  $packageId = $this->input->post('Package_id');
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "http://".$this->config->item('api_host')."/api/".$this->config->item('api_version')."/package/".$packageId,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "DELETE",
    CURLOPT_HTTPHEADER => array(
      "cache-control: no-cache",
      "token: ".$this->config->item('token')
    ),
  ));
  
  $response = curl_exec($curl);
  $err = curl_error($curl);
  
  curl_close($curl);
  
  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    $this->session->set_flashdata('message', $response);
    redirect("/packages", "refresh");
  }
  
 }
 
 function report($id){
  
     
     $curl = curl_init();
     
     curl_setopt_array($curl, array(
       CURLOPT_URL => "http://".$this->config->item('api_host')."/api/".$this->config->item('api_version')."/salesreport/".$id,
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_ENCODING => "",
       CURLOPT_MAXREDIRS => 10,
       CURLOPT_TIMEOUT => 30,
       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
       CURLOPT_CUSTOMREQUEST => "GET",
       CURLOPT_HTTPHEADER => array(
         "cache-control: no-cache",
         "token: ".$this->config->item('token')
       ),
     ));
     
     $response = curl_exec($curl);
     $err = curl_error($curl);
     
     curl_close($curl);
     
     if ($err) {
       die("cURL Error #:" . $err);
     } else {
       $this->template->load("templates/logged_in", "containers/dashboard", "containers/report", ['data' => $response, 'container' => ['page_name' => "package_report"]]);
     }
 }

function id($id){
   $curl_response = $this->getPackages();

    $response['container']['menu_packages'] = $curl_response;

    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => "http://".$this->config->item('api_host')."/api/".$this->config->item('api_version')."/season/package/".$id,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "token: ".$this->config->item('token')
      ),
    ));
    
    $response['events'] = curl_exec($curl);
    $response['id'] = $id;
    $err = curl_error($curl);
    
    curl_close($curl);
        
    
    
    $response['calendar'] = draw_calendar($response['events']);
  
    $response['container']['page_name'] = "packages";
    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      if(count($response) > 0){
        $this->template->load("templates/logged_in", "containers/dashboard", "containers/tickets", $response);
      } else {
        $this->noPackages();
      }
    }
 }


 public function createPost(){
  $curl = curl_init();
  
  $data['Sport'] = $this->input->post('Sport');
  $data['Team'] = $this->input->post('Team');
  $data['Year'] = $this->input->post('Year');
  $data['Section'] = $this->input->post('Section');
  $data['Zone'] = $this->input->post('Zone');
  $data['Price'] = $this->input->post('Price');
  $data['Row'] = $this->input->post('Row');
  $data['Qty'] = $this->input->post('Qty');
  @$data['List_All'] = $this->input->post('List_All');
  $data['Seats'] = new ArrayObject(array());

  foreach($this->input->post('Seats') as $val){
    $data['Seats']->append($val);
  }
  
  if(isset($data['Sport']) && isset($data['Team']) && isset($data['Year']) && isset($data['Section']) && isset($data['Zone']) && isset($data['Price']) && isset($data['Row']) && isset($data['Qty']) && isset($data['Seats'])){
      curl_setopt_array($curl, array(
        CURLOPT_URL => "http://".$this->config->item('api_host')."/api/".$this->config->item('api_version')."/package",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data, JSON_FORCE_OBJECT),
        CURLOPT_HTTPHEADER => array(
          "cache-control: no-cache",
          "content-type: application/json",
          "token: ".$this->config->item('token')
        ),
      ));
      
      $response = curl_exec($curl);
      $err = curl_error($curl);
      
      
      if ($err) {
        echo "cURL Error #:" . $err;
      } else {
        if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 201){
         redirect("/packages", "refresh");
        } else {
         //echo json_encode($data);
         //echo ($response);
         header("location:javascript://history.go(-1)");
        }
      }
      
      
      curl_close($curl);
      
      
  } else {
     if(!isset($data['Sport']))
      $returnData = "Missing \"Sport\" <br>";
      
     if(!isset($data['Team']))
      $returnData = "Missing \"Team\" <br>";
      
     if(!isset($data['Year']))
      $returnData = "Missing \"Year\" <br>";
      
     if(!isset($data['Section']))
      $returnData = "Missing \"Section\" <br>";
      
     if(!isset($data['Zone']))
      $returnData = "Missing \"Zone\" <br>";
      
     if(!isset($data['Price']))
      $returnData = "Missing \"Price\" <br>";
      
     if(!isset($data['Row']))
      $returnData = "Missing \"Row\" <br>";
      
     if(!isset($data['Qty']))
      $returnData = "Missing \"Qty\" <br>";
     
     if(!isset($data['Seats']))
      $returnData = "Missing \"Seats\" <br>";
  }
  
 }
 public function create(){
  $this->template->load("templates/logged_in", "containers/dashboard", "containers/newPackages", ["container" => ["page_name" => "create_packages"]]);
 }
}

?>