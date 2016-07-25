<?php
require_once("Includes/db.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">      
        <title>Vertical Prototype</title>
        <link href="style.css" type="text/css" rel="stylesheet" media="all" />
        <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
        <style type="text/css">

            /* Sticky footer styles
            -------------------------------------------------- */

            html,
            body {
                height: 100%;
                /* The html and body elements cannot have any padding or margin. */
            }

            /* Wrapper for page content to push down footer */
            #wrap {
                min-height: 100%;
                height: auto !important;
                height: 100%;
                /* Negative indent footer by it's height */
                margin: 0 auto -60px;
            }

            /* Set the fixed height of the footer here */
            #push,
            #footer {
                height: 60px;
            }
            #footer {
                background-color: #f5f5f5;
            }

            /* Lastly, apply responsive CSS fixes as necessary */
            @media (max-width: 767px) {
                #footer {
                    margin-left: -20px;
                    margin-right: -20px;
                    padding-left: 20px;
                    padding-right: 20px;
                }
            }



            /* Custom page CSS
            -------------------------------------------------- */
            /* Not required for template or sticky footer method. */

            .container {
                width: auto;
                max-width: 680px;
            }
            .container .credit {
                margin: 20px 0;
            }

        </style>
    </head>
    <body>
        <div id="wrap">

            <!-- Fixed navbar -->
            <div class="navbar navbar-fixed-top">
                <div class="navbar-inner">
                    <div class="container">
                        <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="brand" href="index.php">Classifieds</a>
                        <div class="nav-collapse collapse">
                            <ul class="nav" style="float: right;">
                                <li class=""><a href="index.php">Home</a></li>
                                <li><a href="#about">About</a></li>
                                <li><a href="#contact">Contact</a></li>
                                <li>
                                    <a href="register.php" style="float: right;" class="nav">Sign Up</a>
                                </li>
                            </ul>                            
                        </div><!--/.nav-collapse -->
                    </div>
                </div>
            </div>

            <!-- Begin page content -->
            <div class="container">
                <div id="push"></div>
                <div id="leftside" style=" position: absolute; width: 300px; height: 100%; left: 0;">
                    <?php include 'Includes/leftnav.php'; ?>
                </div>
                <div id="rightside">
                    <div class="page-header">
                        <h1>Showing all items</h1>
                    </div>
                    <?php
                    $results = VerticalDB::getInstance()->show_all_items();
                    ?>
                    <div>
                    </div>
                    <table class="table">
                        <tr>
                            <th>Date</th>
                            <th>Item</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>&nbsp;</th>
                        </tr>
                        <?php
                        if ($results) {
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
                        ?>
                    </table>
                </div>
            </div>
        </div> 
        <div id="footer">
            <div class="container">
                <p class="muted credit">SFSU Software Engineering Project, Spring 2014.  For Demonstration Only
                </p>
            </div>
        </div>
        <script src="http://code.jquery.com/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>

</html>