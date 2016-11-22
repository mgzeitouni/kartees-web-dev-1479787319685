<?php
ini_set('display_errors', '1');
require("src/Instagram.php");
require("db.php");


define("CLIENT_ID", "3d150488d6e44fdb8d31ccc76856f46a");
define("CLIENT_SECRET", "ef72e2f8cb2e4240a1755b2b948d1138");

$account = new Instagram();
echo "<form method=\"GET\" action=\"https://api.instagram.com/oauth/authorize/\">".

    "<input type=\"hidden\" name=\"client_id\" value=\"".$account->getApiKey()."\">".
    "<input type=\"hidden\" name=\"redirect_uri\" value=\"".$account->getApiCallback()."\">".
    "<input type=\"hidden\" name=\"scope\" value=\"basic relationships likes comments follower_list\">".
    "<input type=\"hidden\" name=\"response_type\" value=\"code\">".
    "<input type=\"submit\" value=\"LOGIN TO INSTAGRAM\"></form>";
    
$db = new db();
$all = $db->getALL();

foreach($all as $id => $values){
    echo "<a href=\"show.php?id=".$id."\">".$id." - ".$values['instaId']."</a><br>";
}