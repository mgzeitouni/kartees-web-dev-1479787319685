<?php
		session_start();

		
require("addTickets.php");
require("viewTickets.php");
        if($id = getSessionId($_SESSION['auth'])){
			$loggedIn = true;
		} else {
			header('Location: login.php');
		}
        
        if($_POST['team']){
			//create the package
            $packageID = insertPackage($id, $_POST['section'], $_POST['team'], $_POST['price'], $_POST['games']);
            
			//create seats
			for($i = 0; $i < $_POST['games']; $i++){
                $seat = $_POST['seat-'.$i];
				$seatId[] = insertSeats($packageID, $_POST['row'], $seat);
            }
			
			//creat listings
			$games = getGames($_POST['team'], $packageID, false);
			//print_r($games);
			//echo $games;
			if($_POST['all']=="List All"){
				foreach($games as $game){
					$values = "('".date('Y-m-d G:i:s')."','".$_POST['games']."','". $game['Event_Id']."','3','". $packageID."')";
					$listingId = insertListings($values);
					foreach($seatId as $seat){
						insertSeat2Listing($seat, $listingId);
					}
				}
							header("Location: myaccount.php");

			} else if($_POST['unlisted'] == "Dont List Any Yet" || $_POST['unlisted'] == "Decide Later"){
				foreach($games as $game){
					$values = "('".date('Y-m-d G:i:s')."','".$_POST['games']."','". $game['Event_Id']."','1','". $packageID."')";
					$listingId = insertListings($values);
					foreach($seatId as $seat){
						insertSeat2Listing($seat, $listingId);
					}
				}
				header('Location: ticket.php?view=list&pid='.$packageID);

			} else if($_POST['choose'] == "Let Me Choose"){
				foreach($games as $game){
					$values = "('".date('Y-m-d G:i:s')."','".$_POST['games']."','". $game['Event_Id']."','3','". $packageID."')";
					$listingId = insertListings($values);
					foreach($seatId as $seat){
						insertSeat2Listing($seat, $listingId);
					}
				}
				header('Location: ticket.php?view=list&pid='.$packageID);
			}
			

        }
        
        
        								
include('templates/header.php');
?>
        <section class="content-3" style="padding: 50px;">
          <div>
            <div class="container">
                <form action="addSeasonTicket.php" method="POST">
                    <select required class="select-block mbl" onchange="getTeams(this.value);" name="sport" id="sport">
                            <option>Select A Sport</option>
                          <?php
                            $sports = json_decode(getSports(),true);
                            foreach($sports as $sport){
                                echo "<option name=\"".$sport."\">".$sport."</option>";
                            }
                          ?>
                    </select>
                    <br>
					<input type="hidden" value="" name="gamescount" type="text" />
                    <select class="select-block mbl" disabled="disabled" name="team" id="names">
                        <option>Select a Sport</option>
                    </select>
                    <br>&nbsp;<br>
                    <label for="games">Quantity of Seats in the Package</label>
                    <input name="games" class="form-control input-lg" onchange="addSeats()" onkeyup="addSeats()" id="games" type="text"/>
		     <br>&nbsp;<br>
                    <label for="price">What Price did you pay for each seat for the full season?</label>
                    <input name="price" class="form-control input-lg" id="price" type="text"/>
                    <br>&nbsp;<br>
					<label for="year">Year</label>
                    <select name="year" class="select-block mbl" id="year">
						<option>2016</option>
						<option>2017</option>
					</select>
                    <br>&nbsp;<br>
					
                    <label for="section">Section: </label>
                    <input name="section" class="form-control input-lg" id="section" type="text"/>
                    <br>&nbsp;<br>
                    <label for="section">Row: </label>
                    <input name="row" class="form-control input-lg" id="Row" type="text"/>
                    <br>&nbsp;<br>
                    Input Seat Number Below:<br>
                    <div id="seats_inputs">
                        
                    </div>
					<input type="submit" class="btn" name="choose" value="Let Me Choose" />
				    <input type="submit" class="btn" name="all" value="List All" />

                </form>
            </div>
          </div>
        </section>
<script>

document.getTeams = function(team) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
     var obj = JSON.parse(xhttp.responseText);


		
		var x = document.getElementById("names");
		var length = x.options.length;
		if (length == 1) {
            x.remove(x[0]);
        } else {
				for (i = 0; i < length; i++) {
				  x.remove(x[i]);
				}
		}
		
		for	(index = 0; index < obj.length; index++) {
            addOption("names",obj[index].Team_Name, obj[index].Stubhub_Performer_Id);
        }
		
		if (document.getElementById('sport').options[0].value == "Select A Sport") {
                document.getElementById('sport').remove(0);
				count = 2;
        }
        x.disabled = false;
        getNumGame();
        
    }
  };
  xhttp.open("GET", "addTickets.php?teams="+team, true);
  xhttp.send();
};

function getNumGame(sport) {
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            document.getElementById("gamescount").value = xhttp.responseText;
            addSeats();
        }
      };
      xhttp.open("GET", "addTickets.php?num_games="+sport, true);
      xhttp.send();
};

function addSeats() {
    seats = document.getElementById("games").value;
    var inputs = "";
    for(i = 0; i < seats; i++){
        if (((i+1)%10) != 0) {
            inputs += "Seat "+(i+1)+": <input type=\"text\" name=\"seat-"+i+"\" value=\"\" style=\"border:1px solid black;width:25px\">";
        } else {
            inputs += "Seat "+(i+1)+": <input type=\"text\" name=\"seat-"+i+"\" value=\"\" style=\"border:1px solid black;width:25px\"><br>";
        }
    }
    document.getElementById("seats_inputs").innerHTML = inputs;
    
};

function addOption(id, text, value) {
    var x = document.getElementById(id);
    var option = document.createElement("option");
    option.text = text;
    option.value = value
    x.add(option);
};
</script>
<?php include("templates/footer.php"); ?>
<script>
    $( document ).ready(function() {

        $("select").Selectpicker({style: 'btn-hg btn-primary', menuStyle: 'dropdown-inverse'});
    
    });
    </script>