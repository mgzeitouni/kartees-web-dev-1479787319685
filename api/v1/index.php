<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/**
 * Step 1: Require the Slim Framework using Composer's autoloader
 *
 * If you are not using Composer, you need to load Slim Framework with your own
 * PSR-4 autoloader.
 */
require_once 'vendor/autoload.php';
require_once('Middleware/TokenAuth.php');
require_once('login.php');

/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */


include("DB.php");
$app = new Slim\App();
$app->add(new \TokenAuth());


/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`
 * is an anonymous function.
*/
/**
=========================================
* Login
=========================================
*/

$app->post('/login', '\login');



/**
=========================================
* Package Table
=========================================
*/

$app->get('/package/id[/{id}]', function($request, $response, $args){
   global $database;
   $results = ($database->select('package', "*", ["Package_Id" => $args['id']]));
   //$response->write($database->select("package", "*",  ["Package_Id" => $id]));
   $response->write(json_encode($results));
});

$app->get('/package/active[/{id}]', function($request, $response, $args){
   global $database;
   $results = ($database->select('package', "*", ["Active" => $args['id']]));
   //$response->write($database->select("package", "*",  ["Package_Id" => $id]));
   $response->write(json_encode($results));
});

$app->get('/package/user/{user}', function($request, $response, $args){
   global $database;
   $results = ($database->select('package', "*", ["User_Id" => $args['user']]));
   //$response->write($database->select("package", "*",  ["Package_Id" => $id]));
   $response->write(json_encode($results));
});


/**
=========================================
* Listing Table
=========================================
*/
$app->get('/listings/user/{user}', function($request, $response, $args){
   global $database;
   $packages = ($database->select('package', "Package_Id", ["User_Id" => $args['user']]));
   foreach($packages as $package){
      $results[] = ($database->select('listing', "*", ["Package_Id" => $package]));
   }
   $response->write(json_encode($results));
});

$app->post('/listing/{listing_id}/setactive/{active}', function($request, $response, $args){
   global $database;
   $newstate = $args['active'];
   if(!$database->has('listing', array("Listing_Id" => $args['listing_id'])))
      die("Listing with ID ".$args['listing_id']." does not exist");
   $database->update('listing', array("Active" => $newstate), ["Listing_Id" => $args['listing_id']]);
   print_r(json_encode($database->select('listing', "*", ["Listing_Id" => $args['listing_id']])));
});

$app->post('/listing/update/{listing_id}', function($request, $response, $args){
   global $database;
   if(!$database->has('listing', array("Listing_Id" => $args['listing_id'])))
      die("Listing with ID ".$args['listing_id']." does not exist");
   $data = $request->getParsedBody();
   $rows = array("Listing_Id", "Stubhub_Listing_Id", "Date_Created", "Date_Activated", "Date_Stubhub_Created", "Date_Updated", "Quantity", "Price_Per_Ticket", "Event_Id", "Active", "Package_Id", "Face_Value");
   foreach($data as $key => $row){
      if(in_array($key, $rows))
         $update[$key] = $row;
      else
         die("Unknown row: \"".$key."\"");
   }
   unset($update['Listing_Id']);
   unset($update['Date_Created']);
   unset($update['Date_Updated']);
   unset($update['Quantity']);
   unset($update['Event_Id']);
   unset($update['Package_Id']);
   $database->update('listing', $update, ["Listing_Id" => $args['listing_id']]);
   print_r(json_encode($database->select('listing', "*", ["Listing_Id" => $args['listing_id']])));
});


/**
=========================================
* Sell, Check Sold Status, Check Price
=========================================
*/

$app->post('/listing/{id}/sold', function($request, $response, $args){
   global $database;

   //Check if Listing exists, die if doesnt
   if(!$database->has('listing', array("Listing_Id" => $args['id']))){
      die("Listing with ID ".$args['listing_id']." does not exist");
      $qty = $database->select('listing', 'Quantity', array("Listing_Id" => $args['id']));
   }
   //get and verify body information
   $data = $request->getParsedBody();
   $rows = array("Listing_Id", "Stubhub_Listing_Id", "Quantity", "Price_Per_Ticket", "Package_Id");
   foreach($data as $key => $row){
      if(in_array($key, $rows))
         $update[$key] = $row;
      else
         die("Unknown row: \"".$key."\"");
   }

   //Check if whole listing was sold
   if($qty > $update['Quantity']){
      $soldStatus = 6;
   } else if($qty == $update['Quantity']){
      $soldStatus = 2;
   } else {
      die("There are only ". $qty ." seats in this listing");
   }

   $database->update('listing', array("Price_Per_Ticket" => $update['Price_Per_Ticket'],  "Active" => $soldStatus), array("Listing_Id" => $update['Listing_Id']));
   /*for($i = 0, $i < $update['Quantity'], $i++){
      $database->update('seat2listing', array("Price_Sold" => $update['Price_Per_Ticket'], "Sold" => "2"), array("Listing_Id" => $update['Listing_Id']));
   }*/

   
   

});


/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();