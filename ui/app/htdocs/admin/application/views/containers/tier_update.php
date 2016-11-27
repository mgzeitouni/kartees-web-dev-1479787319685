<form method="post" action="<?= $this->config->item("base_url"); ?>tierupdate/submit" id="form" enctype="application/json">

<html>
    <div><?php if(isset($message)) echo $message; else null ?></div>
    <br>Click Add Column to begin<br>
    <select name="Sport" id="Sport" onchange="getTeams(this.value)" required="required">
        <option value="MLB">MLB</option>
        <option value="NBA">NBA</option>
        <option value="NHL">NHL</option>
        <option value="NFL">NFL</option>
    </select>
    <select name="team" id="team" required="required">
        
    </select>
    <select name="year" id="year" required="required">
        <option value="2016">2016</option>
        <option value="2017">2017</option>
        <option value="2018">2018</option>
    </select>
    <input type="submit" name="submit" value="Submit">
    <table style="display: inline" id="main">
        <tr id="headercol">
            <td></td>
        </tr>
        <tR id="row[1]">
        </tR>
    </table>
    <a style="display: inline" href="#" onclick="addCol()">Add Game Zone</a><br>
    <a href="#" style="display: none" id="addrow" onclick="addRow()">Add Tier</a>
</form>
<script type="text/javascript">
    var row = [];
    var rows = 1;
    var col = [];
    
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
            console.log(data);
            for(var key in data){
                document.getElementById('team').innerHTML += "<option value=\""+key+"\">"+data[key]['team_name']+"</option>";
            }
          }
        });
        
        xhr.open("GET", "https://<?= $this->config->item('base_url') ?>/api/v3/teamName/sport/"+sport);
        xhr.setRequestHeader("token", "@9920353502fc425250a3a826b78e2751");
        xhr.setRequestHeader("cache-control", "no-cache");
        
        xhr.send(data);
    }
    
    function addRow(){
        var name = prompt("Tier Name");
        while(row.indexOf(name) != -1){
            var name = prompt("Name Already Exists Please Type a New Column Name");
        }
        row.push(name);
        
        var add = document.createElement("tr");
        add.id = "row["+(row.length)+"]";
        
        var add2 = document.createElement("td");
            add2.innerHTML = row[row.length-1];
            add.appendChild(add2);
        for(var i=0; i<col.length; i++){
            var add2 = document.createElement("td");
            add2.innerHTML = '<input type="text" id="cell['+row[row.length-1]+']['+col[i]+']" name="cell['+row[row.length-1]+']['+col[i]+']">';
            add.appendChild(add2);
        }
        document.getElementById('main').appendChild(add);
    }
    
    function addCol(){
        var name = prompt("Enter the Zone");
        if(col.length > 0){
            while(col.indexOf(name) != -1){
                var name = prompt("Name Already Exists Please Type a New Column Name");
            }
        }
        col.push(name);
        var add2 = document.createElement("td");
                add2.style.textAlign = "center";
                add2.innerHTML = col[col.length-1];
                document.getElementById("headercol").appendChild(add2);
        
        if(row.length > 0){
            for(var i=0; i<=row.length; i++){
                var add2 = document.createElement("td");
                add2.innerHTML = '<input type="text" name="cell['+row[i]+']['+col[col.length-1]+']">';
                document.getElementById("row["+(i+1)+"]").appendChild(add2);
            }
        } else {
            var name = prompt("Tier Name");
            row.push(name);
            var add2 = document.createElement("td");
                add2.innerHTML = row[row.length-1];
                document.getElementById('row['+(1)+']').appendChild(add2);
            for(var i=0; i<row.length; i++){
                var add2 = document.createElement("td");
                add2.name = "cell["+col+"]";
                add2.innerHTML = '<input type="text" name="cell['+row[i]+']['+col[col.length-1]+']">';
                document.getElementById('row['+row.length+']').appendChild(add2);
            }
            document.getElementById("addrow").style.display = "block";
        }
        
    }
</script>

</html>