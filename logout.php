<?php
session_start();
unset($_SESSION["user"]);
unset($_SESSION['user_email']);
session_destroy();
$ref = $_SERVER['HTTP_REFERER'];
if(strpos($ref,$_SERVER['SERVER_NAME']) && !strpos($ref, 'home.php')){
    header("location:".$_SERVER['HTTP_REFERER']);
    exit();
}else{
    header("location: index.php");
    exit();    
}
