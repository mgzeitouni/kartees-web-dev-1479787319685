<?php
class season{
    public static function getGames($request, $response, $args, $outside = true){
        global $database;
        
        $id = $args['id'];
        $games = $database->get("season", "*", ['games.0.Home_Team' => $id]);
        if($outside){
            $response->write(json_encode($games));
            return $response;
        } else {
            return $games;
        }
    }
    
    public function getGamesByPackage($request, $response, $args){
        global $database;
        $id = $args['id'];
        
        $listings = new package;
        $listings = json_decode($listings->listings(False, False, ['id' => $id]), true);
        $team = $database->get("package", "Team, Sport", ['_id' => $id]);
        //print_r($team);
        $games = $database->get("season", "*", ['games.0.Home_Team' => $team[0]['Team']])[0]['games'];
        //print_r($games);
        foreach($listings as $data){
            $key = $this->array_search_inner($games, "Event_Id",$data['event_id']);
            $return[] = ['lid' => $data['_id'],
                         'sold' => $data['active']['status'],
                         'start' => $games[$key]['Game_Date_Time'],
                         'color' => $this->getColor($data['active']['status'], $data['price']),
                         'title' => $this->getTitle($games[$key]['Home_Team'], $games[$key]['Opponent'], $team[0]['Sport'])];
            //echo "Key".$this->array_search_inner($games, "Event_Id",$data['event_id']);
        }
        $response->write(json_encode($return));
        return $response;
    }
    
    private function array_search_inner($array, $key, $value){
        foreach($array as $return=>$val){
            if($val[$key] == $value){
                return $return;
            }
        }
    }
    
    private function getColor($status, $price){
        //echo $status."\n";
        switch($status){
            case "SOLD":
                $this->message = "Sold For $".money_format("%i",$price);
                return "#1abc9c";
                break;
            case "NEVERLISTED":
                $this->message = "Enjoy The Game!";
                return "white";
                break;
            case "LISTED":
                $this->message = "Listed!";
                return "green";
                break;
            case "UNLISTED":
                $this->message = "Enjoy The Game!";
                return "white";
                break;
            case "PENDING-LISTED":
                $this->message = "Listing...";
                return "yellow";
                break;
            case "PENDING-UNLISTED":
                $this->message = "Delisting...";
                return "red";
                break;
            case "PENDING-LISTED-MANUAL":
                $this->message = "Listing at $".money_format("%i",$price)."...";
                return "rgb(5, 181, 255)";
                break;
        }
    }
    
    private function getTitle($h, $o, $sport){
       
        switch($sport){
            case "sports_1":
                $sport = "MLB";
                break;
            case "sports_2":
                $sport = "NBA";
                break;
            case "sports_3":
                $sport = "NFL";
                break;
            case "sports_4":
                $sport = "NHL";
                break;
        }
        
        $teams = new team;
        if(isset($this->nameCache[$h]))
            $home = $this->nameCache[$h]['team_name'];
        else {
            $home = $teams->getNameBySport(true, true, ['sport' => $sport], true);
            $this->nameCache = $home;
            $home = $this->nameCache[$h]['team_name'];
        }
        
        if(isset($this->nameCache[$o]))
            $opponent = $this->nameCache[$o]['team_name'];
        else {
            $opponent = $teams->getNameBySport(true, true, ['sport' => $sport], true);
            $this->nameCache = $opponent;
            $opponent = $this->nameCache[$o]['team_name'];
        }
        //echo "Home";
        //print_r($home);
        //print_r($this->nameCache);
        
        return $opponent." at ".$home."<div class='message'>".$this->message."</div>";
    }

}