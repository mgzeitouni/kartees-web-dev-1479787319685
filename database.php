<?php
		/*global $name = "kartees1";
		static $jdbcUrl = "jdbc:mysql://us-cdbr-iron-east-03.cleardb.net/ad_52063f30b709ce5?user=b3c48395bc1a72&password=facf79d9";
        static $uri = "mysql://b3c48395bc1a72:facf79d9@us-cdbr-iron-east-03.cleardb.net:3306/ad_52063f30b709ce5?reconnect=true";
        static $db_name = "ad_52063f30b709ce5";

*/
		date_default_timezone_set('UTC');
			global $conn;
			$conn = new mysqli("us-cdbr-iron-east-03.cleardb.net", "b3c48395bc1a72", "facf79d9", "ad_52063f30b709ce5");
			
			if ($conn->connect_error) {
			    die("Connection failed: " . $conn->connect_error);
			} 

		function getTable($table, $specific_row = "*"){
			global $conn;
			$query = "SELECT ".$specific_row." FROM `".$table."`";
			$print = $conn->query($query);
			while($a = $print->fetch_assoc()){
				if($specific_row != "*"){
					$data[] = $a[$specific_row];
				} else{
					$data[] = $a;
				}
			}
			return $data;
		}
		
		function getTableSearch($table, $specific_row = "*", $row, $value){
			if($table && $specific_row && $row && $value){
				global $conn;
				$query = "";
				$query = "SELECT ".$specific_row." FROM `".$table."` WHERE ".$row." = '".$value."'";
				//echo $query;
				$print = $conn->query($query);
				while($a = $print->fetch_assoc()){
					if($specific_row != "*" && strpos($specific_row, ',') === false){
						$data[] = $a[$specific_row];
					} else{
						$data[] = $a;
					}
				}
				return $data;
			} else {
				die("sql search error");	
			}
		}
		
		
		
		function dbSearch($table, $row, $value){
			$query = run("SELECT * FROM `$table` WHERE $row = '$value'");
			if($query){
				return $query;
			} else {
				return False;
			}
		}
		function dbUpdate($table, $searchrow, $searchvalue, $updaterow, $updatevalue){
			echo "$searchvalue";
			$query = "UPDATE `$table` SET $updaterow='$updatevalue' WHERE $searchrow='$searchvalue'";
			$print = run($query);
			//echo $query;
			if($print){
				echo $print;
			} else {
				print False;
			}
		}
		function dbInsert($table, $keys, $values, $id=false){
			global $conn;
			$query = "INSERT INTO `$table` ";
			$query .= "(".implode(", ", $keys).")";
				foreach($values as $value){
					$values2[] = "'".$value."'";
				}
			$query .= " VALUES (".implode(', ', $values2).")";
			//echo $query;
				//$resultat = $conn->query($query);
				if ($conn->query($query) === TRUE) {
					if($id) {
						return $conn->insert_id;
					} else {
						return true;
					}
				} else {
					echo "dbinsert Error: " . $sql . "<br>" . $conn->error;
				}
		}
		function dbDelete($table, $row, $id){
			$query = "DELETE FROM `$table` WHERE $row = '$id'";
			run($query);
		}
		function run($query, $insert = false){
			global $conn;
			$print = $conn->query($query) or die("Error: ".mysqli_error($conn));
			if($insert){
				//printf("Error: %s\n", mysqli_error($conn));
				//exit();
				return $print;
			} else {
				//printf("Error: %s\n", mysqli_error($conn));
				return mysqli_fetch_array($print);
			
			}
		}
		function getNameFromId($id, $full = False){
			$data = dbSearch('login', 'ID', $id);
			if($full)
				return $data['Fname']." ".$data['Lname'];
			else
				return $data['Fname'];
		}
		
		
		function deleteSessionId(){
			dbDelete("sessions", "session_hash", $_SESSION['auth']);
		}
		function getSessionId($id){
			if($data = dbSearch("sessions", "session_hash", $id)){
				return($data['user_id']);
			} else {
				return False;
			}
		}
		function putSessionId($userid){
			$hash = md5(microtime());
			$keys = array('user_id','session_hash','date');
			$values = array($userid, $hash,date('m|d|Y'));
			dbInsert("sessions", $keys, $values);
			$_SESSION['auth'] = $hash;
		}


