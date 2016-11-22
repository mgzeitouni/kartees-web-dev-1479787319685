<?php
date_default_timezone_set('America/New_York');
    require("database.php");
    $csv = array_map('str_getcsv', file("games.csv"));
    array_walk($csv, function(&$a) use ($csv) {
      $a = array_combine($csv[0], $a);
    });
    array_shift($csv); # remove column header
    
    foreach($csv as $arr){
        foreach($arr as $key => $row){
            if($key == "Game_Date_Time"){
                $keys[] = $key;
                //echo $key.": ";
                $date = explode("/", $row);
                $values[] = $date[2]."-".$date[0]."-".$date[1];
                //echo $row." ".strtotime($row." 13:00")."<br>";
            } else if($key == "sport"){
                $keys[] = $key;
                switch($row){
                    case "MLB":
                        $values[] = 2;
                        break;
                    case "NBA":
                        $values[] = 12;
                        break;
                    case "NFL":
                        $values[] = 22;
                        break;
                    case "NHL":
                        $values[] = 32;
                        break;
                }
            
            } else {
                //echo $key.": "; 
                $keys[] = $key;
                //echo $row . "<br>";
                $values[] = $row;
            }
        }
        /*echo "<pre>";
        print_r($keys);
        print_r($values);
        
        echo '</pre><br>';*/
        echo dbInsert('game', $keys, $values);

        $keys = "";
        $values = "";
        unset($keys);
        unset($values);
    }
?> 