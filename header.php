<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="row">
            <div class="navbar-header col-md-6">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#">Kartees</a>
            </div>
            <div class="navbar-header col-md-2 text-right">
                <?php
                    	if($id = getSessionId($_SESSION['auth'])){
                            echo "Logged in as ".getNameFromId($id, True);
                        } else {
                            echo "<div>
				<a href=\"login.php\">Login</a>
				<a href=\"signup.php\">Signup</a>
			</div>";
                        }
                ?>
            </div>

        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">About Us</a></li>
            <li><a href="#">Contact Us</a></li>
            <li><a href="#">My Account</a></li>
          </ul>
        </div>
      </div>
    </nav>