<?php
/*
*	File started by: Naimah
*	Updated by: INSERT NAME - DATE
*/
session_start();
require_once("Includes/db.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <?php
        $title = "Digital Classifieds"; // ADD PAGE TITLE HERE
        include('Includes/navbar.php');
        include('Includes/head.php');
        ?>
        <style type="text/css"></style>
    </head>
    <body>
        <div id="wrap">

            <!-- Fixed navbar -->
            <?php echo $navbar; ?>
            <div class="row-fluid">
            <div id="leftside"  class="col-xs-2">
                <?php include "Includes/leftnav.php"; ?>
            </div>
            <div class="container col-md-8" >
                <div id="push"></div>
                <div>
                <div class="page-header">
                    <h2>Welcome to Digital Classifieds</h2><h4>Online Marketplace</h4></div>
                     <!--div class="page-header"-->
                    <h2>Browse</h2>
                    <!--/div-->
                    <!-- Begin page content -->
                    <?php if (!$_SERVER['QUERY_STRING']): ?>

                        <div class="row">
                            <?php
                            $results = VerticalDB::getInstance()->get_categories_list();
                            $categories = array();
                            if ($results->num_rows) {
                                while ($row = mysqli_fetch_array($results)) {
                                    if ($row['parent'] != 0) {
                                        $categories[$row['parent']] .= "," . $row['name'];
                                    } else {
                                        $categories[$row['id']] = $row['name'];
                                    }
                                }
                                mysqli_free_result($results);
                            }

                            echo '<table class="table"><tr>';
                            $count = 0;
                            foreach ($categories as $category) {
                                if ($count % 5 == 0)
                                    echo '</tr><tr>';
                                echo '<td><dl>';
                                $split = explode(",", $category);
                                $parent = $split[0];
                                if (count($split) > 1) {
                                    echo '<dt><a href="browse.php?' . $parent . '" >' . ucfirst($parent) . '</dt>';
                                    foreach (array_slice($split, 1) as $child) {
                                        echo '<dd><a href="browse.php?' . $child . '">' . ucfirst($child) . '</a></dd> ';
                                    }
                                } else {
                                    echo '<dt><a href="browse.php?' . $parent . '" >' . ucfirst($parent) . '</dt>';
                                }

                                $count++;
                                echo '</dl></td>';
                            }

                            echo '</tr></table>';
                            ?>
                        </div>
                        <?php
                    else:
                        $category = $_SERVER['QUERY_STRING'];
                        echo '<h3>' . ucwords($category) . '</h3>';
                        //    <div class="row">
                        // <form class="col-md-4" action="search_results.php">
                        //     <input type="text" class="form-control" name="q" placeholder="Search within this category">
                        // </form></div>';
                        $results = VerticalDB::getInstance()->get_items_by_category($category);
                        echo '<table class="table">';
                        if ($results->num_rows) {
                            while ($row = mysqli_fetch_array($results)) {
                                echo "<tr><td>&nbsp;" . htmlentities(date('M j', strtotime($row['date']))) . "</a></td>";
                                echo "<td>&nbsp;<a href='item.php?id=" . htmlentities($row['id']) . "'> " . htmlentities($row['name']) . "</a></td>";
                                echo "<td>&nbsp;" . htmlentities($row['description']) . "</td>";
                                echo "<td>&nbsp;$" . htmlentities($row['price']) . "</td>";
                                echo ($row['image_uri']) ? "<td>&nbsp;<img style='height: 100px; width:100px; margin-left: 20px; ' src='" . htmlentities($row['image_uri']) . "' /></td>" : "";
                                echo "</tr>\n";
                            }
                            mysqli_free_result($results);
                        } else {
                            echo '<tr><td colspan="6">There are no results.</td></tr>';
                        }
                        echo '</table>';
                        ?>  
                    <?php endif ?>  
                </div>
            </div>
            <div id="rightside"  class="col-xs-2">
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
