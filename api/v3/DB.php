<?php 
// If you installed via composer, just use this code to requrie autoloader on the top of your projects.
//require_once 'vendor/autoload.php';
 
// Or if you just download the medoo.php into directory, and require it with the correct path.
//require_once 'vendor/catfan/medoo/medoo.php';
 
/* Initialize
$database = new medoo([
    'database_type' => 'mysql',
    'database_name' => 'ad_52063f30b709ce5',
    'server' => 'us-cdbr-iron-east-03.cleardb.net',
    'username' => 'b3c48395bc1a72',
    'password' => 'facf79d9',
    'charset' => 'utf8'
]);
 */
?>
<?php

class database{
    public $model = [
        "login" => [
            "_id" => true,
            "_rev" => false,
            "user" => true,
            "pass" => true,
            "api_user" => false,
            "api_pass" => false,
            "Fname" => true,
            "Lname" => true
        ],
        "sessions" => [
            "_id" => true,
            "_rev" => false,
            "user_id" => true,
            "token" => true,
            "expiry" => true
        ],
        "testarr" => [
            "_id" => true,
            "_rev" => false,
            "array1" => [
                "arra" => true,
                "a" => false
            ]
        ],
        "indexes" => true,
        "package" => [
            "_id" => true,
            "_rev" => false,
            "season_year" => true,
            "section" => true,
            "zone" => true,
            "team" => true,
            "row" => true,
            "price" => true,
            "qty" => true,
            "active" => true,
            "seats" => [
                "data" => "flex"
            ],
            "users" => [
                "data" => "flex"
            ]
        ],
        "listing" => [
            "_id" => true,
            "_rev" => false,
            "stubhub_listing_id" => false,
            "date_created" => true,
            "date_activated" => false,
            "date_uploaded" => false,
            "date_updated" => true,
            "quantity" => true,
            "seats" => [
                "data" => "flex"
                /*"seat" => [
                    "price" => true,
                    "active" => true,
                    "barcode" => false,
                    "barcode_status" => true
                ]*/
                
            ],
            "price" => true,
            "event_id" => true,
            "active" => true,
            "package_id" => true,
            "face_value" => false
        ],
        "teams" => [
            "_id" => true,
            "_rev" => false,
            "stubhub_performer_id" => true,
            "team_name" => true,
            "city" => true,
            "sport" => true,
            "venue_id" => true,
            "zones" => [
                "data" => true
            ]
        ],
        "tiers" => [
          "_id" => true,
          "team" => true,
          "year" => true,
          "zones" => [
            "data" => true
          ]
        ],
        "sports" => [
            "_id" => true,
            "_rev" => false,
            "name" => true,
            "num_games" => true
        ],
        "season" => true,
        "report_package" => true
            /*"_id" => true,
            "_rev" => false,
            "event_id" => true,
            "game_time_date" => true,
            "home_team" => true,
            "opponent_id" => true,
            "sport" => true,
            "a" => true*/
    ];
        
    

	
	public function has($type, $values){
		$body=array();
        $body['selector']['_id']['$regex'] = $type."_*";
		foreach($values as $key=>$value){
			$body['selector'][$key]['$eq'] = $value;
		}
        //echo json_encode($body);
		$response = $this->send(json_encode($body), "POST", "/_find");
        $response = json_decode($response, true);
		if(count($response['docs']) < 1){
            //print_r($response['docs']);
			return false;
		} else {
			return true;
		}
	}
    
    public function get($type, $specific, $values, $sort = false, $limit = false){
		$body=array();
        if($limit)
            $body['limit'] = $limit;
        if($sort)
            $body['sort'] = $sort;
        if(!isset($values['_id']))
            $body['selector']['_id']['$regex'] = "^".$type."_";
        if($specific != "*"){
            $specifics = explode(", ", $specific);
            foreach($specifics as $specific){
                $body['fields'][]= $specific;
            }
        }
        //echo json_encode($values);
        //echo "a";
        if($values != null){
            if(key_exists("_OR", $values)){
                foreach($values['_OR'] as $key=>$value){
                    foreach($value as $val){
                        $body['selector']['$or'][][$key]['$eq'] = $val;
                    }
                }
            } else if(key_exists("_NOT", $values)){
                foreach($values['_NOT'] as $key=>$value){
                    $body['selector']['$not'][$key]['$eq'] = $value;
                }
            } else {
                foreach($values as $key=>$value){
                    $body['selector'][$key]['$eq'] = $value;
                }
            }
        }
        //echo json_encode($body);
		$response = $this->send(json_encode($body), "POST", "/_find");
        $response = json_decode($response, true);
        //print_r($response);
		if(@count($response['docs'][0]) == 0){
			return false;
		} else {
			return $response['docs'];
		}
	}
    
    public function insert($table, $data, $id = null){
        //print_r($this->model);
        if(!isset($this->model[$table])){
            die("Dataset ".$table." does not exist");
        }
        $index = ($id == null) ? $this->getNextIndex($table) : $id;
        $data["_id"] = $index;
        $verified = $this->verifyModel($this->model[$table], $data);
        if($verified){
            //echo "all verified!";
            $request = $verified;
        } else {
            echo "an error in the verification process occured";
        }
        
        //print_r(json_encode($request));
        return $this->send(json_encode($request), "POST");

    }
    
    public function update($table, $update, $where){
        $info = $this->get($table, "*", $where);
        $info = $info[0];
        
        foreach($update as $key=>$param){
            $info[$key] = $param;
        }
        if(isset($this->model[$table])){
            $request = $this->verifyModel($this->model[$table], $info);
            if($request){
                //print_r($info);
                $exec = json_decode($this->send(json_encode($info), "PUT", "/".$info["_id"]), true);
                //print_r($exec);
                if($exec['ok'])
                    return true;
                else
                    return false;
            }
        } else {
            die("Table does not exist");
        }
    }
    
    public function delete($table, $where){
        $docs = $this->get($table, "_id, _rev", $where);
        //print_r($docs);
        if(isset($docs) && $docs != false)
            foreach($docs as $key=>$val){
                $delete['docs'][]= [
                                    "_id" => $val['_id'],
                                    "_rev" => $val['_rev'],
                                    "_deleted" => true
                                    ];
            }
        return $this->send(json_encode(@$delete), "POST", "/_bulk_docs");
    }
    
    public function bulkInsert($data, $debug=false){
        foreach($data as $table=>$inserts){
            $indexes = $this->getNextIndex($table, count($inserts));
            //print_r($indexes);
            foreach($inserts as $key=>$insert){
                $insert["_id"] = (is_array($indexes)) ? array_shift($indexes) : $indexes;
                //echo $insert["_id"];
                //echo $key."\n";
                $arr = $this->verifyModel($this->model[$table], $insert);
                if($arr){
                    $ins['docs'][] = $arr;
                }
            }
        }
        //$ins['docs'] = array_values($ins);
        if($debug)
            echo json_encode($ins);
            
        return $this->send(json_encode($ins), "POST", "/_bulk_docs");
    }
    

    private function verifyModel($model, $data){
        if($model || ($model["data"] == "flex")){
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
	public function getNextIndex($table, $amount = 1){
            $prevIndex = $this->get("indexes", $table, ["for"=>"indexing"]);
            if($prevIndex){
                $index = explode("_", $prevIndex[0][$table]);
                $index = $index[1]+$amount;
                if($amount == 1){
                    $newIndex = $table."_".$index;
                } else {
                    for($i=$amount; $i>0; $i--){
                        $newIndex[] = $table."_".($index-$i);
                    }
                    
                }
                $newMax = $table."_".$index;
                $this->update('indexes', [$table => $newMax], ["for" => "indexing"]);
            } else {
                $index = 1+$amount;
                if($amount == 1)
                    $newIndex = $table."_1";
                else{
                    for($i=$amount; $i>0; $i--){
                        $newIndex[] = $table."_".($index-$i);
                    }
                }
                $newMax = $table."_".$index;
                $this->update('indexes', [$table => $newMax], ["for" => "indexing"]);
            }
            

        return $newIndex;
    }
	public function send($request, $method = "GET", $endpoint = null){
        $this->curl = curl_init();
        curl_setopt_array($this->curl, array(
          CURLOPT_URL => "https://f928c1ab-28b5-46ec-87b6-214e11b34f8d-bluemix:812c7d2a00e355663e82865190f3607bd5f41b42a48e349f4646a99a2f05feab@f928c1ab-28b5-46ec-87b6-214e11b34f8d-bluemix.cloudant.com/kartees".$endpoint,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => $method,
          CURLOPT_POSTFIELDS => $request,
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/json",
            "password: 812c7d2a00e355663e82865190f3607bd5f41b42a48e349f4646a99a2f05feab",
            "username: f928c1ab-28b5-46ec-87b6-214e11b34f8d-bluemix"
          ),
        ));
        $response = curl_exec($this->curl);
        $err = curl_error($this->curl);
        
        curl_close($this->curl);
        
        if ($err) {
          die( "cURL Error #:" . $err );
        } else {
          return $response;
        }
	}
	
}

$database = new database;