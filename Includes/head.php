<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$bootstrap_cs = '<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">';
$meta = '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">';     
$title = ($title ? "<title>".$title." | Digital Classifieds</title>" 
                 : "<title> Digital Classifieds</title>"); //if title is assigned then prepend it to site title
$main_cs    = '<link href="css/style.css" type="text/css" rel="stylesheet" media="all" />';


print $meta;
print $bootstrap_cs;
print $title;
print $main_cs;
print '<link rel="shortcut icon" type="image/x-icon" href="favicon.ico.png">';
print "<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-50778219-1', 'sfsuswe.com');
  ga('send', 'pageview');

</script>";