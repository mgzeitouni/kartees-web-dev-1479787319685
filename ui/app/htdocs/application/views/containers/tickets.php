            <!-- content-10  -->

	


				
						
<div class="col-sm-12">
	<form method='post' name='updatelistings' id='updatelistings'>
		<div id="customCal">
			<div id="multiSelect" class="col-xs-12">
                <div class="col-xs-5">
                    <div  id="selected_checkboxes">
                        
                    </div>
                    <a onclick="clearChecks()">clear selection</a>
                </div>
                <div class="col-xs-5" id="submit_types">
                    <input type="radio" name="list" value="4" data-toggle="radio">
                    <label class="radio">
                      List Selected
                    </label>
                    <input type="radio" name="list" value="3" data-toggle="radio">
                    <label class="radio">
                        Delist Selected
                    </label>
                </div>
                <div class="col-xs-2" id=""><input type="submit" class="btn btn-hge btn-success" value="Submit Changes" />
                </div>
            </div>
			<div id="calContainer">
                <a href="<?= $this->config->item('base_url') ?>/packages/report/<?= $id ?>">Report</a><?= $calendar ?>
            </div>
        </div>
	</form>
</div>


            
			


<link rel='stylesheet' href='<?= $this->config->item('base_url') ?>/fullcalendar/lib/cupertino/jquery-ui.min.css' />
<link href='<?= $this->config->item('base_url') ?>/fullcalendar/fullcalendar.css' rel='stylesheet' />
<link href='<?= $this->config->item('base_url') ?>/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='<?= $this->config->item('base_url') ?>/fullcalendar/lib/moment.min.js'></script>
<script src='<?= $this->config->item('base_url') ?>/fullcalendar/lib/jquery.min.js'></script>
<script src='<?= $this->config->item('base_url') ?>/fullcalendar/fullcalendar.min.js'></script>
		<div id='calscript'>

</div>

				<script>
						
						function prev(){
							for(i=0; i<months.length; i++){
										if(months[i].state == "enabled"){
												var active = i;
										}
								}
							if(active != 0){
								document.getElementById(months[active].id).classList.remove("active");
								document.getElementById(months[active].id).classList.add("nonActive");
								
								document.getElementById(months[active-1].id).classList.add("active");
								document.getElementById(months[active-1].id).classList.remove("nonActive");
								
								months[active].state = "disabled";
								months[active-1].state = "enabled";
							}
						}
						function next(){
								for(i=0; i<months.length; i++){
										if(months[i].state == "enabled"){
												var active = i;
										}
								}
							if(active != months.length){
								document.getElementById(months[active].id).classList.remove("active");
								document.getElementById(months[active].id).classList.add("nonActive");
								
								document.getElementById(months[active+1].id).classList.add("active");
								document.getElementById(months[active+1].id).classList.remove("nonActive");
								
								months[active].state = "disabled";
								months[active+1].state = "enabled";
							}
						}
                        
                        
                        
						function status(name, element){
						     if(!document.getElementById("edit"+element)){
								var btn = document.createElement("div");        
								btn.innerHTML = '<button style="float:right; margin-top:-20px" onclick="closepopout(\''+element+'\')\">Close</button><br>';
								btn.id = "edit"+element;
								btn.className = "popout";
								document.body.appendChild(btn);
								
								var btn = document.createElement("div");        
								btn.id = "cover"+element;
								btn.className = "coverpopout";
								btn.addEventListener("click", closepopout, false);
								document.body.appendChild(btn);
								
								getOptions(element);// Create a text node



						     }
						     
						}
						function closepopout(element){
								var item = document.getElementById("edit"+element);
								document.body.removeChild(item);
								var item = document.getElementById("cover"+element);
								document.body.removeChild(item);
								
									   
						}
						function getOptions(seat){
								var xhttp = new XMLHttpRequest();

								xhttp.onreadystatechange = function() {
								  if (xhttp.readyState == 4 && xhttp.status == 200) {
                                        switch (xhttp.responseText) {
                                            case "NEVERLISTED":
                                                document.getElementById("edit"+seat).innerHTML += "<button class='btn btn-hg btn-primary' onclick='listAuto(\""+seat+"\")'>List</button><br>"+
                                                                                                "<div class='input-group'>"+
                                                                                                "<span class='input-group-addon'>$</span>"+
                                                                                                "<input class='form-control input-hg' id='price_"+seat+"' style='width:50%' placeholder='Placeholder' type='number'/>"+
                                                                                                "<button onclick='listManual(\""+seat+"\")' style='margin: 0px; border-top-left-radius:0px; border-bottom-left-radius:0px; margin-left: -20px; z-index: -1; padding: 15.5px' class='btn btn-hg btn-primary'>List and Set Price</button>"+
                                                                                                "</div>";
                                                break;
                                            case "PENDING-LISTED":
                                                document.getElementById("edit"+seat).innerHTML += "<button class='btn btn-hg btn-danger' onclick='unlist(\""+seat+"\")'>Unlist</button><br>"+
                                                                                                "<div class='input-group'>"+
                                                                                                "<span class='input-group-addon'>$</span>"+
                                                                                                "<input  id='price_"+seat+"' class='form-control input-hg' style='width:50%' placeholder='Placeholder' type='number'/>"+
                                                                                                "<button onclick='listManual(\""+seat+"\")' style='margin: 0px; border-top-left-radius:0px; border-bottom-left-radius:0px; margin-left: -20px; z-index: -1; padding: 15.5px' class='btn btn-hg btn-primary'>List and Set Price</button>"+
                                                                                                "</div>";
                                                break;
                                        }
								  }
								};
								xhttp.open("GET", "http://<?= $this->config->item('api_host') ?>/api/<?= $this->config->item('api_version') ?>/listing/status/"+seat, true);
								xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                                xhttp.setRequestHeader("token", "<?= $this->config->item('token') ?>");
								xhttp.send();

						}
                        function unlist(seat) {
                            var xhttp = new XMLHttpRequest();

							xhttp.onreadystatechange = function() {
								  if (xhttp.readyState == 4 && xhttp.status == 200) {
                                        switch (xhttp.responseText) {
                                            case "Success":
                                                closepopout(seat);
                                                document.getElementById("cell_"+seat).style.backgroundColor = "white";
                                                node = document.getElementById("title_"+seat).childNodes;
                                                node[1].innerHTML = "Delisting...";
                                                break;
                                        }
								  }
							};
							xhttp.open("PUT", "http://<?= $this->config->item('api_host') ?>/api/<?= $this->config->item('api_version') ?>/listing/"+seat.substr(seat.indexOf("_") + 1), true);
							xhttp.setRequestHeader("Content-type", "application/json");
                            xhttp.setRequestHeader("token", "<?= $this->config->item('token') ?>");
							xhttp.send('{"active": {"status": "PENDING-UNLISTED"}}');	
                        }
                        function listAuto(seat) {
                            var xhttp = new XMLHttpRequest();

							xhttp.onreadystatechange = function() {
								  if (xhttp.readyState == 4 && xhttp.status == 200) {
                                        switch (xhttp.responseText) {
                                            case "Success":
                                                closepopout(seat);
                                                document.getElementById("cell_"+seat).style.backgroundColor = "yellow";
                                                node = document.getElementById("title_"+seat).childNodes;
                                                node[1].innerHTML = "Listing...";
                                                break;
                                        }
								  }
							};
							xhttp.open("PUT", "http://<?= $this->config->item('api_host') ?>/api/<?= $this->config->item('api_version') ?>/listing/"+seat.substr(seat.indexOf("_") + 1), true);
							xhttp.setRequestHeader("Content-type", "application/json");
                            xhttp.setRequestHeader("token", "<?= $this->config->item('token') ?>");
							xhttp.send('{"active": {"status": "PENDING-LISTED"}}');	
                        }
                        
                        function listManual(seat) {
                            
                            var price = document.getElementById("price_"+seat).value;
                            alert(price);
                            
                            if (price <= 0) {
                                document.getElementById("price_"+seat).style.borderColor = "red";
                                return;
                            }
                            
                            var xhttp = new XMLHttpRequest();

							xhttp.onreadystatechange = function() {
								  if (xhttp.readyState == 4 && xhttp.status == 200) {
                                        switch (xhttp.responseText) {
                                            case "Success":
                                                closepopout(seat);
                                                document.getElementById("cell_"+seat).style.backgroundColor = "yellow";
                                                node = document.getElementById("title_"+seat).childNodes;
                                                node[1].innerHTML = "Listing...";
                                                break;
                                        }
								  }
							};
							xhttp.open("PUT", "http://<?= $this->config->item('api_host') ?>/api/<?= $this->config->item('api_version') ?>/listing/"+seat.substr(seat.indexOf("_") + 1), true);
							xhttp.setRequestHeader("Content-type", "application/json");
                            xhttp.setRequestHeader("token", "<?= $this->config->item('token') ?>");
							xhttp.send('{"active": {"status": "PENDING-LISTED-MANUAL"}, "price": '+price+'}');	
                        }
                        
						function sendUpdate(lid, state){
								var xhttp = new XMLHttpRequest();
								xhttp.onreadystatechange = function() {
								  if (xhttp.readyState == 4 && xhttp.status == 200) {
										response = JSON.parse(xhttp.responseText);
										document.getElementById("cell"+lid).innerHTML = response[0];
										document.getElementById("cell"+lid).style.backgroundColor = response[1];
										clearChecks();
								  }
								};
								xhttp.open("POST", "changeStatus.php", true);
								xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
								xhttp.send("lid="+lid+"&state="+state);
								
								closepopout(lid);
						}
						
						var checked = 0;
						$(document.body).on('change', ".checkbox", function() {
								if(checked < 0){
										checked = 0;
								}
								if(this.checked) {
								    checked++;
								    $("#multiSelect").show(); 
								    $("#selected_checkboxes").text(parseInt(checked)+" Events Selected");
								} else if(!this.checked){
								    checked--;
								    if(checked > 0)
										$("#selected_checkboxes").text(parseInt(checked)+" Events Selected");
								     else {
										$("#selected_checkboxes").text(" ");
										 $("#multiSelect").hide();
										 checked = 0;
								     }

								}
						});
						
						$("#updatelistings").submit(function(e){
								e.preventDefault();
								if($("input[name=list]").is(':checked')){
								     var datastring = $("#updatelistings").serialize();
								     $.ajax({
										type: 'POST',
										url: 'changeStatus.php',
										data: "multiselect=1&"+datastring,
										success: function(data){ location.reload();},
										dataType: 'text'
									      });
									      
								} else {
								     alert("Please Select An Action");
								}
						});
						function clearChecks(){
								$('.checkbox').prop("checked", false);
										$("#selected_checkboxes").text(" ");
										 $("#multiSelect").hide();
										 checked = 0;
						}
				</script>
