<?php
session_start();
require_once("Includes/db.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <?php
        $title = null; // ADD PAGE TITLE HERE
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
                        <h1><!-- PAGE DESCRIPTION OR TITLE --></h1>
                    </div>
                    <!-- Begin page content -->

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
