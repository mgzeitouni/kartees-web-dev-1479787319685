<form method="post" action="" enctype="application/json">

<?php
require("database.php");
$teams = getTable("team");
echo "<select name=\"team\" onchange=\"fillTable(this.value)\">";
foreach($teams as $team){
    echo "<option value='".$team['Stubhub_Performer_Id']."'>".$team['City']." ".$team['Team_Name']."</option>";
    //$json[$team['Stubhub_Performer_Id']] = $team['json'];
}
echo "</select>";
if($_POST['submit']){
    echo "<pre>";
    print_r(json_encode($_POST['cell']));
    echo "</pre>";
    dbUpdate("team", "Stubhub_Performer_Id", $_POST['team'], "json", json_encode($_POST['cell']));
    
}
?>
<html>
    <br>Click Add Column to begin<br>
    <input type="submit" name="submit" value="Submit">
    <table style="display: inline" id="main">
        <tr id="headercol">
            <td></td>
        </tr>
        <tR id="row[1]">
        </tR>
    </table>
    <a style="display: inline" href="#" onclick="addCol()">Add Game Values</a><br>
    <a href="#" style="display: none" id="addrow" onclick="addRow()">Add Zone Name</a>
</form>
<script type="text/javascript">
    var row = [];
    var rows = 1;
    var col = [];
    
    function addRow(){
        var name = prompt("Zone Name");
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
            add2.innerHTML = '<input type="text" name="cell['+row[row.length-1]+']['+col[i]+']">';
            add.appendChild(add2);
        }
        document.getElementById('main').appendChild(add);
    }
    
    function addCol(){
        var name = prompt("Game Value");
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
            var name = prompt("Zone Name");
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