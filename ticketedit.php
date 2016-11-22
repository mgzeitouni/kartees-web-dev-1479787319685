<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require("database.php");
require("viewTickets.php");
require("Tickets/Package.php");
require("Tickets/Seats.php");



session_start();
if($id = getSessionId($_SESSION['auth'])){
	$loggedIn = true;
} else {
	header('Location: login.php');
}
$user = $id;
include('templates/header.php'); 


if(hasTicket($id)){
	$tickets = getPackages($id);
	foreach($tickets as $ticket){
		if($_GET['pid']==$ticket['Package_Id']){
		        $auth = True;
			$pid = $_GET['pid'];
		}
	}
}


if(isset($_POST['delete']) && $_POST['delete'] == 'delete'){
	disablePackage($pid);
}

$Ticket = new Package($user, $pid);
$Seats = new Seats($pid);

?>

	
<section class="content-3" style="padding: 50px">
	<div>
		<div class="container">
			<div class="row">
				<?php $Ticket->menu(); ?>	
				<div style="margin: 0 auto; width: 75%; font-size: 15px;">
					<span style='text-align:center; font-size:20px; font-weight:bold'>
						<?= $Ticket->getPackageTeamCity() ?>&nbsp;
						<?= $Ticket->getPackageTeamName() ?>&nbsp;
						<?= $Ticket->getPackageYear() ?>&nbsp;Package
					</span><br>
					Sport: <?= $Ticket->getPackageSport() ?> <br>
					Section: <?= $Ticket->getPackageSection() ?> <br>
					Price: <?= $Ticket->getPackagePrice() ?> <br>
					Seats: <?= $Ticket->getPackageQty() ?> <br>
					<?php
						$i = 1;
						foreach($Seats->getSeats() as $seat){
							echo "Seat ".$i.":  Row: ".$seat['Row']."<br>";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Seat Number: ".$seat['Seat_Num']."<br>";
							$i++;
						}
					?>
					<button id="deletePackage" class="btn btn-lge btn-danger">Delete Package</button>
				</div>
			</div>
		</div>
	</div>
</section>
	    
<?php
$FOOTER_CONTENT = "
<script>
$('#deletePackage').click(function(){
		 $.ajax({
			type: 'POST',
			url: 'ticketedit.php',
			data: \"delete=delete&pid=".$pid."\",
			success: function(data){ window.open('myaccount.php','_self');},
			dataType: 'text'
	      });
})
</script>
"; 
include("templates/footer.php");
?>