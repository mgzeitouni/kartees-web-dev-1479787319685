<form method="post" action="" id="form" enctype="application/json">

<html>
    <div><?php if(isset($message)) echo $message; else null ?></div>
    <br>Click Add Column to begin<br>
    <select name="Sport" id="Sport" onchange="getTeams(this.value)" required="required">
        <option disabled="disabled">Choose A Sport</option>
        <option value="MLB">MLB</option>
        <option value="NBA">NBA</option>
        <option value="NHL">NHL</option>
        <option value="NFL">NFL</option>
    </select>
    <select name="team" id="team" required="required">
        
    </select>
   
    <input type="submit" name="submit" value="Refresh" style="color:black">
    
</form>

<div class="col-sm-6" id="alert-container">

</div>

<script type="text/javascript">

    
    document.getElementById("form").addEventListener("submit", function(event){
        event.preventDefault();
        
        var team = document.getElementById('team').value;
        var sport = document.getElementById('Sport').value;        
        var data = null;

        var xhr = new XMLHttpRequest();
        xhr.withCredentials = true;
        
        xhr.addEventListener("readystatechange", function () {
          if (this.readyState === 4) {
            var data = JSON.parse(this.responseText)
            showPopUp(data.message);
          }
        });
        
        xhr.open("POST", "http://<?= $this->config->item('api_server') ?>/api/v3/season/import/"+sport+"/"+team);
        xhr.setRequestHeader("token", "login_2@cbbdce707de9aaa471540e508b53eb1d");
        xhr.setRequestHeader("cache-control", "no-cache");
        
        xhr.send(data);
    });
    
    function getTeams(sport) {
        var data = null;

        var xhr = new XMLHttpRequest();
        xhr.withCredentials = true;
        
        xhr.addEventListener("readystatechange", function () {
          if (this.readyState === 4) {
            var data = JSON.parse(this.responseText)
            document.getElementById('team').innerHTML = "";
            console.log(data);
            for(var key in data){
                document.getElementById('team').innerHTML += "<option value=\""+data[key]['city']+" "+data[key]['team_name']+"\">"+data[key]['city']+" "+data[key]['team_name']+"</option>";
            }
          }
        });
        
        xhr.open("GET", "http://<?= $this->config->item('api_server') ?>/api/v3/teamName/sport/"+sport);
        xhr.setRequestHeader("token", "@9920353502fc425250a3a826b78e2751");
        xhr.setRequestHeader("cache-control", "no-cache");
        
        xhr.send(data);
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

</html>