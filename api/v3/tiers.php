<?php

require_once('DB.php');


class tiers {
    public static function get($request, $response, $args, $outside=true){
        global $database;
        if($values = $database->get("tiers", "*", ['team' => $args['id'], 'year' => date('Y')])){
            if($outside){
				$newResponse = $response->withStatus(200);
				$newResponse->write(json_encode($values));
			} else {
				return $values;
			}
        } else {
			if($outside)
				$newResponse = $response->withStatus(204);
			else
				return false;
        }
        return $newResponse;
    }
    
    public function insert($request, $response, $args){
        global $database;
        $data = $request->getParsedBody();
        if(isset($data['team']) && isset($data['year']) && isset($data['zones'])){
			if($database->get("tiers", "*", ['team' => $data['team'], 'year' => $data['year']])){
				$database->update("tiers", ['zones'=>$data['zones']], ['team' => $data['team'], 'year' => $data['year']]);
				$newResponse = $response->withStatus(201);
			} else if($database->insert("tiers", ["team" => $data['team'], "year" => $data['year'], "zones" => $data['zones']])){
                $newResponse = $response->withStatus(201);
            } 
        } else {
            $newResponse = $response->withStatus(400);
            $newResponse->write("Missing required fields");
        }
        return $newResponse;
    }
    
    public function link($request, $response, $args){
        global $database;
        $data = $request->getParsedBody();
        $req = array("selector" => [
                        "games" => [
                            '$elemMatch' => [
                                "Event_Id" => [
                                    '$eq' => $args['id']
                                ]
                            ]
                        ]
        ]);
        $event = $database->send(json_encode($req), "POST", "/_find");
        $event = json_decode($event, true);
        if(@count($event['docs'][0]) == 0){
			return false;
		} else {
            foreach($event['docs'][0]['games'] as &$events){
                if($events['Event_Id'] == $args['id']){
                    $events['game_value'] = $args['tier'];
                }
            }
            //$event['docs'][0]['games'][$key]['game_value'] = $data['tier'];
            return $database->send(json_encode($event['docs'][0]), "PUT", "/".$event['docs'][0]['_id']);
		}
    }
    
    public function getEventValue($request, $response, $args){
        global $database;
        $data = $request->getParsedBody();
        /*$req = array("selector" => [
                        "games" => [
                            '$elemMatch' => [
                                "Event_Id" => [
                                    '$eq' => $args['id']
                                ]
                            ]
                        ]
                    ],
                    "fields" => ["games"]
        );*/
        //$event = $database->send(json_encode($req), "POST", "/_find");
        $events = $database->get("season", "games", ['games.0.Home_Team' => $args['id']]);
        //$event = json_decode($event, true);
        $tier = array();
        if(!$events){
			return false;
		} else {
            foreach($events[0]['games'] as $event){
                if($event['game_value'] == $args['tier']){
                    $tier[] = ['Event_Id' => $event['Event_Id'], 'Date' => $event['Game_Date_Time']];
                }
            }
            if(!$tier){
                $tier = ['Error' => "Tier does not exist for specified team"];
            }
            return json_encode($tier); 
		}
    }
}