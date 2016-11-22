<?php
require_once("DB.php");
class TokenAuth{
 
    public function __construct() {
        //Define the urls that you want to exclude from Authentication, aka public urls     
        $this->whiteList = array('\/login', '\/user\/signup', '\/project\/([a-z0-9]+)\/thumb\/([a-z0-9]+).([a-z]+)\/(.*)');
    }
 
    /**
     * Deny Access
     *
     */
    public function deny_access($response) {
        $res = $response;
        $res->withStatus(401);
        $res->write(json_encode(array("Error Code" => "403", "Message" => "Access Denied!")));
        return $res;
    }
 
    /**
     * Check against the DB if the token is valid
     * 
     * @param string $token
     * @return bool
     */
    public function authenticate($token) {
        global $database, $user_Id;
        if(is_array($token))
            $token = $token[0];
        if($response = $database->get('sessions', "user_id", ["session_hash" => $token])){
            $user_Id = $response[0]['user_id'];
            return true;
        } else
            return false;
    }
 
    /**
     * This function will compare the provided url against the whitelist and
     * return wether the $url is public or not
     * 
     * @param string $url
     * @return bool
     */
    public function isPublicUrl($url) {
        $patterns_flattened = implode('|', $this->whiteList);
        $matches = null;
        preg_match('/' . $patterns_flattened . '/', $url, $matches);
        return (count($matches) > 0);
    }
 
    /**
     * Call
     * 
     * @todo beautify this method ASAP!
     *
     */
    public function __invoke($request, $response, $next) {
        //Get the token sent from jquery
        global $database;
        $tokenAuth = ($request->getHeader('Token') != null) ? $request->getHeader('Token') : null;
        //We can check if the url requested is public or protected
        if ($this->isPublicUrl($request->getUri())) {
            //if public, then we just call the next middleware and continue execution normally
            return $next($request, $response);
        } else {
            //If protected url, we check if our token is valid
            $id = $this->authenticate($tokenAuth);
            if ($id) {
                //Get the user and make it available for the controller
                //$this->app->auth_user = $database->get('sessions', 'user_id', array('session_hash' => $tokenAuth));
                return $next($request, $response);
            } else {
                return $this->deny_access($response);
            }
        }
    }
 
}