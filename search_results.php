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
                    <h1>Search results for  <?php
                        $query = $_GET['q'];
                        $category = $_GET['cat'];
                        echo ($category) ? $query . ' - <small><a href="browse.php?' . $category . '">Back</a>' : $query;
                        ?></h1>
                </div>
                <?php
                $results = ($category) ?  VerticalDB::getInstance()->get_item_by_description_and_category($query, $category): VerticalDB::getInstance()->get_item_by_description($query);
                if (!$results) {
                    exit("There are no items that matches your search of " . $query . " . Please check the spelling and try again");
                }
                ?>
                <div>
                </div>
                <table class="table">
                    <tr>
                        <th>Item</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>&nbsp;</th>
                    </tr>
                    <?php
                    while ($row = mysqli_fetch_array($results)) {
                        $uris = split(',', $row['image_uri']);
                        echo "<tr><td><a href='item.php?id=" . htmlentities($row['id']) . "'> " . htmlentities($row['name']) . "</a></td>";
                        echo "<td>" . htmlentities($row['description']) . "</td>";
                        echo "<td>$" . htmlentities($row['price']) . "</td>";
                        echo ($uris[0]) ? "<td><img style='height: 100px; width:100px; margin-left: 20px; ' src='" . htmlentities($uris[0]) . "' /></td>" : "<td>&nbsp;</td>";
                        echo "</tr>\n";
                    }
                    mysqli_free_result($results);
                    ?>
                </table>

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
    </div>>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<!-- PLACE SCRIPTS THAT REQUIRE JQUERY OR BOOTSTRAP HERE AND LOWER-->
<script src="js/main.js"></script>
</body>

</html>
