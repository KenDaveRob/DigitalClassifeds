<?php
require_once("Includes/db.php");

    $message = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image_uri = $_POST['image_uri'];
    
    if ($name != "" && $description != "" && $price != "") {
        VerticalDB::getInstance()->create_item($name, $description, $price, $image_uri);
        header('Location: allItems.php');
        exit;
    } else {

        $message .= ($name == "") ? "Name cannot be blank <br>" : "";
        $message .= ($description == "") ? "Description cannot be blank <br>" : "";
        $message .= ($price == "") ? "Price cannot be blank <br>" : "";
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">      
        <title>Vertical Prototype</title>
        <link href="wishlist.css" type="text/css" rel="stylesheet" media="all" />
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
                                <li class="active"><a href="#">Home</a></li>
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
                    <?php include "Includes/leftnav.php";?>
                </div>
                <div id="rightside">
                    <h1>Add an item</h1>
                     <?php echo ($message !== null) ? $message : ""; ?>
                    <form name="itemForm" class="form-horizontal" action="index.php" method="POST">
                        <div class="control-group">
                            <label class="control-label" for="itemName">Name</label>
                            <div class="controls">
                                <input type="text" id="itemName" placeholder="Name" name="name">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="itemDescription">Description</label>
                            <div class="controls">
                                <input type="text" id="itemDescription" placeholder="Description" name="description">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="itemPrice">Price</label>
                            <div class="controls">
                                <input type="text" id="itemPrice" placeholder="00.00" name="price">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="itemImage">Image Filename</label>
                            <div class="controls">
                                <input type="text" id="itemImage" placeholder="desk.jpg" name="image_uri">
                                <button class="btn" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
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

    <script>
        // $(function () {
        //	$('#myTab a:first').tab('show');
        // })
    </script>
</body>

</html>
