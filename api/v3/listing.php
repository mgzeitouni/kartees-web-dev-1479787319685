<?php
require_once('DB.php');

class listing {
    
    public function getNextNew($request, $response, $args){
        $mode = (isset($args['mode']))? $args['mode'] : "";
        $active = ($mode=="reprice")?"PENDING-LISTED-UNPRICED":'PENDING-LISTED';
        $sent = ($mode == "reprice")?"PENDING-LISTED-UNPRICED":"PENDING-LISTED-SENT";
        global $database;
        $row = $database->get("listing", "_id, active.status, event_id, seats, package_id", ['active.status' => $active], [['active.statuschangetime:number' => 'asc']], 1);
        
        if($row){
            $newResponse = $response->withStatus(200);
            $package = $database->get("package", "Section, Row, Qty", ["_id" => $row[0]['package_id']]);
            $row[0]["section"] = $package[0]['Section'];
            $row[0]["Row"] = @$package[0]['Row'];
            $row[0]["Qty"] = count($row[0]['seats']);
            $database->update("listing", ["active" => ["status" => $sent, "statuschangetime" => time()]], ["_id" => $row[0]["_id"]]);
            $row[0]['_id'] = explode("_", $row[0]['_id'])[1];
            $newResponse->write(json_encode($row[0]));
        } else {
            $newResponse = $response->withStatus(204);
            $newResponse->write('{"error": "No Records To Update"}');
        }
        return $newResponse;
    }
    
    public function getReprice($request, $response, $args){
        $active = "LISTED";
        global $database;
        $row = $database->get("listing", "_id, active.status, event_id, seats, package_id", ['active.status' => $active], [['active.statuschangetime:number' => 'asc']], 1);
        
        if($row){
            $newResponse = $response->withStatus(200);
            $package = $database->get("package", "Section, Row, Qty", ["_id" => $row[0]['package_id']]);
            $row[0]["section"] = $package[0]['Section'];
            $row[0]["row"] = $package[0]['Row'];
            $row[0]["Qty"] = count($row[0]['seats']);
            $database->update("listing", ["active" => ["status" => $row[0]['active']['status'], "statuschangetime" => time()]], ["_id" => $row[0]["_id"]]);
            $row[0]['_id'] = explode("_", $row[0]['_id'])[1];
            $newResponse->write(json_encode($row[0]));
        } else {
            $newResponse = $response->withStatus(204);
            $newResponse->write('{"error": "No Records To Update"}');
        }
        return $newResponse;
    }
    
    public function getNextRelist($request, $response, $args){
        global $database;
        $active = "PENDING-RELISTED";
        $sent = "PENDING-RELISTED-SENT";
        $row = $database->get("listing", "_id, active.status, event_id, seats", ['active.status' => $active], [['active.statuschangetime:number' => 'asc']], 1);
        if($row){
            $newResponse = $response->withStatus(200);
            $database->update("listing", ["active" => ["status" => $sent, "statuschangetime" => time()]], ["_id" => $row[0]["_id"]]);
            foreach($row as &$rows)    
                $rows['_id'] = explode("_", $row[0]['_id'])[1];
            $newResponse->write(json_encode($row));
        } else {
            $newResponse = $response->withStatus(204);
            $newResponse->write('{"error": "No Records To Update"}');
        }
        return $newResponse;
    }
    
    public function getNextDelist($request, $response, $args){
        global $database;
        $active = "PENDING-DELISTED";
        $sent = "PENDING-DELISTED-SENT";
        $row = $database->get("listing", "_id, active.status, event_id, seats", ['active.status' => $active], [['active.statuschangetime:number' => 'asc']], 1);
        if($row){
            $newResponse = $response->withStatus(200);
            $database->update("listing", ["active" => ["status" => $sent, "statuschangetime" => time()]], ["_id" => $row[0]["_id"]]);
            foreach($row as &$rows)    
                $rows['_id'] = explode("_", $row[0]['_id'])[1];
            $newResponse->write(json_encode($row));
        } else {
            $newResponse = $response->withStatus(204);
            $newResponse->write('{"error": "No Records To Update"}');
        }
        return $newResponse;
    }
    
    public function update($request, $response, $args){
        global $database;
        $args['id'] = "listing_".$args['id'];
        $model = $database->model['listing'];
        $data = $request->getParsedBody();
        foreach($data as $key=>$feild){
            if(!key_exists($key, $model)){
                $newResponse = $response->withStatus(404);
                $newResponse->write("Did not recongnize field \"".$key."\"");
                return $newResponse;
            }
            
            if($key == "active"){
                $data[$key]['statuschangetime'] = time();
            }
        }
        if(isset($args['id'])){
            if(!$database->update('listing', $data, ['_id' => $args['id']])){
                $newResponse = $response->withStatus(400);
                $newResponse->write("Error Updating Database");
                return $newResponse;
            } else {
                $newResponse = $response->withStatus(200);
                $newResponse->write("Success");
                return $newResponse;
            }
        }
    }
    
    public function getListingByEventId($request, $response, $args){
        global $database;
        $listing = $database->get("listing", "_id", ['event_id' => $args['id']]);
        if($listing){
            foreach($listing as $id){
                $return[] = explode("_", $id['_id'])[1];
            }
            return json_encode($return);
        }
    }
    
    public function getStatus($request, $response, $args){
        global $database;
        $status = $database->get("listing", "active.status", ['_id' => $args['id']]);
        if(is_array($status)){
            $newResponse = $response->withStatus(200);
            $newResponse->write($status[0]['active']['status']);
        } else {
            $newResponse = $response->withStatus(400);
        }
        return $newResponse;
    }
}

?>