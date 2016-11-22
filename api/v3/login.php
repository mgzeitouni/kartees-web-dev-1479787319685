<?php
require_once("DB.php");
class login{
        function __invoke($request, $response, $args){
                $data = $request->getParsedBody();
                $login = $this->loginUser($data['user'], $data['pass']);
                if(!is_array($login)){
                   $error["Message"] = "Invalid Username or Password";
                   $error["status"] = 401;
                   $response->write($login);
                   $newResponse = $this->respond($error, 401, $response);
                   
                } else {
                   $newResponse = $this->respond($login, 201, $response);

                }
                return $newResponse;
        }
        
        function respond($data, $status, $response){
                $newResponse = $response->withStatus($status);
                $newResponse->write(json_encode($data));
                return $newResponse;
        }
        
        function loginUser($username = null, $password = null){
                global $database;
                date_default_timezone_set("America/New_York");
        
                if ($username && $password ){
                        $finalresult = $database->get('login', '_id', ["api_user" => $username , "api_pass" => $password]);
                        //print_r($finalresult);
                        if(count($finalresult) == 0){
                            return false;
                        } else {
                            $id = $finalresult[0]['_id'];
                            //$database->delete('sessions', array("user_id" => $id));
                            $token = $id."@".md5(microtime());
                            //echo count_chars($id);
                            if(isset($id) && ($id != "")){
                               if($database->insert('sessions', array("user_id" => $id, "session_hash" => $token, "date" => date('m|d|Y'))))
                               return array("user" => $username, "token" => $token); 
                            }
                        }
                }
        
        }
        
}
