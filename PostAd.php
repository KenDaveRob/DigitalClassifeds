<?php
/*
  Written by Kenneth Robertson
  First Version Done On April 14 2014
  Page is for posting new items to the website for sale.
 */
session_start();
require_once("Includes/db.php");
if (!isset($_SESSION['user'])) {
    header("location: login.php?PostAd.php");
    exit();
}
$result = VerticalDB::getInstance()->get_categories_list();
$cats = array();
while ($row = mysqli_fetch_array($result)) {
    $cats[] = $row['name'];
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $result = null;
    $price = $_POST['price'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $user = $_SESSION['user'];
    $file_temp = $_FILES['file'] ['tmp_name'];
    $file = $_FILES['file'] ['name'];
    $allowable_ext = array("jpg", "jpeg", "png", "bmp", "gif");
    $uris = null;
    //if file is of the appropriate type and saved properly
    
    for($i = 0; $i < count($file_temp); $i++){
        $image = 'uploads/img/' . $file[$i];
        if ((move_uploaded_file($file_temp[$i],$image)) && (in_array(substr($image, -3), $allowable_ext)))
            $uris .= ($i > 0) ? ','.$image : $image;
    }
            $result = VerticalDB::getInstance()->create_item($title, $user, $category, $description, $price, $uris);
    if ($result) {
        header("location: item.php?id=" . $result);
        exit();
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <?php
        $title = "Sell Item"; // ADD PAGE TITLE HERE
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
                    <h1>Post Ad</h1>
                    <div class="row">
                        <div class="col-md-2">1. Choose Item Category</div>
                        <div class="col-md-2">2. Fill out post</div>
                        <div class="col-md-2">3. Post</div>
                    </div>
                    <br/>
                    <!--
                    <div class ="row">1. Choose Item Category</div> 
                    <div class="row">2. Fill out post</div>
                    -->
                    <form role="form" action="PostAd.php" method="post" enctype= "multipart/form-data">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="postTitle">Post Title <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="postTitle" name="title" placeholder="Title" required>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="itemPrice">Price $ <span style="color:red;">*</span></label>
                                <input type="number" class="form-control" id="itemPrice" name="price" placeholder="None" required>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="category">Category <span style="color:red;">*</span></label>
                                <select type="number" class="form-control" id="category" name="category" required>
                                    <?php
                                    foreach ($cats as $category)
                                        echo '<option value="' . $category . '">' . ucfirst($category) . '</option>';
                                    ?>
                                    <label for="category">Category</label>
                                    <select type="number" class="form-control" id="category" name="category">
                                        <?php
                                        foreach ($cats as $category)
                                            echo '<option value="' . $category . '">' . ucfirst($category) . '</option>';
                                        ?>
                                    </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-8">
                                <label for="postDescription">Post Description</label>
                                <textarea class="form-control" id="postDescription" rows="9" name="description"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="addImage">Add Image</label>
                            <input type="file" id="addImage" name="file[]" multiple="multiple">
                        </div>
                        <div class="row">
                            <button type="button" class="btn btn-default"> <a href="/">Cancel</a></button>
                            <button type="submit" class="btn btn-default">Post</button>
                        </div>
                    </form>
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

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <!-- PLACE SCRIPTS THAT REQUIRE JQUERY OR BOOTSTRAP HERE AND LOWER-->
        <script src="js/main.js"></script>
    </body>

</html>
