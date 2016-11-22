<?php 
// If you installed via composer, just use this code to requrie autoloader on the top of your projects.
require_once 'vendor/autoload.php';
 
// Or if you just download the medoo.php into directory, and require it with the correct path.
require_once 'vendor/catfan/medoo/medoo.php';
 
// Initialize
$database = new medoo([
    'database_type' => 'mysql',
    'database_name' => 'ad_52063f30b709ce5',
    'server' => 'us-cdbr-iron-east-03.cleardb.net',
    'username' => 'b3c48395bc1a72',
    'password' => 'facf79d9',
    'charset' => 'utf8'
]);
 