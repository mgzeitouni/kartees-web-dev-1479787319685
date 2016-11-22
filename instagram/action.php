<?php
ini_set('display_errors', '1');
require("db.php");
require("src/Instagram.php");

$instagram = new Instagram();
// grab OAuth callback code
$code = $_GET['code'];
$data = json_decode(json_encode($instagram->getOAuthToken($code)), true);
//$data = $instagram->getUser();
echo "aa<pre>";
print_r($data);

if(!isset($data['error_type'])){
    $db = new db();
    //print_r($instagram->getUser());
    $db->add($data['user']['username'], $data['user']['id'], $data['access_token']);
    
    //echo 'Your username is: ' . $data->user->username;
}
?>