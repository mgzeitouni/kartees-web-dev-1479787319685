<?php
require_once('DB.php');

class reporting{
    function details($request, $response, $args){
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
                         'start' => $games[$key]['Game_Date_Time'],
                         'price' => $data['price'],
                         'status' => $data['active']['status'],
                         'team' => $this->getTitle($games[$key]['Home_Team'], $games[$key]['Opponent'], $team[0]['Sport'])];
            //echo "Key".$this->array_search_inner($games, "Event_Id",$data['event_id']);
        }
        $response->write(json_encode($return));
        return $response;
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
        
        $output['Home'] = $home;
        $output['opponent'] = $opponent;
        
        return ($output);
    }
    
    public function builtReport($request, $response, $args){
        global $database;
        $row = $database->get("report_package", "*", null, [['statuschangetime:number' => 'asc']], 1)[0];
        $data['tier_table'] = array();
        //echo $row['_id'];
        if (substr($row['_id'], 0, strlen("report_")) == "report_") {
            $id = substr($row['_id'], strlen("report_"));
        }
        if(!isset($id))
            die("Connection Error");
        //echo $id;
        $userPackageInfo = $database->get("package", "Team, Zone, Price", ['_id' => $id])[0];
        $usersSchedule = json_decode(package::listings(null, null, ['id' => $id]), true);
        $systemTiers = (tiers::get(null, null, ['id'=>$userPackageInfo['Team']], false));
        $systemGames = (season::getGames(null,null, ['id'=>$userPackageInfo['Team']], false));
        
        //print_r($usersSchedule);
        
        $active_listings = 0;
        $total_listings = 0;
        
        foreach($usersSchedule as $usersSchedule2){
            if(($usersSchedule2['active']['status'] != "NEVERLISTED") && ($usersSchedule2['active']['status'] != "UNLISTED")){
                $active_listings++;
            }
            $total_listings++;
            $userSchedule[] = $usersSchedule2;
        }
        
        //print_r($userSchedule);
        
        foreach($userSchedule as $userListing){
            $class = $systemGames[0]['games'][$this->customIndex($systemGames[0]['games'], 'Event_Id', $userListing['event_id'])]['game_value'];
            $listings[$class][] = $userListing;
        }
        $totalFaceValue = 0;
        foreach($listings as $tier=>$listing){
            $tier_active_listings = 0;
            $tier_total_listings = 0;
                    foreach($listing as $game){
                        if(($game['active']['status'] != "NEVERLISTED") && ($game['active']['status'] != "UNLISTED")){
                            $tier_active_listings++;
                        }
                        $tier_total_listings++;
                    }
            $report[$tier]['face_value'] = ($tier_active_listings * $systemTiers[0]['zones'][$tier][$userPackageInfo['Zone']]);
            $report[$tier]['total_num_games'] = $tier_active_listings;
            $totalFaceValue += $report[$tier]['face_value'];
            
            $report[$tier]['num_attending/attended'] = $tier_total_listings-$tier_active_listings;
        }
        
        
        foreach($listings as $tier2=>$listing2){
            $report[$tier2]['total_investment'] = ($report[$tier2]['face_value']/$totalFaceValue)*$userPackageInfo['Price'];
            $sold = 0;
            $unsold = 0;
            $profit = 0;
            $revenue = 0;
            foreach($listing2 as $each){
                if($each['active']['status'] == 'SOLD'){
                    $sold += 1;
                    $revenue += $each['price'];
                    $profit += $each['price']-($report[$tier2]['total_investment']/count($listing2));
                } else if($each['active']['status'] == 'LISTED' || $each['active']['status'] == 'PENDING-LISTED'){
                    $unsold += 1;
                }
            }
            $report[$tier2]['total_sales'] = $revenue;
            $report[$tier2]['total_profit'] = $profit;
            $report[$tier2]['num_games_sold'] = $sold;
            $report[$tier2]['num_games_unsold'] = $unsold;
            $report[$tier2]['percent_sold'] = (($sold * 100)/$active_listings);
            $report[$tier2]['avergae_profit_per_sale'] = ($sold !== 0) ? $profit/$sold : "N/A";
            $report[$tier2]['ROI'] = ($report[$tier2]['total_investment'] !== 0) ? ($profit/$report[$tier2]['total_investment'])*100 : "N/A";
            
        }
        $report['statuschangetime'] = time();
        print_r($report);
        $database->update("report_package", $report, ['_id' => $row['_id']]);
        
    }
    
    public function get($request, $response, $args){
        global $database;
        if (substr($args['id'], 0, strlen("report_")) == "report_") {
            $id = $args['id'];
        } else if (substr($args['id'], 0, strlen("package_")) == "package_") {
            $id = "report_".$args['id'];
        } else {
            $id = "report_package_".$args['id'];
        }
        print_r(json_encode($database->get("report_package", "*", ["_id" => $id], false, 1)));
        //return $response;
    }
    
    private function customIndex($array, $field, $value){
        foreach($array as $key=>$val){
            if($val[$field] == $value){
                return $key;
            }
        }
    }
    
    private function array_search_inner($array, $key, $value){
        foreach($array as $return=>$val){
            if($val[$key] == $value){
                return $return;
            }
        }
    }
}