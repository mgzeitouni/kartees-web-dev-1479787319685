<?php
require("calendar.php");
if(!$_POST){
    header('Location: myaccount.php');
}
require("database.php");
require("viewTickets.php");
$active = (getTableSearch('listing', "Active", "Listing_Id", $_POST['seat']));
$active = $active[0];

if($active == 1 || $active == 4){
    echo "<button class=\"btn btn-hg btn-warning\" onclick=\"sendUpdate('".$_POST['seat']."','3')\">Delist ticket I want to go</button><br>";
    echo "<button class=\"btn btn-hg btn-danger\">Dump Price I want to sell asap</button>";
} else if($active == 3){
    echo "<button class=\"btn btn-hg btn-success\" onclick=\"sendUpdate('".$_POST['seat']."','4')\">Relist</button><br>";
    echo "<button class=\"btn btn-hg btn-danger\">Dump Price I want to sell asap</button>";
}
if(isset($_POST['lid']) && isset($_POST['state'])){
    updateListingStatus($_POST['state'], $_POST['lid']);
    $info = getTableSearch('listing', "*", "Listing_Id", $_POST['lid']);
    $info = $info[0];
    
    $query = "SELECT * FROM game WHERE `Event_Id` = ".$info['Event_Id'];
	//echo $query;
    $print = $conn->query($query);
    $a = $print->fetch_assoc();
    $opp = getTeam($a['Opponent']);
    $opp = $opp['Team_Name'];
    $home = getTeam($a['Home_Team']);
    $home = $home['Team_Name'];
    
    if($info['Price_Per_Ticket'] && $info['Active'] == 1)
        $message = "Listing Price: $".$info['Price_Per_Ticket'];
    else if(!$info['Price_Per_Ticket'] && $info['Active'] == 4)
	$message = "Pending Upload";
    else if($info['Price_Per_Ticket'] && ($info['Active'] == 2))
	$message = "<span style='font-weight:bold; font-size:17px'>Sold for: $".$info['Price_Per_Ticket']."</span>";
    else 
	$message = "Enjoy The Game!";
    
    $calendar = '<div class="day-number" style="font-size: 12px; font-weight: normal">'.explode("-", $a['Game_Date_Time'])[2].'</div>';    
    $calendar .= ($info['Active'] != "2") ? '<input type="checkbox" style="display:none" class="checkbox" name="'.$info['Listing_Id'].'" id="'.$info['Listing_Id'].'"/>' : "";
    $calendar .= ($info['Active'] != "2") ? '<label for="'.$info['Listing_Id'].'">' : "<div class='sold'>";
    $calendar .= $opp." @ ".$home."<br>".$message;
    $calendar .= ($info['Active'] != "2") ?  '</label>' : "</div>";
    $calendar .= ($info['Active'] != "2") ? "<span class='status' onclick=\"status('a','".$info['Listing_Id']."')\">Change Status</span>" : "";
    
    
    
    if($info['Active'] == 0){
	$color = "white";
    } else if($info['Active'] == 1){
	$color = "white";
    } else if($info['Active'] == 2){
	$color = "rgba(0,142,0,.7)";
    } else if($info['Active'] == 3){
	$color = "#ff9f89";
    } else if($info['Active'] == 4){
	$color = "yellow";
    }
    
    $response = array($calendar, $color);
    echo json_encode($response);

        
        
} else if($_POST['multiselect'] == '1'){
    $data = $_POST;
    unset($data['multiselect']);
    unset($data['list']);
    foreach($data as $id=>$event){
        if($event == "on" && is_numeric($id) ){
            updateListingStatus($_POST['list'], $id);
        }
    }
}