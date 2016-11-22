<?php
Class User extends CI_Model
{
 
 public function __construct()
    {
        parent::__construct();
    }
    
public function getToken(){
   $session_data = $this->session->userdata('logged_in');
   $this->db->where("session_id", $session_data['session_hash']);
   $q = ($this->db->get("sessions"));
   return $q->result_array()[0]['api_token']; 
}
 
 
 function login($username, $password)
 {     
     $curl = curl_init();
     
     $query = array("user" => $username, "pass" => $password);
     $query = json_encode($query);
     //echo "https://".$this->config->item('api_host')."/api/".$this->config->item('api_version')."/login<br>";
     curl_setopt_array($curl, array(
       CURLOPT_URL => "http://".$this->config->item('api_host')."/api/".$this->config->item('api_version')."/login",
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_ENCODING => "",
       CURLOPT_MAXREDIRS => 10,
       CURLOPT_TIMEOUT => 30,
       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
       CURLOPT_CUSTOMREQUEST => "POST",
       CURLOPT_POSTFIELDS => $query,
       CURLOPT_HTTPHEADER => array(
         "cache-control: no-cache",
         "content-type: application/json"
       ),
     ));
     
     $response = curl_exec($curl);
     $err = curl_error($curl);
     
     curl_close($curl);
     $vars = json_decode($response, true);

     if ($err) {
       exit("cURL Error #:" . $err);
       return false;
     } else if(isset($vars['message'])){
       return false;
     } else if(isset($vars['token']) && isset($vars['user'])) {
        $data = $this->processLogin($vars['token'], $vars['user']);   
        //exit( $username);
        return $data;
     }
 }
 
 private function processLogin($token, $user){
    
  
  
  
  
    $session_id = uniqid("",true);

    $data = array(
                  'user_id' => $user,
                  'api_token' => $token,
                  'session_id' => $session_id,
                  'expire_date' => (time() + (7 * 24 * 60 * 60))
                 );
    $this->db->insert('sessions', $data);
    
    $session_data = array(
     "username" => $user,
     "session_hash" => $session_id,
     "expire" =>(time() + (7 * 24 * 60 * 60))
    );
    $this->session->set_userdata('logged_in', $session_data);
    
    return $data;
 }
}
?>