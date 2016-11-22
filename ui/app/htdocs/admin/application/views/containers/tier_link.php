

<form method="post" action="<?= $this->config->item("base_url"); ?>tierupdate/submit" id="form" class="col-sm-6" enctype="application/json">
    <div><?php if(isset($message)) echo $message; else null ?></div>
    <select name="Sport" id="Sport" onchange="getTeams(this.value)" required="required">
        <option value="MLB">MLB</option>
        <option value="NBA">NBA</option>
        <option value="NHL">NHL</option>
        <option value="NFL">NFL</option>
    </select>
    <select name="team" id="team" onchange="getEvents(this.value)" required="required">
        
    </select>
    <select name="year" id="year" required="required">
        <option value="2016">2016</option>
        <option value="2017">2017</option>
        <option value="2018">2018</option>
    </select>
    
    <div id="eventlist"></div>
    
    
        
    
    <input type="submit" name="submit" value="Submit">
</form>
<div class="col-sm-6" id="alert-container">

</div>
<script type="text/javascript">
    var row = [];
    var rows = 1;
    var col = [];
    var events = [];
    var tiers = "";
    
    document.getElementById("form").addEventListener("submit", function(event){
        
    });
    
    function getTeams(sport) {
        var data = null;
        var xhr = new XMLHttpRequest();
        xhr.withCredentials = true;
        
        xhr.addEventListener("readystatechange", function () {
          if (this.readyState === 4) {
            var data = JSON.parse(this.responseText)
            document.getElementById('team').innerHTML = "";
            
            var index = 0;
            for(var key in data){
                if (index == 0) {
                    getEvents(key);
                    
                }
                document.getElementById('team').innerHTML += "<option value=\""+key+"\">"+data[key]['team_name']+"</option>";
                index++;
            }
          }
        });
        
        xhr.open("GET", "http://100.2.150.31/api/v3/teamName/sport/"+sport);
        xhr.setRequestHeader("token", "login_2@cbbdce707de9aaa471540e508b53eb1d");
        xhr.setRequestHeader("cache-control", "no-cache");
        
        xhr.send(data);
    }
    
    function getEvents2(team) {
        
    }
    var returnData = " ";
    function getEvents(team) {
        var data = null;
        returnData = "<option value=\"\" disabled selected>Select a Tier</option>";
        if (team.length > 0) {
            var xhr = new XMLHttpRequest();
            xhr.withCredentials = true;
            
            xhr.addEventListener("readystatechange", function () {
              if (this.readyState === 4) {
                var data = JSON.parse(this.responseText);
                data = (data[0]["zones"]);
                var index = 0;
                for (var key1 in data) {
                        returnData += "<option value=\""+key1+"\">"+key1+"</option>";
                        console.log(returnData);
                }
                
                
                
                    var data = null;
                    document.getElementById('eventlist').innerHTML = ""
                    var xhr = new XMLHttpRequest();
                    xhr.withCredentials = true;
                    
                    xhr.addEventListener("readystatechange", function () {
                      if (this.readyState === 4) {
                        data = JSON.parse(this.responseText);
                        data = data[0]['games'];
                        for(var key in data){
                            document.getElementById('eventlist').innerHTML += data[key]['Game_Date_Time']+"  ---- to ---->  <select onchange=\"updatetier(this.name,this.value)\" name=\""+
                                                                            data[key]['Event_Id']+"\">"+returnData+"</select><span id=\"alert_"+data[key]['Event_Id']+"\"></span><br>";
                        }
                      }
                    });
                    
                    xhr.open("GET", "http://100.2.150.31/api/v3/season/team/"+team);
                    xhr.setRequestHeader("token", "login_2@cbbdce707de9aaa471540e508b53eb1d");
                    xhr.setRequestHeader("cache-control", "no-cache");
                    
                    xhr.send(data);
                
                
                
                
              }
              
            });
            
            xhr.open("GET", "http://100.2.150.31/api/v3/team/"+team+"/game_values");
            xhr.setRequestHeader("token", "login_2@cbbdce707de9aaa471540e508b53eb1d");
            xhr.setRequestHeader("cache-control", "no-cache");
            
            xhr.send(data);
        }
    }
    
    function updatetier(name, value) {

            var xhr = new XMLHttpRequest();
            xhr.withCredentials = true;
            
            xhr.addEventListener("readystatechange", function () {
              if (this.readyState === 4) {
                var response = JSON.parse(this.responseText);
                if (response['ok'] === true) {
                    showPopUp("Success");
                }
              }
            });
            
            xhr.open("PUT", "http://100.2.150.31/api/v3/event/"+name+"/"+value);
            xhr.setRequestHeader("token", "login_2@cbbdce707de9aaa471540e508b53eb1d");
            xhr.setRequestHeader("content-type", "application/json");
            xhr.setRequestHeader("cache-control", "no-cache");
            
            xhr.send(null);
    }
    
    function showPopUp(message) {
        var from = 1;
        var to = 10;
        var alert = document.createElement("div");
        alert.className = "alert-success alert-dismissable alert";
        alert.innerHTML = '<button data-dismiss="alert" class="close" type="button">×</button>';
        id = Math.floor((Math.random() * to) + from);
        from = from + to + 1;
        to = to + to;
        alert.id = id;
        alert.innerHTML += message;
        document.getElementById('alert-container').appendChild(alert);
        
        setTimeout(function(){
            var parent = document.getElementById("alert-container");
            var child = document.getElementById(id);
            parent.removeChild(child);
        }, 3000);
    }
</script>

