<?php
require_once('DB.php');

class team {
    
    public function getNameById($request, $response, $args, $local = false){
        global $database;
        $name = $database->get("teams", "team_name, city", ["_id" => $args['id']]);
        if($name){
            if($local){
                return $name[0];
            } else {
                $newResponse = $response->withStatus(200);
                $newResponse->write(json_encode($name[0]));
            } 
        } else {
            if($local){
                return false;
            } else {
                $newResponse = $response->withStatus(404);
                $newResponse->write(json_encode(['Error' => 'Team Not Found']));
            }
        }
        return $newResponse;
    }
    
    public function getNameBySid($request, $response, $args, $local = false){
        global $database;
        $name = $database->get("teams", "team_name, city", ["Stubhub_Performer_Id" => $args['id']]);
        if($name){
            if($local){
                return $name[0]['team_name'];
            } else {
                $newResponse = $response->withStatus(200);
                $newResponse->write(json_encode($name[0]));
            } 
        } else {
            if($local){
                return false;
            } else {
                $newResponse = $response->withStatus(404);
                $newResponse->write(json_encode(['Error' => 'Team Not Found']));
            }
        }
        return $newResponse;
    }
    
    public function getNamesBySid($request, $response, $args, $local = false){
        global $database;
        $requests = $request->getParsedBody();
        foreach($requests as $team){
            $data['selector']['$or'][]['Stubhub_Performer_Id']['$eq'] = $team;
        }
        $data['fields'] = ['Stubhub_Performer_Id', 'team_name'];
        $names = $database->send(json_encode($data), "POST", "/_find");
        $names = json_decode($names, true)['docs'];
        foreach($names as $name){
            $returnData[$name['Stubhub_Performer_Id']] = $name['team_name'];
        }
        $response->write(json_encode($returnData));
        return $response;
    }
    
    public function getNameBySport($request, $response, $args, $local = false){
        global $database;
        $name = $database->get("teams", "Stubhub_Performer_Id, team_name, city", ["sport" => $args['sport']]);
        if($name){
            if($local){
                foreach($name as $names){
                    $return[$names['Stubhub_Performer_Id']] = ['team_name' => $names['team_name'], 'city' => $names['city']];
                }
                return $return;
            } else {
                foreach($name as $names){
                    $return[$names['Stubhub_Performer_Id']] = ['team_name' => $names['team_name'], 'city' => $names['city']];
                }
                $newResponse = $response->withStatus(200);
                $newResponse->write(json_encode($return));
            } 
        } else {
            if($local){
                return false;
            } else {
                $newResponse = $response->withStatus(404);
                $newResponse->write(json_encode(['Error' => 'Teams Not Found']));
            }
        }
        return $newResponse;
    }
    
    public function getZoneById($request, $response, $args){
        global $database;
        $name = $database->get("teams", "zones", ["_id" => $args['id']]);
        if($name){
            $newResponse = $response->withStatus(200);
            $newResponse->write(json_encode($name[0]));
        } else {
            $newResponse = $response->withStatus(404);
            $newResponse->write(json_encode(['Error' => 'Team Not Found']));
        }
        return $newResponse;
    }
    
}

?>