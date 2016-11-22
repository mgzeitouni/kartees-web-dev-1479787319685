<?php
require_once('DB.php');

class package {
        
    public function create($request, $response, $args){
        global $database, $user_Id;
        $data = $request->getParsedBody();
        $keys = ["Sport" => true,
                 "Team" => true,
                 "Year" => true,
                 "Section" => true,
                 "Row" => true,
                 "Zone" => true,
                 "Price" => true,
                 "Qty" => true,
                 "List_All" => false,
                 "Seats" => [
                             "data" => "flex"
                             ]
                ];
        //$database->get("sports", "num_games", ['name' => $data['Team']]);
        if(is_array($data)){
            $season = $database->get("season", "games, sport", ["games.0.Home_Team" => $data['Team'], "season_year" => $data['Year']]);
            if($this->verifyModel($keys, $data) && ($data['Sport'] == $season[0]['sport'])){
                //print_r($data);
                $data['users'][] = $user_Id;
                $packageId = $database->insert("package", $data);
                $packageId = json_decode($packageId, true);
                $database->insert("report_package", ['statuschangetime' => time()], "report_".$packageId['id']);
                //$packageId = "1";
                $list_all = ($data['List_All'] == "List All") ? true : false;
                $listings = $this->createListings($data['Seats'], $data['Price'], $packageId['id'], $season[0]['games'], $list_all);
                foreach($listings as $listing){
                    $insert['listing'][] = $listing;
                }
                //$insert['package'][] = $data;
                //echo json_encode($insert);
                $database->bulkInsert($insert, false);
                $newResponse = $response->withStatus(201);
            } else {
                $newResponse = $response->withStatus(404);
                $newResponse->write("invalid input");
            }
        } else {
            $newResponse->write("no input detected");
        }
            
        return $newResponse;
        
    }
    
    public function get($request, $response, $args){
        global $database, $user_Id;
        
        $req = array("selector" => [
                        "users" => [
                            '$elemMatch' => [
                                '$eq' => $user_Id
                            ]
                        ]
                ]);
        $req['selector']['_id'] = (isset($args['id'])) ? ['$eq' => $args['id']] : ['$regex' => "^package_"];
        $req = json_encode($req);
            $response = json_decode($database->send($req, "POST", "/_find"), true);
            print_r(json_encode($response['docs']));
    }
    
    public static function listings($request, $response, $args){
        global $database, $user_Id;
        $data = $database->get("listing", "*", ["package_id" => $args['id']]);
        return json_encode($data);
    }
    
    private function createListings($seats, $price, $packageId, $season, $listed = false){
        global $database;
        foreach($season as $event){
                foreach($seats as $seat){
                    $seatf[] = [
                        "barcode_status" => "0",
                        "active" => "0",
                        "seat_num" => $seat
                    ];
                }
                $listing[] = [
                            "date_created" => @date("Y-m-d-G:i:s_e"),
                            "date_updated" => @date("Y-m-d-G:i:s_e"),
                            "quantity" => count($seats),
                            "seats" => $seatf,
                            "price" => $price,
                            "event_id" => $event['Event_Id'],
                            "active" => [
                                "status" => ($listed) ? "PENDING-LISTED" : "NEVERLISTED",
                                "statuschangetime" => time()
                            ],
                            "package_id" => $packageId
                            ];
                unset($seatf);
        }
        //echo $database->bulkInsert($listing, true);
        return ($listing);
    }
    
    public function updatePackage($request, $response, $args){
        global $database, $user_Id;
        $data = $request->getParsedBody();
        if(isset($data['Section']))
            $update['Section'] = $data['Section'];
        if(isset($data['Zone']))
            $update['Zone'] = $data['Zone'];
        if(isset($data['Price']))
            $update['Price'] = $data['Price'];
        if(isset($data['Row']))
            $update['Row'] = $data['Row'];
        //if(isset($data['users']))
            //$update['users'] = $data['users'];
            
        if(isset($args['id']) && (count(@$update) > 0))
            $database->update('package', $update, ['_id' => $args['id']]);
        else
            echo "Please enter update variables";
    }
    
    public function deletePackage($request, $response, $args){
        global $database;
        if(strpos($args['id'], 'package_') !== false){
            if($database->delete("listing", ['package_id' => $args['id']]) &&
            $database->delete("package", ['_id' => $args['id']]) &&
            $database->delete("report_", ['_id' => 'report_'.$args['id']])){
                $response->write("Successfully Deleted");
            }
        } else {
            $response->write("Please input real Package_ID");
        }
        return $response;
    }
    
    private function verifyModel($model, $data){
        if(@$model["data"] == "flex"){
            $request = $data;
        } else {
            foreach($model as $key=>$row){
                if(($row == true) && array_key_exists($key, $data)){
                    if(is_array($data[$key])){
                        $array = $this->verifyModel($model[$key], $data[$key]);
                        if($array)
                            $request[$key] = $array;
                        else
                            return false;
                    } else {
                        $request[$key] = $data[$key];
                    }
                } else if($row == false){
                    if(!isset($data[$key])){
                         $request[$key] = "";
                    } else {
                        if(is_array($data[$key])){
                            $this->verifyModel($model[$key], $data[$key]);
                        } else {
                            $request[$key] = $data[$key];
                        }
                    }
                } else {
                    return false;
                }
            }
        }
        return $request; 
    }
}
