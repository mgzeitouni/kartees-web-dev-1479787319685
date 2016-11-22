<section class="content-3" style="padding: 50px;">
          <div>
            <div class="container">
                <form action="<?= $this->config->item('base_url') ?>/packages/createPost" method="POST" enctype="application/json">
            <div class="col-sm-6">
                    <label for="Sport">Sport</label><br>
                    <select required class="form-control select select-primary mbl select2-offscreen" name="Sport" id="Sport" onchange="getTeams(this.value)" required="required">
                            <option selected="selected">Select A Sport</option>
                            <option value="sports_1">MLB</option>
                            <option value="sports_2">NBA</option>
                            <option value="sports_4">NHL</option>
                            <option value="sports_3">NFL</option>
                    </select>
                    <br>
                    <label for="Team">Team</label>
                    <br>
                    <select onchange="getTiers(this.value)" class="form-control select select-primary mbl select2-offscreen" name="Team" id="team" required="required">
                        <option>Select a Sport</option>
                    </select>
                    <br>&nbsp;<br>
                    <label for="Qty">Quantity of Seats in the Package</label>
                    <input name="Qty" class="form-control input-lg" onchange="addSeats()" onkeyup="addSeats()" id="games" type="number"/>
                    <br>&nbsp;<br>
                    <label for="Price">What Price did you pay for each seat for the full season?</label>
                    <div class="input-group ">
                        <span class="input-group-addon ">$</span>
                        <input name="Price" class="form-control input-lg" id="price" placeholder="" type="text"/>
                    </div>
                    <br>&nbsp;<br>
					<label for="Year">Year</label><br>
                    <select name="Year" class="form-control select select-primary mbl select2-offscreen" id="year">
						<option value="2016">2016</option>
						<option value="2017">2017</option>
					</select>
            </div>
					<div class="col-sm-6">
                        <label for="Zone">Zone</label><br>
                        <select name="Zone" class="form-control select select-primary mbl select2-offscreen" id="Zone">
                            
                        </select>
                        <br>
                        <label for="Section">Section: </label>
                        <input name="Section" class="form-control input-lg" id="section" type="text"/>
                        <br>&nbsp;<br>
                        <label for="Row">Row: </label>
                        <input name="Row" class="form-control input-lg" id="Row" type="text"/>
                        <br>&nbsp;<br>
                        Input Seat Number Below:<br>
                        <div id="seats_inputs">
                            
                        </div>
                    </div>
					<input type="submit" class="btn" name="choose" value="Let Me Choose" />
				    <input type="submit" class="btn" name="List_All" value="List All" />

                </form>
            </div>
          </div>
        </section>
<script>
    $( document ).ready(function() {
        $("select").select2({dropdownCssClass: 'dropdown-inverse'});
    });
    var options = [];
    function getTeams(sport) {
        switch (sport) {
            case("sports_1"):
                sport = "MLB";
                break;
            case("sports_2"):
                sport = "NBA";
                break;
            case("sports_3"):
                sport = "NFL";
                break;
            case("sports_4"):
                sport = "NHL";
                break;
        }
            
        if (options[sport] !== undefined) {
            document.getElementById('team').innerHTML = options[sport];
        } else {
                var data = null;
                var xhr = new XMLHttpRequest();
                xhr.withCredentials = true;
                
                xhr.addEventListener("readystatechange", function () {
                  if (this.readyState === 4) {
                    var data = JSON.parse(this.responseText)
                    document.getElementById('team').innerHTML = "";
                    for(var key in data){
                        options[sport] += "<option value=\""+key+"\">"+data[key]['team_name']+"</option>";
                    }
                    document.getElementById('team').innerHTML = options[sport];
                  }
                          getTiers(document.getElementById('team').value);

                });
                
                xhr.open("GET", "http://<?= $this->config->item('api_host') ?>/api/v3/teamName/sport/"+sport);
                xhr.setRequestHeader("token", "<?= $this->config->item('token') ?>");
                xhr.setRequestHeader("cache-control", "no-cache");
                
                xhr.send(data);
        }
    }
function addSeats() {
    seats = document.getElementById("games").value;
    var inputs = "";
    for(i = 0; i < seats; i++){
        if (((i+1)%10) != 0) {
            inputs += "Seat "+(i+1)+": <input type=\"text\" name=\"Seats['"+i+"']\" value=\"\" style=\"border:1px solid black;width:25px\">";
        } else {
            inputs += "Seat "+(i+1)+": <input type=\"text\" name=\"Seats['"+i+"']\" value=\"\" style=\"border:1px solid black;width:25px\"><br>";
        }
    }
    document.getElementById("seats_inputs").innerHTML = inputs;    
};




    function getTiers(team) {
        document.getElementById('Zone').innerHTML = "";
        //console.log(team);
        var data = null;
        returnData = "<option value=\"\" disabled selected>Select a Tier</option>";
        if (team.length > 0) {
            var xhr = new XMLHttpRequest();
            xhr.withCredentials = true;
            
            xhr.addEventListener("readystatechange", function () {
            if (this.readyState === 4) {
                //console.log(data);
                var data = JSON.parse(this.responseText);
                data = (data[0]["zones"]);
                var index = 0;
                
                
                for (var key1 in data) {
                    if (index == 0) {
                        for(zone in data[key1]){
                            returnData += "<option value=\""+zone+"\">"+zone+"</option>";
                            console.log(returnData);
                        }
                    }
                    index++;  
                }
                document.getElementById('Zone').innerHTML = returnData;
              }
              
            });
            
            xhr.open("GET", "http://<?= $this->config->item('api_host') ?>/api/v3/team/"+team+"/game_values");
            xhr.setRequestHeader("token", "login_2@cbbdce707de9aaa471540e508b53eb1d");
            xhr.setRequestHeader("cache-control", "no-cache");
            
            xhr.send(data);
        }
    }
</script>