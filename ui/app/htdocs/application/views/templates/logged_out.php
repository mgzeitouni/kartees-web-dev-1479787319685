<!DOCTYPE html>
<html lang="en">
    <head>
		
        <meta charset="utf-8">
        <title>Welcom to Kartees</title>
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <link rel="shortcut icon" href="<?= $this->config->item('base_url') ?>/flat-ui/images/favicon.ico">
        <link rel="stylesheet" href="<?= $this->config->item('base_url') ?>/flat-ui/bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="<?= $this->config->item('base_url') ?>/flat-ui/css/flat-ui.css">
        <!-- Using only with Flat-UI (free)-->
        <link rel="stylesheet" href="<?= $this->config->item('base_url') ?>/common-files/css/icon-font.css">
        <!-- end -->
        <link rel="stylesheet" href="<?= $this->config->item('base_url') ?>/css/style.css">
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
                                <a class="brand" href=""><img src="img/logo@2x.png" width="50" height="50" alt=""> Kartees</a>
                            </div>
                            <div class="collapse navbar-collapse">
                                <ul class="nav pull-right">
										
			<li><a href='<?= $this->config->item('base_url') ?>/login'>Login</a></li>
			<li><a href='<?= $this->config->item('base_url') ?>/signup'>Signup</a></li>
		
                                    
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


            <?= $contents ?>


            <footer class="footer-1 bg-midnight-blue">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-5">
                            <p class="lead">
                                <b>200,000</b> users registered since January
                            </p>
                            <div class="social-btns">
                                <a href="#" class="social-btn-facebook" data-text="Startup Design Framework - http://designmodo.com/startup/ Suit Up your Startup!" data-url="http://designmodo.com/startup/">
                                    <div class="fui-facebook"></div>
                                    <div class="fui-facebook"></div>
                                </a>
                                <a href="#" class="social-btn-twitter" data-text="Startup Design Framework - http://designmodo.com/startup/ Suit Up your Startup!" data-url="http://designmodo.com/startup/">
                                    <div class="fui-twitter"></div>
                                    <div class="fui-twitter"></div>
                                </a>
                            </div>
                        </div>
                        <nav>
                            <div class="col-sm-2 col-sm-offset-1">
                                <h6>About</h6>
                                <ul>
                                    <li>
                                        <a href="#">About Us</a>
                                    </li>
                                    <li>
                                        <a href="#">Blog</a>
                                    </li>
                                    <li>
                                        <a href="#">Team</a>
                                    </li>
                                    <li>
                                        <a href="#">Career</a>
                                    </li>
                                    <li>
                                        <a href="#">Contact</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-sm-2">
                                <h6>Follow Us</h6>
                                <ul>
                                    <li>
                                        <a href="#">Facebook</a>
                                    </li>
                                    <li>
                                        <a href="#">Twitter</a>
                                    </li>
                                    <li>
                                        <a href="#">Instagram</a>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                        <div class="col-sm-2 buy-btn">
                            <a class="btn btn-danger btn-block" href="#">Buy App</a>
                            or <a href="#">Learn More</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5 additional-links">
                            <a href="#">Terms of Service</a>
                            <a href="#">Special Terms</a>
                            <a href="#">Privacy Policy</a>
                        </div>
                    </div>
                </div>
            </footer>
        

        <!-- Placed at the end of the document so the pages load faster -->
        <script src="<?= $this->config->item('base_url') ?>/flat-ui/js/bootstrap-select.js"></script>
        <script src="<?= $this->config->item('base_url') ?>/common-files/js/jquery-1.10.2.min.js"></script>
        <script src="<?= $this->config->item('base_url') ?>/flat-ui/js/bootstrap.min.js"></script>
        <script src="<?= $this->config->item('base_url') ?>/common-files/js/modernizr.custom.js"></script>
        <script src="<?= $this->config->item('base_url') ?>/common-files/js/jquery.sharrre.min.js"></script>
        <script src="<?= $this->config->item('base_url') ?>/common-files/js/startup-kit.js"></script>
        <script src="<?= $this->config->item('base_url') ?>/js/script.js"></script>
        
        
        							
    </body>
</html>