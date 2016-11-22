<?php
if(!isset($conn)){
	require("database.php");
}


function getPackage($id){
				$sql = getTableSearch("package","*", "Package_Id", $id);
				return $sql;
}

function hasTicket($user){
    
    global $conn, $id;
    if($user != $id){
        $msg = 'User '.$id.' tried to impersonate user '.$user.'\nSecond line of text';    
        $msg = wordwrap($msg,70);
        mail("mplushnick@gmail.com","Security Alert!",$msg);
    } else {
        $sql = dbSearch("package", "User_Id", $user);
        return $sql;
    }
}


$getPackages_Cache=array();
function getPackages($user){
    
		global $conn, $id, $getPackages_Cache;
		if(!isset($getPackages_Cache[$user])){
			if($user != $id){
				$msg = 'User '.$id.' tried to impersonate user '.$user.'\nSecond line of text';    
				$msg = wordwrap($msg,70);
				mail("mplushnick@gmail.com","Security Alert!",$msg);
			} else {
				$sql = getTableSearch("package","*", "User_Id", $user);
				foreach($sql as $row1){
					if($row1['Active'] != '0'){
						$row[] = $row1;
					}
				}
				//print_r($sql);
				$getPackages_Cache[$user] = $row;
				return $row;
			}
		} else {
			return $getPackages_Cache[$user];
		}
}

function disablePackage($id){
	dbUpdate('package','Package_Id', $id, 'Active', '0');
}

$getSeats_Cache=array();
function getSeats($package_ID, $user){
	global $getSeats_Cache;
	if(!isset($getSeats_Cache[$package_ID] )){
		$seats = json_encode(getTableSearch('seat', '*', 'Package_Id', $package_ID));
		$getSeats_Cache[$package_ID] = $seats;
		return $seats;
	} else {
		return $getSeats_Cache[$package_ID];
	}
}

function getGames($team, $pid, $json = true, $msg = true){
	global $conn;
	//echo $team;
	$query = "SELECT * FROM game WHERE `Home_Team` = $team";
	//echo $query;
	$print = $conn->query($query);
	while($a = $print->fetch_assoc()){
		$data[] = $a;
	}
	
	
	if($json){
		$i = 0;
		$seats = checkSoldnew($pid);
		foreach($data as $row){
				$opp = getTeam($row['Opponent']);
				$opp = $opp['Team_Name'];
				$home = getTeam($row['Home_Team']);
				$home = $home['Team_Name'];
				if($msg){
					if($seats[$row['Event_Id']]['ppt'] && ($seats[$row['Event_Id']]['Sold'] == 1))
						$message = "Listing Price: $".$seats[$row['Event_Id']]['ppt'];
					else if(!$seats[$row['Event_Id']]['ppt'] && ($seats[$row['Event_Id']]['Sold'] == 4))
						$message = "Pending Upload";
					else if($seats[$row['Event_Id']]['ppt'] && ($seats[$row['Event_Id']]['Sold'] == 2))
						$message = "<span style='font-weight:bold; font-size:17px'>Sold for: $".$seats[$row['Event_Id']]['ppt']."</span>";
					else 
						$message = "Enjoy The Game!";
						
					$arr[$i]['title'] = $opp." @ ".$home."<br>".$message/*." ".$seats[$row['Event_Id']]['Sold']." ".$seats[$row['Event_Id']]['lid']*/;
					$arr[$i]['start'] = $row['Game_Date_Time'];
					$arr[$i]['textColor'] = "black";
					$arr[$i]['lid'] = $seats[$row['Event_Id']]['lid'];
					$arr[$i]['sold'] = $seats[$row['Event_Id']]['Sold'];

					if($seats[$row['Event_Id']]['Sold'] == 0){
						$arr[$i]['color'] = "white";
					} else if($seats[$row['Event_Id']]['Sold'] == 1){
						$arr[$i]['color'] = "white";
					} else if($seats[$row['Event_Id']]['Sold'] == 2){
						$arr[$i]['color'] = "rgba(0,142,0,.7)";
					} else if($seats[$row['Event_Id']]['Sold'] == 3){
						$arr[$i]['color'] = "#ff9f89";
					} else if($seats[$row['Event_Id']]['Sold'] == 4){
						$arr[$i]['color'] = "yellow";
					}
				} else {
					$arr[$i]['title'] = $opp." @ ".$home;
					$arr[$i]['start'] = $row['Game_Date_Time'];
					$arr[$i]['lid'] = $seats[$row['Event_Id']]['lid'];
					//$arr[$i]['textColor'] = "black";
					if($seats[$row['Event_Id']]['Sold'] == 0){
						$arr[$i]['avail'] = "0";
					} else if($seats[$row['Event_Id']]['Sold'] == 1){
						$arr[$i]['avail'] = "1";
					} else if($seats[$row['Event_Id']]['Sold'] == 2){
						$arr[$i]['avail'] = "2";
						$arr[$i]['ppt'] = $seats[$row['Event_Id']]['ppt'];
					} else if($seats[$row['Event_Id']]['Sold'] == 3){
						$arr[$i]['avail'] = "3";
					} else if($seats[$row['Event_Id']]['Sold'] == 4){
						$arr[$i]['avail'] = "4";
					}
				}
				//$opp = $row['Opponent'];
				//$home = $row['Home_Team'];

			
			$i++;
		}
		return json_encode($arr);
	} else {
		return $data;
	}
}


$getListingEvents_Cache = array();
function getListingEvents($pid){
	global $getListingEvents_Cache;
	if(!isset($getListingEvents_Cache[$pid])){
		$result = getTableSearch('listing', 'Listing_Id, Event_Id, Active, Price_Per_Ticket', 'Package_Id', $pid);
		//echo "new";
		foreach($result as $x){
			$result2[$x['Listing_Id']]['Event'] = $x['Event_Id'];
			$result2[$x['Listing_Id']]['Status'] = $x['Active'];
			$result2[$x['Listing_Id']]['ppt'] = $x['Price_Per_Ticket'];
		}
		$getListingEvents_Cache[$pid] = $result2;
		return $getListingEvents_Cache[$pid];
	} else {
		return $getListingEvents_Cache[$pid];
	}
}
//echo "<pre>";
//print_r(getListingEvents('12'));


$checkSold_Cache = array();
function checkSoldnew($pid, $user =null){
	global $conn, $checkSold_Cache;
	if(!isset($checkSold_Cache[$pid])){
		if($user != getSessionId($_SESSION['auth'])){
			$seats = json_decode(getSeats($pid, $user),true);
			//print_r($seats);
			foreach($seats as $seat){
				$seatIds[] = $seat['Seat_Id'];
			}
			$seats = implode(",", $seatIds);
			$query = "SELECT * FROM `seat2listing` WHERE `Seat_Id` in ($seats)";
			//echo $query;
			$print = $conn->query($query);
			while($a = $print->fetch_assoc()){
				$id = "";
				$id = getListingEvents($pid);
				//print_r($id);
				$a['Event_Id'] = $id[$a['Listing_Id']]['Event'];
				$data[$id[$a['Listing_Id']]['Event']]['Sold'] = $id[$a['Listing_Id']]['Status'];
				$data[$id[$a['Listing_Id']]['Event']]['ppt'] = $id[$a['Listing_Id']]['ppt'];
				$data[$id[$a['Listing_Id']]['Event']]['lid'] = $a['Listing_Id'];
				
			}
			$checkSold_Cache[$pid] = $data;
			//echo "new";
			return $data;
		}
	} else {
		return $checkSold_Cache[$pid];
	}
}



$checkSold_Cache = array();
function checkSold($pid, $user =null){
	global $conn, $checkSold_Cache;
	if(!isset($checkSold_Cache[$pid])){
		if($user != getSessionId($_SESSION['auth'])){
			$seats = json_decode(getSeats($pid, $user),true);
			//print_r($seats);
			foreach($seats as $seat){
				$seatIds[] = $seat['Seat_Id'];
			}
			$seats = implode(",", $seatIds);
			$query = "SELECT * FROM `seat2listing` WHERE `Seat_Id` in ($seats)";
			//echo $query;
			$print = $conn->query($query);
			while($a = $print->fetch_assoc()){
				$id = "";
				$id = getListingEvents($pid);
				//print_r($id);
				$a['Event_Id'] = $id[$a['Listing_Id']];
				$data[$id[$a['Listing_Id']]] = $a;
			}
			$checkSold_Cache[$pid] = $data;
			//echo "new";
			return $data;
		}
	} else {
		return $checkSold_Cache[$pid];
	}
}
//echo "<pre>";
//print_r(checkSold('12', '2'));
//echo "<pre>";
//print_r(getGames('1062'));

$getTeam_Cache=array();
function getTeam($id){
	global $getTeam_Cache;
	
	if(!isset($getTeam_Cache[$id])){
		$sports = getTableSearch("team", "*", "Stubhub_Performer_Id", $id);
		$getTeam_Cache[$id]=$sports[0];
		//$sports[0]['Team_Name'] = $sport[0]['Team_Name'];
		//echo "GOT ".$sports[0]['Team_Name']."<BR>";
		return $sports[0];
	} else {
		return $getTeam_Cache[$id];
	}   
}

function updateListingsStatus($pid, $state){
	if($state == 4)
		$sql = "UPDATE `listing` SET `Active` = ".$state." WHERE `Package_Id` = ".$pid." AND `Active` = 3";
	else if($state == 3)
		$sql = "UPDATE `listing` SET `Active` = ".$state." WHERE `Package_Id` = ".$pid." AND (`Active`=1 OR `Active`=3 OR `Active`=4)";
	run($sql);
	
	//echo $sql;
}

function updateListingStatus($state, $lid){
	if($state == 4)
		$sql = "UPDATE `listing` SET `Active` = ".$state." WHERE `Active` = 3 AND `Listing_Id` = ".$lid."";
	else if($state == 3)
		$sql = "UPDATE `listing` SET `Active` = '".$state."' WHERE (`Active`=1 OR `Active`=3 OR `Active`=4) AND `Listing_Id` = '".$lid."'";
	if(run($sql))
		return true;
	//echo $sql;
}



?>