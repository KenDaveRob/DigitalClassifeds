<?php

require_once("Includes/db.php");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$username = "";
$email = "";
if (isset($_SESSION['user'])) {
    $result = VerticalDB::getInstance()->get_user_by_user_id($_SESSION['user']);
    if ($result) {
        $row = mysqli_fetch_array($result);

        $username = $row["username"];
        $email = $row["email"];
    }
}

$navbar = '<nav class="navbar navbar-inverse navbar-static-top" role="navigation">
              <div class="container">
                <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".nav-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                          </button>
                        <a class="navbar-brand" href="index.php">Digital Classifieds</a>
                  </div>
                  <div class="collapse navbar-collapse ">
                            <ul class="nav navbar-nav pull-left">';
//$navbar .= ($title == 'About') ? '<li class="active"><a href="about.php">About</a></li>' : '<li><a href="about.php">About</a></li>';
$navbar .= ($title == 'Browse') ? '<li class="active"><a href="browse.php">Browse</a></li>' : '<li><a href="browse.php">Browse</a></li>';
$navbar .= ($title == 'Sell Item') ? '<li class="active"><a href="PostAd.php">Sell Item</a></li>' : '<li><a href="PostAd.php">Sell Item</a></li>';
$navbar .= '</ul><ul class="nav navbar-nav pull-right">';
$navbar .= ($username != "") ? '<li><a href="home.php"><span class="glyphicon glyphicon-user"></span> ' . $username . '</a></li><li><a href="logout.php"><small>Logout</small></a></li>' : '<li><a href="login.php">Login</a></li>';
//$navbar .= ($username != "") ? '<li><a href="home.php" style="border-left: #fff solid 2px;">' . $username . '</a></li>' : '<li><a href="login.php">Login</a></li>';
$navbar .= '</ul>        
                        </div><!--/.nav-collapse -->
                   </div><!--/container-->
                </nav>';

