<?php
ini_set('display_errors', '1');
require("src/Instagram.php");
require("src/InstagramException.php");
require("db.php");

$id = $_GET['id'];
$db = new db();
$info = $db->get($id);
$code =  $info['code'];
echo $code."<br>";

$instagram = new Instagram();
$instagram->setAccessToken($code);
    // get all user likes
    $followers = $instagram->getUserFollower();
    //$likes = $instagram->modifyRelationship('follow', 1574083);
    $likes = $instagram->searchUser("groupon");
    // take a look at the API response
    echo '<pre>';
    print_r($likes);
    echo '<pre>';