<?php

/*
 * 	File started by: Naimah
 * 	Updated by: INSERT NAME - DATE
 */
require_once 'db.php';
$results = VerticalDB::getInstance()->get_categories_list();
$categories = array();
if ($results) {
    while ($row = mysqli_fetch_array($results)) {
        if ($row['parent'] != 0) {
            $categories[$row['parent']] .= "," . $row['name'];
        } else {
            $categories[$row['id']] = $row['name'];
        }
    }
    mysqli_free_result($results);
}

$leftNav = '<div><h4>Categories</h4><div class="list-group" id="leftNav">';
$currCat = urldecode($_SERVER['QUERY_STRING']);
foreach ($categories as $category) {
    $split = explode(",", $category);
    $parent = $split[0];
    $encodedParent = urlencode($parent);
    if (count($split) > 1) {
        
        $leftNav .= ($currCat == $parent ? '<div><div class="list-group-item active open' // if the current category is this parent its active
                . '"><a class="parent" href="browse.php?' . $encodedParent . '">' . $parent . '</a><span class="glyphicon glyphicon-minus"></span></div>' :
        '<div><div class="list-group-item"><a class="parent" href="browse.php?' . $encodedParent . '">' . $parent . '</a><span class="glyphicon glyphicon-plus"></span></div>');

        foreach (array_slice($split, 1, -1) as $child) {
            $encodedChild = urlencode($child);
            if ($currCat == $child) {
                $leftNav .= '<a class="list-group-item child active" href="browse.php?' . $encodedChild . '" style="display:block;"> - ' . $child . '</a>';
            } else if (in_array($currCat, array_slice($split, 1))) {
                $leftNav .= '<a class="list-group-item child" href="browse.php?' . $encodedChild . '" style="display:block;"> - ' . $child . '</a>';
            } else {
                $leftNav .= '<a class="list-group-item child" href="browse.php?' . $encodedChild . '"> - ' . $child . '</a>';
            }
        }
        $child = $split[count($split) - 1];
        $encodedChild = urlencode($child);
        $leftNav .= ($currCat == $child) ? '<a class="list-group-item child active" href="browse.php?' . $encodedChild . '" style="display:block;"> - ' . $child . '</a></div>' : (in_array($currCat, array_slice($split, 1))) ? '<a class="list-group-item child" href="browse.php?' . $encodedChild . '" style="display:block;"> - ' . $child . '</a></div>' : '<a class="list-group-item child" href="browse.php?' . $encodedChild . '" > - ' . $child . '</a></div>';
    } else
        $leftNav .= '<a class="list-group-item ' . ($currCat == $parent ? 'active' : '') . '" href="browse.php?' . $encodedParent . '">' . $parent . '</a>';
}

$leftNav .= '</div></div>';

echo $leftNav;
?>