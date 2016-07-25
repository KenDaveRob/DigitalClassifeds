<?php
session_start();
require_once("Includes/db.php");
$query = $_GET['id'];

$is_user = false;
$admin_options = false;
$item = VerticalDB::getInstance()->get_item_by_item_id($query);

while ($row = mysqli_fetch_array($item)) { //get the info associated with this item.
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
        $is_user = ($row['id'] == $user_id ? true : false);
    }
} else {
    header("location: index.php");
    exit();
}
if($_SERVER['REQUEST_METHOD'] == 'POST'){ //then 
    
}


$result = VerticalDB::getInstance()->get_categories_list();
$cats = array();
while ($row = mysqli_fetch_array($result)) { //get categories for dropdown
    $cats[] = $row['name'];
}

mysqli_free_result($item);
mysqli_free_result($result);
mysqli_free_result($user);
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
                    <form>
                        <div class="page-header">
                            <div class='row'>
                                <?php
                                //this are the inputs to edit the name and price
                                echo "<input class='col-md-4' name='name' value='" . $name . "'></input>"
                                . " $<input name='price' value='" . $price . "'></input>"
                                . "<select name='category' style='display:inline;'>"
                                . "<option>Change category</option>";
                                foreach ($cats as $category)
                                    echo "<option>" . $category . "</option>";
                                echo "</select>";

                                //this is the button to save edits
                                echo "<button role='button' id='editSave' class='btn btn-default btn-md pull-right'>"
                                . "<span class='glyphicon glyphicon-ok-sign'></span> Save</button>";

                                //this is the button to cancel editing
                                echo "<a role='button' href='item.php?id=".$item_id."' class='btn btn-default btn-md pull-right'>"
                                . "<span class='glyphicon glyphicon-remove'></span> Cancel</a>";
                                ?>
                            </div>
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
                                    //this prints the carousel indicators
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
                                    //this prints the actual images
                                    $i = 0;
                                    echo '<div class="item active">
                                               <img src="' . $image_uris[0] . '">
                                               <div class="carousel-caption editCaption">
                                                <label class="btn btn-md" role="button">
                                                 <input type="checkbox" name="image[0]" />
                                                 <span class="glyphicon glyphicon-trash"></span>
                                                </label>
                                               </div>
                                              </div>';
                                    $i++;
                                    foreach (array_slice($image_uris, 1) as $uri) {
                                        echo '<div class="item ">
                                               <img src="' . $uri . '">
                                               <div class="carousel-caption editCaption">
                                                <label class="btn btn-md" role="button">
                                                 <input type="checkbox" name="image[' . $i . ']" />
                                                 <span class="glyphicon glyphicon-trash"></span>
                                                </label>
                                               </div>
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
        <script type="text/javascript">
            $('#editSave').click(function() {
                if (confirm("Save changes?"))
                    $('form').submit();
            })
        </script>
    </body>

</html>
