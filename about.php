<?php
session_start();
require_once("Includes/db.php");

if($_SERVER['REQUEST_METHOD']=='POST'){
    $message = $_POST['contactMessage'];
    VerticalDB::getInstance()->message_admin($message);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <?php
        $title = "About"; // ADD PAGE TITLE HERE
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
                        <h1>About Us</h1>
                    </div>
                    <!-- Begin page content -->
                    <div class="row">
                        <div class="col-lg-6">
                            Digital Classifieds brings the newspapers classifieds section to you! A SFSU Software Engineering Project, Spring 2014 that is for demonstration only, Digital Classifieds was built by Naimah Mumin, Kenneth Robertson, Xuning Tian, John Rittweger and Claude Boulingui.
                        </div>
                        <div class="col-lg-6">
                            <a class='btn btn-default btn-sm pull-right'data-toggle="modal" data-target="#contactModal" >
                                <span class='glyphicon glyphicon-envelope'></span>Contact Us
                            </a>
                            Shoot us an email to contact us. 
                        </div>
                    </div>
                </div>
            </div>
            <div id="rightside"  class="col-xs-2">
                <?php include "Includes/rightnav.php"; ?>
            </div>

        </div>
        <div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="denyModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="about.php" method="post" class="contactForm">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel">Contact us:</h4>
                        </div>
                        <div class="modal-body">
                            <div class="list-group">
                                <textarea class="form-control" rows="10" style="resize: none;" name="contactMessage" id="contactMessage"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal" id="close">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="submitButton" >Send</button>
                        </div>
                    </form>
                </div>
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
