<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php?message.php');
    exit;
}

if (!isset($_GET['id'])) { //if no id is sent along then exit because error
    header("location: index.php");
    exit();
}
require_once("Includes/db.php");
$query = $_GET['id'];
$user = VerticalDB::getInstance()->get_user_by_user_id($query);
$recipient = true;
if (!isset($user)) {
    $recipient = false;
}
$id = "";
$name = "";
$user_name = "";
$email = "";
$location = "";
while ($row = mysqli_fetch_array($user)) {
    $id = htmlentities($row['id']);
    $name = htmlentities($row['first_name']);
    $name .= ' ' . htmlentities($row['last_name']);
    $user_name = ($row['username']);
    $email = htmlentities($row['email']);
    $location = htmlentities($row['location']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['writeMessage'] != ""){
        if(VerticalDB::getInstance()->send_mail($_SESSION['user'], $query, "STANDARD", $_POST['writeMessage'])){
            //sends current user's id, the recipient id, the type of message and the body
             header("location: home.php");
             exit();
        } 
    }
}
mysqli_free_result($user);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <?php
        $title = "message"; // ADD PAGE TITLE HERE
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
                    <h1 >Compose Message</h1>
                    <h1></h1>
                    <form action="" method="post">
                        <ul>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="to">To:</label>
                                    <input type="text" class="form-control" id="to" <?php echo $recipient ? 'disabled placeholder="' . $user_name . '"' : ""; ?>>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-8">
                                    <label for="postDescription">Write Message Here:</label>
                                    <textarea class="form-control" id="writeMessage" rows="9" name="writeMessage"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <input type="submit" name="sendMessage" value="Send Message" style="position: absolute; left: 500px; top: 400px;"/>
                                <input type="button" name="cancel" value="Cancel" style="position: absolute; left: 80px; top: 400px;" onclick="history.back();"/>

                            </div>
                        </ul>
                    </form>

                </div>
            </div>

            <div id="rightside"  class="col-xs-2">
                <?php include "Includes/rightnav.php"; ?>
            </div>

        </div>

        <div id="footer">
            <div class="container">
                <p class="muted credit"><a class="btn"  href="contact.php" disabled>Contact Us</a>  <a class="btn" href="privacy.php" disabled>Privacy Policy</a></p>
            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <!-- PLACE SCRIPTS THAT REQUIRE JQUERY OR BOOTSTRAP HERE AND LOWER-->
        <script src="js/main.js"></script>
    </body>

</html>
