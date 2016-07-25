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
        <?php 
            $title = "Add Item"; // ADD PAGE TITLE HERE
            include('Includes/navbar.php');
            include('Includes/head.php');
        ?>
        <style type="text/css"></style>
    </head>
    <body>
        <div id="wrap">

            <!-- Fixed navbar -->
            <?php echo $navbar; ?>
            
            <div id="leftside" class="col-xs-3">
                <?php include "Includes/leftnav.php";?>
            </div>
            <div class="container col-md-9" >
                <div id="push"></div>
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
                <div id="rightside" class="col-xs-3">
                </div>
    </div> 
   
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<!-- PLACE SCRIPTS THAT REQUIRE JQUERY OR BOOTSTRAP HERE AND LOWER-->
    <script src="js/main.js"></script>
</body>

</html>