<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class import extends CI_Controller {
    
function __construct()
    {
	}
	public function index(){
            $curl = curl_init();
		

            curl_setopt_array($curl, array(
              CURLOPT_URL => "http://stubhub-python-dev.mybluemix.net/get-team-games?teamName=New%20York%20Mets",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "GET",
              CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json",
                "token: {{Login_token}}"
              ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
				
				//print_r($response);
            curl_close($curl);

            if ($err) {
              echo "cURL Error #:" . $err;
            } else {
              echo $response;
            }
	}
}