<?php
require("database.php");
require("viewTickets.php");
require("calendar.php");
require("Tickets/Package.php");
require("Tickets/Seats.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
$Ticket->menu();

$Seats = new Seats($pid);
?>
<div class="col-sm-5">
   Number of Seats in This Package: <?= count($Seats->getSeats()) ?>
</div>
<div class="col-sm-7">
   Number of Sold Tickets <?= count($Seats->getSoldListings()) ?>
</div>

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
$FOOTER_CONTENT = "";
include("templates/footer.php");
?>