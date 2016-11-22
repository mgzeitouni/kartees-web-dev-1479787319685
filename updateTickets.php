<?php
require_once 'viewTickets.php';
if($_POST['updateActive'] == "ALL"){
	updateListingsStatus($_POST['pid'], $_POST['active']);
	//header('Location: ticket.php?pid='.$_POST['pid']);
	$pid = $_POST['pid'];
	$team = $_POST['team'];
	echo "
			 <script> var $ = jQuery.noConflict();
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
						  success: function(data){ $('#calscript' ).html(data); },
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
						  success: function(data){ $('#calscript' ).html(data); },
						  dataType: 'text'
						});
			        }
			    }
		    },
			header: {
				left: 'prev,next today, listALL, delistALL',
				center: '',
                right: 'title'
			},
			defaultDate: '".date('Y-m-d')."',
			editable: true,
			eventLimit: true, // allow \"more\" link when too many events
			events: ".getGames($team, $pid).",
			eventClick: function(calEvent, jsEvent, view) {

				//alert('Event: ' + calEvent.title);
				//alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
				//alert('View: ' + view.name);
				confirm('do you want to sell this ticket?');
				
				// change the border color just for fun
				//$(this).css('border-color', 'red');

			}
		});
		
			</script>
			";
} else if($_POST['updateActive'] == "IND"){
	$pids = $_POST['pid'];
	$actives = $_POST['active'];
	$lids = $_POST['lid'];
	updateListingsStatus($pid, $active, $lid);
	//header('Location: ticket.php?pid='.$_POST['pid']);
	$pid = $_POST['pid'];
	$team = $_POST['team'];
} else if($_POST['updateSelected'] == "TRUE"){
	$pid = $_POST['pid'];
	$data = $_POST;
	//echo "<PRE>";
	//print_r($data);
	$newarr = array_diff_assoc($data['game'], $data['curr']);
	//print_r($newarr);
	foreach($newarr as $key=>$state){
		switch($state){
			case "on":
				$state = "4";
				break;
			case "off":
				$state = "3";
				break;
		}
		//echo $key." ".$state."<br>";
		updateListingStatus($state, $key);
	}
	header('Location: ticket.php?pid='.$pid.'&view=list');
}