<?php
require("database.php");
require("viewTickets.php");
require("calendar.php");
require("Tickets/Package.php");


		session_start();
        if($id = getSessionId($_SESSION['auth'])){
			$loggedIn = true;
				$user = $id;
		} else {
			header('Location: login.php');
		}
						$pid = $_GET['pid'];		
include('templates/header.php'); ?>
            <!-- content-10  -->
		<section class="content-3" style="padding: 50px">
          <div>
            <div class="container">
              <div class="row">
	<?php if(hasTicket($id)){ 

				
				
				$tickets = getPackages($id);
				foreach($tickets as $ticket){
					if($pid==$ticket['Package_Id'])
						$team = $ticket['Team'];
				}

$Ticket = new Package($user, $pid);

        if($_GET['view'] == "list"){
					$options = array('Reserve', 'List');
					//echo "<pre>";
					$Ticket->menu();
			  
					echo "<form enctype='application/json' method='POST' name='form' action='updateTickets.php'><table style='width:50%; position:relative; margin: 0 auto'><thead style='border-bottom:1px black solid'><td>Listed</td><td>Game</td><td></td> <td>Date</td></thead>";
					$games = (json_decode(getGames($team, $pid, true, false), true));
					$i = 0;
					echo "<input name='updateSelected' value='TRUE' type='hidden' />";
					foreach($games as $game2){
						echo "<tr>";
						echo "<td>";
							
							//echo "<input name='lid[".$i."]' value='".$game2['lid']."' type='hidden' />";
							echo "<input type='hidden' name='game[".$game2['lid']."]' value='off' >";
							echo "<input type='hidden' name='pid' value='".$pid."'>";
								
							if($game2['avail'] == "1"  || $game2['avail'] == "4"){
								echo "<input type='checkbox' name='game[".$game2['lid']."]' value='on' checked >";
								echo "<input type='hidden' name='curr[".$game2['lid']."]' value='on' >";
							} else if($game2['avail'] == "3"){
								echo "<input type='checkbox' name='game[".$game2['lid']."]' >";
								echo "<input type='hidden' name='curr[".$game2['lid']."]' value='off' >";


							} else if($game2['avail'] == "2"){
								echo "<span style='color:green; font-weight:bold'>Sold for $".$game2['ppt'].'</span>';
							}
						echo "</td><td>".$game2['title']."</td><td> </td><td>".$game2['start']."</td></tr>";
						$i++;
						
						
					}
					echo "<tr><td colspan='4'><input class='btn-primary' style='width:100%' type='submit' value='Submit'></td></tr></table></form>";
					//print_r(getGames($team, $pid, false, false));

		} else /*if($_GET['view'] == "beta") */{



				$Ticket->menu();

				echo '
						
						<div class="col-sm-12">
						<form method=\'post\' name=\'updatelistings\' id=\'updatelistings\'>
						  <div id="customCal">'; 
						  echo '<div id="multiSelect" class="col-xs-12"> <div class="col-xs-5"><div  id="selected_checkboxes"></div> <a onclick="clearChecks()">clear selection</a></div>';
						  echo '<div class="col-xs-5" id="submit_types">
								<input type="radio" name="list" value="4" data-toggle="radio">
								<label class="radio">
								  List Selected
								</label>
								
								<input type="radio" name="list" value="3" data-toggle="radio">
								<label class="radio">
								Delist Selected
							      </label>
							      
						  </div>';
						  echo '<div class="col-xs-2" id=""><input type="submit" class="btn btn-hge btn-success" value="Submit Changes" /> </div></div>';
				 echo "<div id=\"calContainer\">";
				 draw_calendar(getGames($team,$pid));
				 print_r(getGames($team, $pid));
				 echo "</form></div>";
				 //echo "<pre>";
				 //print_r(json_decode(getGames($team, $pid), true));
				 //echo "</pre>";
						  echo '</div>
						</div>';
		} /*else { echo '
						<div class="col-xs-4">
							<div class="btn-toolbar">
							  <div class="btn-group">
								<a class="btn btn-primary active" href="?pid='.$pid.'&view=cal">Calendar View</a>
								<a class="btn btn-primary " href="?pid='.$pid.'&view=beta">beta</a>
								<a class="btn btn-primary " href="?pid='.$pid.'&view=list">List View</a>
							  </div>
							</div> 
						</div>';
						?>
								
							<div class="col-xs-6" style="text-align:center">
								<div class="btn-toolbar">
									<div class="btn-group">
										<?php
											foreach($tickets as $ticket){
												$teams = getTeam($ticket['Team']);
												$active = "";
												if($pid == $ticket['Package_Id'])
													$active = "current";
												echo "<a class='teams-top-bar ".$active."' href='ticket.php?pid=".$ticket['Package_Id']."&view=cal'>".$teams['Team_Name']."</a>";
											}
										?>
									</div>
								</div>
							</div>
					  <?php
					echo '
						<div class="col-xs-2" style="text-align:right; margin-bottom:10px;">
							<a href="ticketedit.php?pid='.$pid.'"><img src="flat-ui/images/icons/png/Book.png" width="42px" ></a>
						</div>
						<br>
						<div class="col-sm-12">
						  <div id="calendar"></div>
						</div>';
		}*/
	?>

              </div>
            </div>
          </div>
        </section>
	<?php } else { ?>
                <!--<div class="col-sm-6 aligment">
                  <h3>It looks like you haven't added any team season packages yet</h3>
                  <p>
                    <a class="btn btn-inverse" href="addSeasonTicket.php">Add Package</a>
                  </p>
                </div>
                <div class="col-sm-6">
                  <div class="img">
                    <img src="../../common-files/img/content/ticket-green@2x.png" alt="">
                  </div>
                </div>-->

	<?php } ?>
				</div>
            </div>
          </div>
        </section>
            <!-- logos 
            <section class="logos">
                <div class="container">
                    <div><img src="img/logos/generator.png" height="29" width="140" alt="Generator" /></div>
                    <div><img src="img/logos/theGuardian.png" height="29" width="164" alt="TheGuardian" /></div>
                    <div><img src="img/logos/forbes.png" height="29" width="93" alt="Forbes" /></div>
                    <div><img src="img/logos/theNewYorkTimes.png" height="29" width="201" alt="TheNewYorkTimes" /></div>
                    <div><img src="img/logos/tumblr.png" height="29" width="119" alt="Tumblr." /></div>
                </div>
            </section>       -->

            <!-- footer-1 -->
			
<?php
if(($_GET['view'] == "list") && ($_GET['view'] == "cal")){
$FOOTER_CONTENT = "

<link rel='stylesheet' href='fullcalendar/lib/cupertino/jquery-ui.min.css' />
<link href='fullcalendar/fullcalendar.css' rel='stylesheet' />
<link href='fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='fullcalendar/lib/moment.min.js'></script>
<script src='fullcalendar/lib/jquery.min.js'></script>
<script src='fullcalendar/fullcalendar.min.js'></script>
		<div id='calscript'>
<script>
    
	$(document).ready(function() {
        var $ = jQuery.noConflict();
		$('#calendar').fullCalendar({
			theme: true,
			customButtons: {
		        listALL: {
		            text: 'List This Entire Package',
		            click: function() {
		                /*xhttp.open('POST', '', true);
						xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
						xhttp.send();*/
						$.ajax({
						  type: 'POST',
						  url: 'updateTickets.php',
						  data: 'updateActive=ALL&pid=".$pid."&active=4&team=".$team."',
						  success: function(data){ /*eval($('#calscript' ).html(data)); */location.reload();},
						  dataType: 'text'
						});
			        }
			    },
				delistALL: {
		            text: 'Unlist This Entire Package',
		            click: function() {
		                /*xhttp.open('POST', '', true);
						xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
						xhttp.send();*/
						$.ajax({
						  type: 'POST',
						  url: 'updateTickets.php',
						  data: 'updateActive=ALL&pid=".$pid."&active=3&team=".$team."',
						  success: function(data){ /*eval($('#calscript' ).html(data));*/ location.reload();} ,
						  dataType: 'text'
						});
			        }
			    }
		    },
			header: {
				left: 'listALL, delistALL',
				center: '',
                right: 'prev, title, next'
			},
			defaultDate: '".date('Y-m-d')."',
			editable: true,
			eventLimit: true, // allow \"more\" link when too many events
			events: ".getGames($team, $pid).",
			eventClick: function(calEvent, jsEvent, view) {

				//alert('Event: ' + calEvent.title);
				//alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
				//alert('View: ' + view.name);
				//confirm('do you want to sell this ticket?');
				
				// change the border color just for fun
				//$(this).css('border-color', 'red');

			}
		});
		
	});

</script>
</div>
";
} else {
		$FOOTER_CONTENT = "
				<script>
						
						function prev(){
							for(i=0; i<months.length; i++){
										if(months[i].state == \"enabled\"){
												var active = i;
										}
								}
							if(active != 0){
								document.getElementById(months[active].id).classList.remove(\"active\");
								document.getElementById(months[active].id).classList.add(\"nonActive\");
								
								document.getElementById(months[active-1].id).classList.add(\"active\");
								document.getElementById(months[active-1].id).classList.remove(\"nonActive\");
								
								months[active].state = \"disabled\";
								months[active-1].state = \"enabled\";
							}
						}
						function next(){
								for(i=0; i<months.length; i++){
										if(months[i].state == \"enabled\"){
												var active = i;
										}
								}
							if(active != months.length){
								document.getElementById(months[active].id).classList.remove(\"active\");
								document.getElementById(months[active].id).classList.add(\"nonActive\");
								
								document.getElementById(months[active+1].id).classList.add(\"active\");
								document.getElementById(months[active+1].id).classList.remove(\"nonActive\");
								
								months[active].state = \"disabled\";
								months[active+1].state = \"enabled\";
							}
						}
						function status(name, element){
						     if(!document.getElementById(\"edit\"+element)){
								var btn = document.createElement(\"div\");        
								btn.innerHTML = '<button style=\"float:right; margin-top:-20px\" onclick=\"closepopout(\''+element+'\')\">Close</button><br>';
								btn.id = \"edit\"+element;
								btn.className = \"popout\";
								document.body.appendChild(btn);
								
								var btn = document.createElement(\"div\");        
								btn.id = \"cover\"+element;
								btn.className = \"coverpopout\";
								btn.addEventListener(\"click\", closepopout, false);
								document.body.appendChild(btn);
								
								getOptions(element);// Create a text node



						     }
						     
						}
						function closepopout(element){
								var item = document.getElementById(\"edit\"+element);
								document.body.removeChild(item);
								var item = document.getElementById(\"cover\"+element);
								document.body.removeChild(item);
								
									   
						}
						function getOptions(seat){
								var xhttp = new XMLHttpRequest();

								xhttp.onreadystatechange = function() {
								  if (xhttp.readyState == 4 && xhttp.status == 200) {
										document.getElementById(\"edit\"+seat).innerHTML += xhttp.responseText;
								  }
								};
								xhttp.open(\"POST\", \"changeStatus.php\", true);
								xhttp.setRequestHeader(\"Content-type\", \"application/x-www-form-urlencoded\");
								xhttp.send(\"seat=\"+seat);

						}
						function sendUpdate(lid, state){
								var xhttp = new XMLHttpRequest();
								xhttp.onreadystatechange = function() {
								  if (xhttp.readyState == 4 && xhttp.status == 200) {
										response = JSON.parse(xhttp.responseText);
										document.getElementById(\"cell\"+lid).innerHTML = response[0];
										document.getElementById(\"cell\"+lid).style.backgroundColor = response[1];
										clearChecks();
								  }
								};
								xhttp.open(\"POST\", \"changeStatus.php\", true);
								xhttp.setRequestHeader(\"Content-type\", \"application/x-www-form-urlencoded\");
								xhttp.send(\"lid=\"+lid+\"&state=\"+state);
								
								closepopout(lid);
						}
						
						var checked = 0;
						$(document.body).on('change', \".checkbox\", function() {
								if(checked < 0){
										checked = 0;
								}
								if(this.checked) {
								    checked++;
								    $(\"#multiSelect\").show(); 
								    $(\"#selected_checkboxes\").text(parseInt(checked)+\" Events Selected\");
								} else if(!this.checked){
								    checked--;
								    if(checked > 0)
										$(\"#selected_checkboxes\").text(parseInt(checked)+\" Events Selected\");
								     else {
										$(\"#selected_checkboxes\").text(\" \");
										 $(\"#multiSelect\").hide();
										 checked = 0;
								     }

								}
						});
						
						$(\"#updatelistings\").submit(function(e){
								e.preventDefault();
								if($(\"input[name=list]\").is(':checked')){
								     var datastring = $(\"#updatelistings\").serialize();
								     $.ajax({
										type: 'POST',
										url: 'changeStatus.php',
										data: \"multiselect=1&\"+datastring,
										success: function(data){ location.reload();},
										dataType: 'text'
									      });
									      
								} else {
								     alert(\"Please Select An Action\");
								}
						});
						function clearChecks(){
								$('.checkbox').prop(\"checked\", false);
										$(\"#selected_checkboxes\").text(\" \");
										 $(\"#multiSelect\").hide();
										 checked = 0;
						}
				</script>
		";
}
include("templates/footer.php");
?>