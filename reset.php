<?php
session_start();
require_once("Includes/db.php");
if (!isset($_SESSION['user']))
    $loggedin = false;
$message = "";
// verify user's credentials
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $secretquestion = $_POST['secretquestion'];
    $secretanswer = $_POST['secretanswer'];

    if ($email == "")
        $message = "Enter a valid email";
    if ($password == "")
        $message = "Enter a valid password";
    if ($secretquestion == "Select your secret question")
        $message = "Select your secret question";
    if ($secretanswer == "")
        $message = "Enter a valid answer";

    if ($message == "") {
        $reset = VerticalDB::getInstance()->reset_password($email, $secretquestion, $secretanswer, $password);
        if ($reset) {
            $reset = VerticalDB::getInstance()->reset_password($email, $password);
            $_SESSION['user_email'] = $email;

            $_SESSION['user'] = $loggedin;
            header('Location: home.php');
            exit;
        } else {
            $message = "<em style=\"color:red;\"class=\"help-block\">Password or email is incorrect.</em>";
        }
    }
}

if (isset($_SESSION['user'])) {
    $loggedin = true;
    header('Location: home.php');
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Tr ansitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <?php
        $title = "Login"; // ADD PAGE TITLE HERE
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
                    <h1>Password reset</h1>
                    <!-- Begin page content -->
                    <form role="form" class="col-md-4" action="login.php" method="post">
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
                            <?php echo $message; ?>
                        </div>
                        <div class="form-group">
                            <label for="location">Secret question</label>
                            <select type="text" class="form-control"  name="secretquestion" required>
                                <option value="Select your secret question">Select your secret question</option>
                                <option value='maiden'>What is your mothers maiden name?</option>
                                <option value='pet'>What is your first pet's name?</option>
                                <option value='hospital'>What is your birth hospital?</option>
                            </select>
                            <input type="text" class="form-control"  name="secretanswer" placeholder="Secret answer"required />
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                    </form>
                    <a href="register.php">Sign up</a>
                </div>
            </div>

            <div id="rightside"  class="col-xs-2">
                <?php include "Includes/rightnav.php"; ?>
            </div>
            <div id="footer">
                <div class="container">
                    <p class="muted credit"><a class="btn"  href="about.php" >Contact Us</a>  <a class="btn" href="privacy.php" >Privacy Policy</a></p>
                </div>
            </div>

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
            <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
            <!-- PLACE SCRIPTS THAT REQUIRE JQUERY OR BOOTSTRAP HERE AND LOWER-->
            <script src="js/main.js"></script>
    </body>

</html>
