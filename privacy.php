<?php
session_start();
require_once("Includes/db.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <?php
        $title = "Privacy"; // ADD PAGE TITLE HERE
        include('Includes/navbar.php');
        include('Includes/head.php');
        ?>
        <style type="text/css"></style>
    </head>
    <body>
        <div id="wrap">

            <!-- Fixed navbar -->
            <?php echo $navbar; ?>

            <div id="leftside"  class="col-xs-2">
                <?php include "Includes/leftnav.php"; ?>
            </div>
            <div class="container col-md-8" >
                <div id="push"></div>
                <div>
                    <div class="page-header">
                        <h1>Privacy Policy</h1>
                    </div>
                    <!-- Begin page content -->
                    <div class="row">
			<ul>
				<li>Your email and personal information shall not be disclosed</li><li>
				Your password shall be protected
				</li>			
			</ul>
                    </div>
                    <div class="page-header">
                        <h1>Posting Guidelines</h1>
                    </div>
                    <div class="row">
			<ul>
				<li>Lewd images and language is not allowed in posting</li><li>
				Copyrighted images are not allowed to be uploaded.
				</li>
				<li>Postings will be approved by admin before appearing live on site</li>
                    </div>
			
                </div>
            </div>
            <div id="rightside"  class="col-xs-2">
                <?php include "Includes/rightnav.php"; ?>
            </div>

        </div>
        <div id="footer">
            <div class="container">
                <p class="muted credit"><a class="btn"  href="about.php">Contact Us</a>  <a class="btn" href="privacy.php">Privacy Policy</a></p>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <!-- PLACE SCRIPTS THAT REQUIRE JQUERY OR BOOTSTRAP HERE AND LOWER-->
        <script src="js/main.js"></script>
    </body>

</html>
