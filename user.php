<?php
require_once("Includes/db.php");
$query = $_GET['id'];
$item = VerticalDB::getInstance()->get_items_by_user_id($query);
$user = VerticalDB::getInstance()->get_user_by_user_id($query);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Tr ansitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <?php
        $title = "User"; // ADD PAGE TITLE HERE
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
                <?php
                while ($row = mysqli_fetch_array($user))
                    $username = htmlentities($row['username']);
                mysqli_free_result($user);

                echo "<h1 style='display:inline;'>" . $username . "'s items for sale</h1>";
                echo "<a href='message.php?id=" . $query . "' role='button' class='btn btn-default btn-md pull-right'>"
                . "<span class='glyphicon glyphicon-envelope'></span> Contact seller</a>";
                ?>
                <div>

                    <table class="table">
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th></th>
                        </tr>
                        <?php
                        while ($row = mysqli_fetch_array($item)) {
                            $image_uri = split(',', htmlentities($row['image_uri']));
                            $name = htmlentities($row['name']);
                            $description = ($row['description']);
                            $price = htmlentities($row['price']);

                            echo "<tr><td>&nbsp;<a href='item.php?id=" . htmlentities($row['id']) . "'>" . $name . " </a></td>";
                            echo "<td>" . $price . " </td>";
                            echo ($row['image_uri']) ? "<td>&nbsp;<img style='height: 100px; width:100px; margin-left: 20px; ' src='" . $image_uri[0] . "' /></td>" : "";
                            echo "</tr>\n";
                        }
                        mysqli_free_result($item);
                        ?>
                    </table>
                </div>

            </div>

            <div id="rightside"  class="col-xs-2">
                <?php include "Includes/rightnav.php"; ?>
            </div>
            <div id="footer">
                <div class="container">
                    <p class="muted credit"><a class="btn"  href="about.php">Contact Us</a>  <a class="btn" href="privacy.php">Privacy Policy</a></p>
                </div>
            </div>
        </div>
        <script src="http://code.jquery.com/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>

</html>