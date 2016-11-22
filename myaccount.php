<?php
require("database.php");
require("viewTickets.php");
		session_start();
        if($id = getSessionId($_SESSION['auth'])){
			$loggedIn = true;
		} else {
			header('Location: login.php');
		}
										
include('templates/header.php');
               ?>
            <!-- content-10  -->
		<section class="content-3" style="padding: 50px">
          <div>
            <div class="container">
              <div class="row">
	<?php if(hasTicket($id)){ ?>

				
				<?php
				$tickets = getPackages($id);
				foreach($tickets as $ticket){
					
					$team = getTeam($ticket['Team']);?>
					<a href="ticket.php?pid=<?php echo $ticket['Package_Id']; ?>">
						<div class="col-sm-6">
						  <div class="img ticket-image">
							<img src="images/ticket@2x.png" alt="">
							<span class="sport"><?php echo $team['sport']." Season Ticket"; ?></span>
							<span class="year"><?php echo $ticket['Season_Year']; ?></span>
							<span class="team"><?php echo $team['City']." ".$team['Team_Name']; ?><br><small>Team</small></span>
						  </div>
						</div>
					</a>
				<?php } ?>
					<a href="addSeasonTicket.php">
						<div class="col-sm-6">
						  <div class="img ticket-image">
							<img src="images/ticket@2x.png" alt="" style="opacity: .2">
							<span style="position: absolute; top: 45%; left: 35%">Add Package</span>
						  </div>
						</div>
					</a>

              </div>
            </div>
          </div>
        </section>
	<?php } else { ?>
                <div class="col-sm-6 aligment">
                  <h3>It looks like you haven't added any team season packages yet</h3>
                  <p>
                    <a class="btn btn-inverse" href="addSeasonTicket.php">Add Package</a>
                  </p>
                </div>
                <div class="col-sm-6">
                  <div class="img">
                    <img src="common-files/img/content/ticket-green@2x.png" alt="">
                  </div>
                </div>

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
<?php include("templates/footer.php");