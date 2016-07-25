<?php
/* Written by Kenneth Robertson
  First Version Done On May 5 2014
  Page is for completing purchases.
 */
session_start();
require_once("Includes/db.php");
if (!isset($_GET['id'])) { //if no id is sent along then exit because error
    header("location: index.php");
    exit();
}
$query = $_GET['id'];

if (!isset($_SESSION['user'])) {
    header("location: login.php?item.php%3F" . $query);
    exit();
}
$is_user = false;
$admin_options = false;
$item = VerticalDB::getInstance()->get_item_by_item_id($query);
if (!$item->num_rows > 0) {
    if (isset($_SERVER['HTTP_REFERER'])) {
        if (strpos($_SERVER['HTTP_REFERER'], "sfsuswe") !== false)
            header("location: " . $_SERVER['HTTP_REFERER']);
        else
            header("location: index.php");
    } else
        header("location: index.php");
    exit();
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

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // sends the item id, buyer's id, and seller's id
    VerticalDB::getInstance()->buy_item($query, $_SESSION['user'], $user_ID);

    //sends who from, to whom, what kind of message, and the actual message
    VerticalDB::getInstance()->send_mail($_SESSION['user'], $user_ID, "PURCHASE", $name . " was purchased");
    
    header("location: index.php"); //exit
    exit();
}

mysqli_free_result($item);
?>
<html>
    <head>
        <?php
        $title = "Purchase Item"; // ADD PAGE TITLE HERE
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
                    <h1>Complete Purchase</h1>
                    <div class="col-md-6 pull-right">
                        Purchase: <?php echo $name . " - $" . $price ?>
                        <?php echo ($image_uris[0] != '') ? '<img style="position:absolute; margin-left: -200px; margin-top: 20px; width:400px;" src="' . $image_uris[0] . '" />' : ''; ?>
                    </div>
                    <br/>

                    <!-- 
                    NOTE--------------------------------------------------------------------------
                    many of these fields can be coded better using the examples listed at
                    http://bootstrapformhelpers.com/state/#jquery-plugins
                    NOTE--------------------------------------------------------------------------
                    -->
                    <form role="form" action="purchaseItem.php?id=<?php echo $query; ?>" method="post">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <!--Start Shipping Address-->
                                Shipping Address:
                            </div>
                        </div>                            
                        <div class="row">
                            <!--Shipping Address: Address-->
                            <div class="form-group col-md-4">
                                <label for="shippingAddress">Address <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="shippingAddress" name="shippingAddress" placeholder="" required>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Shipping Address: City-->
                            <div class="form-group col-md-4">
                                <label for="shippingCity">City <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="shippingCity" name="shippingCity" placeholder="" required>
                            </div>
                        </div>
                        <div class="row">
                            <!--Shipping Address: State-->
                            <div class="form-group col-md-4">
                                <label for="shippingState">State <span style="color:red;">*</span></label>
                                <select class="form-control" id="shippingState" name="shippingState" required>
                                    <option value="AL">AL</option>
                                    <option value="AK">AK</option>
                                    <option value="AZ">AZ</option>
                                    <option value="AR">AR</option>
                                    <option value="CA">CA</option>
                                    <option value="CO">CO</option>
                                    <option value="CT">CT</option>
                                    <option value="DE">DE</option>
                                    <option value="DC">DC</option>
                                    <option value="FL">FL</option>
                                    <option value="GA">GA</option>
                                    <option value="HI">HI</option>
                                    <option value="ID">ID</option>
                                    <option value="IL">IL</option>
                                    <option value="IN">IN</option>
                                    <option value="IA">IA</option>
                                    <option value="KS">KS</option>
                                    <option value="KY">KY</option>
                                    <option value="LA">LA</option>
                                    <option value="ME">ME</option>
                                    <option value="MD">MD</option>
                                    <option value="MA">MA</option>
                                    <option value="MI">MI</option>
                                    <option value="MN">MN</option>
                                    <option value="MS">MS</option>
                                    <option value="MO">MO</option>
                                    <option value="MT">MT</option>
                                    <option value="NE">NE</option>
                                    <option value="NV">NV</option>
                                    <option value="NH">NH</option>
                                    <option value="NJ">NJ</option>
                                    <option value="NM">NM</option>
                                    <option value="NY">NY</option>
                                    <option value="NC">NC</option>
                                    <option value="ND">ND</option>
                                    <option value="OH">OH</option>
                                    <option value="OK">OK</option>
                                    <option value="OR">OR</option>
                                    <option value="PA">PA</option>
                                    <option value="RI">RI</option>
                                    <option value="SC">SC</option>
                                    <option value="SD">SD</option>
                                    <option value="TN">TN</option>
                                    <option value="TX">TX</option>
                                    <option value="UT">UT</option>
                                    <option value="VT">VT</option>
                                    <option value="VA">VA</option>
                                    <option value="WA">WA</option>
                                    <option value="WV">WV</option>
                                    <option value="WI">WI</option>
                                    <option value="WY">WY</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <!--Shipping Address: Zip-->
                            <div class="form-group col-md-4">
                                <label for="shippingZip">Zip <span style="color:red;">*</span></label>
                                <input type="number" class="form-control" id="shippingZip" name="shippingZip" placeholder="" required>
                            </div>
                        </div>
                        <!--End Shipping Address-->
                        <br>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <!--Start Billing Address-->
                                Billing Address:
                            </div>
                        </div>
                        <!-- Billing same as Shipping Check Box-->
                        <div class="row">
                            <div class="checkbox">
                                <label>
                                    <input id="shipping_check" type="checkbox"> Same as Shipping Address 
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <!--Billing Address: Address-->
                            <div class="form-group col-md-4">
                                <label for="billingAddress">Address <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="billingAddress" name="billingAddress" placeholder="" required>
                            </div>
                        </div>
                        <div class="row"> 
                            <!--Billing Address: City-->
                            <div class="form-group col-md-4">
                                <label for="billingCity">City <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="billingCity" name="billingCity" placeholder="" required>
                            </div>
                        </div>
                        <div class="row">
                            <!--Billing Address: State-->
                            <div class="form-group col-md-4">
                                <label for="billingState">State <span style="color:red;">*</span></label>
                                <select class="form-control" id="billingState" name="billingState" required>
                                    <option value="AL">AL</option>
                                    <option value="AK">AK</option>
                                    <option value="AZ">AZ</option>
                                    <option value="AR">AR</option>
                                    <option value="CA">CA</option>
                                    <option value="CO">CO</option>
                                    <option value="CT">CT</option>
                                    <option value="DE">DE</option>
                                    <option value="DC">DC</option>
                                    <option value="FL">FL</option>
                                    <option value="GA">GA</option>
                                    <option value="HI">HI</option>
                                    <option value="ID">ID</option>
                                    <option value="IL">IL</option>
                                    <option value="IN">IN</option>
                                    <option value="IA">IA</option>
                                    <option value="KS">KS</option>
                                    <option value="KY">KY</option>
                                    <option value="LA">LA</option>
                                    <option value="ME">ME</option>
                                    <option value="MD">MD</option>
                                    <option value="MA">MA</option>
                                    <option value="MI">MI</option>
                                    <option value="MN">MN</option>
                                    <option value="MS">MS</option>
                                    <option value="MO">MO</option>
                                    <option value="MT">MT</option>
                                    <option value="NE">NE</option>
                                    <option value="NV">NV</option>
                                    <option value="NH">NH</option>
                                    <option value="NJ">NJ</option>
                                    <option value="NM">NM</option>
                                    <option value="NY">NY</option>
                                    <option value="NC">NC</option>
                                    <option value="ND">ND</option>
                                    <option value="OH">OH</option>
                                    <option value="OK">OK</option>
                                    <option value="OR">OR</option>
                                    <option value="PA">PA</option>
                                    <option value="RI">RI</option>
                                    <option value="SC">SC</option>
                                    <option value="SD">SD</option>
                                    <option value="TN">TN</option>
                                    <option value="TX">TX</option>
                                    <option value="UT">UT</option>
                                    <option value="VT">VT</option>
                                    <option value="VA">VA</option>
                                    <option value="WA">WA</option>
                                    <option value="WV">WV</option>
                                    <option value="WI">WI</option>
                                    <option value="WY">WY</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <!--Billing Address: Zip-->
                            <div class="form-group col-md-4">
                                <label for="billingZip">Zip <span style="color:red;">*</span></label>
                                <input type="number" class="form-control" id="billingZip" name="billingZip" placeholder="" required>
                            </div>
                        </div>

                        <div class="row">
                            <!--Billing Address: Name on Credit Card-->
                            <div class="form-group col-md-4">
                                <label for="cardName">Name on Card <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="cardName" name="cardName" placeholder="" required>
                            </div>
                            <!--Billing Address: Credit Card Number-->
                            <div class="form-group col-md-4">
                                <div class="form-group">
                                    <label for="cardNumber">Credit-card number <span style="color:red;">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="cardnumber" name="cardNumber" data-mask="9999999999999999" data-mask-placeholder="*" required>
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-credit-card"></i></span>
                                    </div>
                                </div>
                            </div>
                            <!--Billing Address: Credit Card Expiration Date-->
                            <div class="form-inline col-md-4">
                                <label for="expirationDate">Expiration Date <span style ="color:red">*</span></label>
                                <br>
                                <select class="form-control"  name="month" required>
                                    <option>01</option>
                                    <option>02</option>
                                    <option>03</option>
                                    <option>04</option>
                                    <option>05</option>
                                    <option>06</option>
                                    <option>07</option>
                                    <option>08</option>
                                    <option>09</option>
                                    <option>10</option>
                                    <option>11</option>
                                    <option>12</option>
                                </select>
                                <select class="form-control" name="year" required>
                                    <option>2014</option>
                                    <option>2015</option>
                                    <option>2016</option>
                                    <option>2017</option>
                                    <option>2018</option>
                                    <option>2019</option>
                                    <option>2020</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <button type="button" class="btn btn-default"><a href="item.php?id=<?php echo $query; ?>">Cancel</a></button>
                            <button type="submit"  id="purchase" class="btn btn-default" style="background-color:yellow;">Purchase</button>
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
                <p class="muted credit"><a class="btn"  href="contact.php">Contact Us</a>  <a class="btn" href="privacy.php">Privacy Policy</a></p>
            </div>
        </div> 

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <!-- PLACE SCRIPTS THAT REQUIRE JQUERY OR BOOTSTRAP HERE AND LOWER-->
        <script src="js/main.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#shipping_check').change(function() {
                    if ($(this).is(':checked')) {
                        $('#billingState').val($('#shippingState').val());
                        $('#billingAddress').val($('#shippingAddress').val());
                        $('#billingCity').val($('#shippingCity').val());
                        $('#billingState').val($('#shippingState').val());
                        $('#billingZip').val($('#shippingZip').val());
                    } else {
                        $('#billingState').val('');
                        $('#billingAddress').val('');
                        $('#billingCity').val('');
                        $('#billingState').val('');
                        $('#billingZip').val('');
                    }
                })
                
                $('#purchase').click(function() {
                    var e = 0;
                    if (/^\$?([0-9]{5})$/.exec($('#billingZip').val()) == null) //makes sure zip is 5 digits
                        e++;
                    if (/^\$?([0-9]{5})$/.exec($('#shippingZip').val()) == null) //makes sure zip is 5 digits 
                        e++;
                    if (/^\$?([0-9]{16})$/.exec($('#cardnumber').val()) == null) //makes sure card is 16 digits 
                        e++;

                    if (e == 0) //no errors? submit!
                        $('form').submit();
                    else
                        alert("Form has errors")

                })
            })

        </script>
    </body>

</html>
