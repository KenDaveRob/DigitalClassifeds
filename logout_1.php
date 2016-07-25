<?php
function messages($id) {
    $result = VerticalDB::getInstance()->display_mail($id);
    if (is_array($result)) {
        echo "<table class='table'>";
        foreach ($result as $key) {
            $fromuser = VerticalDB::getInstance()->get_user_by_user_id($_SESSION['user']);
            if ($fromuser) {
                $row = mysqli_fetch_array($fromuser);
                $othername = $row['username'];
                mysqli_free_result($fromuser);
            }
            echo "<tr><td> From: ".$othername  . "</td>";
           /* echo "<tr><td> From: <a href='" . $key['from'] . "'>" .$othername  . "</a></td>";// keep if link to user*/
            // echo "<td>" . htmlentities($row['description']) . "</td>"; //add some subject functionality
            echo "<td><a href='?messageid=" . $key['message_id'] . "'> View Message </a></td>";
            echo "</tr>";
        }
        echo '</table>';
    }

}

function message($id, $messageid){
    $result = VerticalDB::getInstance()->display_mail($id, $messagid);
    if (is_array($result)) {
        echo "<table class='table'>";
        foreach ($result as $key) {
            $fromuser = VerticalDB::getInstance()->get_user_by_user_id($_SESSION['user']);
            if ($fromuser) {
                $row = mysqli_fetch_array($fromuser);
                $othername = $row['username'];
                mysqli_free_result($fromuser);
            }
            echo "<tr><td> From: ".$othername  . "</td>";
            echo "<tr><td>" . $key['message_body'] . "'</td></tr>";
        }
        echo '</table>';
    }

}

if(isset($_GET['id']))
    if(isset($_GET['messageid']))
        message($_GET['id'], $_GET['messageid']);
    else
        messages($_GET['id']);