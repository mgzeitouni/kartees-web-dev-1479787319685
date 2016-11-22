<?php
include_once('DB.php');
class login{
        function __invoke($request, $response, $args){
                $data = $request->getParsedBody();
                $login = $this->login($data['user'], $data['pass']);
                if($login){
                   $newResponse = $this->respond($login, 200, $response);
                } else {
                   $error["Message"] = "Invalid Username or Password";
                   $error["status"] = 401;
                   $newResponse = $this->respond($error, 401, $response);

                }
                return $newResponse;
        }
        function respond($data, $status, $response){
                $newResponse = $response->withStatus($status);
                $newResponse->write(json_encode($data));
                return $newResponse;
        }
        function login($username = null, $password = null){
                global $database;
                date_default_timezone_set("America/New_York");
        
                if ($username && $password ){
                    if(!$database->has('login', array("user" => $username))){
                        return false;
                    }
                    else {
                        $hash = $database->get('login', 'pass', array("user" => $username));
                        $pass = crypt($password, $hash);
                        $finalresult = $database->has('login', ["AND" => ["user" => $username , "pass" => $pass]]);
                        if(!$finalresult){
                            return false;
                        } else {
                            $id = $database->get('login', 'ID', ["AND" => ["user" => $username, "pass" => $pass]]);
                            $database->delete('sessions', array("user_id" => $id));
                            $token = $id."@".md5(microtime());
                            if($database->insert('sessions', array("user_id" => $id, "session_hash" => $token, "date" => date('m|d|Y'))))
                                return array("user" => $username, "token" => $token);
                        }
                    }
                }
        }
        
}
