<?php
require 'flight/Flight.php';

Flight::route('/', function(){
    echo 'hello world!';
});

Flight::route('POST /example/@route', function($route, $params){
   //print_r($params);
   //$id = Flight::request()->data['a'];
   $id = Flight::response();
   print_r($id);
}, true);

Flight::start();
?>
