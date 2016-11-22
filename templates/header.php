<!DOCTYPE html>
<html lang="en">
    <head>
		<?php $base = "Kartees/"; ?>
        <meta charset="utf-8">
        <title>Welcom to Kartees</title>
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <link rel="shortcut icon" href="flat-ui/images/favicon.ico">
        <link rel="stylesheet" href="flat-ui/bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="flat-ui/css/flat-ui.css">
        <!-- Using only with Flat-UI (free)-->
        <link rel="stylesheet" href="common-files/css/icon-font.css">
        <!-- end -->
        <link rel="stylesheet" href="css/style.css">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
					<style>
.ticket-image { 
   position: relative; 
   width: 100%; /* for IE 6 */
   margin: 10px;
   font-size:23px;
   font-weight: bold
}

.ticket-image .year { 
   position: absolute; 
   top: 8%; 
   left: 80%; 
   width: 100%;
   color: white;
}
.ticket-image .team { 
   position: absolute; 
   top: 40%; 
   left: 5%; 
   width: 100%; 
}
.ticket-image .sport { 
   position: absolute; 
   top: 8%; 
   left: 5%; 
   width: 100%;
   color: white;
}
.ticket-image small{
	font-size: 15px;
	color: grey;
}
.teams-top-bar{
	background-color: #3498DB;
	padding: 10px;
	border-top-left-radius: 5px;
	border-top-right-radius: 5px;
	color: white;
	margin-right: 5px;
	float: left;
}
.teams-top-bar:hover{
	padding-bottom: 15px;
	margin-top: -5px;
	color: white;
	background-color: #2980B9;
}
.teams-top-bar.current{
	padding-bottom: 15px;
	margin-top: -5px;
	color: white;
	background-color: #2980B9;
}

			</style>
					
		<style>
								#customCal .nonActive{
										display:none;
								}
						</style>			
					
					

    </head>

    <body>
        <div class="page-wrapper">
            <!-- header-2 -->
            <header class="header-2">
                <div class="container">
                    <div class="row">
                        <div class="navbar col-sm-12 navbar-fixed-top" role="navigation">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle"></button>
                                <a class="brand" href="<?php
										
		if($id = getSessionId($_SESSION['auth'])){
			echo "myaccount.php";
		} else {
			 echo 'index.php';
		}
										

	?>"><img src="img/logo@2x.png" width="50" height="50" alt=""> Kartees</a>
                            </div>
                            <div class="collapse navbar-collapse">
                                <ul class="nav pull-right">
										<?php
										
		if($id = getSessionId($_SESSION['auth'])){
			echo "<li><a href='myaccount.php'>Welcome, ".getNameFromId($id)."!</a></li>";
			echo "<li><a href='login.php?Logout=1'>Logout</a></li>";
		} else {
			 echo '<li>
                                        <a href="login.php">Login</a>
                                    </li>
                                    <li>
                                        <a href="signup.php">Signup</a>
                                    </li>';
		}
										

	?>
                                    
                                    <li>
                                        <a href="#">Blog</a>
                                    </li>
                                    <li>
                                        <a href="#">Contact</a>
                                    </li>
                                </ul>
                                <ul class="subnav">
                                    <li>
                                        <a href="#">Privacy</a>
                                    </li>
                                    <li>
                                        <a href="#">Terms</a>
                                    </li>
                                    <li>
                                        <a href="#">Advertise</a>
                                    </li>
                                    <li>
                                        <a href="#">Affiliates</a>
                                    </li>
                                    <li>
                                        <a href="#">Newsletter</a>
                                    </li>
                                    <li>
                                        <a href="#">About</a>
                                    </li>
                                    <li>
                                        <a href="#">Contact Us</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <section class="header-2-sub bg-midnight-blue" style="padding-top: 150px; padding-bottom: 0px;">
                
                
            </section>
