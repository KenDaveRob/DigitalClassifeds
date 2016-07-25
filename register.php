<?php
session_start();
if (isset($_SESSION['user'])) {
    header('Location: home.php');
    exit;
}
require_once("Includes/db.php");
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST as $field)
        if ($field == "")
            $message .= $field . " can not be left empty\n";

    $email = $_POST['email'];
    $result = null;
    $result = VerticalDB::getInstance()->get_user_by_email($email);

    if ($result) {
        $row = mysqli_fetch_array($result);
        if ($row['id'])
            $message .= "Email is already taken.";
    }
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    if ($password2 != $password)
        $message .= "Passwords must match";

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $location = $_POST['location'];
    $username = $_POST['username'];
    $secretquestion = $_POST['secretquestion'];
    $secretanswer = $_POST['secretanswer'];
    $result = null;
    if ($message == "") {
        $result = VerticalDB::getInstance()->save_user($first_name, $last_name, $username, $password, $email, $location, $secretquestion, $secretanswer);
        $loggedin = VerticalDB::getInstance()->login($email, $password);
        if ($loggedin) {
            $_SESSION['user_email'] = $email;
            $_SESSION['user'] = $result;
            header('Location: home.php');
            exit;
        }
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <?php
        $title = "Sign Up"; // ADD PAGE TITLE HERE
        include('Includes/navbar.php');
        include('Includes/head.php');
        ?>


        <script  type="text/javascript">

            function validatelogin(form)
            {
                var Allfieldsarefilled = true;
                var lettersonlylogin = /^[a-zA-Z]+$/;
                var alphanum = /^[a-zA-Z0-9]+$/;
                var username = document.forms["form-horizontal"]["username"].value;
                var userpasswd = document.forms["form-horizontal"]["password"].value;
                var userpasswd2 = document.forms["form-horizontal"]["password2"].value;
                var userfirstname = document.forms["form-horizontal"]["first_name"].value;
                var userlastname = document.forms["form-horizontal"]["last_name"].value;
                var useremail = document.forms["form-horizontal"]["email"].value;
                var userlocation = document.forms["form-horizontal"]["location"].value;
                var usersecretquestion = document.forms["form-horizontal"]["secretquestion"].value;
                var usersecretanswer = document.forms["form-horizontal"]["secretanswer"].value;
                var terms = document.forms["form-horizontal"]["terms"].checked;



                if ((username === null || username === "") || (userpasswd === null || userpasswd === "")
                        || (userpasswd2 === null || userpasswd2 === "")
                        || (userfirstname === null || userfirstname === "")
                        || (userlastname === null || userlastname === "")
                        || (useremail === null || useremail === "")
                        || (usersecretanswer === null || usersecretanswer === "")
                        || (userlocation == "Select your city")
                        || (usersecretquestion == "Select your secret question"))
                {
                    alert("Every field must be filled out");
                    document.getElementById("Textboxfirstname").focus();

                    Allfieldsarefilled = false;

                    return false;
                }

                if (Allfieldsarefilled && (userpasswd !== userpasswd2))
                {
                    alert("Password must be the same");
                    document.getElementById("Textboxpwd").focus();



                    return false;
                }


                if (Allfieldsarefilled && !terms)
                {
                    alert("Terms must be accepted");
                    document.getElementById("terms").focus();



                    return false;
                }


                if ((username === null || username === "") || (userpasswd === null || userpasswd === ""))
                {
                    alert("Login and password must be filled out");
                    //window.location.reload();

                    document.getElementById("Textboxusername").focus();
                    return false;
                }
                if (lettersonlylogin.test(username) && alphanum.test(userpasswd))
                {

                    if (username.length < 5)
                    {
                        alert("user name must be at least 5 characters!");
                        document.getElementById("Textboxusername").focus();
                        return false;
                    }
                    else if (userpasswd.length < 6)
                    {
                        alert("password must be at least 6 characters!");
                        //window.location.reload();
                        document.getElementById("Textboxpwd").focus();
                        return false;
                    }
                }

                else if (!lettersonlylogin.test(username))
                {
                    alert(" letters only are required for login!", username.value);
                    document.getElementById("Textboxusername").focus();
                    return false;
                }

                if (userpasswd.match(/[^0-9a-z]/i))
                {
                    alert("Only letters and digits allowed for password!");
                    document.getElementById("Textboxpwd").focus();
                    return false;
                }
                else if (!userpasswd.match(/\d/))
                {
                    alert("At least one digit required for password!");
                    document.getElementById("Textboxpwd").focus();
                    return false;
                }
                else if (!userpasswd.match(/[a-z]/i))
                {
                    alert("At least one letter required for password!");
                    document.getElementById("Textboxpwd").focus();
                    return false;
                }


                return true;
            }

        </script>
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
                    <h1>Sign Up</h1>
                    <!-- Begin page content -->
                    <?php if ($message != ""): ?>
                        <span style="color: red;"><?php printf($message); ?></span>
                    <?php endif; ?>
                    <form name="form-horizontal" role="form" class="form-horizontal" action="register.php" onsubmit="return validatelogin(this)" method="post">
                        <div class="form-group">                             
                            <label class="col-md-2" for="first_name">First Name</label>          
                            <input class="col-md-2 " type="text" id="Textboxfirstname" name="first_name" id="first_name" placeholder="First Name" required>    
                        </div>
                        <div class="form-group">

                            <label class="col-md-2 " for="last_name">Last Name</label>
                            <input class="col-md-2 " type="text" name="last_name" id="last_name" placeholder="Last Name" required>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 "  for="username">Username</label>
                            <input class="col-md-2 " type="text" id="Textboxusername" name="username" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 "  for="password">Password</label>
                            <input class="col-md-2 " type="password" id="Textboxpwd" name="password" placeholder="Password" required>

                        </div>
                        <div class="form-group">
                            <label class="col-md-2 "  for="password">Confirm Password</label>
                            <input class="col-md-2 " type="password" name="password2" placeholder="Confirm password" required>

                        </div>
                        <div class="form-group">
                            <label class="col-md-2 "  for="email">Email address</label>
                            <input class="col-md-2 " type="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 "  for="location">Location</label>
                            <select class="col-md-2 " type="text" name="location" required>
                                <option>Select your city</option><option>Alameda</option><option>Albany</option><option>American Canyon</option>
                                <option>Antioch</option><option>Belmont</option><option>Belvedere</option>
                                <option>Benicia</option><option>Berkeley</option><option>Brentwood</option>
                                <option>Brisbane</option><option>Burlingame</option><option>Calistoga</option>
                                <option>Campbell</option><option>Clayton</option><option>Cloverdale</option>
                                <option>Concord</option><option>Cotati</option><option>Cupertino</option>
                                <option>Daly City</option><option>Dixon</option><option>Dublin</option>
                                <option>East Palo Alto</option><option>El Cerrito</option><option>Emeryville</option>
                                <option>Fairfield</option><option>Foster City</option>
                                <option>Fremont</option><option>Gilroy</option>
                                <option>Half Moon Bay</option><option>Hayward</option><option>Healdsburg</option>
                                <option>Hercules</option><option>Lafayette</option><option>Larkspur</option>
                                <option>Livermore</option><option>Los Altos</option><option>Martinez</option>
                                <option>Menlo Park</option><option>Mill Valley</option><option>Millbrae</option>
                                <option>Milpitas</option><option>Monte Sereno</option><option>Morgan Hill</option>
                                <option>Mountain View</option><option>Napa</option><option>Newark</option>
                                <option>Novato</option><option>Oakland</option><option>Oakley</option>
                                <option>Orinda</option><option>Pacifica</option><option>Palo Alto</option>
                                <option>Petaluma</option><option>Piedmont</option><option>Pinole</option>
                                <option>Pittsburg</option><option>Pleasant Hill</option><option>Pleasanton</option>
                                <option>Redwood City</option><option>Richmond</option><option>Rio Vista</option>
                                <option>Rohnert Park</option><option>San Bruno</option><option>San Carlos</option>
                                <option>San Francisco</option><option>San Jose</option><option>San Leandro</option>
                                <option>San Mateo</option><option>San Pablo</option><option>San Rafael</option>
                                <option>San Ramon</option><option>Santa Clara</option><option>Santa Rosa</option>
                                <option>Saratoga</option><option>Sausalito</option><option>Sebastopol</option>
                                <option>Sonoma</option><option>South San Francisco</option><option>St. Helena</option>
                                <option>Suisun City</option><option>Sunnyvale</option><option>Union City</option>
                                <option>Vacaville</option><option>Vallejo</option><option>Walnut Creek</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 "  for="location">Secret question</label>
                            <select type="text" class="col-md-3" style="margin-right: 5px; line-height: inherit;" name="secretquestion" required>
                                <option>Select your secret question</option>
                                <option value='maiden'>What is your mothers maiden name?</option>
                                <option value='pet'>What is your first pet's name?</option>
                                <option value='hospital'>What is your birth hospital?</option>
                            </select>
                            <input type="text" name="secretanswer" required />
                        </div>
                        <div class="form-group">

                            <label class="checkbox">
                                <input type="checkbox" name="terms" id="terms" required> I agree to the <a href="privacy.php">term and services.</a>
                            </label> 

                            <label class="checkbox">
                                <input type="checkbox" name="email_updates"> Email me updates.
                            </label>
                        </div>
                        <div class="row">
                            <button type="submit" class="btn">Register</button>  
                        </div>
                    </form>
                </div>
                <div id="rightside"  class="col-xs-2" >
                    <?php include "Includes/rightnav.php"; ?>
                </div>

            </div>
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
