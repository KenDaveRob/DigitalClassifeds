<?php
session_start();
require_once("Includes/db.php");

if (!isset($_GET['id'])) { //if no id is sent along then exit because error
    header("location: index.php");
    exit();
}

$query = $_GET['id'];


$belongs_to_user = false;
$admin_options = false;
$item = VerticalDB::getInstance()->get_item_by_item_id($query);
if (!$item) {
    exit("There are no items that matches your search of " . $query . " . Please check the spelling and try again");
}
while ($row = mysqli_fetch_array($item)) {
    $image_uris = split(',', htmlentities($row['image_uri']));
    $name = htmlentities($row['name']);
    $description = ($row['description']);
    $price = htmlentities($row['price']);
    $user_id = htmlentities($row['user_id']);
    $item_id = htmlentities($row['id']);
    $approved = htmlentities($row['is_approved']) == 0 ? false : true;
}


if (isset($_SESSION['user'])) {
    $user = VerticalDB::getInstance()->get_user_by_user_id($_SESSION['user']);

    while ($row = mysqli_fetch_array($user)) {
        $belongs_to_user = ($row['id'] == $user_id ? true : false);
        $admin_options = ($row['is_admin'] == 0 ? false : true);
    }
    mysqli_free_result($user);

    /* check if current user is owner of an unapproved post */
    if (!$approved && !$belongs_to_user) {
        header('Location: index.php');
        exit;
    }

    /* functionality to approve or deny posting */
    if ($_SERVER['REQUEST_METHOD'] == "POST" && $admin_options) { //if signed in and an admin
        $reasons = array("imageDenial" => "Sorry your image does not meet the terms of the privacy policy.",
            "termsDenial" => "Sorry your item does not meet the terms of the privacy policy.",
            "duplicateDenial" => "Duplicate posting.");
        $message = "";
        if (isset($_POST['approved']))
            VerticalDB::getInstance()->approve_post($_POST['approved']);
        else {
            foreach ($reasons as $key => $value) {
                if (isset($_POST[$key]))
                    $message = $value;
            }
            $message = ($message == "") ? $_POST['customDenial'] : $message; // if message is blank than it is custom message
            VerticalDB::getInstance()->deny_post($_POST['denyID']);
            VerticalDB::getInstance()->send_mail($id, $_POST['denyUserID'], "Deny", $message);
        }
    }
}elseif (!$approved) {
    header('Location: index.php');
    exit;
}

mysqli_free_result($item);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <?php
        $title = $name; // ADD PAGE TITLE HERE
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
                        <?php
                        echo "<h1 style='display:inline;'>" . $name . "</h1><h3 style='display:inline;'> $" . $price . "</h3>";

                        if ($belongs_to_user) { //if user owns this item
                            echo "<a href='edit.php?id=" . $item_id . "'  id='editButton' role='button' class='btn btn-default btn-md pull-right'>"
                            . "<span class='glyphicon glyphicon-pencil'></span> Edit</a>";
                        } else {
                            echo "<a href='message.php?id=" . $user_id . "' role='button' class='btn btn-default btn-md pull-right'>"
                            . "<span class='glyphicon glyphicon-envelope'></span> Contact seller</a>";
                        }
                        if (!$belongs_to_user && $approved) { //if the item is approved and this user does not own item
                            echo "<a href='purchaseItem.php?id=" . $item_id . "' id='purchaseButton' role='button' class='btn btn-default btn-md pull-right'>"
                            . "<span class='glyphicon glyphicon-shopping-cart'></span> Buy item</a>";
                        }
                        if ($admin_options) {//logged in user is admin
                            
                                echo "<a role='button' data-toggle='modal' data-target='#denyModal' class='btn btn-default btn-md pull-right'>"
                            . "<span class='glyphicon glyphicon-remove'></span>Deny</a>";
                            if(!$approved)
                                echo "<form action='home.php' method='post' class='pull-right '><input type='hidden' name='approved' value='"
                            . $item_id . "'><button role='button' type='submit' class='btn btn-default btn-md bg-success'>"
                            . "<span class='glyphicon glyphicon-ok-sign'></span>Approve</button>";
                        }
                        ?>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <?php
                            $i = 0;
                            while ($i < count($image_uris)) {
                                echo '<div class="col-xs-6 col-md-3"><a href="#" class="thumbnail">';
                                echo '<img data-target="#editCarousel" data-slide-to="' . $i . '"  src="' . $image_uris[$i] . '" class="img-responsive"/>';
                                echo '</a></div>';
                                $i++;
                            }
                            ?>
                        </div>
                        <div>
                            <div id="editCarousel" class="carousel slide" data-ride="carousel">
                                <!-- Indicators -->
                                <?php
                                $i = 0;
                                echo '<ol class="carousel-indicators">';
                                echo '<li data-target="#editCarousel" data-slide-to="' . $i . '" class="active"></li>';
                                $i++;
                                while ($i < count($image_uris)) {
                                    echo '<li data-target="#editCarousel" data-slide-to="' . $i . '"></li>';
                                    $i++;
                                }
                                echo '</ol>';

                                echo '<div class="carousel-inner">';

                                echo '<div class="item active">
                                               
                                               <img src="' . $image_uris[0] . '">
                                              </div>';
                                foreach (array_slice($image_uris, 1) as $uri) {
                                    echo '<div class="item ">
                                               <img src="' . $uri . '">
                                              </div>';
                                }
                                echo '</div>';


                                echo '<a class="left carousel-control" href="#editCarousel" data-slide="prev">
                                            <span class="glyphicon glyphicon-chevron-left"></span>
                                        </a>
                                        <a class="right carousel-control" href="#editCarousel" data-slide="next">
                                            <span class="glyphicon glyphicon-chevron-right"></span>
                                        </a>';
                                ?>
                            </div>
                        </div>


                    </div>
                    <div class="col-md-6">
                        <?php
                        echo "<p>" . $description . "</p>";
                        ?>
                    </div>
                    <?php if (!$approved): ?>
                        <div class="col-md-12" style="height: 95%; position: absolute;
                             top: 87px; left: 0; background-color: rgba(255, 255, 255, 0.5); z-index: 1000;
                             ">
                            <div style="color: black; font-size: 5em; width: 80%;
                                 margin-left: auto; margin-right: auto; margin-top: 25%;">
                                PENDING APPROVAL
                            </div>
                        </div>
                    <?php endif ?>
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
        <div class="modal fade" id="denyModal" tabindex="-1" role="dialog" aria-labelledby="denyModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" method="post" class="denyForm">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel">Denial message:</h4>
                        </div>
                        <div class="modal-body">
                            <div class="list-group">
                                <a href="#" class="list-group-item">
                                    Sorry your image does not meet the terms of the privacy policy.
                                </a>
                                <input type="radio" name="imageDenial" style="display:none;"></input>
                                <a href="#" class="list-group-item">
                                    Sorry your item does not meet the terms of the privacy policy.</a>
                                <input type="radio" name="termsDenial" style="display:none;"></input>
                                <a href="#" class="list-group-item">Duplicate posting</a>
                                <input type="radio" name="duplicateDenial" style="display:none;"></input>
                                <a href="#" class="list-group-item" id="customDenialTrigger">Custom</a>
                                <textarea name="customDenial" id="customDenial"></textarea>
                                <input type="hidden" name="denyID"  style="display:none;"id="denyIDInput"/>
                                <input type="hidden" name="denyUserID" style="display:none;" id="denyUserIDInput"/>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal" id="close">Close</button>
                            <button type="button" class="btn btn-primary" id="submitButton" data-dismiss="modal">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <!-- PLACE SCRIPTS THAT REQUIRE JQUERY OR BOOTSTRAP HERE AND LOWER-->
        <script src="js/main.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                var denialMessage = "Denial message:";
                $("#customDenialTrigger").click(function() {
                    var trigger = $(this);
                    var otherTriggers = trigger.siblings();

                    trigger.addClass('active');
                    otherTriggers.removeClass('active')
                    otherTriggers.next().prop('checked', false);

                    // $("#customDenial").toggle();
                });

                $(".denyForm a").not("#customDenialTrigger").click(function() {
                    var trigger = $(this);
                    var otherTriggers = trigger.siblings();
                    var selection = trigger.next();
                    var others = $('.denyForm input').not(selection);
                    trigger.addClass('active');
                    otherTriggers.removeClass('active');
                    $("#customDenial").hide();

                    selection.prop('checked', true);
                    others.prop('checked', false);
                });

                $("#submitButton").click(function() {
                    if ($("#customDenial").val() == "" && !$('.denyForm input').is(':checked')) {
                        //invalid submissions
                        $("#myModalLabel").html(denialMessage + "<small style='color:red;'> No message selected.<small>")
                    } else {
                        if ($("#customDenial").is(":hidden"))
                            $("#customDenial").val() == "";
                        $("#myModalLabel").html(denialMessage);
                        $('.denyForm').submit();
                    }
                });

                $('#close').click(function() {
                    $("#customDenial").val(null);
                })
            });
        </script>
    </body>

</html>
