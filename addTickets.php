<?php
require("database.php");

function getSports(){
    $sports = json_encode(getTable("sport", "name"));
    return $sports;
}

function getTeams($team){
    $sports = json_encode(getTableSearch("team", "`Team_Name`,`Stubhub_Performer_Id`", "sport", $team));
    return $sports;
}

function getNumGames($team){
    $sports = getTableSearch("sport", "num_games", "name", $team);
    return $sports[0];
}

if($_GET['sports'] == "all"){
    echo getSports();
} else if($_GET['teams']){
    $teams = htmlentities($_GET['teams']);
    echo getTeams($teams);
} else if($_GET['num_games']){
    $teams = htmlentities($_GET['num_games']);
    echo getNumGames($teams);
}

function getIdFromName($name){
	$data = dbSearch('team', 'Team_Name', $name);
	return $data['Stubhub_Performer_Id'];
}

function insertPackage($user, $section, $team, $price, $qty){
    global $conn;
    date_default_timezone_set('UTC');
    $sql = "INSERT INTO package (User_Id, Season_Year, Venue_Section, Team, Price, Qty, Create_Date) VALUES ('$user', '".date("Y")."', '$section', '$team', '$price', '$qty', '".date('Y-m-d G:i:s')."')";
    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;
        return $last_id;
    } else {
        echo "insert package Error: " . $sql . "<br>" . $conn->error;
    }
}

function insertSeats($package_ID, $row, $seatnum){
	
	global $conn;
	$query = "INSERT INTO `seat` (Row, Seat_Num, Package_Id) VALUES ('$row', '$seatnum', '$package_ID')";
            
	if ($conn->query($query) === TRUE) {
        $last_id = $conn->insert_id;
        return $last_id;
    } else {
        echo "insert seats Error: " . $sql . "<br>" . $conn->error;
    }
}

function insertSeat2Listing($seatId, $listingId){
	dbInsert('seat2listing', array('Seat_Id', 'Sold', 'Listing_Id'), array($seatId, "3", $listingId));
}

function insertListings($values){
	global $conn;
		$q = "INSERT INTO `listing` (`Date_Created`,`Quantity`,`Event_Id`,`Active`,`Package_Id`) VALUES ".$values;
		if ($conn->query($q) === TRUE) {
			$last_id = $conn->insert_id;
			return $last_id;
		} else {
			die("insert listing Error: " . $sql . "<br>" . $conn->error . "<br>" . $q);
		}
	
}
/*
ini_set('auto_detect_line_endings', true);
$fp = fopen('Sports_Teams.csv', 'r');

// get the first (header) line
$header = fgetcsv($fp);

// get the rest of the rows
$data = array();
while ($row = fgetcsv($fp)) {
  $arr = array();
  foreach ($header as $i => $col)
    $arr[$col] = $row[$i];
  $data[] = $arr;
}

foreach($data as $team){
    if($team['NFL Team'] != null)
        dbInsert("team",array("Team_Name", "City", "Sport"),array($team['NFL Team'],$team['NFL City'],"NFL"));
        //echo "Team ".$team['MLB Team']." City:".$team['MLB City']."<br>";
}
*/
?>