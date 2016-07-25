<?php
/*
 * 	File started by: Naimah
 * 	Updated by: INSERT NAME - DATE
 */
session_start();
require_once("Includes/db.php");


$username = "";
$email = "";
$is_admin = 0;
$id = null;
if (isset($_SESSION['user'])) {
    $id = $_SESSION['user'];
    $result = VerticalDB::getInstance()->get_user_by_user_id($id);
    if ($result) {
        $row = mysqli_fetch_array($result);
        $first_name = $row['first_name'];
        $username = $row["username"];
        $email = $row["email"];
        $is_admin = $row["is_admin"];
    }
} else {
    header("location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $message = "";
    if (isset($_POST['approved']))
        VerticalDB::getInstance()->approve_post($_POST['approved']);
    else {
        foreach ($reasons as $key => $value) {
            if (isset($_POST[$key]))
                $message = $value;
        }
        $reasons = array("imageDenial" => "Sorry your <a href='item.php?'".$_POST['denyID'].">image</a> on does not meet the terms of the privacy policy.",
        "termsDenial" => "Sorry your item does not meet the terms of the privacy policy.",
        "duplicateDenial" => "Duplicate posting.");
        $message = ($message == "") ? $_POST['customDenial'] : $message; // if message is blank than it is custom message
        VerticalDB::getInstance()->deny_post($_POST['denyID']);
        VerticalDB::getInstance()->send_mail($id, $_POST['denyUserID'], "Deny", $message);
    }
}

function recent_activity($id) {
    $result = VerticalDB::getInstance()->get_items_by_user_id($id);
    if ($result) {
        echo "<table class='table'>";
        while ($row = mysqli_fetch_array($result)) {
            $uris = split(',', $row['image_uri']);
            echo "<tr><td>" . htmlentities(date('M j', strtotime($row['date']))) . "</a></td>";
            echo "<td><a href='item.php?id=" . htmlentities($row['id']) . "'> " . htmlentities($row['name']) . "</a></td>";
            echo "<td>" . htmlentities($row['description']) . "</td>";
            echo "<td>$" . htmlentities($row['price']) . "</td>";
            echo ($uris[0]) ? "<td><img style='height: 100px; width:100px; margin-left: 20px; ' src='" . htmlentities($uris[0]) . "' /></td>" : "";
            echo "<td style='color:red;'>";
            if (($row['is_sold'])) {
                echo "<td style='color:yellow;'>";
                echo "Sold";
                echo "</td>";
            } else if ($row['is_approved'] == '0') {
                echo "<td style='color:blue;'>";
                echo "Pending approval";
                echo "</td>";
            } else if ($row['is_approved'] == '1') {
                echo "<td style='color:green;'>";
                echo "Approved";
                echo "</td>";
            } else if ($row['is_approved'] == '-1') {
                echo "<td style='color:red;'>";
                echo "Denied";
                echo "</td>";
            }
            echo "</tr>";
        }
        echo '</table>';
        mysqli_free_result($result);
    }  else {
        echo "No recent activity";
    }
}

function messages($id) {
    $result = VerticalDB::getInstance()->display_mail($id);
    if (is_array($result)) {
        echo "<table class='table'>";
        foreach ($result as $key) {
            $fromuser = VerticalDB::getInstance()->get_user_by_user_id($key['from']);
            if ($fromuser) {
                $row = mysqli_fetch_array($fromuser);
                $othername = $row['username'];
                mysqli_free_result($fromuser);
            }
            //echo "<tr><td> From: " . $othername . "</td>";
            echo "<tr><td> From: <a href='user.php?id=" . $key['from'] . "'>" .$othername  . "</a></td>";// keep if link to user
            // echo "<td>" . htmlentities($row['description']) . "</td>"; //add some subject functionality
            echo "<td><a href='?messageid=" . $key['message_id'] . "#messages'> View Message </a></td>";
            echo "</tr>";
        }
        echo '</table>';
    } else {
        echo "No messages";
    }
}

function message($id, $messageid) {
    $result = VerticalDB::getInstance()->display_mail($id, $messageid);
    if (is_array($result)) {
        echo "<div class='well'><table class='table'>";
        foreach ($result as $key) {
            $fromuser = VerticalDB::getInstance()->get_user_by_user_id($key['from']);
            if ($fromuser) {
                $row = mysqli_fetch_array($fromuser);
                $othername = $row['username'];
                mysqli_free_result($fromuser);
            }
            echo (trim($key['subject']) == "Deny") ? "<tr><td> From: Admin</td>" : "<tr><td> From: " . $othername . "</td>";
            echo "<tr><td style='border-left: 1px solid rgb(221, 221, 221);"
            . "border-right: 1px solid rgb(221, 221, 221);'>" . $key['body'] . "</td></tr>";
        }
        echo "</table><a href='home.php#allmessages'>Return to messages</a></div>'";
    }  else {
        echo "No message";
    }
    
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <?php
        $title = $username . "'s homepage"; // ADD PAGE TITLE HERE
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
                    <h1>Welcome <?php echo $first_name; ?>!</h1>
                    <!-- Begin page content -->
                    <ul class="nav nav-tabs"> 
                        <?php if ($is_admin): ?>
                            <li><a href="#recent_activity" data-toggle="tab">Recent Activity</a></li>
                            <li><a href="#messages" data-toggle="tab">Messages</a></li>
                            <li class="active"><a href="#admin"  data-toggle="tab">Admin</a></li>
                        <?php else: ?>
                            <li class="active"><a href="#recent_activity" data-toggle="tab">Recent Activity</a></li>
                            <li><a href="#messages" data-toggle="tab">Messages</a></li>
                        <?php endif; ?>
                    </ul>
                    <div class="tab-content">

                        <?php
                        if ($is_admin) {

                            echo '<div class="tab-pane" id="recent_activity">';

                            recent_activity($id);

                            echo '</div>
                                <div class="tab-pane" id="messages">';

                            if (isset($_GET['messageid']))
                                message($id, $_GET['messageid']);
                            else
                                messages($id);
                            echo '</div>
                                <div class="tab-pane active" id="admin">';
                            $result = VerticalDB::getInstance()->get_pending_items();

                            if ($result->num_rows) {
                                echo "<table class='table'>";
                                while ($row = mysqli_fetch_array($result)) {
                                    $uris = split(',', $row['image_uri']);
                                    echo "<tr><td>" . htmlentities(date('M j', strtotime($row['date']))) . "</a></td>";
                                    echo "<td><a href='item.php?id=" . htmlentities($row['id']) . "'> " . htmlentities($row['name']) . "</a></td>";
                                    echo "<td>" . htmlentities($row['description']) . "</td>";
                                    echo "<td>$" . htmlentities($row['price']) . "</td>";
                                    echo ($uris[0]) ? "<td><img style='height: 100px; width:100px; margin-left: 20px; ' src='" . htmlentities($uris[0]) . "' /></td>" : "";
                                    echo '<td><form action="home.php" method="post"><input type="hidden" name="approved" value="' . $row['id'] . '" /> <button type="submit" role="button" class="btn btn-default btn-sm pull-right"><span class="glyphicon glyphicon-ok-sign">'
                                    . '</span><br>Approve</button></form>'
                                    . '<a href="#" role="button" data-toggle="modal" data-target="#denyModal"  data-itemid="' . $row['id'] . '" data-userid="' . $row['user_id'] . '" class="btn btn-default btn-sm pull-right denyButton">'
                                    . '<span class="glyphicon glyphicon-remove"></span><br>&nbsp;&nbsp;Deny&nbsp;&nbsp;</a></td>';
                                    echo "</tr>\n";
                                }
                                echo '</table>';
                                mysqli_free_result($result);
                            }
                            echo '</div>';
                        } else {
                            echo '<div class="tab-pane active" id="recent_activity">';

                            recent_activity($id);
                            echo '</div>
                                <div class="tab-pane" id="messages">';
                            if (isset($_GET['messageid']))
                                message($id, $_GET['messageid']);
                            else
                                messages($id);
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div id="rightside"  class="col-xs-2">
                <?php include "Includes/rightnav.php"; ?>
            </div>
        </div>
        <div id="footer">
            <div class="container">
                <p class="muted credit"><a class="btn"  href="about.php" >Contact Us</a>  <a class="btn" href="privacy.php" >Privacy Policy</a></p>
            </div>
        </div>
        <?php if (isset($is_admin) && $is_admin != false) : ?>
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
                                    <input type="radio" name="imageDenial"></input>
                                    <a href="#" class="list-group-item">
                                        Sorry your item does not meet the terms of the privacy policy.</a>
                                    <input type="radio" name="termsDenial"></input>
                                    <a href="#" class="list-group-item">Duplicate posting</a>
                                    <input type="radio" name="duplicateDenial"></input>
                                    <a href="#" class="list-group-item" id="customDenialTrigger">Custom</a>
                                    <textarea name="customDenial" id="customDenial"></textarea>
                                    <input type="hidden" name="denyID" id="denyIDInput"/>
                                    <input type="hidden" name="denyUserID" id="denyUserIDInput"/>
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
        <?php endif; ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <!-- PLACE SCRIPTS THAT REQUIRE JQUERY OR BOOTSTRAP HERE AND LOWER-->
        <script src="js/main.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                if (location.hash == "#messages" || location.hash == "#allmessages")
                    $('.nav-tabs a[href="#messages"]').tab('show');

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

                $('.denyButton').click(function() {
                    $('#denyIDInput').val($(this).data('itemid'));
                    $('#denyUserIDInput').val($(this).data('userid'));
                });

                $('#close').click(function() {
                    $('#denyIDInput').val(null);
                    $('#denyUserIDInput').val(null);
                    $("#customDenial").val(null);
                })
            });
        </script>
    </body>

</html>
